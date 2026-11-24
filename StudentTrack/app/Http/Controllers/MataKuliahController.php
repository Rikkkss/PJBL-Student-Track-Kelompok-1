<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    /**
     * Menampilkan data mata kuliah.
     * Admin melihat semua data.
     * Mahasiswa hanya melihat data sendiri.
     */
    public function index()
    {
        $query = MataKuliah::query();

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $mataKuliah = $query->latest()->get();

        return view('mata-kuliah.index', compact('mataKuliah'));
    }

    /**
     * Form tambah mata kuliah.
     */
    public function create()
    {
        return view('mata-kuliah.create');
    }

    /**
     * Menyimpan mata kuliah baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mata_kuliah', 'kode'),
            ],
            'nama' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        // Pemilik data ditentukan dari akun yang sedang login.
        $data['user_id'] = auth()->id();

        MataKuliah::create($data);

        return redirect()
            ->route('mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    /**
     * Form edit mata kuliah.
     */
    public function edit(MataKuliah $mataKuliah)
    {
        $this->checkOwnership($mataKuliah);

        return view('mata-kuliah.edit', compact('mataKuliah'));
    }

    /**
     * Memperbarui mata kuliah.
     */
    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $this->checkOwnership($mataKuliah);

        $data = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mata_kuliah', 'kode')
                    ->ignore($mataKuliah->id),
            ],
            'nama' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        // Kepemilikan data tidak boleh diubah melalui form.
        unset($data['user_id']);

        $mataKuliah->update($data);

        return redirect()
            ->route('mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    /**
     * Menghapus mata kuliah.
     */
    public function destroy(MataKuliah $mataKuliah)
    {
        $this->checkOwnership($mataKuliah);

        $mataKuliah->delete();

        return redirect()
            ->route('mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }

    /**
     * Memeriksa hak akses kepemilikan data.
     */
    private function checkOwnership(MataKuliah $mataKuliah): void
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $mataKuliah->user_id != $user->id) {
            abort(404, 'Data mata kuliah tidak ditemukan.');
        }
    }
}
