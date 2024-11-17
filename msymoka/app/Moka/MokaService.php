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
     * API response format
     *
     * @param string $resultCode
     * @param string|null $resultMessage
     * @param string|null $exception
     * @param mixed $data
     * @return array
     */
    protected function apiResponse(string $resultCode, ?string $resultMessage, ?string $exception, mixed $data): array
    {
        return [
            'ResultCode' => $resultCode,
            'ResultMessage' => $resultMessage,
            'Exception' => $exception,
            'Data' => $data,
        ];
    }
}
