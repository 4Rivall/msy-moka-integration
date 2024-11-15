<?php
namespace App\Moka;

use App\Moka\Payment\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class MokaService
{
    private string $dealerCode;
    private string $username;
    private string $password;
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
     * Kimlik doğrulama verilerini döndürme
     */
    public function getAuthData(): array
    {
        return [
            'DealerCode' => $this->dealerCode,
            'Username' => $this->username,
            'Password' => $this->password,
            'CheckKey' => $this->generateCheckKey()
        ];
    }

    /**
     * Moka API'ye POST isteği gönder
     *
     * @param string $url
     * @param array $params
     * @return array
     */
    public function sendRequest(string $url, array $paymentData): array
    {

        // Log the request details (optional)
        Log::info('Sending request to Moka API', [
            'url' => $url,
            'params' => $paymentData
        ]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json', // JSON içeriği gönderdiğimizi belirtiyoruz
        ])->post($url, $paymentData); // $paymentData zaten bir array, bu JSON'a dönüştürülür
        

        // Parse the JSON response from Moka
        $responseData = $response->json();

        // Get ResultCode, ResultMessage, and Data
        $resultCode = $responseData['ResultCode'] ?? null;
        $resultMessage = $responseData['ResultMessage'] ?? null;
        $data = $responseData['Data'] ?? null;

        // If there's an error, get the error message
        if ($resultCode && $resultCode !== '000' && !$data) {
            $errorMessage = Payment::getErrorMessage($resultCode);

            // Log the error
            Log::error('Moka API Request failed', [
                'ResultCode' => $resultCode,
                'ResultMessage' => $resultMessage,
                'Error' => $errorMessage,
                'Params' => $paymentData
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

        // Return the successful response
        return $this->apiResponse($resultCode, $resultMessage, null, $data);
    }

    /**
     * API response format
     *
     * @param string $resultCode
     * @param string|null $resultMessage
     * @param string|null $exception
     * @param mixed $data
     * @return array
     */
    private function apiResponse(string $resultCode, ?string $resultMessage, ?string $exception, mixed $data): array
    {
        return [
            'ResultCode' => $resultCode,
            'ResultMessage' => $resultMessage,
            'Exception' => $exception,
            'Data' => $data,
        ];
    }
}
