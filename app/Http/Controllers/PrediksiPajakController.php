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
        return $this->responsePrediksiexponential_smoothing($request);
    }
    public function dataRiwayat($jenis, $tahun)
    {
        if ($jenis !== 'PBJT') {
            // $realisasiData = DataPajak::$pajak[$tahunSebelumnya][$jenis]['REALISASI'] ?? [];
            $realisasiData = DataPajak::$pajak[$tahun][$jenis] ?? [];
        } else {
            $categories = ['HOTEL', 'RESTORAN', 'HIBURAN', 'PPJ', 'PARKIR'];
            $totalTarget = 0;
            $totalPendapatan = 0;
            $realisasi = array_fill(1, 12, 0);

            foreach ($categories as $category) {
                if (isset(DataPajak::$pajak[$tahun][$category])) {
                    $categoryData = DataPajak::$pajak[$tahun][$category];
                    $totalTarget += $categoryData['TARGET'];
                    $totalPendapatan += $categoryData['PENDAPATAN'];
                    for ($i = 1; $i <= 12; $i++) {
                        if (isset($categoryData['REALISASI'][$i])) {
                            $realisasi[$i] += $categoryData['REALISASI'][$i];
                        }
                    }
                }
            }
            $realisasiData = [
                'TARGET' => $totalTarget,
                'PENDAPATAN' => $totalPendapatan,
                'REALISASI' => $realisasi
            ];
        }
        return $realisasiData;
    }

    public function responsePrediksiexponential_smoothing($request)
    {
        $jenisPajak = $request->jenisPajak;
        $duaTahunLalu = date('Y') - 2;
        $satuTahunLalu = date('Y') - 1;
        $tahunBerjalan = date('Y');
        $tahunBerikutnya = date('Y') + 1;
        $dataHistoryPajak = [];
        $dataPajakDuaTahunLalu = $this->dataRiwayat($jenisPajak, $duaTahunLalu);
        $dataHistoryDuaTahunSebelumnya = array_map(function ($entry, $index) use ($duaTahunLalu) {
            return [
                'tanggal' => $duaTahunLalu . '-' . $index,
                'total' => "$entry"
            ];
        }, $dataPajakDuaTahunLalu['REALISASI'], array_keys($dataPajakDuaTahunLalu['REALISASI']));
        $dataHistoryPajak = array_merge($dataHistoryPajak, $dataHistoryDuaTahunSebelumnya);
        $dataPajakSatuTahunLalu = $this->dataRiwayat($jenisPajak, $satuTahunLalu);
        $dataHistorySatuTahunSebelumnya = array_map(function ($entry, $index) use ($satuTahunLalu) {
            return [
                'tanggal' => $satuTahunLalu . '-' . $index,
                'total' => "$entry"
            ];
        }, $dataPajakSatuTahunLalu['REALISASI'], array_keys($dataPajakSatuTahunLalu['REALISASI']));
        $dataHistoryPajak = array_merge($dataHistoryPajak, $dataHistorySatuTahunSebelumnya);
        $dataPajak = $this->getRealisasiBulananByJenisPajak($jenisPajak)->toArray();
        // remove array terakhir
        if (count($dataPajak) <= date('m') - 1) {
            array_push($dataPajak, [0]);
        }
        array_pop($dataPajak);
        $dataHistoryTahunBerjalan = array_map(function ($entry) use ($tahunBerjalan) {
            return [
                'tanggal' => $tahunBerjalan . '-' . $entry->bulan,
                'total' => $entry->total
            ];
        }, $dataPajak);
        $dataHistoryPajak = array_merge($dataHistoryPajak, $dataHistoryTahunBerjalan);

        $historicalData = [];
        $timeline = [];
        $historicalData = array_map(function ($entry) {
            return $entry['total'];
        }, $dataHistoryPajak);
        $timeline = array_map(function ($entry) {
            return $entry['tanggal'];
        }, $dataHistoryPajak);

        $category = array_map('UbahKeBulan', array_keys($dataPajakSatuTahunLalu['REALISASI']));
        $datas = array_values($dataPajakSatuTahunLalu['REALISASI']);
        
        $series = [
            [
                'name' => "$satuTahunLalu",
                'data' => $datas
            ]
            ];
        $dataTahunBerjalan = array_column($dataPajak, 'total');

        $dataPrediksi = $this->prediksiBulanTahunan($datas, $dataTahunBerjalan);
        $bulanBerjalan = 13 - date('m');
        $futurePeriods = $bulanBerjalan + 12;

        $forecasts = $this->forecastETSNEW($historicalData, $timeline, $futurePeriods, 12, 0, 'median');
        $dataPajakTahunBerjalan = array_merge($dataTahunBerjalan, array_slice($forecasts, 0, $bulanBerjalan));
        $series[] = [
            'name' => $tahunBerjalan,
            'data' => $dataPajakTahunBerjalan,
        ];
        $dataPajakTahunBerikutnya = array_slice($forecasts, $bulanBerjalan);
        $series[] = [
            'name' => $tahunBerikutnya,
            'data' => $dataPajakTahunBerikutnya,
        ];
        $targetTahunLalu = $dataPajakSatuTahunLalu['TARGET'];
        $totalRealisasiTahunLalu = array_sum($datas);
        $targetTahunIni = $this->getTargetByJenisPajak($jenisPajak);
        $totalRealisasiTahunIni = array_sum($dataPajakTahunBerjalan);
        $totalRealisasiTahunBerikutnya = array_sum($dataPajakTahunBerikutnya);
        return [
            'grafik' => [
                'judul' => 'PREDIKSI PAJAK ' . $jenisPajak . ' PADA TAHUN ' . $tahunBerjalan,
                'series' => $series,
                'category' => $category,
            ],
            'tabel' => [
                $satuTahunLalu => [
                    'target' => currency($targetTahunLalu),
                    'realisasi' => array_map('currency', $datas),
                    'total' => currency($totalRealisasiTahunLalu),
                    'persentase' => number_format(($totalRealisasiTahunLalu / $targetTahunLalu) * 100, 2),
                ],
                $tahunBerjalan => [
                    'target' => currency($targetTahunIni),
                    'realisasi' => array_map('currency', $dataPajakTahunBerjalan),
                    'total' => currency($totalRealisasiTahunIni),
                    'persentase' => number_format(($totalRealisasiTahunIni / $targetTahunIni) * 100, 2),
                ],
                $tahunBerikutnya => [
                    'target' => 'Belum Ada',
                    'realisasi' => array_map('currency', $dataPajakTahunBerikutnya),
                    'total' => currency($totalRealisasiTahunBerikutnya),
                    'persentase' => 0,
                ]
            ]
        ];
    }

    private function getRealisasiBulananByJenisPajak($jenisPajak)
    {
        switch ($jenisPajak) {
            case 'PBB':
                return $this->pbb->getRealisasiBulanan();
            case 'BPHTB':
                return $this->bphtb->getRealisasiBulanan();
            case 'PBJT':
                return $this->pbjt->getRealisasiBulanan();
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
    private function getTargetByJenisPajak($jenisPajak)
    {
        switch ($jenisPajak) {
            case 'PBB':
                return $this->pbb->getTarget();
            case 'BPHTB':
                return $this->bphtb->getTarget();
            case 'PBJT':
                return $this->pbjt->getTarget();
            case 'HOTEL':
                return $this->hotel->getTarget();
            case 'RESTORAN':
                return $this->restoran->getTarget();
            case 'HIBURAN':
                return $this->hiburan->getTarget();
            case 'REKLAME':
                return $this->reklame->getTarget();
            case 'PPJ':
                return $this->ppj->getTarget();
            case 'PARKIR':
                return $this->parkir->getTarget();
            case 'AIR TANAH':
                return $this->airTanah->getTarget();
            default:
                return [];
        }
    }

    private function prediksiBulanTahunan($data, $dataTahunIni)
    {
        $bulanSekarang = date('m');
        for ($i = $bulanSekarang; $i < 13; $i++) {
            $nilaiBulanSebelumnya = $data[$i - 2];
            $nilaiBulanIni = $data[$i - 1];
            $nilaiTahunIniBulanLalu = $dataTahunIni[$i - 2] ?? 0;
            if ($nilaiBulanSebelumnya != 0) {
                $persentasePerubahan = number_format((($nilaiBulanIni - $nilaiBulanSebelumnya) / $nilaiBulanSebelumnya) * 100, 0);
            } else {
                $persentasePerubahan = 0;
            }
            $nilai = floor(((100 + $persentasePerubahan) / 100) * $nilaiTahunIniBulanLalu);
            array_push($dataTahunIni, $nilai);
        }
        return $dataTahunIni;
    }

    function forecastETSNEW(array $historicalData, array $timeline, $futurePeriods = 1, $seasonality = 12, $dataCompletion = 0, $aggregation = 'median')
    {
        $n = count($historicalData);

        if ($n < $seasonality * 2) {
            throw new Exception("Not enough data to apply seasonality");
        }

        // Handle Missing Data (Interpolation or Zeros)
        if ($dataCompletion === 0) {
            $historicalData = $this->interpolateMissingData($historicalData, $timeline);
        } elseif ($dataCompletion === 1) {
            $historicalData = $this->fillMissingDataWithZeros($historicalData, $timeline);
        }

        // Initial Values
        $level = $historicalData[0];
        $trend = ($historicalData[1] - $historicalData[0]) / $seasonality;
        $season = array_slice($historicalData, 0, $seasonality);

        // Calculate Level, Trend, and Seasonality Components
        for ($i = $seasonality; $i < $n; $i++) {
            $previousLevel = $level;
            $level = 0.2 * ($historicalData[$i] - $season[$i % $seasonality]) + 0.8 * ($previousLevel + $trend);
            $trend = 0.1 * ($level - $previousLevel) + 0.9 * $trend;

            // Apply the median aggregation for seasonality
            if ($aggregation === 'median') {
                $season[$i % $seasonality] = $this->calculateMedian([$historicalData[$i] - $level, $season[$i % $seasonality]]);
            } else {
                $season[$i % $seasonality] = 0.1 * ($historicalData[$i] - $level) + 0.9 * $season[$i % $seasonality];
            }
        }

        // Forecast Calculation for Future Periods
        $forecasts = [];
        for ($i = 1; $i <= $futurePeriods; $i++) {
            $forecast = ($level + $i * $trend) + $season[($n + $i - 1) % $seasonality];
            $forecasts[] = $forecast > 0 ? $forecast : 0;
        }

        return $forecasts;
    }

    function calculateMedian(array $values)
    {
        sort($values);
        $count = count($values);
        $middle = floor($count / 2);

        if ($count % 2) {
            return $values[$middle];
        }

        return ($values[$middle - 1] + $values[$middle]) / 2.0;
    }



    function interpolateMissingData(array $data, array $timeline)
    {
        // Implement linear interpolation for missing data
        // For simplicity, let's assume no missing data here
        return $data;
    }

    function fillMissingDataWithZeros(array $data, array $timeline)
    {
        // Implement logic to fill missing data with zeros
        // For simplicity, let's assume no missing data here
        return $data;
    }



}
