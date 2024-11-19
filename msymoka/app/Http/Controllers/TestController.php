<?php

namespace App\Http\Controllers;

use App\Moka\MokaService;
use App\Moka\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Env;

class TestController extends Controller
{

    private MokaService $mokaService;

    public function __construct(MokaService $mokaService)
    {
        $this->mokaService = $mokaService;
    }


    public function index()
    {
        return view('ortakodeme');
    }

    public function testCoPayment(Request $request)
    {

        $data = [
            "PaymentDealerAuthentication" => $this->mokaService->getAuthData(),
            "WebPosRequest" => [
                "Amount" => (float)$request->Amount, // (decimal) The payment amount, stored as a float
                "Currency" => $request->Currency ?? "TL", // (string) Currency code, defaults to "TL"
                "InstallmentNumber" => (int)($request->InstallmentNumber ?? 1), // (integer) The number of installments
                "ClientIP" => $request->ClientIP ?? request()->ip(), // (string) The client IP address
                "OtherTrxCode" => $request->OtherTrxCode ?? 'v001', // (string) Optional unique transaction code
                "ClientWebPosTypeId" => (int)($request->ClientWebPosTypeId ?? 1), // (integer) Web pos type id
                "IsThreeD" => (int)($request->IsThreeD ?? 1), // (int) 3D payment flag
                "IsTokenized" => (int)($request->IsTokenized ?? 1), // (int) Tokenization flag
                "Description" => $request->Description ?? "test açıklama", // (string) Optional description
                "IsPreAuth" => (int)($request->IsPreAuth ?? 0), // (int) Pre-authorization flag
                "Language" => $request->Language ?? "tr", //
                "SubMerchantName" => $request->SubMerchantName ?? "", // (string) Optional sub-merchant name
                "ReturnHash" => (int)1, // (integer) Mandatory value, always 1
                "IsPoolPayment" => (int)($request->IsPoolPayment ?? 0), // (tinyint) Pool payment flag
                "RedirectUrl" => $request->RedirectUrl ?? "https://e-fixed.com.tr", // (string) The redirect URL after payment
                "RedirectType" => (int)($request->RedirectType ?? 0), // (integer) The redirect type (optional)
                "BuyerInformation" => [
                    "BuyerFullName" => $request->BuyerFullName ?? null, // (string) Optional buyer full name
                    "BuyerEmail" => $request->BuyerEmail ?? null, // (string) Optional buyer email
                    "BuyerGsmNumber" => $request->BuyerGsmNumber ?? null, // (string) Optional buyer GSM number
                    "BuyerAddress" => $request->BuyerAddress ?? null, // (string) Optional buyer address
                ] ?? null,
                "CustomerInformation" => [
                    "DealerCustomerId" => (int)($request->DealerCustomerId ?? 6043391), // (integer) Dealer customer ID
                    "CustomerCode" => $request->CustomerCode ?? "Berk0001", // (string) Optional customer code
                    "FirstName" => $request->FirstName ?? null, // (string) Optional first name
                    "LastName" => $request->LastName ?? null, // (string) Optional last name
                    "BirthDate" => $request->BirthDate ? Carbon::createFromFormat('Y-m-d', $request->BirthDate)->format('Ymd') : null,
                    "GsmNumber" => $request->GsmNumber ?? null, // (string) Optional GSM number
                    "Email" => $request->Email ?? null, // (string) Optional email
                    "Address" => $request->Address ?? null, // (string) Optional address
                    "CardName" => $request->CardName ?? 'Yeni Kartım', // (string) Optional card name
                ] ?? null,
            ],
        ];
        

      



        $payment = new Payment($data);
        $response = $payment->ortakOdeme();

        return $response;
    }


    public function x(Request $request)
    {
        $data = [
            "PaymentDealerAuthentication" => $this->mokaService->getAuthData(),
            "WebPosRequest" => [
                "Amount" => $request->Amount,
                "Currency" => $request->Currency ?? "TL",
                "InstallmentNumber" => $request->InstallmentNumber ?? 1,
                "ClientIP" => $request->ClientIP ?? request()->ip(),
                "OtherTrxCode" => $request->OtherTrxCode ?? null,
                "ClientWebPosTypeId" => $request->ClientWebPosTypeId ?? 1,
                "IsThreeD" => $request->IsThreeD ?? 1,
                "IsTokenized" => $request->IsTokenized ?? 0,
                "Description" => $request->Description ?? "test açıklama",
                "IsPreAuth" => $request->IsPreAuth ?? 0,
                "SubMerchantName" => $request->SubMerchantName ?? "",
                "ReturnHash" => 1,
                "RedirectUrl" => $request->RedirectUrl ?? "https://e-fixed.com.tr",
                "RedirectType" => 0,
                "BuyerInformation" => $request->BuyerFullName || $request->BuyerEmail || $request->BuyerGsmNumber || $request->BuyerAddress
                    ? [
                        "BuyerFullName" => $request->BuyerFullName ?? null,
                        "BuyerEmail" => $request->BuyerEmail ?? null,
                        "BuyerGsmNumber" => $request->BuyerGsmNumber ?? null,
                        "BuyerAddress" => $request->BuyerAddress ?? null,
                    ]
                    : null,
                "CustomerInformation" => $request->DealerCustomerId || $request->CustomerCode || $request->FirstName || $request->LastName ||
                    $request->Gender || $request->BirthDate || $request->GsmNumber || $request->Email || $request->Address || $request->CardName
                    ? [
                        "DealerCustomerId" => $request->DealerCustomerId ?? "",
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
                    : null,
            ]
        ];

        $payment = new Payment($data);
        $response = $payment->nonPay3d();

        return $response;
    }

    public function test3d(Request $request)
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
