<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Bukti Barang Keluar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            height: 110px;
        }
        .divider {
            border-top: 3px solid #000;
            margin: 20px 0;
        }
        .logo {
            position: absolute;
            left: 0;
            top: 0;
            right: 450;
        }
        .title-container {
            display: inline-block;
            text-align: center;
            width: 100%;
        }
        .title {
            font-size: 12px;
            margin: 2px 0;
        }
        .subtitle {
            font-size: 14px;
            margin: 5px 0;
            text-align: center;
        }
        .document-title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 14px;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #fff;
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
            font-size: 12px;
        }
        .inline-block {
            display: inline-block;
            margin-right: 10px;
            font-size: 12px;
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
        .label-container {
            display: block;
            margin-bottom: 5px;
        }

        .label {
            display: inline-block;
            width: 100px; /* Sesuaikan lebar label */
            vertical-align: top;

        }

        .separator {
            padding-right: 2px; /* Jarak antara titik dua dan value */
            vertical-align: top;
        }

        .value {
            display: inline-block;
            width: calc(100% - 120px); /* Adjust width calculation */
            word-wrap: break-word; /* Allow text to wrap */
            white-space: normal; /* Allow text to wrap to multiple lines */
            vertical-align: top;
        }
        .recipient-info {
            border: 1px solid black;
            padding: 5px;
            margin-bottom: 15px;
        }
        td.nomor-batch {
        word-wrap: break-word;       /* Memecah teks panjang menjadi beberapa baris */
        word-break: break-word;     /* Memastikan kata panjang terpecah */
        white-space: normal;        /* Membungkus teks secara normal */
        max-width: 150px;           /* Batasi lebar maksimum kolom */
        overflow: hidden;           /* Sembunyikan bagian teks yang melebihi */
        text-overflow: ellipsis;    /* Tambahkan "..." jika teks terpotong */
    }
    td.nama-barang {
        word-wrap: break-word;       /* Memecah teks panjang menjadi beberapa baris */
        word-break: break-word;     /* Memastikan kata panjang terpecah */
        white-space: normal;        /* Membungkus teks secara normal */
        max-width: 150px;           /* Batasi lebar maksimum kolom */
        overflow: hidden;           /* Sembunyikan bagian teks yang melebihi */
        text-overflow: ellipsis;    /* Tambahkan "..." jika teks terpotong */
    }
    </style>
</head>
<body>
<div class="header">
        <div class="logo">
            <img src="logo-jkt.png" alt="Logo Jakarta" style="width: 150px; height: 100px;"/>
        </div>
        <div class="title-container">
            <div class="title">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</div>
            <div class="title">DINAS PEMBERDAYAAN, PERLINDUNGAN ANAK DAN PENGENDALIAN PENDUDUK</div>
            <div class="title" style="font-weight: bold;">SUKU DINAS PEMBERDAYAAN PERLINDUNGAN ANAK DAN</div>
            <div class="title" style="font-weight: bold;">PENGENDALIAN PENDUDUK KOTA ADMINISTRASI JAKARTA TIMUR</div>
            <div>JL. PAHLAWAN REVOLUSI, PONDOK BAMBU</div>
            <div>TELP. : 8612584, FAX. : 8612584</div>
            <div>JAKARTA</div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="document-title">SURAT BUKTI BARANG KELUAR</div>

    <table class="no-border">
        <tr>
            <td class="no-border" style="width: 0px;">NOMOR</td>
            <td class="no-border" style="width: 200px;">: {{ $suratKeluar->nomor }}</td>
            <td class="no-border" style="text-align: right;">TANGGAL:  {{ optional($suratKeluar->barangTransaksis->first())->tanggal_transaksi ? \Carbon\Carbon::parse($suratKeluar->barangTransaksis->first()->tanggal_transaksi)->isoFormat('D MMMM YYYY') : 'N/A' }}</td>
        </tr>
    </table>

    <div class="recipient-info">
        <div class="label-container">
            <span class="label">KEPADA</span>
            <span class="separator">:</span>
            <span class="value">{{ $suratKeluar->faskes->nama ?? 'N/A' }}</span>
        </div>
        <div class="label-container">
            <span class="label">ALAMAT</span>
            <span class="separator">:</span>
            <span class="value">{{ $suratKeluar->faskes->alamat ?? 'N/A' }}</span>
        </div>
        <div class="label-container">
            <span class="label">KODE FASKES</span>
            <span class="separator">:</span>
            <span class="value">{{ $suratKeluar->faskes->kode_faskes ?? 'N/A' }}</span>
        </div>
    </div>

    <table class="no-border">
        <tr>
            <td class="no-border" style="width: 150px; font-size: 12px;">Untuk Keperluan</td>
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
            <span class="box-label">TANGGAL : {{ $suratKeluar->spmb_tanggal ? \Carbon\Carbon::parse($suratKeluar->spmb_tanggal)->isoFormat('D MMMM YYYY') : 'N/A' }}</span>
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
            <th style="border: 1px solid black;" rowspan="2">NAMA<br>BARANG</th>
            <th rowspan="2">JUMLAH</th>
            <th rowspan="2">SATUAN</th>
            <th colspan="2" style="text-align: center;">SPESIFIKASI</th>
            <th colspan="2" style="text-align: center;">HARGA</th>
            <th rowspan="2">SUMBER DANA</th>
            <th rowspan="2">TITIK PERMI<br>NTAAN DARU<br>RAT</th>
            <th rowspan="2">TITIK<br>STOK<br>REALO<br>KASI<br>TERKINI</th>
        </tr>
        <tr>
            <th>NOMOR BATCH</th>
            <th>BATAS KADALUWARSA</th>
            <th>HARGA SATUAN</th>
            <th>TOTAL<br>NILAI<br>BARANG</th>
        </tr>
        @foreach($suratKeluar->barangTransaksis as $transaksi)
            @foreach($transaksi->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="nama-barang" style="text-align: left">{{ $item->barangMaster->nama_barang }}</td>
                    <td style="text-align: center">{{ $item->jumlah }}</td>
                    <td style="text-align: center">{{ $item->barangMaster->satuan }}</td>
                    <td class="nomor-batch" style="text-align: center;">{{ $item->barangMaster->nomor_batch }}</td>
                    <td style="text-align: center">{{ $item->barangMaster->kadaluarsa ? \Carbon\Carbon::parse($item->barangMaster->kadaluarsa)->format('Y/m/d') : 'N/A' }}</td>
                    <td>Rp.{{ number_format($item->barangMaster->harga_satuan, 2, ',', '.') }}</td>
                    <td>Rp.{{ number_format($item->total_harga, 2, ',', '.') }}</td>
                    <td style="text-align: center">{{ $item->barangMaster->sumber_dana }}</td>
                    <td>{{ $item->titik_permintaan_darurat }}</td>
                    <td>{{ $item->titik_stok_realokasi }}</td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="7" class="right-align" style="font-weight: bold;">TOTAL NILAI BARANG</td>
            <td class="right-align">Rp.{{ number_format($suratKeluar->barangTransaksis->flatMap(fn($transaksi) => $transaksi->items)->sum('total_harga'), 2, ',', '.') }}</td>
            <td colspan="3"></td>
        </tr>
    </table>

    <div class="signature" style="font-size: 20px;">
    <table class="no-border" style="border: none; width: 100%; font-size: 20px">
        <tr>
            <td style="font-size: 12px;">Jakarta Timur, ...........</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="font-size: 12px;">Yang mengeluarkan<br>Bendahara Materiil</td>
            <td style="font-size: 12px;">Yang Mengangkut<br>Mengambil/Mengantar</td>
            <td style="text-align: center; font-size: 12px;">Yang Menerima</td>
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
            <td style="font-size: 12px;">
                (Winda Ulaya)<br>
                Pangkat: ..........................<br>
                NIP: 197305021992032001
            </td>
            <td style="font-size: 12px;">
                (...............................)<br>
                Pangkat: ..........................<br>
                NIP: ................................
            </td>
            <td style="text-align: center; font-size: 12px;">
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
