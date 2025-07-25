<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Log;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $invoicePath;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $invoicePath)
    {
        $this->order = $order;
        $this->invoicePath = $invoicePath;

        Log::info('📧 Constructed InvoiceMail', [
            'email' => $order->user_email,
            'pdf_path' => $invoicePath,
            'exists' => file_exists(Storage::disk('local')->path($invoicePath)),
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Pembelian #' . $this->order->invoice_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.invoice-email',
            with: [
                'order' => $this->order
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // $path = Storage::disk('local')->path($this->invoicePath);
        // dd(file_exists($path));
        //attach file dari storage
        return [
            Attachment::fromPath($this->invoicePath)
                ->as ($this->order->invoice_number.'.pdf')
                ->withMime('application/pdf')
        ];
    }
}
