<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index()
    {
        $mataKuliah = MataKuliah::latest()->get();

        return view('mata-kuliah.index', compact('mataKuliah'));
    }

    public function create()
    {
        return view('mata-kuliah.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => 'required|string|max:20|unique:mata_kuliah,kode',
            'nama' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        MataKuliah::create($data);

        return redirect()
            ->route('mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit(MataKuliah $mataKuliah)
    {
        return view('mata-kuliah.edit', compact('mataKuliah'));
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $data = $request->validate([
            'kode' => 'required|string|max:20|unique:mata_kuliah,kode,' . $mataKuliah->id,
            'nama' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        $mataKuliah->update($data);

        return redirect()
            ->route('mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        $mataKuliah->delete();

        return redirect()
            ->route('mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }
}