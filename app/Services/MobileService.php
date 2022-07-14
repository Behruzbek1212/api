<?php

namespace App\Services;

use App\Constants\MobileServiceConst;
use Illuminate\Support\Facades\Http;
use Nette\Utils\Random;

class MobileService extends MobileServiceConst
{
    protected string $payload;
    protected string $login;
    protected string $password;
    protected string $originator;

    /**
     * PlayMobile service constructor
     * 
     * @return MobileService
     */
    public function __construct()
    {
        $this->payload = env('PLAYMOBILE_PAYLOAD');
        $this->login = env('PLAYMOBILE_LOGIN');
        $this->password = env('PLAYMOBILE_PASSWORD');
        $this->originator = env('PLAYMOBILE_ORIGINATOR');

        return $this;
    }

    /**
     * Send Message to phone number
     * 
     * @param integer|string $phone
     * @param string $message
     * @return array<string, string|integer|null>
     */
    public function send($phone, $message)
    {
        $response = Http::withBasicAuth($this->login, $this->password)
            ->post($this->payload, $this->makeRequest($phone, $message))
            ->json();

        return [
            'status' => $response->error_code ?? null,
            'message' => $response->error_description ?? null,
        ];
    }

    /**
     * Make a POST request
     * 
     * @param integer|string $phone
     * @param string $message
     * @return array|object
     */
    protected function makeRequest($phone, $message)
    {
        return [
            'messages' => [[
                'recipient' => $phone,
                'message-id' => 'jobo' . Random::generate(19, '0-9'),

                'sms' => [
                    'originator' => $this->originator,
                    'content' => [
                        'text' => $message
                    ]
                ]
            ]]
        ];
    }
}
