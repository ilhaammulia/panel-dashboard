<?php

namespace Services;

use Services\UserAgentService;

class AntibotService
{
  protected $ip_address;
  protected $user_agent;
  protected $ua_service;

  public function __construct($ip_address, $user_agent)
  {
    $this->ip_address = $ip_address;
    $this->user_agent = $user_agent;
    $this->ua_service = new UserAgentService($user_agent);
  }

  public function verify()
  {
    if (!$this->checkIP()) {
      return false;
    }

    if (!$this->checkASN()) {
      return false;
    }

    if ($this->ua_service->isRobot()) {
      return false;
    }

    return true;
  }

  public function checkIP()
  {
    $fetch = $this->fetch();

    $ipsList = file_get_contents("https://raw.githubusercontent.com/shadedev0/random/main/ips.txt");

    if (strpos($ipsList, $this->ip_address) !== false || $fetch['hosting'] || $fetch['proxy']) {
      return false;
    }

    return true;
  }

  public function checkASN()
  {

    $detail = $this->getIPDetails();
    $asnList = file_get_contents("https://raw.githubusercontent.com/shadedev0/random/main/asn.txt");

    if (strpos($asnList, $detail['asn']) !== false) {
      return false;
    }

    return true;
  }

  private function fetch()
  {
    $key = env('IPAPI_KEY');
    $ip = $this->ip_address;

    $ipApiUrl = "https://pro.ip-api.com/json/{$ip}?fields=status,message,country,timezone,isp,org,as,asname,reverse,mobile,proxy,hosting,query&key={$key}";
    $ipApiResponse = file_get_contents($ipApiUrl);
    return json_decode($ipApiResponse, true);
  }

  private function getIPDetails()
  {
    $ipDetails = json_decode(file_get_contents("https://ipwhois.app/json/" . $this->ip_address), true);
    return $ipDetails;
  }
}
