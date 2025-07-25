<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Storage;

class SendInvoiceEmail implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable, SerializesModels;
    public $orderId;
    public $invoicePath;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, $invoicePath)
    {
        $this->orderId = $order->id;
        $this->invoicePath = $invoicePath;
        Log::info("📦 SendInvoiceEmail job constructed", [
            'order_id' => $this->orderId,
            'invoice_path' => $this->invoicePath
        ]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       $order = Order::find($this->orderId);

        if (!$order) {
            Log::error('❌ Order not found when sending email', ['order_id' => $this->orderId]);
            return;
        }

        $absolutePath = Storage::disk('local')->path($this->invoicePath);
        Log::info('📦 PDF file found', ['path' => $absolutePath]);

        if (!file_exists($absolutePath)) {
            Log::error('❌ PDF file not found', ['path' => $absolutePath]);
            return;
        }

        Mail::to($order->user_email)->send(new InvoiceMail($order, $absolutePath));
        Log::info('📧 Invoice email sent!', ['to' => $order->user_email]);
    }
}
