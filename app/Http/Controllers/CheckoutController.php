<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Jobs\GenerateInvoice;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function generateInvoiceFile(){
        //pertama, kita simpann dulu datanya ke database
        $order = Order::create([
            'invoice_number' => 'INV-'.Str::upper(Str::random(8)),
            'user_email' => 'customer-'.Str::random(8).'@example.com',
            'total_amount' => 3500000,
            'status' => 'PAID'
        ]);

        generateInvoice::dispatch($order->id);
        return 'Order Berhasil Dibuat! Invoice akan dikirim melalui email.';
    }
}
