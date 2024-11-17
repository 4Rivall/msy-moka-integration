<?php

namespace App\Moka\Information;

use App\Moka\MokaService;
use App\Moka\Payment;
use App\Moka\UrlParams;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class BinCheck extends MokaService
{

    public function getBin($data)
    {
        $url = UrlParams::POST_URI.UrlParams::GET_BIN;

        Log::info('Sending Get Bin request to Moka API', [
            'url' => $url,
            'params' => $data
        ]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json', // JSON içeriği gönderdiğimizi belirtiyoruz
        ])
        ->withoutVerifying() // SSL doğrulamasını devre dışı bırakır (cURL'deki CURLOPT_SSL_VERIFYPEER ile aynı)
        ->post($url, $data); // $data zaten bir array, bu JSON'a dönüştürülür

        $result = $response->json();

        //Get ResultCode, ResultMessage, and Data
        $resultCode = $result['ResultCode'] ?? null;
        $resultMessage = $result['ResultMessage'] ?? null;
        $data = $result['Data'] ?? null;

       
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