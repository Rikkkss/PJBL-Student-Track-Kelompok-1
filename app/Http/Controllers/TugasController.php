<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TugasController extends Controller
{
    /**
     * Menampilkan tugas.
     * Admin melihat seluruh tugas.
     * Mahasiswa hanya melihat tugas miliknya.
     */
    public function index()
    {
        $query = Tugas::with('mataKuliah');

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $tugas = $query->orderBy('deadline', 'asc')->get();

        return view('tugas.index', compact('tugas'));
    }

    /**
     * Menampilkan form tambah tugas.
     */
    public function create()
    {
        $query = MataKuliah::query();

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $mataKuliah = $query->orderBy('nama')->get();

        return view('tugas.create', compact('mataKuliah'));
    }

    /**
     * Menyimpan tugas baru.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'mata_kuliah_id' => [
                'required',
                Rule::exists('mata_kuliah', 'id')
                    ->when(
                        !$user->isAdmin(),
                        fn ($rule) => $rule->where('user_id', $user->id)
                    ),
            ],
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline' => 'required|date',
            'prioritas' => 'required|in:Rendah,Sedang,Tinggi',
            'status' => 'required|in:Belum dikerjakan,Dikerjakan,Selesai',
        ]);

        $data['user_id'] = $user->id;

        Tugas::create($data);

        return redirect()
            ->route('tugas.index')
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit tugas.
     */
    public function edit(Tugas $tugas)
    {
        $this->checkOwnership($tugas);

        $query = MataKuliah::query();

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $mataKuliah = $query->orderBy('nama')->get();

        return view('tugas.edit', compact('tugas', 'mataKuliah'));
    }

    /**
     * Memperbarui tugas.
     */
    public function update(Request $request, Tugas $tugas)
    {
        $this->checkOwnership($tugas);

        $user = auth()->user();

        $data = $request->validate([
            'mata_kuliah_id' => [
                'required',
                Rule::exists('mata_kuliah', 'id')
                    ->when(
                        !$user->isAdmin(),
                        fn ($rule) => $rule->where('user_id', $user->id)
                    ),
            ],
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline' => 'required|date',
            'prioritas' => 'required|in:Rendah,Sedang,Tinggi',
            'status' => 'required|in:Belum dikerjakan,Dikerjakan,Selesai',
        ]);

        // Pemilik tugas tidak boleh berubah melalui form.
        $tugas->update($data);

        return redirect()
            ->route('tugas.index')
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    /**
     * Menghapus tugas.
     */
    public function destroy(Tugas $tugas)
    {
        $this->checkOwnership($tugas);

        $tugas->delete();

        return redirect()
            ->route('tugas.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    /**
     * Memeriksa kepemilikan tugas.
     */
    private function checkOwnership(Tugas $tugas): void
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $tugas->user_id != $user->id) {
            abort(404, 'Data tugas tidak ditemukan.');
        }
    }
}
