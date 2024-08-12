<?php

namespace App\Repositories;

use App\Services\ClientDataService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BphtbRepository extends ClientDataService
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
            ->select('bphtb')
            ->where('tahun', now()->year)
            ->orderByDesc('tgl')
            ->first();

        return $result ? $result->bphtb : 0;
    }

    protected function getRealisasiValue()
    {
        $result = DB::connection('oracle')
            ->table('bphtb.sspd')
            ->selectRaw('sum(JML_SSPD_YG_DIBAYAR) total')
            ->where(DB::raw('extract(year from tgl_pembayaran_sspd)'), now()->year)
            ->first();
        return $result ? $result->total : 0;
    }
    protected function getRealisasiBulananValue()
    {
        $result = DB::connection('oracle')
            ->table('bphtb.sspd')
            ->selectRaw('extract(month from tgl_pembayaran_sspd) as bulan, sum(JML_SSPD_YG_DIBAYAR) total')
            ->where(DB::raw('extract(year from tgl_pembayaran_sspd)'), now()->year)
            ->groupBy(DB::raw('extract(year from tgl_pembayaran_sspd)'), DB::raw('extract(month from tgl_pembayaran_sspd)'))
            ->orderBy(DB::raw('extract(month from tgl_pembayaran_sspd)'))
            ->get();

        return $result ? $result : null;
    }

    public function inquiry($idbilling)
    {
        $result = DB::connection('oracle')
            ->table('bphtb_sptpd')
            ->join('bphtb_wp', 'bphtb_wp.no_ktp', 'bphtb_sptpd.no_ktp_pb')
            ->where('id_billing', $idbilling)
            ->first();

        return $result;
    }
}
