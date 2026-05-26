<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Nota {{ $payment->payment_code }}</title>
    <style>
        @page {
            margin: 10px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #ffffff;
            color: #181512;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.35;
        }

        .receipt {
            width: 100%;
            margin: 0 auto;
            padding: 8px 7px 10px;
        }

        .center {
            text-align: center;
        }

        .logo {
            width: 38px;
            height: 38px;
            margin: 0 auto 4px;
            object-fit: contain;
        }

        .brand {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: .08em;
        }

        .subtitle {
            margin-top: 2px;
            color: #62584f;
            font-size: 8.5px;
        }

        .divider {
            margin: 8px 0;
            border-top: 1px dashed #7c7166;
        }

        .row {
            clear: both;
            margin: 3px 0;
        }

        .label {
            float: left;
            width: 45%;
            color: #62584f;
        }

        .value {
            float: right;
            width: 55%;
            text-align: right;
            font-weight: 700;
        }

        .wide-label {
            color: #62584f;
            font-size: 8.5px;
            text-transform: uppercase;
        }

        .wide-value {
            margin-top: 2px;
            font-weight: 800;
        }

        .total {
            font-size: 12px;
            font-weight: 900;
        }

        .muted {
            color: #62584f;
        }

        .status {
            display: inline-block;
            padding: 2px 6px;
            border: 1px solid #181512;
            border-radius: 99px;
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .thanks {
            margin-top: 9px;
            font-size: 8.5px;
            text-align: center;
        }

        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <main class="receipt">
        <div class="center">
            @if ($logoDataUri)
                <img src="{{ $logoDataUri }}" class="logo" alt="VAULTLAUNDRY">
            @endif
            <div class="brand">VAULTLAUNDRY</div>
            <div class="subtitle">Laundry Receipt / Nota Pembayaran</div>
            <div class="subtitle">Jl. Operasional Laundry No. 1</div>
        </div>

        <div class="divider"></div>

        <div class="row"><span class="label">Payment</span><span class="value">{{ $payment->payment_code }}</span></div>
        <div class="row"><span class="label">Booking</span><span class="value">{{ $payment->booking?->booking_code ?? '-' }}</span></div>
        <div class="row"><span class="label">Tanggal</span><span class="value">{{ $payment->payment_date?->format('d/m/Y H:i') }}</span></div>
        <div class="row"><span class="label">Kasir</span><span class="value">{{ $payment->processedBy?->name ?? '-' }}</span></div>
        <div class="clear"></div>

        <div class="divider"></div>

        <div class="wide-label">Customer</div>
        <div class="wide-value">{{ $payment->booking?->customer?->name ?? '-' }}</div>
        <div class="muted">{{ $payment->booking?->customer?->phone ?? '-' }}</div>

        <div class="divider"></div>

        <div class="row"><span class="label">Layanan</span><span class="value">{{ $payment->booking?->service?->name ?? '-' }}</span></div>
        <div class="row">
            <span class="label">Berat</span>
            <span class="value">{{ $payment->booking?->weight ? number_format($payment->booking->weight, 2, ',', '.') . ' kg' : '-' }}</span>
        </div>
        <div class="row">
            <span class="label">Harga/Kg</span>
            <span class="value">Rp {{ number_format($payment->booking?->service?->price_per_kg ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="clear"></div>

        <div class="divider"></div>

        <div class="row total"><span class="label">TOTAL</span><span class="value">Rp {{ number_format($payment->total_bill, 0, ',', '.') }}</span></div>
        <div class="row"><span class="label">Dibayar</span><span class="value">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</span></div>
        <div class="row"><span class="label">Kembali</span><span class="value">Rp {{ number_format($payment->change_amount, 0, ',', '.') }}</span></div>
        <div class="clear"></div>

        <div class="divider"></div>

        <div class="row"><span class="label">Metode</span><span class="value">{{ ucfirst($payment->payment_method) }}</span></div>
        <div class="row"><span class="label">Status</span><span class="value"><span class="status">{{ $payment->payment_status }}</span></span></div>
        <div class="clear"></div>

        @if ($payment->notes)
            <div class="divider"></div>
            <div class="wide-label">Catatan</div>
            <div class="wide-value">{{ $payment->notes }}</div>
        @endif

        <div class="divider"></div>
        <div class="thanks">
            Terima kasih telah menggunakan VAULTLAUNDRY.<br>
            Simpan nota ini sebagai bukti pembayaran.
        </div>
    </main>
</body>
</html>
