<?php

namespace App\Http\Controllers;

use App\Services\DataPajak;
use Illuminate\Http\Request;

class RiwayatPajakController extends Controller
{
    public function index()
    {
        return inertia('RiwayatPajak/Index', [
            'tahun' => $this->tahunBerjalan(),
            'kategoriPajak' => $this->ketegoriPajak(),
            'tahunSebelumnya' => date('Y') - 1,
            'jenisPajak' => 'SEMUA',
        ]);
    }
    public function tahunBerjalan()
    {
        $tahun = date('Y') - 1;
        $dataTahun = [];
        for ($i = $tahun; $i >= date('Y') - 5; $i--) {
            $dataTahun[] = $i;
        }
        return $dataTahun;
    }
    public function ketegoriPajak()
    {
        return DataPajak::$kategoriPajak;
    }
    public function data(Request $request)
    {
        $request->validate([
            'tahun' => ['required', 'numeric'],
            'jenisPajak' => ['required', 'string'],
        ]);
        return $this->dataRiwayat($request);
    }
    public function dataRiwayat($request = null)
    {
        if ($request->jenisPajak === 'SEMUA') {
            return $this->responseDataGrafikSemua(DataPajak::$pajak[$request->tahun], $request->tahun);
        } else {
            return $this->responseDataGrafikPerJenis(DataPajak::$pajak[$request->tahun][$request->jenisPajak]['REALISASI'], $request->tahun, $request->jenisPajak);
        }
    }
    public function responseDataGrafikSemua($data, $tahun)
    {
        $series = [];
        foreach ($data as $k => $value) {
            $datas = [];
            $category = [];
            foreach ($data[$k]['REALISASI'] as $d => $isi) {
                $datas[] = $isi;
                $category[] = UbahKeBulan($d);
            }
            $series[] = [
                'name' => $k,
                'data' => $datas
            ];
        }
        return [
            'judul' => 'DATA PAJAK PADA TAHUN ' . $tahun,
            'series' => $series,
            'category' => $category,
        ];
    }
    public function responseDataGrafikPerJenis($data, $tahun, $jenisPajak)
    {
        $series = [];
        $category = [];
        foreach ($data as $k => $value) {
            $series[] = $value;
            $category[] = UbahKeBulan($k);
        }
        return [
            'judul' => 'DATA PAJAK ' . $jenisPajak . ' PADA TAHUN ' . $tahun,
            'series' => [[
                'name' => $jenisPajak,
                'data' => $series,
            ]],
            'category' => $category,
        ];
    }
}
