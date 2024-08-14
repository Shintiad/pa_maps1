<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Penyakit;
use App\Models\Tahun;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function showAll() {
        $data = [
            'tahun_count' => Tahun::count(),
            'kecamatan_count' => Kecamatan::count(),
            'penyakit_count' => Penyakit::count(),
            'user_count' => User::where('role', 0)->count(),
        ];

        return view('pages.dashboard', $data);
    }
}
