@component('mail::message')
# Invoice Pembelian
# Halo {{ $order->user_email ?? 'Customer' }},

Terima kasih telah melakukan pembelian.

**Nomor Invoice:** {{ $order->invoice_number }}  
**Total:** Rp{{ number_format($order->total_amount, 0, ',', '.') }}

@component('mail::button', ['url' => config('app.url')])
Lihat Detail
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
