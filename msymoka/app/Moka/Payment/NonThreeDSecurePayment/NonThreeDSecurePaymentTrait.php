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
        return [
            'PaymentDealerAuthentication' => [
                'DealerCode' => $authData['DealerCode'],
                'Username' => $authData['Username'],
                'Password' => $authData['Password'],
                'CheckKey' => $authData['CheckKey'],
            ],
            'PaymentDealerRequest' => [
                'CardHolderFullName' => $paymentDetails['CardHolderFullName'],
                'CardNumber' => $paymentDetails['CardNumber'],
                'ExpMonth' => $paymentDetails['ExpMonth'],
                'ExpYear' => $paymentDetails['ExpYear'],
                'CvcNumber' => $paymentDetails['CvcNumber'],
                'CardToken' => $paymentDetails['CardToken'],
                'Amount' => $paymentDetails['Amount'],
                'Currency' => $paymentDetails['Currency'],
                'InstallmentNumber' => $paymentDetails['InstallmentNumber'],
                'ClientIP' => $paymentDetails['ClientIP'],
                'OtherTrxCode' => $paymentDetails['OtherTrxCode'],
                'SubMerchantName' => $paymentDetails['SubMerchantName'],
                'IsPoolPayment' => $paymentDetails['IsPoolPayment'],
                'IsTokenized' => $paymentDetails['IsTokenized'],
                'IntegratorId' => $paymentDetails['IntegratorId'],
                'Software' => $paymentDetails['Software'],
                'Description' => $paymentDetails['Description'],
                'IsPreAuth' => $paymentDetails['IsPreAuth'],
                'BuyerInformation' => $paymentDetails['BuyerInformation'],
                'CustomerInformation' => $paymentDetails['CustomerInformation'],
                'BasketProduct' => $paymentDetails['BasketProduct'],
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
