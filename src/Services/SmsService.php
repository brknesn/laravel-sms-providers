<?php

namespace SuStartX\SMSProviders\Services;

use \Exception;

class SmsService
{
    private $gateway;
    private $message;
    private $recipients;
    private $title;
    private $type;
    private $country_prefix;

    public function __construct($gateway)
    {
        $this->recipients = [];
        $this->gateway    = $gateway;
        $this->country_prefix = env('SMS_COUNTRY_PREFIX', '+9');
    }

    public function preparePhoneNumber($phoneNumber){
        if (strpos($phoneNumber, '+') === 0) {
            return $phoneNumber;
        }
        if(strlen($phoneNumber) == 10 || strlen($phoneNumber) == 11){
            if(strval($phoneNumber[0]) !== '0'){
                $phoneNumber = '0' . $phoneNumber;
            }

            $phoneNumber = $this->country_prefix . $phoneNumber;
        }
        return $phoneNumber;
    }

    public function setCountryPrefix($country_prefix)
    {
        $this->country_prefix = $country_prefix;

        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function addRecipient($phoneNumber)
    {
        $phoneNumber = $this->preparePhoneNumber($phoneNumber);

        if (!in_array($phoneNumber, $this->recipients)) {
            $this->recipients[] = $phoneNumber;
        }

        return $this;
    }

    public function clearRecipients()
    {
        $this->recipients = [];
    }

    public function setType($type): SmsService
    {
        $this->type = $type;

        return $this;
    }

    public function removeRecipient($phoneNumber)
    {
        $phoneNumber = $this->preparePhoneNumber($phoneNumber);

        if (in_array($phoneNumber, $this->recipients)) {
            unset($this->recipients[array_search($phoneNumber, $this->recipients)]);
        }

        return $this;
    }

    public function send()
    {
        return $this->gateway->send($this->title, $this->message, $this->recipients, $this->type);
    }
}
