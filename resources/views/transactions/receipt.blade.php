<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 58mm;
            margin: 0;
            padding: 5mm;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .line { border-bottom: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 2px 0; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="center bold">
        CafePOS Pro<br>
        Jl. Contoh No. 123<br>
        Jakarta<br>
        Tel: 021-12345678
    </div>

    <div class="line"></div>

    <div>
        <strong>No. Transaksi:</strong> #{{ $transaction->id }}<br>
        <strong>Tanggal:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }}<br>
        <strong>Kasir:</strong> {{ $transaction->user->name ?? 'N/A' }}
    </div>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Qty</th>
                <th class="right">Harga</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                </tr>
                @if($item->notes)
                    <tr>
                        <td colspan="4" style="font-size: 10px; color: #666;">Catatan: {{ $item->notes }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <div class="right">
        <div>Subtotal: Rp {{ number_format($transaction->subtotal ?? $transaction->total_amount, 0, ',', '.') }}</div>
        @if(isset($transaction->tax_amount) && $transaction->tax_amount > 0)
            <div>Pajak: Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</div>
        @endif
        @if(isset($transaction->discount_amount) && $transaction->discount_amount > 0)
            <div>Diskon: Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</div>
        @endif
        <div class="total">TOTAL: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
    </div>

    <div class="line"></div>

    <div>
        <strong>Metode Bayar:</strong> {{ $transaction->payment_method === 'cash' ? 'Tunai' : $transaction->payment_method }}<br>
        <strong>Status:</strong> {{ $transaction->payment_status === 'paid' ? 'Lunas' : 'Menunggu' }}
    </div>

    @if($transaction->payment_method !== 'cash' && $transaction->tripay_reference)
        <div>
            <strong>Ref Tripay:</strong> {{ $transaction->tripay_reference }}
        </div>
    @endif

    <div class="center" style="margin-top: 10px;">
        Terima Kasih<br>
        Atas Kunjungan Anda!
    </div>

    <div class="center" style="margin-top: 5px; font-size: 10px;">
        Dicetak: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
