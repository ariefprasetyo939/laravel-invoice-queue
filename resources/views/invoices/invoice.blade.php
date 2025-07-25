<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            padding: 30px;
        }
        h1, h2, h3 {
            margin-bottom: 0;
        }
        .header {
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .footer {
            border-top: 1px solid #ccc;
            margin-top: 30px;
            padding-top: 10px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
        .invoice-box {
            width: 100%;
        }
        .invoice-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-box td {
            padding: 8px;
        }
        .invoice-box th {
            background: #f5f5f5;
            font-weight: bold;
            padding: 8px;
            text-align: left;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
        }
        .right {
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Invoice</h2>
        <p>No: <strong>{{ $order->invoice_number }}</strong></p>
        <p>Tanggal: {{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</p>
    </div>

    <div class="invoice-box">
        <table>
            <tr>
                <th>Detail Pemesan</th>
                <th>Status Pembayaran</th>
            </tr>
            <tr>
                <td>
                    Email: {{ $order->user_email }}<br>
                    ID Order: {{ $order->invoice_number }}
                </td>
                <td>
                    <strong>{{ strtoupper($order->status) }}</strong>
                </td>
            </tr>
        </table>

        <br>

        <table>
            <tr>
                <th>Deskripsi</th>
                <th class="right">Jumlah</th>
            </tr>
            <tr>
                <td>Pembelian Produk</td>
                <td class="right">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="total">Total</td>
                <td class="total right">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Terima kasih telah melakukan pembelian.<br>
        Invoice ini dibuat secara otomatis oleh sistem.
    </div>

</body>
</html>