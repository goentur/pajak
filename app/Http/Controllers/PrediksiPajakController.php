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
use App\Services\DataPajak;
use Illuminate\Http\Request;

class PrediksiPajakController extends Controller
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
    public function index()
    {
        return inertia('PrediksiPajak/Index', [
            'kategoriPajak' => $this->ketegoriPajak(),
            'jenisPajak' => 'PBB',
        ]);
    }
    public function ketegoriPajak()
    {
        return DataPajak::$kategoriPajakPrediksi;
    }
    public function data(Request $request)
    {
        $request->validate([
            'jenisPajak' => ['required', 'string'],
        ]);
        return $this->dataRiwayat($request);
    }
    public function dataRiwayat($request = null)
    {
        $tahunSebelumnya = date('Y') - 1;
        $realisasiData = DataPajak::$pajak[$tahunSebelumnya][$request->jenisPajak]['REALISASI'] ?? [];

        return $this->responsePrediksiexponential_smoothing($realisasiData, $request->jenisPajak, $tahunSebelumnya);
    }



    public function responsePrediksiexponential_smoothing($data, $jenisPajak, $tahunSebelumnya)
    {
        $category = array_map('UbahKeBulan', array_keys($data));
        $datas = array_values($data);

        $series = [
            [
                'name' => "$tahunSebelumnya",
                'data' => $datas
            ]
        ];
        $dataPajak = $this->getRealisasiBulananByJenisPajak($jenisPajak)->toArray();
        // remove array terakhir
        array_pop($dataPajak);
        $dataTahunBerjalan = array_column($dataPajak, 'total');
        // hanya prediksi bulan depan saja
        $dataPrediksi = $this->prediksiBulanDepanSaja($datas, $dataTahunBerjalan);
        $datahasilPrediksi = array_merge($dataTahunBerjalan, $dataPrediksi);
        // tanpa methode
        // $perdiksiBulanTerakhir = array_slice($datas, 0, date('m') - 12);
        // $dataPrediksi = $this->exponential_smoothing($perdiksiBulanTerakhir, $alpha);
        // $datahasilPrediksi = array_merge($dataTahunBerjalan, array_slice($datas, count($dataTahunBerjalan)));
        // menggunakan methode exponential smoothing
        // $alpha = 0.5;
        // $dataPrediksi = $this->exponential_smoothing($datas, $alpha);
        // $datahasilPrediksi = array_merge($dataTahunBerjalan, array_slice($dataPrediksi, count($dataTahunBerjalan)));

        $tahunBerjalan = date('Y');
        $series[] = [
            'name' => $tahunBerjalan,
            'data' => $datahasilPrediksi
        ];

        return [
            'judul' => 'PREDIKSI PAJAK ' . $jenisPajak . ' PADA TAHUN ' . $tahunBerjalan,
            'series' => $series,
            'category' => $category,
        ];
    }

    private function getRealisasiBulananByJenisPajak($jenisPajak)
    {
        switch ($jenisPajak) {
            case 'PBB':
                return $this->pbb->getRealisasiBulanan();
            case 'BPHTB':
                return $this->bphtb->getRealisasiBulanan();
            case 'HOTEL':
                return $this->hotel->getRealisasiBulanan();
            case 'RESTORAN':
                return $this->restoran->getRealisasiBulanan();
            case 'HIBURAN':
                return $this->hiburan->getRealisasiBulanan();
            case 'REKLAME':
                return $this->reklame->getRealisasiBulanan();
            case 'PPJ':
                return $this->ppj->getRealisasiBulanan();
            case 'PARKIR':
                return $this->parkir->getRealisasiBulanan();
            case 'AIR TANAH':
                return $this->airTanah->getRealisasiBulanan();
            default:
                return [];
        }
    }

    private function prediksiBulanDepanSaja($data, $dataTahunIni)
    {
        $bulanSekarang = date('m');
        $nilaiBulanSebelumnya  = $data[$bulanSekarang - 2];
        $nilaiBulanIni  = $data[$bulanSekarang - 1];
        $nilaiTahunIniBulanLalu = $dataTahunIni[$bulanSekarang - 2];
        if ($nilaiBulanSebelumnya != 0) {
            $persentasePerubahan = number_format((($nilaiBulanIni - $nilaiBulanSebelumnya) / $nilaiBulanSebelumnya) * 100, 0);
        } else {
            $persentasePerubahan = 0;
        }
        $result[] = $nilaiTahunIniBulanLalu + ($nilaiTahunIniBulanLalu * $persentasePerubahan) / 100;
        for ($i = $bulanSekarang; $i < 12; $i++) {
            $result[] = null;
        }
        return $result;
    }
    private function exponential_smoothing($data, $alpha)
    {

        $forecast = [];
        $count = count($data);
        for ($i = 0; $i < $count; $i++) {
            $arraySebelumnya = 0;
            if ($i > 0) {
                $arraySebelumnya = $forecast[$i - 1];
            }
            $forecast[$i] = $alpha * $data[$i] + (1 - $alpha) * $arraySebelumnya;
        }

        return $forecast;
    }

}
