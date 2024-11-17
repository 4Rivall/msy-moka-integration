<?php

namespace App\Http\Controllers;

use App\Moka\MokaService;
use App\Moka\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Env;

class TestController extends Controller
{

    private MokaService $mokaService;

    public function __construct(MokaService $mokaService)
    {
        $this->mokaService = $mokaService;
    }


    public function index(Request $request)
    {

        $data = [
            "PaymentDealerAuthentication" => $this->mokaService->getAuthData(),
            "PaymentDealerRequest" => [
                "CardHolderFullName" => $request->CardHolderFullName,
                "CardNumber" => $request->CardNumber ?? null,
                "ExpMonth" => $request->ExpMonth ?? null,
                "ExpYear" => $request->ExpYear ?? null,
                "CvcNumber" => $request->CvcNumber ?? null,
                "CardToken" => $request->CardToken ?? null,
                "Amount" => $request->Amount,
                "Currency" => $request->Currency ?? null,
                "InstallmentNumber" => $request->InstallmentNumber ?? null,
                "ClientIP" => $request->ClientIP ?? request()->ip(), // ClientIP için varsayılan değeri manuel atıyoruz
                "OtherTrxCode" => $request->OtherTrxCode ?? null,
                "SubMerchantName" => $request->SubMerchantName ?? null,
                "IsPoolPayment" => $request->IsPoolPayment ?? 0,
                "IsTokenized" => $request->IsTokenized ?? 0,
                "Software" => $request->Software ?? env('APP_NAME', 'E-Fixed'),
                "Description" => $request->Description ?? null,
                "IsPreAuth" => $request->IsPreAuth ?? 0,
            ],
            "CustomerInformation" => [
                "DealerCustomerId" => $request->DealerCustomerId ?? null, // Eğer var ise
                "CustomerCode" => $request->CustomerCode ?? null,
                "FirstName" => $request->FirstName ?? null,
                "LastName" => $request->LastName ?? null,
                "Gender" => $request->Gender ?? null,
                "BirthDate" => $request->BirthDate ?? null,
                "GsmNumber" => $request->GsmNumber ?? null,
                "Email" => $request->Email ?? null,
                "Address" => $request->Address ?? null,
                "CardName" => $request->CardName ?? null,
            ]
        ];
        

        $payment = new Payment($data);
        $response = $payment->nonPay3d();
    }


    public function testBin(Request $request)
    {
        $data = [
            "PaymentDealerAuthentication" => $this->mokaService->getAuthData(),
            "BankCardInformationRequest" => [
                'BinNumber' => $request->bin
            ],
        ];

        // Payment nesnesi ile işlem yap
        $payment = new Payment($data);

        $response = $payment->binCheck();

        // Yanıtı döndür veya logla
        return response()->json($response);
    }
}
