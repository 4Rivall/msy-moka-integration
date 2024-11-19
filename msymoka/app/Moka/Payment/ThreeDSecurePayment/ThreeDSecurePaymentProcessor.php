<?php

namespace App\Moka\Payment\ThreeDSecurePayment;

use App\Moka\MokaService;
use App\Moka\Payment\ThreeDSecurePayment\ThreeDSecurePaymentTrait;
use App\Moka\Payment;
use App\Moka\UrlParams;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class ThreeDSecurePaymentProcessor extends MokaService
{
    use ThreeDSecurePaymentTrait;


    public function __construct() {}


    /**
     * Moka API'ye POST isteği gönder
     *
     * @param string $url
     * @param array $params
     * @return array
     */
    public function process($data)
    {
        $url = UrlParams::POST_URI.UrlParams::_3D_URI;

        // Log the request details (optional)
        Log::info('Sending request to Moka API', [
            'url' => $url,
            'params' => $data
        ]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json', // JSON içeriği gönderdiğimizi belirtiyoruz
        ])
        ->withoutVerifying() // SSL doğrulamasını devre dışı bırakır (cURL'deki CURLOPT_SSL_VERIFYPEER ile aynı)
        ->post($url, $data); // $data zaten bir array, bu JSON'a dönüştürülür

        // Parse the JSON response from Moka
        $responseData = $response->json();

        //Get ResultCode, ResultMessage, and Data
        $resultCode = $responseData['ResultCode'] ?? null;
        $resultMessage = $responseData['ResultMessage'] ?? null;
        $data = $responseData['Data'] ?? null;

        if ($resultCode && $resultCode !== '000' && !$data) {
            $errorMessage = Payment::getErrorMessage($resultCode);

            // Log the error
            Log::error('Moka API Request failed', [
                'ResultCode' => $resultCode,
                'ResultMessage' => $resultMessage,
                'Error' => $errorMessage,
                'Params' => $data
            ]);

            // Return the error details
            return $this->apiResponse($resultCode, $errorMessage, $resultMessage, null);
        }

         // Log the success response
         Log::info('Moka API Request succeeded', [
            'ResultCode' => $resultCode,
            'ResultMessage' => $resultMessage,
            'Data' => $data
        ]);



        return $this->apiResponse($resultCode, $resultMessage, null, $data);
    }
}
