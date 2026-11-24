<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Tugas;
use App\Models\Nilai;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik mata kuliah
        $totalMataKuliah = MataKuliah::count();
        $totalSKS = MataKuliah::sum('sks');

        // Statistik tugas
        $totalTugas = Tugas::count();

        $tugasBelumDikerjakan = Tugas::where(
            'status',
            'Belum dikerjakan'
        )->count();

        $tugasSelesai = Tugas::where(
            'status',
            'Selesai'
        )->count();

        // Menghitung status tugas lainnya
        $tugasLainnya = max(
            0,
            $totalTugas - $tugasBelumDikerjakan - $tugasSelesai
        );

        // Statistik nilai
        $rataRataNilai = Nilai::avg('nilai');

        // Grafik rata-rata nilai per mata kuliah
        $mataKuliahDenganNilai = MataKuliah::whereHas('nilai')
            ->withAvg('nilai', 'nilai')
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

        // Data grafik status tugas
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
