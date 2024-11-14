<?php

namespace App\Moka\Payment\ThreeDSecurePayment;


use App\Moka\Payment\ThreeDSecurePayment\ThreeDSecurePaymentInterface;
use App\Moka\Payment\ThreeDSecurePayment\ThreeDSecurePaymentTrait;

final class ThreeDSecurePaymentProcessor implements ThreeDSecurePaymentInterface
{
    use ThreeDSecurePaymentTrait;

    protected array $config;

    public function init(array $config): void
    {
        $this->config = $config;
    }

    public function processPayment(): bool
    {
        // Ödeme işlemini başlat
        // Bu örnekte şematik bir yer tutucu
        $this->paymentStatus = 'success';
        $this->paymentDetails = [
            'transaction_id' => '1234567890',
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->paymentStatus
        ];

        return $this->paymentStatus === 'success';
    }

}
