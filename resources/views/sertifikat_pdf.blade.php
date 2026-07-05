<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Serif', serif;
        }
        .page {
            position: relative;
            width: 297mm;
            height: 210mm;
            background-color: #f5f0e6;
        }
        .bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        /* Semua teks diposisikan ABSOLUTE dalam persen, diatur dari halaman admin.
           translate(-50%, -50%) supaya titik top/left = TITIK TENGAH teks,
           jadi teksnya rata tengah persis di koordinat yang diatur admin. */
        .teks-nama {
            position: absolute;
            top: {{ $setting->nama_top }}%;
            left: {{ $setting->nama_left }}%;
            transform: translate(-50%, -50%);
            font-size: {{ $setting->nama_font_size }}pt;
            font-weight: bold;
            color: #b8860b;
            white-space: nowrap;
            text-align: center;
        }
        .teks-nomor {
            position: absolute;
            top: {{ $setting->nomor_top }}%;
            left: {{ $setting->nomor_left }}%;
            transform: translate(-50%, -50%);
            font-size: {{ $setting->nomor_font_size }}pt;
            color: #555;
            white-space: nowrap;
            text-align: center;
        }
        /* Info tambahan (instansi & tanggal) diletakkan tetap relatif
           terhadap posisi nama, supaya otomatis ikut kalau posisi nama diubah. */
        .teks-deskripsi {
            position: absolute;
            top: calc({{ $setting->nama_top }}% + 45px);
            left: 15%;
            width: 70%;
            font-size: 13px;
            color: #333;
            line-height: 1.7;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="page">

        @if($setting->background_path)
            <img src="{{ public_path('storage/' . $setting->background_path) }}" class="bg-image">
        @endif

        <div class="teks-nomor">Nomor: {{ $nomor_surat }}</div>

        <div class="teks-nama">{{ $nama }}</div>

        <div class="teks-deskripsi">
            Atas partisipasi dan dedikasinya dalam menyelesaikan program magang
            dari <strong>{{ $instansi }}</strong>,
            terhitung sejak tanggal <strong>{{ $mulai }}</strong>
            sampai dengan <strong>{{ $selesai }}</strong>,
            dengan penuh tanggung jawab dan kinerja yang baik.
        </div>

    </div>
</body>
</html>