<?php

namespace App\Http\Controllers;

use App\Moka\Payment;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $data = ["test", "test2"];
        $payment = new Payment($data); 
        $response = $payment->nonPay3d();
    }
}
