<?php

namespace App\Services;

use App\Repositories\AirTanahRepository;
use App\Repositories\BphtbRepository;
use App\Repositories\HiburanRepository;
use App\Repositories\HotelRepository;
use App\Repositories\ParkirRepository;
use App\Repositories\PbbRepository;
use App\Repositories\PbjtRepository;
use App\Repositories\PpjRepository;
use App\Repositories\ReklameRepository;
use App\Repositories\RestoranRepository;
use App\Repositories\RetribusiRepository;
use App\Services\Contracts\DataServiceInterface;

class DataService
{
    /**
     * Get all available service.
     *
     * @return array{name: string, service: \App\Services\Contracts\DataServiceInterface}
     */
    public static function getServices(): array
    {
        return [
            [
                'name' => 'PBB',
                'color' => '#4AB9A8',
                'link' => route('pajak.pbb'),
                'service' => app()->make(PbbRepository::class)->toArray(),
                'persentase' => true,
                'target' => true,
                'realisasi' => true,
            ],
            [
                'name' => 'BPHTB',
                'color' => '#FD9847',
                'link' => route('pajak.bphtb'),
                'service' => app()->make(BphtbRepository::class)->toArray(),
                'persentase' => true,
                'target' => true,
                'realisasi' => true,
            ],
            [
                'name' => 'PBJT',
                'color' => '#8666C9',
                'link' => route('pajak.pbjt'),
                'service' => app()->make(PbjtRepository::class)->toArray(),
                'persentase' => true,
                'target' => true,
                'realisasi' => false,
            ],
            [
                'name' => 'HOTEL',
                'color' => '#33C6E0',
                'link' => route('pajak.hotel'),
                'service' => app()->make(HotelRepository::class)->toArray(),
                'persentase' => false,
                'target' => false,
                'realisasi' => true,
            ],
            [
                'name' => 'RESTORAN',
                'color' => '#F14A91',
                'link' => route('pajak.restoran'),
                'service' => app()->make(RestoranRepository::class)->toArray(),
                'persentase' => false,
                'target' => false,
                'realisasi' => true,
            ],
            [
                'name' => 'HIBURAN',
                'color' => '#7A5BFB',
                'link' => route('pajak.hiburan'),
                'service' => app()->make(HiburanRepository::class)->toArray(),
                'persentase' => false,
                'target' => false,
                'realisasi' => true,
            ],
            [
                'name' => 'PPJ',
                'color' => '#33CCCC',
                'link' => route('pajak.ppj'),
                'service' => app()->make(PpjRepository::class)->toArray(),
                'persentase' => false,
                'target' => false,
                'realisasi' => true,
            ],
            [
                'name' => 'PARKIR',
                'color' => '#E34D5E',
                'link' => route('pajak.parkir'),
                'service' => app()->make(ParkirRepository::class)->toArray(),
                'persentase' => false,
                'target' => false,
                'realisasi' => true,
            ],
            [
                'name' => 'REKLAME',
                'color' => '#FFD347',
                'link' => route('pajak.reklame'),
                'service' => app()->make(ReklameRepository::class)->toArray(),
                'persentase' => true,
                'target' => true,
                'realisasi' => true,
            ],
            [
                'name' => 'AIR TANAH',
                'color' => '#33C578',
                'link' => route('pajak.air-tanah'),
                'service' => app()->make(AirTanahRepository::class)->toArray(),
                'persentase' => true,
                'target' => true,
                'realisasi' => true,
            ],
            [
                'name' => 'RETRIBUSI',
                'color' => '#3A87FA',
                'link' => route('pajak.retribusi'),
                'service' => app()->make(RetribusiRepository::class)->toArray(),
                'persentase' => true,
                'target' => true,
                'realisasi' => true,
            ],
        ];
    }

    public function all(): array
    {
        return self::getServices();
    }

    /**
     * @param $service
     * @return DataServiceInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function make($service)
    {
        return app()->make($service);
    }
}
