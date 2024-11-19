<?php

namespace App\Moka\Payment\ThreeDSecurePayment;

interface ThreeDSecurePaymentInterface
{

    public function init (array $config): void;

    public function setAmount(float $amount): void;

    public function setCurrency(string $currency): void;

    public function process(): bool;

    public function getPaymentStatus(): string;

    public function getPaymentDetails(): array;
}
