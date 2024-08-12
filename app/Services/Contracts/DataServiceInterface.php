<?php

namespace App\Services\Contracts;

interface DataServiceInterface
{
    /**
     * Mendapatkan value dari target sesuai jenis pajak.
     *
     * @return int|mixed
     */
    public function getTarget();

    /**
     * Mendapatkan value dari target sesuai jenis pajak.
     *
     * @return int|mixed
     */
    public function getRealisasi();

    public function getPersenRealisasi();

    public function getRealisasiBulanan();

    public function toArray();
}
