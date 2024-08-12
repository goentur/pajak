<?php

namespace App\Http\Controllers;

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

class PajakController extends Controller
{
    private $pbb, $bphtb, $pbjt, $hotel, $restoran, $hiburan, $ppj, $parkir, $reklame, $airTanah, $retribusi;
    public function __construct()
    {
        $this->pbb = new PbbRepository();
        $this->bphtb = new BphtbRepository();
        $this->pbjt = new PbjtRepository();
        $this->hotel = new HotelRepository();
        $this->restoran = new RestoranRepository();
        $this->hiburan = new HiburanRepository();
        $this->ppj = new PpjRepository();
        $this->parkir = new ParkirRepository();
        $this->reklame = new ReklameRepository();
        $this->airTanah = new AirTanahRepository();
        $this->retribusi = new RetribusiRepository();
    }
    public function pbb()
    {
        $kirim = [
            'title' => 'PBB TAHUN ' . date('Y'),
            'name' => 'PBB',
            'realisasi' => $this->pbb->getRealisasiBulanan(),
        ];
        return inertia('Pajak/Index', $kirim);
    }
    public function bphtb()
    {
        $kirim = [
            'title' => 'BPHTB TAHUN ' . date('Y'),
            'name' => 'BPHTB',
            'realisasi' => $this->bphtb->getRealisasiBulanan(),
        ];
        return inertia('Pajak/Index', $kirim);
    }
    public function pbjt()
    {
        $kirim = [
            'title' => 'PBJT TAHUN ' . date('Y'),
            'name' => 'PBJT',
            'realisasi' => $this->pbjt->getRealisasiBulanan(),
        ];
        return inertia('Pajak/Index', $kirim);
    }
    public function hotel()
    {
        $kirim = [
            'title' => 'HOTEL TAHUN ' . date('Y'),
            'name' => 'HOTEL',
            'realisasi' => $this->hotel->getRealisasiBulanan(),
        ];
        return inertia('Pajak/Index', $kirim);
    }
    public function restoran()
    {
        $kirim = [
            'title' => 'RESTORAN TAHUN ' . date('Y'),
            'name' => 'RESTORAN',
            'realisasi' => $this->restoran->getRealisasiBulanan(),
        ];
        return inertia('Pajak/Index', $kirim);
    }
    public function hiburan()
    {
        $kirim = [
            'title' => 'HIBURAN TAHUN ' . date('Y'),
            'name' => 'HIBURAN',
            'realisasi' => $this->hiburan->getRealisasiBulanan(),
        ];
        return inertia('Pajak/Index', $kirim);
    }
    public function ppj()
    {
        $kirim = [
            'title' => 'PPJ TAHUN ' . date('Y'),
            'name' => 'PPJ',
            'realisasi' => $this->ppj->getRealisasiBulanan(),
        ];
        return inertia('Pajak/Index', $kirim);
    }
    public function parkir()
    {
        $kirim = [
            'title' => 'PARKIR TAHUN ' . date('Y'),
            'name' => 'PARKIR',
            'realisasi' => $this->parkir->getRealisasiBulanan(),
        ];
        return inertia('Pajak/Index', $kirim);
    }
    public function reklame()
    {
        $kirim = [
            'title' => 'REKLAME TAHUN ' . date('Y'),
            'name' => 'REKLAME',
            'realisasi' => $this->reklame->getRealisasiBulanan(),
        ];
        return inertia('Pajak/Index', $kirim);
    }
    public function airTanah()
    {
        $kirim = [
            'title' => 'AIR TANAH TAHUN ' . date('Y'),
            'name' => 'AIR TANAH',
            'realisasi' => $this->airTanah->getRealisasiBulanan(),
        ];
        return inertia('Pajak/Index', $kirim);
    }
    public function retribusi()
    {
        $kirim = [
            'title' => 'RETRIBUSI TAHUN ' . date('Y'),
            'name' => 'RETRIBUSI',
            'realisasi' => $this->retribusi->getRealisasiBulanan(),
        ];
        return inertia('Pajak/Index', $kirim);
    }
}
