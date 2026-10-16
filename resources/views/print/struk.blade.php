<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk {{ $transaction->nomor_struk }}</title>
    <style>
        /* Pengaturan ukuran kertas kasir thermal (biasanya 58mm atau 80mm) */
        body { font-family: 'Courier New', Courier, monospace; width: 300px; margin: 0 auto; padding: 10px; font-size: 13px; color: #000; }
        .center { text-align: center; }
        .right { text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        td { padding: 2px 0; }
        .border-top { border-top: 1px dashed #000; padding-top: 5px; margin-top: 5px; }
        .border-bottom { border-bottom: 1px dashed #000; padding-bottom: 5px; margin-bottom: 5px; }
        @media print {
            @page { margin: 0; }
            body { margin: 0.5cm; }
        }
    </style>
</head>
<body onload="window.print()"> <!-- Otomatis memunculkan dialog print -->
    
    <div class="center border-bottom">
        <h2 style="margin: 0 0 5px 0;">TOKO GLASSMORPHISM</h2>
        <p style="margin: 0 0 10px 0;">Jl. Teknologi No. 99, Surabaya<br>Telp: 0812-3456-7890</p>
    </div>

    <table>
        <tr><td>No. Struk</td><td class="right">{{ $transaction->nomor_struk }}</td></tr>
        <tr><td>Kasir</td><td class="right">{{ $transaction->user->name ?? 'Admin' }}</td></tr>
        <tr><td>Waktu</td><td class="right">{{ $transaction->created_at->format('d/m/Y H:i') }}</td></tr>
    </table>

    <div class="border-bottom"></div>

    <table>
        @foreach($transaction->details as $detail)
        <tr>
            <td colspan="2">{{ $detail->product->nama_produk ?? 'Produk Dihapus' }}</td>
        </tr>
        <tr>
            <td>{{ $detail->kuantitas }} x {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
            <td class="right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="border-top border-bottom">
        <table>
            <tr><td><strong>TOTAL</strong></td><td class="right"><strong>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</strong></td></tr>
            <tr><td>TUNAI</td><td class="right">Rp {{ number_format($transaction->uang_dibayar, 0, ',', '.') }}</td></tr>
            <tr><td>KEMBALI</td><td class="right">Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td></tr>
        </table>
    </div>

    <div class="center" style="margin-top: 15px;">
        <p>Terima Kasih Atas Kunjungan Anda<br>Barang yang sudah dibeli tidak dapat ditukar.</p>
    </div>

</body>
</html>