<?php

namespace App\Http\Controllers;

use App\Services\DataPajak;
use Illuminate\Http\Request;

class PrediksiPajakController extends Controller
{
    public function index()
    {
        return inertia('PrediksiPajak/Index', [
            'kategoriPajak' => $this->ketegoriPajak(),
            'jenisPajak' => 'SEMUA',
        ]);
    }
    public function ketegoriPajak()
    {
        return DataPajak::$kategoriPajak;
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
