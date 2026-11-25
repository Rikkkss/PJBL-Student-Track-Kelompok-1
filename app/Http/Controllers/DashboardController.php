<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Tugas;
use App\Models\Nilai;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Admin melihat seluruh data.
        // Mahasiswa hanya melihat data miliknya.
        $queryMataKuliah = MataKuliah::query();
        $queryTugas = Tugas::query();
        $queryNilai = Nilai::query();

        if (!$user->isAdmin()) {
            $queryMataKuliah->where('user_id', $user->id);
            $queryTugas->where('user_id', $user->id);
            $queryNilai->where('user_id', $user->id);
        }

        // Statistik mata kuliah
        $totalMataKuliah = (clone $queryMataKuliah)->count();
        $totalSKS = (clone $queryMataKuliah)->sum('sks');

        // Statistik tugas
        $totalTugas = (clone $queryTugas)->count();

        $tugasBelumDikerjakan = (clone $queryTugas)
            ->where('status', 'Belum dikerjakan')
            ->count();

        $tugasSelesai = (clone $queryTugas)
            ->where('status', 'Selesai')
            ->count();

        $tugasLainnya = max(
            0,
            $totalTugas - $tugasBelumDikerjakan - $tugasSelesai
        );

        // Statistik nilai
        $rataRataNilai = (clone $queryNilai)->avg('nilai');

        // Grafik rata-rata nilai per mata kuliah
        $mataKuliahDenganNilai = (clone $queryMataKuliah)
            ->whereHas('nilai', function ($query) use ($user) {
                if (!$user->isAdmin()) {
                    $query->where('user_id', $user->id);
                }
            })
            ->withAvg([
                'nilai' => function ($query) use ($user) {
                    if (!$user->isAdmin()) {
                        $query->where('user_id', $user->id);
                    }
                }
            ], 'nilai')
            ->orderBy('nama')
            ->get();

        $labelGrafik = $mataKuliahDenganNilai
            ->pluck('nama')
            ->values();

        $dataGrafik = $mataKuliahDenganNilai
            ->map(function ($mataKuliah) {
                return round(
                    (float) $mataKuliah->nilai_avg_nilai,
                    2
                );
            })
            ->values();

        // Grafik status tugas
        $labelGrafikTugas = [
            'Selesai',
            'Belum dikerjakan',
            'Status lainnya',
        ];

        $dataGrafikTugas = [
            $tugasSelesai,
            $tugasBelumDikerjakan,
            $tugasLainnya,
        ];

        return view('dashboard', compact(
            'totalMataKuliah',
            'totalSKS',
            'totalTugas',
            'tugasBelumDikerjakan',
            'tugasSelesai',
            'tugasLainnya',
            'rataRataNilai',
            'labelGrafik',
            'dataGrafik',
            'labelGrafikTugas',
            'dataGrafikTugas'
        ));
    }
}
