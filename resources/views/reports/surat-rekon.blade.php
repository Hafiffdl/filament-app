<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Serah Terima Rekon</title>
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
            background-color: #fff;
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
            background-color: #fff;
        }
        .subtotal-label {
            text-align: right;
            vertical-align: bottom;
            padding-bottom: 5px;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .signature-block {
            text-align: center;
            width: 45%;
        }
        .center-signature {
            text-align: center;
            margin-top: 20px;
        }
        .label-container {
            display: block;
            margin-bottom: 3px;
        }

        .label {
            display: inline-block;
            width: 50px; /* Sesuaikan lebar label */

        }

        .separator {
            padding-right: 2px; /* Jarak antara titik dua dan value */
        }

        .value {
            display: inline-block;
        }
        td.nama-barang{
            word-wrap: break-word;       /* Memecah teks panjang menjadi beberapa baris */
            word-break: break-word;     /* Memastikan kata panjang terpecah */
            white-space: normal;        /* Membungkus teks secara normal */
            max-width: 130px;           /* Batasi lebar maksimum kolom */
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

    <div class="document-title">
        BERITA ACARA<br>SERAH TERIMA BARANG PERSEDIAAN<br>ALAT/OBAT KONTRASEPSI DAN NON KONTRASEPSI
    </div>

    <div class="document-number">Nomor: {{ $suratRekon->nomor }}</div>

    <p>Pada hari ini {{ \Carbon\Carbon::parse($suratRekon->tanggal)->locale('id')->translatedFormat('l') }} tanggal {{ \Carbon\Carbon::parse($suratRekon->tanggal)->locale('id')->translatedFormat('d') }} bulan {{ \Carbon\Carbon::parse($suratRekon->tanggal)->locale('id')->translatedFormat('F') }} tahun {{ \Carbon\Carbon::parse($suratRekon->tanggal)->locale('id')->translatedFormat('Y') }}, di Jakarta, kami yang bertanda tangan di bawah ini :</p>

    <ol>
        <li>
        <div class="label-container">
                <span class="label">Nama</span>
                <span class="separator">:</span>
                <span class="value">Winda Ulaya Hanafi</span>
            </div>
            <div class="label-container">
                <span class="label">NIP</span>
                <span class="separator">:</span>
                <span class="value">197305021992032001</span>
            </div>
            <div class="label-container">
                <span class="label">Jabatan</span>
                <span class="separator">:</span>
                <span class="value">Pengurus Barang Pembantu</span>
            </div>
        </li>
    </ol>

    <p>dalam hal ini bertindak untuk dan atas nama Kepala Sudin Pemberdayaan, Perlindungan Anak dan Pengendalian Penduduk Kota Administrasi Jakarta Timur, yang selanjutnya dalam Berita Acara ini disebut <strong> PIHAK PERTAMA </strong></p>

    <ol start="2">
        <li>
            <div class="label-container">
                <span class="label">Nama</span>
                <span class="separator">:</span>
                <span class="value">{{ $suratRekon->faskes->nama_pengurus_barang ?? 'N/A' }}</span>
            </div>
            <div class="label-container">
                <span class="label">NIP</span>
                <span class="separator">:</span>
                <span class="value">{{ $suratRekon->faskes->nip_pengurus_barang ?? 'N/A' }}</span>
            </div>
            <div class="label-container">
                <span class="label">Jabatan</span>
                <span class="separator">:</span>
                <span class="value">Pengurus Barang Pembantu</span>
            </div>
        </li>
    </ol>

    <p>dalam hal ini bertindak dan atas nama Jabatan tersebut di atas yang selanjutnya dalam Berita Acara ini disebut <strong>PIHAK KEDUA</strong></p>

    <p>Dengan mengingat telah melaksanakan penyerahan dan penerimaan barang persediaan alat/obat kontrasepsi dan non kontrasepsi *) <span style="font-weight: bold;">{{ \Carbon\Carbon::parse($suratRekon->start_date)->locale('id')->translatedFormat('F') }} s/d {{ \Carbon\Carbon::parse($suratRekon->end_date)->locale('id')->translatedFormat('F') }}</span> dengan rincian sebagai berikut:</p>

    <table>
        <tr>
            <th>NO</th>
            <th>NAMA BARANG</th>
            <th>JUMLAH BARANG</th>
            <th>SATUAN / KEMASAN</th>
            <th>HARGA SATUAN BARANG</th>
            <th>JUMLAH</th>
        </tr>
        @php
            $groupedItems = $suratRekon->barangTransaksis->flatMap(function ($transaksi) {
                return $transaksi->items->map(function ($item) use ($transaksi) {
                    return [
                        'nama_barang' => $item->barangMaster->nama_barang,
                        'nomor_batch' => $item->barangMaster->nomor_batch,
                        'jumlah' => $item->jumlah,
                        'satuan' => $item->barangMaster->satuan,
                        'harga_satuan' => $item->barangMaster->harga_satuan,
                    ];
                });
            })->groupBy(function ($item) {
                return $item['nama_barang'] . '-' . $item['nomor_batch'];
            });

            $totalHarga = 0;
        @endphp

        @foreach($groupedItems as $key => $group)
            @php
                $totalJumlah = $group->sum('jumlah');
                $hargaSatuan = $group->first()['harga_satuan'];
                $jumlahHarga = $totalJumlah * $hargaSatuan;
                $totalHarga += $jumlahHarga;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="nama-barang">{{ $group->first()['nama_barang'] }}</td>
                <td style="text-align: center;">{{ $totalJumlah }}</td>
                <td style="text-align: center;">{{ $group->first()['satuan'] }}</td>
                <td style="text-align: right;">Rp {{ number_format($hargaSatuan, 2, ',', '.') }}</td>
                <td>Rp {{ number_format($jumlahHarga, 2, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr class="subtotal-row">
            <td colspan="2" class="subtotal-label">SUB TOTAL</td>
            <td colspan="3"></td>
            <td>Rp {{ number_format($totalHarga, 2, ',', '.') }}</td>
        </tr>
    </table>

    <p>Demikian Berita Acara serah terima Barang Persediaan ini dibuat untuk dipergunakan sebagaimana mestinya</p>

    <div class="signature-section">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; text-align: center; border: none;">
                    <p><strong>PIHAK PERTAMA</strong><br>Pengurus Barang Pembantu<br>Sudin PPAPP Kota Adm. Jakarta Timur</p>
                    <br><br>
                    <p>Winda Ulaya Hanafi<br>NIP. 197305021992032001</p>
                </td>
                <td style="width: 50%; text-align: center; border: none;">
                    <p><strong>PIHAK KEDUA</strong><br>Pengurus Barang Pembantu<br>{{ $suratRekon->faskes->nama ?? 'N/A' }}</p>
                    <br><br>
                    <p>{{ $suratRekon->faskes->nama_pengurus_barang ?? 'N/A' }}<br>NIP. {{ $suratRekon->faskes->nip_pengurus_barang ?? 'N/A' }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="center-signature">
        <p>Kepala Sudin Pemberdayaan, Perlindungan Anak<br>
        dan Pengendalian Penduduk<br>
        Kota Administrasi Jakarta Timur</p>
        <br><br>
        <p>Hary Sutanto<br>NIP. 197808052010011021</p>
    </div>
</body>
</html>
