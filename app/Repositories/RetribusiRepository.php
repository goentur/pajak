<?php

namespace App\Repositories;

use App\Constants\JenisPajak;
use App\Services\ClientDataService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RetribusiRepository extends ClientDataService
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
        $result = DB::connection('pgsql')
            ->table('t_target_detail')
            ->selectRaw('sum(t_target_detail.t_jumlah) total')
            ->join('s_rekening', 's_rekening.id', '=', 't_target_detail.t_id_rekening')
            ->join('s_jenis_objek', 's_jenis_objek.id', '=', 's_rekening.s_id_jenis_objek')
            ->joinSub(function ($query) {
                $query->from('t_target_header')
                    ->where('t_target_header.t_tahun', '=', now()->year)
                    ->orderByDesc('t_id_jenis_target')
                    ->limit(1);
            }, 't_target_header', 't_target_header.id', 't_target_detail.t_id_target_header')
            ->where('s_jenis_objek.s_jenis_pungutan', '=', JenisPajak::RETRIBUSI)
            ->first();

        return $result ? $result->total : 0;
    }

    protected function getRealisasiValue()
    {
        $result = DB::connection('pgsql')
            ->table('t_pembayaran')
            ->selectRaw('sum(t_pembayaran.t_jumlah_pembayaran) total')
            ->where(DB::raw('extract(year from t_pembayaran.t_tgl_pembayaran)'), now()->year)
            ->join('s_rekening', 's_rekening.id', '=', 't_pembayaran.t_id_rekening_pembayaran')
            ->join('s_jenis_objek', 's_jenis_objek.id', '=', 's_rekening.s_id_jenis_objek')
            ->where('s_jenis_objek.s_jenis_pungutan', '=', JenisPajak::RETRIBUSI)
            ->first();

        return $result ? $result->total : 0;
    }
    protected function getRealisasiBulananValue()
    {
        $result = DB::connection('pgsql')
        ->table('t_pembayaran')
        ->selectRaw('extract(month from t_pembayaran.t_tgl_pembayaran) as bulan, sum(t_pembayaran.t_jumlah_pembayaran) as total')
        ->where(DB::raw('extract(year from t_pembayaran.t_tgl_pembayaran)'), now()->year)
            ->join('s_rekening', 's_rekening.id', '=', 't_pembayaran.t_id_rekening_pembayaran')
            ->join('s_jenis_objek', 's_jenis_objek.id', '=', 's_rekening.s_id_jenis_objek')
            ->where('s_jenis_objek.s_jenis_pungutan', '=', JenisPajak::RETRIBUSI)
            ->groupBy(DB::raw('extract(year from t_pembayaran.t_tgl_pembayaran)'), DB::raw('extract(month from t_pembayaran.t_tgl_pembayaran)'))
            ->orderBy(DB::raw('extract(month from t_pembayaran.t_tgl_pembayaran)'))
            ->get();

        return $result ? $result : null;
    }
}
