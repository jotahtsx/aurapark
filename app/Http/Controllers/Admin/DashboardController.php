<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'totalVagas'          => 128,
            'reservasHoje'        => 32,
            'avulsosEmPatio'      => 45,
            'mensalistasAtivos'   => 51,
            'mensalistasInativos' => 3,
        ];

        return view('pages.dashboard.home', compact('metrics'));
    }
}
