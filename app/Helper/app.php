<?php

function rupiah($d)
{
     return 'Rp ' . number_format($d, 0, ',', ',');
}
function UbahKeBulan($bulan)
{
     $months = [
          "Januari", "Februari", "Maret", "April", "Mei", "Juni",
          "Juli", "Agustus", "September", "Oktober", "November", "Desember"
     ];

     if ($bulan < 1 || $bulan > 12) {
          throw new Exception("Nomor bulan harus antara 1 dan 12.");
     }

     return $months[$bulan - 1];
}
