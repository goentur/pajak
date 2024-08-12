<?php

namespace App\Services;

use App\Services\Contracts\DataServiceInterface;

abstract class ClientDataService implements DataServiceInterface
{
    public function getPersenRealisasi()
    {
        $realisasi = $this->getRealisasi() ?? 0;
        $target = $this->getTarget() ?? 0;

        return ($target > 0 && $realisasi > 0) ? floor(($realisasi / $target) * 100) : 0;
    }

    public function toArray()
    {
        return [
            'target' => $this->getTarget(),
            'realisasi' => $this->getRealisasi(),
            'persen_realisasi' => $this->getPersenRealisasi(),
            'grafik_bulanan' => $this->getRealisasiBulanan(),
        ];
    }
}
