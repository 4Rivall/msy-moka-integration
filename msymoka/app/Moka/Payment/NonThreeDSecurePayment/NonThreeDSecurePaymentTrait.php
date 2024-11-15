<?php

namespace App\Moka\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

trait NonThreeDSecurePaymentTrait
{
    /**
     * Prepare payment data for the API request.
     *
     * @param array $paymentDetails
     * @return array
     */
    public function preparePaymentData(array $paymentDetails, array $authData): array
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
    
    

    /**
     * Handle the response from Moka API.
     *
     * @param array $response
     * @return array
     */
    public function handleResponse(array $response): array
    {
        if ($response['ResultCode'] === 'Success' && $response['Data']['IsSuccessful'] === true) {
            return [
                'success' => true,
                'VirtualPosOrderId' => $response['Data']['VirtualPosOrderId'],
            ];
        }

        return [
            'success' => false,
            'error' => $response['ResultMessage'] ?? 'Unknown error',
            'ResultCode' => $response['ResultCode'],
        ];
    }

    /**
     * Send SMS notification.
     *
     * @param string $phoneNumber
     * @param string $message
     */
    protected function sendSms(string $phoneNumber, string $message): void {}

    /**
     * Send email notification.
     *
     * @param string $email
     * @param string $subject
     * @param string $message
     */
    protected function sendEmail(string $email, string $subject, string $message): void {}

  
}
