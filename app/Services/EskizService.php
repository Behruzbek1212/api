<?php
namespace App\Services;
use App\Constants\MobileServiceConst;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class EskizService extends MobileServiceConst
{
    protected $apiUrl;
    protected $email;
    protected $password;

    public function __construct()
    {
        $this->apiUrl = config('services.eskiz.api_url');
        $this->email = config('services.eskiz.email');
        $this->password = config('services.eskiz.password');
    }

    public function authenticate()
    {

        $response = Http::post("{$this->apiUrl}/auth/login", [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $data = $response->json();

        if ($data['message'] === 'token_generated') {
            Cache::put('eskiz_token', $data['data']['token'], 3600);
            return $data['data']['token'];
        }

        return false;
    }

    public function getToken()
    {
        if (Cache::has('eskiz_token')) {
            return Cache::get('eskiz_token');
        }

        return $this->authenticate();
    }

    public function send($phone, $message, $from = 'jobo', $callbackUrl = null)
    {
        try {
            $token = $this->getToken();

            $payload = [
                'mobile_phone' => $phone,
                'message' => $message,
                'from' => 'Jobo' ,
            ];

            $response = Http::withToken($token)
                ->asForm()
                ->post("{$this->apiUrl}/message/sms/send", $payload);

            if ($response->successful()) {
                return $response->json();
            } else {
                // Handle API error responses
                return [
                    'error' => true,
                    'message' => 'Failed to send SMS',
                    'status' => $response->status(),
                ];
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return [
                'error' => true,
                'message' => 'Exception occurred: ' . $e->getMessage(),
            ];
        }
    }
}
