<?php

namespace App\Http\Controllers;

use App\Models\Centrall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $mutationCounts = Centrall::with('user')
            ->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->get()
            ->filter(fn($item) => $item->user !== null) // Hanya ambil yang punya user
            ->keyBy(fn($item) => $item->user->name); // Ganti key jadi nama user

        return view('dashboard', ['mutationCounts' => $mutationCounts]);
    }
}
