<?php

namespace App\Repositories;

use App\Services\ClientDataService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PbbRepository extends ClientDataService
{
    /**
     * Mendapatkan value dari target sesuai jenis pajak.
     *
     * @return int|mixed
     */
    public function getTarget()
    {
        return Cache::remember(__CLASS__ . __FUNCTION__, now()->addMinutes(), function () {
            return $this->getTargetValue();
        });
    }

    /**
     * Mendapatkan value dari target sesuai jenis pajak.
     *
     * @return int|mixed
     */
    public function getRealisasi()
    {
        return Cache::remember(__CLASS__ . __FUNCTION__, now()->addMinutes(), function () {
            return $this->getRealisasiValue();
        });
    }

    /**
     * Mendapatkan value bulanan dari target sesuai jenis pajak.
     *
     * @return int|mixed
     */
    public function getRealisasiBulanan()
    {
        return Cache::remember(__CLASS__ . __FUNCTION__, now()->addMinutes(), function () {
            return $this->getRealisasiBulananValue();
        });
    }

    protected function getTargetValue()
    {
        $result = DB::connection('oracle')
            ->table('pbb.target')
            ->select('pbb')
            ->where('tahun', now()->year)
            ->orderByDesc('tgl')
            ->first();

        return $result ? $result->pbb : 0;
    }

    protected function getRealisasiValue()
    {
        $result = DB::connection('oracle')
            ->table('pbb.pembayaran_sppt')
            ->selectRaw('sum(jml_sppt_yg_dibayar - denda_sppt) total')
            ->where(DB::raw('extract(year from tgl_pembayaran_sppt)'), now()->year)
            ->first();

        return $result ? $result->total : 0;
    }
    protected function getRealisasiBulananValue()
    {
        $result = DB::connection('oracle')
        ->table('pbb.pembayaran_sppt')
        ->selectRaw('extract(month from tgl_pembayaran_sppt) as bulan, sum(jml_sppt_yg_dibayar - denda_sppt) total')
        ->where(DB::raw('extract(year from tgl_pembayaran_sppt)'), now()->year)
            ->groupBy(DB::raw('extract(year from tgl_pembayaran_sppt)'), DB::raw('extract(month from tgl_pembayaran_sppt)'))
            ->orderBy(DB::raw('extract(month from tgl_pembayaran_sppt)'))
            ->get();

        return $result ? $result : null;
    }

    public function inquiry($idbilling)
    {
        $tahun = now()->year;
        $kd_propinsi = substr($idbilling, 0, 2);
        $kd_dati2 = substr($idbilling, 2, 2);
        $kd_kecamatan = substr($idbilling, 4, 3);
        $kd_kelurahan = substr($idbilling, 7, 3);
        $kd_blok = substr($idbilling, 10, 3);
        $no_urut = substr($idbilling, 13, 4);
        $kd_jns_op = substr($idbilling, 17, 1);

        $result = DB::connection('oracle')
            ->table('dat_tagihan')
            ->where('kd_propinsi', $kd_propinsi)
            ->where('kd_dati2', $kd_dati2)
            ->where('kd_kecamatan', $kd_kecamatan)
            ->where('kd_kelurahan', $kd_kelurahan)
            ->where('kd_blok', $kd_blok)
            ->where('no_urut', $no_urut)
            ->where('kd_jns_op', $kd_jns_op)
            ->where('thn_pajak_sppt', $tahun)
            ->first();

        return $result;
    }
}
