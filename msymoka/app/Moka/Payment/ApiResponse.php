<?php

namespace App\Moka;

class ApiResponse
{
    public ?array $data;
    public string $resultCode;
    public ?string $resultMessage;
    public ?string $exception;

    public function __construct(array $response)
    {
        $this->resultCode = $response['ResultCode'] ?? '';
        $this->data = $response['Data'] ?? null;
        $this->resultMessage = $response['ResultMessage'] ?? null;
        $this->exception = $response['Exception'] ?? null;
    }

    /**
     * Check if the response indicates a success.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->resultCode === 'Success' && $this->data !== null;
    }

    /**
     * Get the error message if the resultCode is not success.
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        if ($this->resultCode !== 'Success') {
            return $this->resultMessage ?? 'Unknown error';
        }
        return '';
    }

    /**
     * Get the exception message if any.
     *
     * @return string
     */
    public function getExceptionMessage(): string
    {
        return $this->exception ?? '';
    }
}
