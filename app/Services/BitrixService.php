<?php

namespace App\Services;

class BitrixService
{
    // protected string $payload;
    // protected string $login;
    // protected string $password;
    // protected string $originator;

    /**
     * PlayMobile service constructor
     * 
     * @return MobileService
     */
    public function __construct()
    {
        // $this->payload = env('PLAYMOBILE_PAYLOAD');
        // $this->login = env('PLAYMOBILE_LOGIN');
        // $this->password = env('PLAYMOBILE_PASSWORD');
        // $this->originator = env('PLAYMOBILE_ORIGINATOR');

        return $this;
    }

    /**
     * Send Message to phone number
     * 
     * @param integer|string $phone
     * @param string $message
     * @return void
     */
    public function send($phone, $message)
    {
        //
    }

    /**
     * 
     * @param integer|string $phone
     * @param string $message
     * @return array|object
     */
    protected function makeRequest($phone, $message)
    {
        //
    }
}
