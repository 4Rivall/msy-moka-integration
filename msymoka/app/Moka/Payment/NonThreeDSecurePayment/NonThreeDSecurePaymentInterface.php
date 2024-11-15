<?php

namespace App\Moka\Payment;

interface NonThreeDSecurePaymentInterface
{
/**
     * Process a non-3D secure payment.
     *
     * @param array $paymentDetails
     * @return array
     */
    public function processPayment(array $paymentDetails): array;

}
