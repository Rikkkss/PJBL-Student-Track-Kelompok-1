<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Carbon\Carbon;

class KalenderLiburController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil tahun dari form
        $tahun = $request->input('tahun', date('Y'));

        // Validasi tahun
        if (
            !is_numeric($tahun) ||
            $tahun < 2000 ||
            $tahun > 2100
        ) {
            $tahun = date('Y');
        }

        $tahun = (int) $tahun;

        $libur = collect([]);
        $liburMendatang = null;
        $error = null;

        try {

            // Mengambil data dari API dengan cache 12 jam
            $dataAPI = Cache::remember(
                'kalender_libur_' . $tahun,
                now()->addHours(12),
                function () use ($tahun) {

                    $response = Http::timeout(20)->get(
                        "https://date.nager.at/api/v3/PublicHolidays/{$tahun}/ID"
                    );

                    $response->throw();

                    return $response->json();
                }
            );

            // Mengubah array menjadi Collection
            $libur = collect($dataAPI);

            // Mengurutkan berdasarkan tanggal
            $libur = $libur->sortBy('date')->values();

            // Mencari hari libur berikutnya
            $hariIni = Carbon::today()->toDateString();

            $liburMendatang = $libur->first(function ($item) use ($hariIni) {
                return isset($item['date']) &&
                       $item['date'] >= $hariIni;
            });

        } catch (ConnectionException $e) {

            $error = 'Gagal menghubungi API kalender libur. Periksa koneksi internet Anda.';

        } catch (RequestException $e) {

            $error = 'API kalender libur memberikan respons yang tidak berhasil.';

        } catch (\Throwable $e) {

            $error = 'Terjadi kesalahan saat mengambil data hari libur: '
                . $e->getMessage();
        }

        // Mengirim data ke Blade
        return view('kalender-libur.index', [
            'tahun' => $tahun,
            'libur' => $libur,
            'liburMendatang' => $liburMendatang,
            'error' => $error
        ]);
    }
}
