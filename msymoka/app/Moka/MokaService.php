<?php

namespace App\Moka;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MokaService
{
    protected string $dealerCode;
    protected string $username;
    protected string $password;
    protected string $checkKey;

    public function __construct()
    {
        $this->dealerCode = config('services.moka.dealer_code');
        $this->username = config('services.moka.username');
        $this->password = config('services.moka.password');
        $this->checkKey = $this->generateCheckKey();
    }

    /**
     * SHA-256 ile CheckKey oluşturma
     */
    private function generateCheckKey(): string
    {
        $checkString = $this->dealerCode . 'MK' . $this->username . 'PD' . $this->password;
        return hash('sha256', $checkString);
    }

    /**
     * Non-3D Ödeme talebi gönder
     */
    public function doDirectPayment(array $paymentData): array
    {
        $authenticationData = [
            'DealerCode' => $this->dealerCode,
            'Username'   => $this->username,
            'Password'   => $this->password,
            'CheckKey'   =>  $this->checkKey,
        ];

        $data = [
            'PaymentDealerAuthentication' => $authenticationData,
            'PaymentDealerRequest' => $paymentData,
        ];

        $response = Http::post(config('services.moka.endpoints.payment'), $data);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Moka Payment Error: ', $response->json());
        return [
            'ResultCode' => 'Error',
            'ResultMessage' => 'An error occurred during payment processing.'
        ];
    }
}
