<?php

namespace App\Moka\Payment\Card;

use App\Moka\MokaService;
use App\Moka\Payment;
use App\Moka\UrlParams;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SaveCardProcessor extends MokaService
{


    public function __construct() {}

    /**
     * Moka API'ye POST isteği gönder
     *
     * @param string $url
     * @param array $params
     * @return array
     */
    public function saveCardProcess($data)
    {
        $url = UrlParams::ORTAK_URI;

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
            return $this->coPaymentResponse($resultCode, $errorMessage, $resultMessage, $data);
        }

        // Başarılıysa Data içindeki URL'yi al ve yönlendir
        $paymentUrl = $data['Url'] ?? null;

        if ($paymentUrl) {
            Log::info('Redirecting to Moka payment page', ['Url' => $paymentUrl]);
            return redirect()->away($paymentUrl);
        }

        // Eğer URL alınamazsa hata döndür
        Log::error('Payment URL not found in Moka response', ['Response' => $responseData]);
        return $this->coPaymentResponse('999', 'Payment URL not found', null, null);



        return $this->coPaymentResponse($resultCode, $errorMessage, $resultMessage, $data);
    }


    public function test(array $paymentDetails, array $authData): array
    {
        // Doğrudan veri tipini belirtmek zor olsa da, parametreleri doğrulamak için içeriği kontrol edebiliriz.
        return [
            'PaymentDealerAuthentication' => [
                'DealerCode' => (string) $authData['DealerCode'],
                'Username' => (string) $authData['Username'],
                'Password' => (string) $authData['Password'],
                'CheckKey' => (string) $authData['CheckKey'],
            ],
            'PaymentDealerRequest' => [
                'CardHolderFullName' => (string) $paymentDetails['CardHolderFullName'],
                'CardNumber' => (string) $paymentDetails['CardNumber'],
                'ExpMonth' => (string) $paymentDetails['ExpMonth'],
                'ExpYear' => (string) $paymentDetails['ExpYear'],
                'CvcNumber' => (string) $paymentDetails['CvcNumber'],
                'CardToken' => (string) $paymentDetails['CardToken'],
                'Amount' => (float) $paymentDetails['Amount'],  // Zorunlu float tipine dönüştürme
                'Currency' => (string) $paymentDetails['Currency'],
                'InstallmentNumber' => (int) $paymentDetails['InstallmentNumber'],  // Zorunlu integer tipine dönüştürme
                'ClientIP' => (string) $paymentDetails['ClientIP'],
                'OtherTrxCode' => (string) $paymentDetails['OtherTrxCode'],
                'SubMerchantName' => (string) $paymentDetails['SubMerchantName'],
                'IsPoolPayment' => (int) $paymentDetails['IsPoolPayment'],
                'IsTokenized' => (int) $paymentDetails['IsTokenized'],
                'IntegratorId' => (int) $paymentDetails['IntegratorId'],
                'Software' => (string) $paymentDetails['Software'],
                'Description' => (string) $paymentDetails['Description'],
                'IsPreAuth' => (int) $paymentDetails['IsPreAuth'],
                'BuyerInformation' => (array) $paymentDetails['BuyerInformation'],  // BuyerInformation zorunlu array
                'CustomerInformation' => (array) $paymentDetails['CustomerInformation'],  // CustomerInformation zorunlu array
                'BasketProduct' => (array) $paymentDetails['BasketProduct'],  // BasketProduct zorunlu array
            ]
        ];
    }
}
