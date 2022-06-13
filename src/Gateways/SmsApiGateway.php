<?php

namespace SuStartX\SMSProviders\Gateways;

class SmsApiGateway
{
    private $username;
    private $password;
    private $apiToken;
    private $url;
    private $from;

    public function __construct($username, $password, $url, $apiToken, $from)
    {
        $this->setUsername($username)
             ->setPassword($password)
             ->setUrl($url)
             ->setApiToken($apiToken)
             ->setFrom($from)
        ;
    }

    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getApiToken()
    {
        return $this->apiToken;
    }

    public function setApiToken($apiToken): SmsApiGateway
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    public function send($title, $message, array $receivers, $type = null)
    {

        $receivers = implode(PHP_EOL, $receivers);

        $params = array(
            'to'      => $receivers,
            'from'    => $this->from,
            'message' => $message,
            'format'  => 'json',
        );

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->getUrl());
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $params);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . $this->getApiToken()
        ));

        curl_exec($c);
        $http_status = curl_getinfo($c, CURLINFO_HTTP_CODE);

        if ($http_status != 200) {
            return false;
        }

        curl_close($c);

        return true;
    }
}
