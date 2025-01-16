<?php

if (!function_exists('formatTanggalIndo')) {
    function formatTanggalIndo($date)
    {
        // Mengubah format tanggal menjadi format Indonesia
        $bulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        $tanggal = date('d', strtotime($date));
        $bulanIndex = date('m', strtotime($date));
        $tahun = date('Y', strtotime($date));

        return $tanggal . ' ' . $bulan[$bulanIndex] . ' ' . $tahun;
    }
}
