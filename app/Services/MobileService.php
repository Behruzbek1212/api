<?php

namespace App\Services;

use App\Constants\MobileServiceConst;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\ArrayShape;
use Nette\Utils\Random;

class MobileService extends MobileServiceConst
{
    protected string $payload;
    protected string $login;
    protected string $password;
    protected string $originator;

    /**
     * PlayMobile service co    nstructor
     *
     * @return MobileService
     */
    public function __construct()
    {
        $this->payload = config('services.playmobile.payload');
        $this->login = config('services.playmobile.login');
        $this->password = config('services.playmobile.password');
        $this->originator = config('services.playmobile.originator');

        return $this;
    }

    /**
     * Send Message to phone number
     *
     * @param integer|string $phone
     * @param string $message
     * @return array
     */
    #[ArrayShape(['status' => "mixed", 'message' => "mixed"])]
    public function send(int|string $phone, string $message): array
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
     * @return array
     */
    #[ArrayShape(['messages' => "array[]"])]
    protected function makeRequest(int|string $phone, string $message): array
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
