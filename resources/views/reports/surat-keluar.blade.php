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
            /* padding-left: 60px; */
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
                <span class="box-label">BERDASARKAN SPMB NOMOR : 1215/BP.14.01</span>
            </div>
            <div class="box-item">
                <span class="box-label">TANGGAL : 3 Juni 2024</span>
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
            <th>NO</th>
            <th>NAMA BARANG</th>
            <th>JUMLAH</th>
            <th>SATUAN</th>
            <th>NOMOR BATCH</th>
            <th>BATAS KADALUWARSA</th>
            <th>HARGA SATUAN</th>
            <th>TOTAL NILAI BARANG</th>
            <th>SUMBER DANA</th>
            <th>TITIK PERMINTAAN DARURAT</th>
            <th>TITIK STOK REALOKASI TERKINI</th>
        </tr>
        @foreach($suratKeluar->barangTransaksis as $transaksi)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $transaksi->barangMaster->nama_barang }}</td>
            <td>{{ $transaksi->jumlah }}</td>
            <td>{{ $transaksi->barangMaster->satuan }}</td>
            <td>{{ $transaksi->barangMaster->nomor_batch }}</td>
            <td>{{ $transaksi->kadaluarsa }}</td>
            <td>Rp{{ number_format($transaksi->barangMaster->harga_satuan, 2, ',', '.') }}</td>
            <td>Rp{{ number_format($transaksi->total_harga, 2, ',', '.') }}</td>
            <td>{{ $transaksi->barangMaster->sumber_dana }}</td>
            <td>{{ $transaksi->titik_permintaan_darurat }}</td>
            <td>{{ $transaksi->titik_stok_realokasi }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="7" class="right-align" style="font-weight: bold;">TOTAL NILAI BARANG</td>
            <td class="right-align">Rp{{ number_format($suratKeluar->barangTransaksis->sum('total_harga'), 2, ',', '.') }}</td>
            <td colspan="3"></td>
        </tr>
    </table>

    <div class="signature" style="font-size: 20px;"> <!-- Increased font size -->
    <table class="no-border" style="border: none; width: 100%;"> <!-- Set table width to 100% -->
        <tr>
            <td>Jakarta Timur, ...........</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Yang mengeluarkan<br>Bendahara Materiil</td>
            <td>Yang Mengangkut<br>Mengambil/Mengantar</td>
            <td style="text-align: center;">Yang Menerima</td> <!-- Right-align the header -->
        </tr>
        <tr>
        <td>
            <div style="height: 40px; width: 200px;"></div> <!-- Blank space using a div -->
            </td>
            <td>
                <div style="height: 40px; width: 200px;"></div> <!-- Blank space using a div -->
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
            <td style="text-align: center;"> <!-- Right-align the content -->
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
