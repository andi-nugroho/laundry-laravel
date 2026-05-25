<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota {{ $payment->payment_code }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f3f4f6;
            color: #111827;
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 24px auto;
            background: #ffffff;
            padding: 24mm;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
        }

        .header {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            border-bottom: 2px solid #111827;
            padding-bottom: 18px;
        }

        .brand {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0;
        }

        .muted {
            color: #6b7280;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            margin: 0;
            font-size: 22px;
        }

        .section {
            margin-top: 24px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .box {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
        }

        .label {
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
        }

        .value {
            margin-top: 4px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border-bottom: 1px solid #e5e7eb;
            padding: 10px 0;
            text-align: left;
            vertical-align: top;
        }

        th {
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
        }

        .text-right {
            text-align: right;
        }

        .total-row td {
            border-bottom: 0;
            font-size: 16px;
            font-weight: 700;
        }

        .status {
            display: inline-block;
            border-radius: 999px;
            padding: 4px 10px;
            background: #dcfce7;
            color: #166534;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .actions {
            width: 210mm;
            margin: 0 auto 24px;
            text-align: right;
        }

        .button {
            border: 0;
            border-radius: 6px;
            background: #4f46e5;
            color: #ffffff;
            cursor: pointer;
            font-weight: 700;
            padding: 10px 16px;
        }

        @media print {
            body {
                background: #ffffff;
            }

            .page {
                width: auto;
                min-height: auto;
                margin: 0;
                box-shadow: none;
                padding: 18mm;
            }

            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="actions">
        <button class="button" onclick="window.print()">Cetak Nota</button>
    </div>

    <main class="page">
        <header class="header">
            <div>
                <div class="brand">Laundry Laravel</div>
                <div class="muted">Nota pembayaran laundry</div>
            </div>
            <div class="invoice-title">
                <h1>NOTA PEMBAYARAN</h1>
                <div class="muted">{{ $payment->payment_code }}</div>
            </div>
        </header>

        <section class="section grid">
            <div class="box">
                <div class="label">Customer</div>
                <div class="value">{{ $payment->booking?->customer?->name ?? '-' }}</div>
                <div class="muted">{{ $payment->booking?->customer?->phone ?? '-' }}</div>
            </div>
            <div class="box">
                <div class="label">Detail Pembayaran</div>
                <div class="value">{{ $payment->payment_date?->format('d M Y H:i') }}</div>
                <div class="muted">Kasir: {{ $payment->processedBy?->name ?? '-' }}</div>
            </div>
        </section>

        <section class="section">
            <table>
                <tbody>
                    <tr>
                        <th>Booking Code</th>
                        <td class="text-right">{{ $payment->booking?->booking_code ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Layanan Laundry</th>
                        <td class="text-right">{{ $payment->booking?->service?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Berat Laundry</th>
                        <td class="text-right">
                            {{ $payment->booking?->weight ? number_format($payment->booking->weight, 2, ',', '.') . ' kg' : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Harga per Kg</th>
                        <td class="text-right">
                            Rp {{ number_format($payment->booking?->service?->price_per_kg ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td class="text-right">{{ ucfirst($payment->payment_method) }}</td>
                    </tr>
                    <tr>
                        <th>Status Pembayaran</th>
                        <td class="text-right"><span class="status">{{ $payment->payment_status }}</span></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="section">
            <table>
                <tbody>
                    <tr>
                        <th>Total Tagihan</th>
                        <td class="text-right">Rp {{ number_format($payment->total_bill, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Dibayar</th>
                        <td class="text-right">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Kembalian</td>
                        <td class="text-right">Rp {{ number_format($payment->change_amount, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        @if ($payment->notes)
            <section class="section box">
                <div class="label">Catatan</div>
                <div class="value">{{ $payment->notes }}</div>
            </section>
        @endif

        <section class="section muted">
            Terima kasih telah menggunakan layanan Laundry Laravel.
        </section>
    </main>
</body>
</html>
