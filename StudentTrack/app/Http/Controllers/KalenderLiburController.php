<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class KalenderLiburController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil tahun dari input
        $tahun = $request->input('tahun', date('Y'));

        // Validasi tahun
        if (!is_numeric($tahun) || $tahun < 2000 || $tahun > 2100) {
            $tahun = date('Y');
        }

        $libur = [];
        $error = null;

        try {
            // Mengambil data hari libur dari API
            $libur = Cache::remember(
                'kalender_libur_' . $tahun,
                now()->addDay(),
                function () use ($tahun) {
                    $response = Http::timeout(15)->get(
                        "https://date.nager.at/api/v3/PublicHolidays/{$tahun}/ID"
                    );

                    $response->throw();

                    return $response->json();
                }
            );

        } catch (ConnectionException | RequestException $e) {
            $error = 'Gagal menghubungi API kalender libur. Periksa koneksi internet Anda.';
        } catch (\Throwable $e) {
            $error = 'Terjadi kesalahan saat mengambil data hari libur.';
        }

        // Memastikan data berbentuk array
        if (!is_array($libur)) {
            $libur = [];
        }

        // Mengurutkan berdasarkan tanggal
        usort($libur, function ($a, $b) {
            return strcmp($a['date'] ?? '', $b['date'] ?? '');
        });

        return view('kalender-libur', [
            'tahun' => $tahun,
            'libur' => $libur,
            'error' => $error
        ]);
    }
}
