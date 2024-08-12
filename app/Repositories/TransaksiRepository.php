<?php

namespace App\Repositories;

use App\Models\Oracle\Transaksi;
use Illuminate\Support\Facades\DB;

class TransaksiRepository
{
    public function data($request)
    {
        $page = $request->current;
        $rows = $request->rowCount;

        $columns = [
            DB::raw("TO_CHAR(TGL_TRANS,'DD MON, HH24:MI') TGL"),
            "ID_BILLING",
            "NAMA_WP",
            "THN_PAJAK",
            "JML_TERIMA_POKOK",
            "JML_TERIMA_DENDA",
        ];

        $result = Transaksi::select($columns)
            ->orderByDesc('TGL_TRANS');

        $data['current'] = (int)$page;
        $data['rowCount'] = (int)$rows;
        $data['total'] = $result->count();
        $data['rows'] = ($rows > 0) ? $result->forPage($page, $rows)->get() : $result->get();

        return $data;
    }

    /**
     * Menampilkan data rekapitulasi penerimaan pembayaran dalam kelompok :
     *  - Harian (per hari)
     *  - Mingguan (per minggu)
     *  - Bulanan (per bulan)
     */
    public function rekap($request)
    {
        $sehari = Transaksi::select([
            DB::raw('SUM (JML_TERIMA_POKOK + JML_TERIMA_DENDA) TOTAL'),
            DB::raw('COUNT(ID_BILLING) QTY')
        ])
            ->where('TGL_TRANS', '>=', DB::raw('TRUNC(CURRENT_DATE,\'DAY\')'))
            ->get();

        $seminggu = Transaksi::select([
            DB::raw('SUM (JML_TERIMA_POKOK + JML_TERIMA_DENDA) TOTAL'),
            DB::raw('COUNT(ID_BILLING) QTY')
        ])
            ->where('TGL_TRANS', '>=', DB::raw('TRUNC(CURRENT_DATE,\'DAY\') - 7'))
            ->get();

        $sebulan = Transaksi::select([
            DB::raw('SUM (JML_TERIMA_POKOK + JML_TERIMA_DENDA) TOTAL'),
            DB::raw('COUNT(ID_BILLING) QTY')
        ])
            ->where('TGL_TRANS', '>=', DB::raw('TRUNC(CURRENT_DATE,\'DAY\') - 30'))
            ->get();

        return compact('sehari', 'seminggu', 'sebulan');
    }

    public function perhari($request)
    {
        # code...
        $data = Transaksi::select([
            DB::raw('SUM (JML_TERIMA_POKOK + JML_TERIMA_DENDA) TOTAL'),
            DB::raw('TO_CHAR (TGL_TRANS, \'MM-DD\') TGL')
        ])
            ->where('TGL_TRANS', '>=', DB::raw('TRUNC(SYSDATE - 30)'))
            ->groupBy(DB::raw('TO_CHAR (TGL_TRANS, \'MM-DD\')'))
            ->orderBy('TGL')
            ->get();

        return $data;
    }
}
