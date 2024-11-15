<?php

namespace App\Moka\Payment;


use App\Moka\MokaService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class NonThreeDSecurePayment
{
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
        // Moka Authentication Request
        $authData = [
            'DealerCode' => $paymentDetails['DealerCode'],
            'Username' => $paymentDetails['Username'],
            'Password' => $paymentDetails['Password'],
            'CheckKey' => $this->generateCheckKey($paymentDetails)
        ];

        // Payment Request
        $paymentData = [
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
            'CustomerInformation' => $paymentDetails['CustomerInformation']
        ];

        // Send Payment Request to Moka API
        $response = $this->sendPaymentRequest($authData, $paymentData);

        return $this->handleResponse($response);
    }

    /**
     * Generate CheckKey for authentication.
     *
     * @param array $paymentDetails
     * @return string
     */
    private function generateCheckKey(array $paymentDetails): string
    {
        $checkString = $paymentDetails['DealerCode'] . 'MK' . $paymentDetails['Username'] . 'PD' . $paymentDetails['Password'];
        return hash('sha256', $checkString);
    }

    /**
     * Send payment request to Moka API.
     *
     * @param array $authData
     * @param array $paymentData
     * @return Response
     */
    private function sendPaymentRequest(array $authData, array $paymentData)
    {
        return Http::post('https://api.moka.com.tr/PaymentDealer/DoDirectPayment', [
            'PaymentDealerAuthentication' => $authData,
            'PaymentDealerRequest' => $paymentData,
        ]);
    }

    /**
     * Handle the response from Moka API.
     *
     * @param Response $response
     * @return array
     */
    private function handleResponse(Response $response): array
    {
        $data = $response->json();

        if ($data['ResultCode'] === 'Success' && $data['Data']['IsSuccessful'] === true) {
            return [
                'success' => true,
                'VirtualPosOrderId' => $data['Data']['VirtualPosOrderId']
            ];
        }

        return [
            'success' => false,
            'error' => $data['ResultMessage'] ?? 'Unknown error',
            'ResultCode' => $data['ResultCode']
        ];
    }
}
