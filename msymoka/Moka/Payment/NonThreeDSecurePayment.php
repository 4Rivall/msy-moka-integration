<?php

namespace App\Services\Moka\Payment;

use App\Services\MokaService;
use Illuminate\Support\Facades\Http;

class NonThreeDSecurePayment
{
    protected $mokaService;

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
    public function processPayment(array $paymentDetails)
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
    private function generateCheckKey(array $paymentDetails)
    {
        $checkString = $paymentDetails['DealerCode'] . 'MK' . $paymentDetails['Username'] . 'PD' . $paymentDetails['Password'];
        return hash('sha256', $checkString);
    }

    /**
     * Send payment request to Moka API.
     *
     * @param array $authData
     * @param array $paymentData
     * @return \Illuminate\Http\Client\Response
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
     * @param \Illuminate\Http\Client\Response $response
     * @return array
     */
    private function handleResponse($response)
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

/* Example: 

use App\Services\Moka\Payment\ThreeDSecurePayment;

$paymentDetails = [
    'DealerCode' => 'xxx',
    'Username' => 'xxx',
    'Password' => 'xxx',
    'CardHolderFullName' => 'Ali Yılmaz',
    'CardNumber' => '5555666677778888',
    'ExpMonth' => '09',
    'ExpYear' => '2024',
    'CvcNumber' => '123',
    'CardToken' => '',
    'Amount' => 0.01,
    'Currency' => 'TL',
    'InstallmentNumber' => 1,
    'ClientIP' => '192.168.1.116',
    'OtherTrxCode' => '20210114170108',
    'SubMerchantName' => '',
    'IsPoolPayment' => 0,
    'IsTokenized' => 0,
    'IntegratorId' => 0,
    'Software' => 'Possimulation',
    'Description' => '',
    'IsPreAuth' => 0,
    'BuyerInformation' => [
        'BuyerFullName' => 'Ali Yılmaz',
        'BuyerGsmNumber' => '5551110022',
        'BuyerEmail' => 'aliyilmaz@xyz.com',
        'BuyerAddress' => 'Tasdelen / Çekmeköy'
    ],
    'CustomerInformation' => [
        'DealerCustomerId' => '',
        'CustomerCode' => '1234',
        'FirstName' => 'Ali',
        'LastName' => 'Yılmaz',
        'Gender' => '1',
        'BirthDate' => '',
        'GsmNumber' => '',
        'Email' => 'aliyilmaz@xyz.com',
        'Address' => '',
        'CardName' => 'Maximum kartım'
    ]
];

$paymentService = new ThreeDSecurePayment(new MokaService());
$response = $paymentService->processPayment($paymentDetails);

if ($response['success']) {
    echo 'Payment Successful. VirtualPosOrderId: ' . $response['VirtualPosOrderId'];
} else {
    echo 'Payment Failed. Error: ' . $response['error'];
}
 

*/
