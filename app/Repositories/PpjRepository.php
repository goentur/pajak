<?php

namespace App\Repositories;

use App\Constants\JenisPajak;
use App\Repositories\Concerns\Simpatda;
use App\Services\ClientDataService;
use Illuminate\Support\Facades\Cache;

class PpjRepository extends ClientDataService
{
    use Simpatda;

    public const ID_JENIS_OBJEK = [JenisPajak::PPJ];

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
}
