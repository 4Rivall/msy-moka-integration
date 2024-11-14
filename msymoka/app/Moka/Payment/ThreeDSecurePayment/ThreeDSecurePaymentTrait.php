<?php

namespace App\Moka\Payment\ThreeDSecurePayment;

trait ThreeDSecurePaymentTrait
{
    protected float $amount;
    protected string $currency;
    protected string $paymentStatus;
    protected array $paymentDetails;

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getPaymentStatus(): string
    {
        return $this->paymentStatus;
    }

    public function getPaymentDetails(): array
    {
        return $this->paymentDetails;
    }
}
