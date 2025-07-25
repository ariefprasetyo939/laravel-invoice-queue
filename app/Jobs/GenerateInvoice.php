<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;

class GenerateInvoice implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;
    public $orderId;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        //generate invoice, dan simpan dalam storage
        Log::info("Generate invoice for order {$this->orderId}");
        $order  = Order::find($this->orderId);

        if(!$order){
            Log::error("Order {$this->orderId} not found");
            return;
        }

        try {
            $pdf = PDF::loadView('invoices.invoice', compact('order'));
            $invoicePath = 'invoices/'.$order->invoice_number.'.pdf';

            if(!Storage::disk('local')->exists('invoices')){
                Storage::makeDirectory('invoices');
                Log::info("Invoice directory created");
            }

            Storage::disk('local')->put($invoicePath, $pdf->output());

            Log::info("Invoice for order {$this->orderId} generated");

            // dispatch job untuk mengirim email
            SendInvoiceEmail::dispatch($order, $invoicePath);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error("Error generating invoice for order {$this->orderId}: {$th->getMessage()}");
        }
        
    }
}
