<?php 

namespace App\Moka\Payment;

use App\Moka\MokaService;
use App\Services\NotificationService;
use App\Services\MokaApiService;

class NonThreeDSecurePaymentProcessor implements NonThreeDSecurePaymentInterface
{
    use NonThreeDSecurePaymentTrait;

    protected MokaService $mokaService;

    public function __construct(MokaService $mokaService)
    {
        $this->mokaService = $mokaService;
    }

     /**
     * Process a non-3D secure payment.
     *
     * @param array $paymentDetails
     * @return array
     */
    public function processPayment(array $paymentDetails): array
    {
        $authData = $this->mokaService->getAuthData();
        
        $paymentData = $this->preparePaymentData($authData, $paymentDetails);

        $response = $this->mokaService->sendRequest($paymentData);

        // Notifications
        $this->sendSms(
            $paymentDetails['PhoneNumber'],
            'Your payment has been processed successfully!'
        );

        $this->sendEmail(
            $paymentDetails['Email'],
            'Payment Confirmation',
            'Your payment was successful. Thank you!'
        );

        return $this->handleResponse($response);
        
    }


}
