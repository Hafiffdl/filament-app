<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Bukti Barang Keluar</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 50px;
            float: left;
        }
        .title {
            font-weight: bold;
            font-size: 16px;
            margin: 5px 0;
        }
        .subtitle {
            font-size: 14px;
            margin: 5px 0;
            text-align: center;
        }
        .document-title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-border {
            border: none;
        }
        .checkbox {
            width: 12px;
            height: 12px;
            border: 1px solid black;
            display: inline-block;
            margin-right: 5px;
        }
        .checked {
            background-color: black;
        }
        .signature {
            margin-top: 20px;
        }
        .inline-block {
            display: inline-block;
            margin-right: 10px;
        }
        .right-align {
            text-align: right;
        }
        .box-container {
            border: 1px solid black;
            padding: 5px;
            margin-bottom: 15px;
        }
        .box-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .box-item {
            display: flex;
            align-items: center;
        }
        .box-label {
            margin-right: 10px;
        }
        .recipient-info {
            border: 1px solid black;
            padding: 5px;
            margin-bottom: 15px;
        }
        .recipient-info p {
            margin: 0;
            padding: 2px 0;
        }
        table.no-border,
        table.no-border td {
            border: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="logo-jkt.png" style="width: 150px; height: 100px;"/>
        </div>
        <div class="title">Sudin PPAPP Kota Administrasi Jakarta Timur</div>
        <div class="subtitle">JL. P. Revolusi Pondok Bambu, Duren Sawit, Jakarta Timur</div>
    </div>

    <div class="document-title">SURAT BUKTI BARANG KELUAR</div>

    <table class="no-border">
        <tr>
            <td class="no-border" style="width: 0px;">NOMOR</td>
            <td class="no-border" style="width: 200px;">: {{ $suratKeluar->nomor }}</td>
            <td class="no-border" style="text-align: right;">TANGGAL: {{ $suratKeluar->tanggal ? $suratKeluar->tanggal->format('d F Y') : 'N/A' }}</td>
        </tr>
    </table>

    <div class="recipient-info">
        <p>KEPADA      : {{ $suratKeluar->faskes->nama ?? 'N/A' }}</p>
        <p>ALAMAT      : {{ $suratKeluar->faskes->alamat ?? 'N/A' }}</p>
        <p>KODE FASKES : {{ $suratKeluar->faskes->kode_faskes ?? 'N/A' }}</p>
    </div>

    <table class="no-border">
        <tr>
            <td class="no-border" style="width: 150px;">Untuk Keperluan</td>
            <td class="no-border">
                <label class="inline-block">
                    <span class="checkbox {{ $suratKeluar->keperluan == 'Distribusi Rutin' ? 'checked' : '' }}"></span>
                    Distribusi Rutin
                </label>
                <label class="inline-block">
                    <span class="checkbox {{ $suratKeluar->keperluan == 'Distribusi Non Rutin' ? 'checked' : '' }}"></span>
                    Distribusi Non Rutin (permintaan darurat atau lainnya)
                </label>
            </td>
        </tr>
    </table>

    <div class="box-container">
        <div class="box-row">
            <div class="box-item">
                <span class="box-label">BERDASARKAN SPMB NOMOR : {{ $suratKeluar->spmb_nomor }}</span>
            </div>
            <div class="box-item">
                <span class="box-label">TANGGAL : {{ $suratKeluar->tanggal ? $suratKeluar->tanggal->format('d F Y') : 'N/A' }}</span>
            </div>
        </div>
        <div class="box-row">
            <div class="box-item">
                <span class="box-label">METODE PENGIRIMAN :</span>
                <label>
                    <span class="checkbox {{ $suratKeluar->metode_pengiriman == 'Dikirim' ? 'checked' : '' }}"></span>
                    Dikirim
                </label>
                <label>
                    <span class="checkbox {{ $suratKeluar->metode_pengiriman == 'Diambil' ? 'checked' : '' }}"></span>
                    Diambil
                </label>
                <label>
                    <span class="checkbox {{ $suratKeluar->metode_pengiriman == 'Pihak Ketiga' ? 'checked' : '' }}"></span>
                    Pihak ketiga
                </label>
            </div>
        </div>
    </div>

    <table>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">NAMA BARANG</th>
            <th rowspan="2">JUMLAH</th>
            <th rowspan="2">SATUAN</th>
            <th colspan="2" style="text-align: center;">SPESIFIKASI</th>
            <th colspan="2" style="text-align: center;">HARGA</th>
            <th rowspan="2">SUMBER DANA</th>
            <th rowspan="2">TITIK PERMINTAAN DARURAT</th>
            <th rowspan="2">TITIK STOK REALOKASI TERKINI</th>
        </tr>
        <tr>
            <th>NOMOR BATCH</th>
            <th>BATAS KADALUWARSA</th>
            <th>HARGA SATUAN</th>
            <th>TOTAL NILAI BARANG</th>
        </tr>
        @foreach($suratKeluar->barangTransaksis as $transaksi)
            @foreach($transaksi->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->barangMaster->nama_barang }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $item->barangMaster->satuan }}</td>
                    <td>{{ $item->barangMaster->nomor_batch }}</td>
                    <td>{{ $item->barangMaster->kadaluarsa }}</td>
                    <td>Rp{{ number_format($item->barangMaster->harga_satuan, 2, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->total_harga, 2, ',', '.') }}</td>
                    <td>{{ $item->barangMaster->sumber_dana }}</td>
                    <td>{{ $item->titik_permintaan_darurat }}</td>
                    <td>{{ $item->titik_stok_realokasi }}</td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="7" class="right-align" style="font-weight: bold;">TOTAL NILAI BARANG</td>
            <td class="right-align">Rp{{ number_format($suratKeluar->barangTransaksis->flatMap(fn($transaksi) => $transaksi->items)->sum('total_harga'), 2, ',', '.') }}</td>
            <td colspan="3"></td>
        </tr>
    </table>

    <div class="signature" style="font-size: 20px;">
    <table class="no-border" style="border: none; width: 100%;">
        <tr>
            <td>Jakarta Timur, ...........</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Yang mengeluarkan<br>Bendahara Materiil</td>
            <td>Yang Mengangkut<br>Mengambil/Mengantar</td>
            <td style="text-align: center;">Yang Menerima</td>
        </tr>
        <tr>
        <td>
            <div style="height: 40px; width: 200px;"></div>
            </td>
            <td>
                <div style="height: 40px; width: 200px;"></div>
            </td>
            <td style="text-align: right; padding-left: 0;">&nbsp;</td>
        </tr>
        <tr>
            <td>
                (Winda Ulaya)<br>
                Pangkat: ..........................<br>
                NIP: 197305021992032001
            </td>
            <td>
                (...............................)<br>
                Pangkat: ..........................<br>
                NIP: ................................
            </td>
            <td style="text-align: center;">
                Nama: .............................<br>
                Pangkat: ..........................<br>
                NIP: ................................
            </td>
        </tr>
    </table>
    </div>

    <p style="margin-top: 50px;">
        <strong>Catatan:</strong><br>
        • Harga satuan yang dicantumkan adalah harga satuan pada saat penerimaan terakhir
    </p>
</body>
</html>
