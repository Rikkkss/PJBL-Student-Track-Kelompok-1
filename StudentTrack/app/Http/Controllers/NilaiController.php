<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\MataKuliah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class NilaiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MENAMPILKAN DATA NILAI
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = auth()->user();

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = [
            'created_at',
            'nilai',
            'jenis_penilaian',
            'mata_kuliah',
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $query = Nilai::with(['mataKuliah', 'user']);

        // Mahasiswa hanya melihat nilai miliknya.
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        if ($sort === 'mata_kuliah') {
            $query->orderBy(
                MataKuliah::select('nama')
                    ->whereColumn(
                        'mata_kuliah.id',
                        'nilais.mata_kuliah_id'
                    ),
                $direction
            );
        } else {
            $query->orderBy($sort, $direction);
        }

        $nilai = $query->paginate(10)->withQueryString();

        $statistik = Nilai::query();

        if (!$user->isAdmin()) {
            $statistik->where('user_id', $user->id);
        }

        $rataRata = (clone $statistik)->avg('nilai');

        $totalMataKuliah = (clone $statistik)
            ->distinct()
            ->count('mata_kuliah_id');

        return view('nilai.index', compact(
            'nilai',
            'rataRata',
            'totalMataKuliah',
            'sort',
            'direction'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH NILAI
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $user = auth()->user();

        $query = MataKuliah::query();

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $mataKuliah = $query->orderBy('nama')->get();

        return view('nilai.create', compact('mataKuliah'));
    }

    /*
    |--------------------------------------------------------------------------
    | MENYIMPAN NILAI
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = auth()->user();

        $aturanMataKuliah = Rule::exists('mata_kuliah', 'id');

        if (!$user->isAdmin()) {
            $aturanMataKuliah->where('user_id', $user->id);
        }

        $data = $request->validate([
            'mata_kuliah_id' => [
                'required',
                $aturanMataKuliah,
            ],
            'jenis_penilaian' => 'required|string|max:100',
            'nilai' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $mataKuliah = MataKuliah::findOrFail(
            $data['mata_kuliah_id']
        );

        // Nilai mengikuti pemilik mata kuliah.
        $data['user_id'] = $mataKuliah->user_id;
        $data['keterangan'] = $data['keterangan'] ?: null;

        if ($this->isDuplicate($data)) {
            return back()
                ->withInput()
                ->withErrors([
                    'nilai' => 'Catatan nilai dengan data yang sama sudah tersedia.',
                ]);
        }

        Nilai::create($data);

        return redirect()
            ->route('nilai.index')
            ->with('success', 'Nilai berhasil ditambahkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | FORM EDIT NILAI
    |--------------------------------------------------------------------------
    */

    public function edit(Nilai $nilai)
    {
        $this->checkOwnership($nilai);

        $user = auth()->user();

        $query = MataKuliah::query();

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $mataKuliah = $query->orderBy('nama')->get();

        return view('nilai.edit', compact(
            'nilai',
            'mataKuliah'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | MEMPERBARUI NILAI
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Nilai $nilai)
    {
        $this->checkOwnership($nilai);

        $user = auth()->user();

        $aturanMataKuliah = Rule::exists('mata_kuliah', 'id');

        if (!$user->isAdmin()) {
            $aturanMataKuliah->where('user_id', $user->id);
        }

        $data = $request->validate([
            'mata_kuliah_id' => [
                'required',
                $aturanMataKuliah,
            ],
            'jenis_penilaian' => 'required|string|max:100',
            'nilai' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $mataKuliah = MataKuliah::findOrFail(
            $data['mata_kuliah_id']
        );

        // Pemilik nilai mengikuti pemilik mata kuliah.
        $data['user_id'] = $mataKuliah->user_id;
        $data['keterangan'] = $data['keterangan'] ?: null;

        if ($this->isDuplicate($data, $nilai->id)) {
            return back()
                ->withInput()
                ->withErrors([
                    'nilai' => 'Catatan nilai dengan data yang sama sudah tersedia.',
                ]);
        }

        $nilai->update($data);

        return redirect()
            ->route('nilai.index')
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | MENGHAPUS NILAI
    |--------------------------------------------------------------------------
    */

    public function destroy(Nilai $nilai)
    {
        $this->checkOwnership($nilai);

        $nilai->delete();

        return redirect()
            ->route('nilai.index')
            ->with('success', 'Nilai berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | MEMERIKSA DUPLIKASI NILAI
    |--------------------------------------------------------------------------
    */

    private function isDuplicate(
        array $data,
        ?int $exceptId = null
    ): bool {
        $query = Nilai::where('user_id', $data['user_id'])
            ->where('mata_kuliah_id', $data['mata_kuliah_id'])
            ->where('jenis_penilaian', $data['jenis_penilaian'])
            ->where('nilai', $data['nilai']);

        if ($data['keterangan'] === null) {
            $query->whereNull('keterangan');
        } else {
            $query->where('keterangan', $data['keterangan']);
        }

        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | MEMERIKSA KEPEMILIKAN DATA
    |--------------------------------------------------------------------------
    */

    private function checkOwnership(Nilai $nilai): void
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $nilai->user_id != $user->id) {
            abort(404, 'Data nilai tidak ditemukan.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */

    public function export()
    {
        $user = auth()->user();

        $query = Nilai::with(['mataKuliah', 'user']);

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Nilai');

        $headers = ['Kode MK', 'Mata Kuliah', 'Jenis Penilaian',
            'Nilai', 'Keterangan'];

        if ($user->isAdmin()) {
            $headers[] = 'Nama Mahasiswa';
        }

        $sheet->fromArray([$headers], null, 'A1');

        $row = 2;

        foreach ($data as $item) {
            $baris = [
                $item->mataKuliah->kode ?? '-',
                $item->mataKuliah->nama ?? '-',
                $item->jenis_penilaian,
                (float) $item->nilai,
                $item->keterangan,
            ];

            if ($user->isAdmin()) {
                $baris[] = $item->user->name ?? 'Tidak diketahui';
            }

            $sheet->fromArray([$baris], null, 'A' . $row);

            $row++;
        }

        $lastColumn = $user->isAdmin() ? 'F' : 'E';

        $sheet->getStyle('A1:' . $lastColumn . '1')
            ->getFont()
            ->setBold(true);

        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $info = $spreadsheet->createSheet();
        $info->setTitle('Informasi Export');

        $info->setCellValue('A1', 'Waktu Export');
        $info->setCellValue('B1', now()->format('d-m-Y H:i:s'));

        $info->setCellValue('A2', 'Total Data');
        $info->setCellValue('B2', $data->count());

        $info->setCellValue('A3', 'Pemilik Export');
        $info->setCellValue('B3', $user->name);

        $filename = 'StudentTrack_Nilai_' .
            now()->format('Ymd_His') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            $filename,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT EXCEL
    |--------------------------------------------------------------------------
    */

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $user = auth()->user();

        try {
            $spreadsheet = IOFactory::load(
                $request->file('file')->getRealPath()
            );

            $sheet = $spreadsheet->getSheetByName('Data Nilai')
                ?? $spreadsheet->getActiveSheet();

            $rows = $sheet->toArray();

            $berhasil = 0;
            $duplikat = 0;
            $gagal = 0;

            foreach (array_slice($rows, 1) as $row) {
                $kode = trim((string) ($row[0] ?? ''));
                $jenis = trim((string) ($row[2] ?? ''));
                $nilai = $row[3] ?? null;
                $keterangan = trim((string) ($row[4] ?? ''));

                if ($kode === '' && $jenis === '' && $nilai === null) {
                    continue;
                }

                $queryMataKuliah = MataKuliah::where('kode', $kode);

                // Mahasiswa hanya boleh mengimpor ke mata kuliah sendiri.
                if (!$user->isAdmin()) {
                    $queryMataKuliah->where('user_id', $user->id);
                }

                $mataKuliah = $queryMataKuliah->first();

                $validator = Validator::make([
                    'jenis_penilaian' => $jenis,
                    'nilai' => $nilai,
                ], [
                    'jenis_penilaian' => 'required|string|max:100',
                    'nilai' => 'required|numeric|min:0|max:100',
                ]);

                if (!$mataKuliah || $validator->fails()) {
                    $gagal++;
                    continue;
                }

                $data = [
                    'user_id' => $mataKuliah->user_id,
                    'mata_kuliah_id' => $mataKuliah->id,
                    'jenis_penilaian' => $jenis,
                    'nilai' => $nilai,
                    'keterangan' => $keterangan !== ''
                        ? $keterangan
                        : null,
                ];

                if ($this->isDuplicate($data)) {
                    $duplikat++;
                    continue;
                }

                Nilai::create($data);
                $berhasil++;
            }

            return redirect()
                ->route('nilai.index')
                ->with(
                    'success',
                    "Import selesai. Berhasil: {$berhasil}, duplikat: {$duplikat}, tidak valid: {$gagal}."
                );

        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors([
                'file' => 'File gagal dibaca. Periksa format dan isi file Excel.',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MEMILIH MAHASISWA UNTUK LAPORAN
    |--------------------------------------------------------------------------
    */

    private function getReportUser(Request $request): User
    {
        $user = auth()->user();

        // Admin wajib memilih mahasiswa untuk laporan individual.
        if ($user->isAdmin()) {
            $request->validate([
                'user_id' => [
                    'required',
                    'integer',
                    Rule::exists('users', 'id')
                        ->where('role', 'mahasiswa'),
                ],
            ]);

            return User::where('role', 'mahasiswa')
                ->findOrFail($request->user_id);
        }

        // Mahasiswa hanya dapat mencetak laporan sendiri.
        return $user;
    }

    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL DATA AKADEMIK UNTUK PDF
    |--------------------------------------------------------------------------
    */

    private function getAcademicCourses(
        User $user,
        ?int $semester = null
    ) {
        $query = MataKuliah::where('user_id', $user->id)
            ->with([
                'nilai' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
            ]);

        if ($semester !== null) {
            $query->where('semester', $semester);
        }

        return $query->orderBy('semester')
            ->orderBy('nama')
            ->get()
            ->map(function ($mataKuliah) {
                $komponen = [
                    'Tugas' => [],
                    'Kuis' => [],
                    'UTS' => [],
                    'UAS' => [],
                ];

                foreach ($mataKuliah->nilai as $nilai) {
                    $jenis = strtoupper(
                        trim($nilai->jenis_penilaian)
                    );

                    if (str_contains($jenis, 'TUGAS')) {
                        $komponen['Tugas'][] = (float) $nilai->nilai;
                    } elseif (str_contains($jenis, 'KUIS')) {
                        $komponen['Kuis'][] = (float) $nilai->nilai;
                    } elseif (str_contains($jenis, 'UTS')) {
                        $komponen['UTS'][] = (float) $nilai->nilai;
                    } elseif (str_contains($jenis, 'UAS')) {
                        $komponen['UAS'][] = (float) $nilai->nilai;
                    }
                }

                $rataKomponen = [];

                foreach ($komponen as $nama => $daftarNilai) {
                    $rataKomponen[$nama] = count($daftarNilai) > 0
                        ? array_sum($daftarNilai) / count($daftarNilai)
                        : null;
                }

                $lengkap = !in_array(null, $rataKomponen, true);

                $nilaiAkhir = null;
                $huruf = '-';
                $mutu = null;

                if ($lengkap) {
                    $nilaiAkhir =
                        ($rataKomponen['Tugas'] * 0.20) +
                        ($rataKomponen['Kuis'] * 0.10) +
                        ($rataKomponen['UTS'] * 0.30) +
                        ($rataKomponen['UAS'] * 0.40);

                    [$huruf, $mutu] = $this->convertGrade($nilaiAkhir);
                }

                return [
                    'id' => $mataKuliah->id,
                    'kode' => $mataKuliah->kode,
                    'nama' => $mataKuliah->nama,
                    'semester' => $mataKuliah->semester,
                    'sks' => (int) $mataKuliah->sks,
                    'komponen' => $rataKomponen,
                    'lengkap' => $lengkap,
                    'nilai_akhir' => $nilaiAkhir,
                    'huruf' => $huruf,
                    'mutu' => $mutu,
                ];
            });
    }

    /*
    |--------------------------------------------------------------------------
    | KONVERSI NILAI MENJADI HURUF DAN MUTU
    |--------------------------------------------------------------------------
    */

    private function convertGrade(float $nilai): array
    {
        if ($nilai >= 85) {
            return ['A', 4.00];
        }

        if ($nilai >= 80) {
            return ['AB', 3.50];
        }

        if ($nilai >= 75) {
            return ['B', 3.00];
        }

        if ($nilai >= 70) {
            return ['BC', 2.50];
        }

        if ($nilai >= 60) {
            return ['C', 2.00];
        }

        if ($nilai >= 50) {
            return ['D', 1.00];
        }

        return ['E', 0.00];
    }

    /*
    |--------------------------------------------------------------------------
    | MENGHITUNG IPS DAN IPK
    |--------------------------------------------------------------------------
    */

    private function calculateGpa($courses): array
    {
        $totalMutu = 0;
        $totalSks = 0;

        foreach ($courses as $course) {
            if (!$course['lengkap']) {
                continue;
            }

            $totalMutu += $course['sks'] * $course['mutu'];
            $totalSks += $course['sks'];
        }

        $gpa = $totalSks > 0
            ? $totalMutu / $totalSks
            : null;

        return [
            'total_mutu' => $totalMutu,
            'total_sks' => $totalSks,
            'gpa' => $gpa,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT TEKS UNTUK FPDF
    |--------------------------------------------------------------------------
    */

    private function pdfText($text): string
    {
        $text = (string) $text;

        $converted = iconv(
            'UTF-8',
            'windows-1252//TRANSLIT',
            $text
        );

        return $converted !== false ? $converted : $text;
    }

    /*
    |--------------------------------------------------------------------------
    | MEMBUAT DOKUMEN PDF
    |--------------------------------------------------------------------------
    */

    private function createAcademicPdf(
        User $user,
        $courses,
        string $title,
        ?int $semester = null
    ) {
        if (!class_exists(\FPDF::class)) {
            abort(
                500,
                'Library FPDF belum tersedia. Jalankan composer require setasign/fpdf:^1.9.'
            );
        }

        $pdf = new \FPDF('L', 'mm', 'A4');

        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 12);
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(
            0,
            10,
            $this->pdfText('STUDENT TRACK'),
            0,
            1,
            'C'
        );

        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(
            0,
            8,
            $this->pdfText($title),
            0,
            1,
            'C'
        );

        $pdf->Ln(3);

        $pdf->SetFont('Arial', '', 10);

        $pdf->Cell(
            35,
            7,
            $this->pdfText('Nama Mahasiswa'),
            0,
            0
        );

        $pdf->Cell(
            0,
            7,
            ': ' . $this->pdfText($user->name),
            0,
            1
        );

        $pdf->Cell(
            35,
            7,
            $this->pdfText('Email'),
            0,
            0
        );

        $pdf->Cell(
            0,
            7,
            ': ' . $this->pdfText($user->email),
            0,
            1
        );

        if ($semester !== null) {
            $pdf->Cell(
                35,
                7,
                $this->pdfText('Semester'),
                0,
                0
            );

            $pdf->Cell(
                0,
                7,
                ': ' . $semester,
                0,
                1
            );
        }

        $pdf->Cell(
            35,
            7,
            $this->pdfText('Tanggal Cetak'),
            0,
            0
        );

        $pdf->Cell(
            0,
            7,
            ': ' . now()->format('d-m-Y H:i'),
            0,
            1
        );

        $pdf->Ln(5);

        // Header tabel.
        $pdf->SetFont('Arial', 'B', 8);

        $columns = [
            ['Kode', 22],
            ['Mata Kuliah', 55],
            ['Semester', 18],
            ['SKS', 12],
            ['Tugas', 20],
            ['Kuis', 20],
            ['UTS', 20],
            ['UAS', 20],
            ['Akhir', 20],
            ['Huruf', 18],
            ['Status', 35],
        ];

        foreach ($columns as [$label, $width]) {
            $pdf->Cell(
                $width,
                9,
                $this->pdfText($label),
                1,
                0,
                'C'
            );
        }

        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);

        foreach ($courses as $course) {
            $status = $course['lengkap']
                ? 'Lengkap'
                : 'Belum lengkap';

            $values = [
                [$course['kode'], 22],
                [$course['nama'], 55],
                [$course['semester'], 18],
                [$course['sks'], 12],
                [
                    $course['komponen']['Tugas'] !== null
                        ? number_format($course['komponen']['Tugas'], 2)
                        : '-',
                    20,
                ],
                [
                    $course['komponen']['Kuis'] !== null
                        ? number_format($course['komponen']['Kuis'], 2)
                        : '-',
                    20,
                ],
                [
                    $course['komponen']['UTS'] !== null
                        ? number_format($course['komponen']['UTS'], 2)
                        : '-',
                    20,
                ],
                [
                    $course['komponen']['UAS'] !== null
                        ? number_format($course['komponen']['UAS'], 2)
                        : '-',
                    20,
                ],
                [
                    $course['nilai_akhir'] !== null
                        ? number_format($course['nilai_akhir'], 2)
                        : '-',
                    20,
                ],
                [$course['huruf'], 18],
                [$status, 35],
            ];

            foreach ($values as [$value, $width]) {
                $pdf->Cell(
                    $width,
                    8,
                    $this->pdfText($value),
                    1,
                    0,
                    'C'
                );
            }

            $pdf->Ln();
        }

        $statistik = $this->calculateGpa($courses);

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 10);

        $pdf->Cell(
            0,
            8,
            $this->pdfText('RINGKASAN AKADEMIK'),
            0,
            1
        );

        $pdf->SetFont('Arial', '', 10);

        $pdf->Cell(
            45,
            8,
            $this->pdfText('Total SKS Dihitung'),
            0,
            0
        );

        $pdf->Cell(
            0,
            8,
            ': ' . $statistik['total_sks'] . ' SKS',
            0,
            1
        );

        $pdf->Cell(
            45,
            8,
            $this->pdfText(
                $title === 'KARTU HASIL STUDI' ? 'IPS' : 'IPK'
            ),
            0,
            0
        );

        $pdf->Cell(
            0,
            8,
            ': ' . (
                $statistik['gpa'] !== null
                    ? number_format($statistik['gpa'], 2)
                    : 'Belum tersedia'
            ),
            0,
            1
        );

        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'I', 8);

        $pdf->MultiCell(
            0,
            5,
            $this->pdfText(
                'Catatan: Nilai akhir dihitung dari Tugas 20%, Kuis 10%, UTS 30%, dan UAS 40%. Mata kuliah yang belum memiliki seluruh komponen penilaian tidak dimasukkan dalam perhitungan IPS/IPK. Konversi nilai merupakan skema simulasi StudentTrack, bukan ketentuan resmi IPB.'
            )
        );

        return $pdf;
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD KHS PDF
    |--------------------------------------------------------------------------
    */

    public function downloadKhs(Request $request)
    {
        $user = $this->getReportUser($request);

        // Semester diambil dari semester mata kuliah.
        $semester = $request->filled('semester')
            ? (int) $request->semester
            : null;

        if ($semester === null) {
            $semester = MataKuliah::where('user_id', $user->id)
                ->max('semester');
        }

        if ($semester === null) {
            return back()->withErrors([
                'semester' => 'Mahasiswa belum memiliki data mata kuliah.',
            ]);
        }

        $courses = $this->getAcademicCourses(
            $user,
            $semester
        );

        if ($courses->isEmpty()) {
            return back()->withErrors([
                'semester' => 'Tidak ada data mata kuliah pada semester tersebut.',
            ]);
        }

        $pdf = $this->createAcademicPdf(
            $user,
            $courses,
            'KARTU HASIL STUDI',
            $semester
        );

        $filename = 'KHS_' .
            preg_replace('/[^A-Za-z0-9_-]/', '_', $user->name) .
            '_Semester_' . $semester . '.pdf';

        return response(
            $pdf->Output('S'),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' =>
                    'attachment; filename="' . $filename . '"',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD TRANSKRIP PDF
    |--------------------------------------------------------------------------
    */

    public function downloadTranskrip(Request $request)
    {
        $user = $this->getReportUser($request);

        $courses = $this->getAcademicCourses($user);

        if ($courses->isEmpty()) {
            return back()->withErrors([
                'transkrip' => 'Belum ada data mata kuliah untuk dibuat menjadi transkrip.',
            ]);
        }

        $pdf = $this->createAcademicPdf(
            $user,
            $courses,
            'TRANSKRIP AKADEMIK'
        );

        $filename = 'Transkrip_' .
            preg_replace('/[^A-Za-z0-9_-]/', '_', $user->name) .
            '.pdf';

        return response(
            $pdf->Output('S'),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' =>
                    'attachment; filename="' . $filename . '"',
            ]
        );
    }
}