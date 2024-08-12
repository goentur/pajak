<?php

namespace App\Repositories\Concerns;

use Illuminate\Support\Facades\DB;

trait Simpatda
{
    protected function getTargetValue()
    {
        $result = DB::connection('pgsql')
            ->table('t_target_detail')
            ->selectRaw('sum(t_target_detail.t_jumlah) total')
            ->join('s_rekening', 's_rekening.id', '=', 't_target_detail.t_id_rekening')
            ->joinSub(function ($query) {
                /** @var \Illuminate\Database\Query\Builder $query */
                $query->from('t_target_header')
                    ->where('t_target_header.t_tahun', '=', now()->year)
                    ->orderByDesc('t_id_jenis_target')
                    ->limit(1);
            }, 't_target_header', 't_target_header.id', 't_target_detail.t_id_target_header')
            ->whereIn('s_rekening.s_id_jenis_objek', self::ID_JENIS_OBJEK)
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
            ->whereIn('s_rekening.s_id_jenis_objek', self::ID_JENIS_OBJEK)
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
            ->whereIn('s_rekening.s_id_jenis_objek', self::ID_JENIS_OBJEK)
            ->groupBy(DB::raw('extract(year from t_pembayaran.t_tgl_pembayaran)'), DB::raw('extract(month from t_pembayaran.t_tgl_pembayaran)'))
            ->orderBy(DB::raw('extract(month from t_pembayaran.t_tgl_pembayaran)'))
            ->get();

        return $result ? $result : null;
    }

    public function inquiry($idbilling)
    {
        $result = DB::connection('pgsql')
            ->table('v_tagihan')
            ->join('t_transaksi', 'v_tagihan.id_trans', 't_transaksi.id')
            ->whereNull('t_transaksi.deleted_at')
            ->where('t_transaksi.t_kode_bayar', $idbilling)
            ->first();

        return $result;
    }
}
