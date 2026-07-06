<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Koordinat Kantor
    |--------------------------------------------------------------------------
    | GANTI nilai latitude & longitude di bawah dengan koordinat kantor kamu
    | yang sebenarnya. Cara ambil koordinat: buka Google Maps, klik kanan
    | di lokasi kantor, klik angka koordinat yang muncul (otomatis ke-copy).
    */

    'nama'   => 'BPS Kabupaten Ogan Ilir',

    'latitude'  => -3.2044795555804293, 
    'longitude' => 104.65294495181287,  // GANTI dengan longitude kantor asli

    // Radius toleransi absen, dalam meter. 100 = mahasiswa harus dalam
    // jarak 100 meter dari titik koordinat di atas untuk bisa absen.
    'radius_meter' => 100,

    // Level zoom peta saat modal absen dibuka (makin besar = makin dekat)
    'zoom' => 17,
];