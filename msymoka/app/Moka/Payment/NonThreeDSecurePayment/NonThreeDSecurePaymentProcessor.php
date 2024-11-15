<?php 

namespace App\Moka\Payment\NonThreeDSecurePayment;

use App\Moka\Payment;

class NonThreeDSecurePaymentProcessor extends Payment
{


    public function __construct()
    {
        return self::processPayment(dd($this->data));
    }

     /**
     * Process a non-3D secure payment.
     *
     * @param array $paymentDetails
     * @return array
     */
    public function processPayment($paymentDetails)
    {
       
 
    }


}
