<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MokaService
{
    protected string $dealerCode;
    protected string $username;
    protected string $password;
    protected $checkKey;

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

    /**
     * Make the 3D Secure payment request.
     */
    public function makeThreeDSecurePayment($paymentData)
    {
        // Build the request body with the provided data
        $requestPayload = [
            'PaymentDealerAuthentication' => [
                'DealerCode' => $this->dealerCode,
                'Username' => $this->username,
                'Password' => $this->password,
                'CheckKey' => $this->checkKey
            ],
            'PaymentDealerRequest' => [
                'CardHolderFullName' => $paymentData['CardHolderFullName'],
                'CardNumber' => $paymentData['CardNumber'],
                'ExpMonth' => $paymentData['ExpMonth'],
                'ExpYear' => $paymentData['ExpYear'],
                'CvcNumber' => $paymentData['CvcNumber'],
                'CardToken' => $paymentData['CardToken'] ?? '',
                'Amount' => $paymentData['Amount'],
                'Currency' => $paymentData['Currency'] ?? 'TL', // Default to TL
                'InstallmentNumber' => $paymentData['InstallmentNumber'] ?? 1, // Default to 1
                'ClientIP' => $paymentData['ClientIP'],
                'OtherTrxCode' => $paymentData['OtherTrxCode'],
                'SubMerchantName' => $paymentData['SubMerchantName'] ?? '',
                'IsPoolPayment' => $paymentData['IsPoolPayment'] ?? 0,
                'IsPreAuth' => $paymentData['IsPreAuth'] ?? 0,
                'IsTokenized' => $paymentData['IsTokenized'] ?? 0,
                'IntegratorId' => $paymentData['IntegratorId'] ?? 0,
                'Software' => $paymentData['Software'],
                'Description' => $paymentData['Description'] ?? '',
                'ReturnHash' => 1, // Mandatory for 3D payment
                'RedirectUrl' => $paymentData['RedirectUrl'],
                'RedirectType' => $paymentData['RedirectType'] ?? 0,
            ]
        ];

        // Send the POST request to Moka API endpoint
        $response = Http::post('https://www.moka.com.tr/PaymentDealer/DoDirectPaymentThreeD', $requestPayload);

        // Handle the response
        return $this->handlePaymentResponse($response);
    }

    private function handlePaymentResponse($response)
    {
        $responseBody = $response->json();

        if ($response->successful()) {
            if ($responseBody['ResultCode'] === 'Success') {
                // Payment successful, return the URL for 3D Secure process
                return [
                    'url' => $responseBody['Data']['Url'],
                    'codeForHash' => $responseBody['Data']['CodeForHash']
                ];
            } else {
                // Handle error or failure
                return [
                    'error' => true,
                    'message' => $responseBody['ResultMessage']
                ];
            }
        } else {
            // Handle request failure
            return [
                'error' => true,
                'message' => $response->body()
            ];
        }
    }
}
