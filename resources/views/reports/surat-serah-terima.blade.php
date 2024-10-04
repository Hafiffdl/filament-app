<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Serah Terima Barang Persediaan</title>
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
            height: 110px; /* Adjust based on your logo height */
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
            /* font-weight: bold; */
            font-size: 12px;
            margin: 2px 0;
        }
        .document-title {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin-top: 20px;
            margin-bottom: 5px;
        }
        .document-number {
            text-align: center;
            margin-bottom: 10px;
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
        .right-align {
            text-align: right;
        }
        .divider {
            border-top: 3px solid #000;
            margin: 20px 0;
        }
        .subtotal-row {
            font-weight: bold;
            background-color: #e0e0e0; /* Light grey background for the subtotal row */
        }
        .subtotal-label {
            text-align: right;
            vertical-align: bottom; /* Align text at the bottom of the cell */
            padding-bottom: 5px; /* Lower the text slightly */
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .signature-block {
            text-align: center;
            width: 45%;
        }
        .center-signature {
            text-align: center;
            margin-top: 20px;
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
            <div class="title"style="font-weight: bold;">PENGENDALIAN PENDUDUK KOTA ADMINISTRASI JAKARTA TIMUR</div>
            <div>JL. PAHLAWAN REVOLUSI, PONDOK BAMBU</div>
            <div>TELP. : 8612584, FAX. : 8612584</div>
            <div>JAKARTA</div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="document-title">
        BERITA ACARA<br>SERAH TERIMA BARANG PERSEDIAAN<br>ALAT/OBAT KONTRASEPSI DAN NON KONTRASEPSI
    </div>

    <div class="document-number">Nomor: {{ $suratSerahTerima->spmb_nomor ?? 'N/A' }}</div>

    <p>Pada hari ini Selasa tanggal 4 bulan Juni tahun dua ribu dua puluh empat, di Jakarta, kami yang bertanda tangan di bawah ini :</p>

    <ol>
        <li>
            Nama    : Winda Ulaya Hanafi<br>
            NIP     : 197305021992032001<br>
            Jabatan : Pengurus Barang Pembantu
        </li>
    </ol>

    <p>dalam hal ini bertindak untuk dan atas nama Kepala Sudin Pemberdayaan, Perlindungan Anak dan Pengendalian Penduduk Kota Administrasi Jakarta Timur, yang selanjutnya dalam Berita Acara ini disebut PIHAK PERTAMA</p>

    <ol start="2">
        <li>
            Nama    : {{ $suratSerahTerima->faskes->nama_pengurus_barang ?? 'N/A' }}<br>
            NIP     : {{ $suratSerahTerima->faskes->nip_pengurus_barang ?? 'N/A' }}<br>
            Jabatan : Pengurus Barang Pembantu
        </li>
    </ol>

    <p>dalam hal ini bertindak dan atas nama Jabatan tersebut di atas yang selanjutnya dalam Berita Acara ini disebut PIHAK KEDUA</p>

    <p>Dengan mengingat telah melaksanakan penyerahan dan penerimaan barang persediaan alat/obat kontrasepsi dan non kontrasepsi *) dengan rincian sebagai berikut :</p>

    <table>
        <tr>
            <th>NO</th>
            <th>NAMA BARANG</th>
            <th>JUMLAH BARANG</th>
            <th>SATUAN / KEMASAN</th>
            <th>HARGA SATUAN BARANG</th>
            <th>JUMLAH</th>
        </tr>
        @foreach($suratSerahTerima->barangTransaksis as $transaksi)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $transaksi->barangMaster->nama_barang }}</td>
            <td>{{ $transaksi->jumlah }}</td>
            <td>{{ $transaksi->barangMaster->satuan }}</td>
            <td>Rp {{ number_format($transaksi->barangMaster->harga_satuan, 2, ',', '.') }}</td>
            <td>Rp {{ number_format($transaksi->total_harga, 2, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="subtotal-row">
            <td colspan="2" class="subtotal-label">SUB TOTAL</td>
            <td colspan="3"></td>
            <td>Rp {{ number_format($suratSerahTerima->barangTransaksis->sum('total_harga'), 2, ',', '.') }}</td>
        </tr>
    </table>

    <p>Demikian Berita Acara serah terima Barang Persediaan ini dibuat untuk dipergunakan sebagaimana mestinya</p>
    <div class="signature-section">
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%; text-align: center; border: none;">
                <p><strong>PIHAK PERTAMA</strong><br>Pengurus Barang Pembantu<br>Sudin PPAPP Kota Adm. Jakarta Timur</p>
                <br><br><br>
                <p>Winda Ulaya Hanafi<br>NIP. 197305021992032001</p>
            </td>
            <td style="width: 50%; text-align: center; border: none;">
                <p><strong>PIHAK KEDUA</strong><br>Pengurus Barang Pembantu<br>{{ $suratSerahTerima->faskes->nama ?? 'N/A' }}</p>
                <br><br><br>
                <p>{{ $suratSerahTerima->faskes->nama_pengurus_barang ?? 'N/A' }}<br>NIP.{{ $suratSerahTerima->faskes->nip_pengurus_barang ?? 'N/A' }}</p>
            </td>
        </tr>
    </table>
</div>
    <div class="center-signature">
        <p>Kepala Sudin Pemberdayaan, Perlindungan Anak<br>
        dan Pengendalian Penduduk<br>
        Kota Administrasi Jakarta Timur</p>
        <br><br><br>
        <p>Hary Sutanto<br>NIP. 197808052010011021</p>
    </div>
</body>
</html>
