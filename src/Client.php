<?php

namespace CloudRebue;

use GuzzleHttp\Client AS fetch;

interface iClient 
{
  public function __construct($account_id, $account_secret);
  public function send($sender, $message, $phone, $endpoint = '', $link_id = null, $correlator='');
}

class Client implements iClient
{
  public function __construct($account_id, $account_secret)
  {
    $this->api = 'https://bulk.cloudrebue.co.ke/api/v1/send-sms';
    $this->auth = base64_encode($account_id.':'.$account_secret);
  }

  private function doSend($data)
  {
    $client = new fetch();
    $res = $client->post($this->api,$data);
    $status= $res->getStatusCode();
    $res_json = json_decode($res->getBody(), true);
    return $res_json;
  }

  private function getData($payload)
  {
    return array(
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => 'Basic '.$this->auth
      ],
      'json' => $payload
    );
  }

  public function send($sender, $message, $phone, $endpoint = '', $link_id = null, $correlator='')
  {
    $payload = array(
      'sender' => $sender,
      'message' => $message,
      'phone' => $phone,
      'endpoint' => $endpoint,
      'link_id' => $link_id,
	  'correlator' => $correlator,
    );

    $data = $this->getData($payload);
    return $this->doSend($data);
  }
}