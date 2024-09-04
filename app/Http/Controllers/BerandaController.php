<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return inertia('Beranda/Index', [
            'tahun' => date('Y'),
            'services' => DataService::getServices(),
        ]);
    }
}
