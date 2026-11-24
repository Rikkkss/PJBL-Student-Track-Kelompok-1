<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    /**
     * Menampilkan semua tugas.
     */
    public function index()
    {
        $tugas = Tugas::with('mataKuliah')
            ->orderBy('deadline', 'asc')
            ->get();

        return view('tugas.index', compact('tugas'));
    }

    /**
     * Menampilkan form tambah tugas.
     */
    public function create()
    {
        $mataKuliah = MataKuliah::orderBy('nama')->get();

        return view('tugas.create', compact('mataKuliah'));
    }

    /**
     * Menyimpan tugas baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline' => 'required|date',
            'prioritas' => 'required|in:Rendah,Sedang,Tinggi',
            'status' => 'required|in:Belum dikerjakan,Dikerjakan,Selesai',
        ]);

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
        $mataKuliah = MataKuliah::orderBy('nama')->get();

        return view('tugas.edit', compact('tugas', 'mataKuliah'));
    }

    /**
     * Memperbarui tugas yang sudah ada.
     */
    public function update(Request $request, Tugas $tugas)
    {
        $data = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline' => 'required|date',
            'prioritas' => 'required|in:Rendah,Sedang,Tinggi',
            'status' => 'required|in:Belum dikerjakan,Dikerjakan,Selesai',
        ]);

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
        $tugas->delete();

        return redirect()
            ->route('tugas.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }
}
