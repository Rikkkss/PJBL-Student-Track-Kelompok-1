
@extends('layouts.app')

@section('title', 'Edit Tugas - StudentTrack')

@section('content')
<h2 class="fw-bold mb-1">Edit Tugas</h2>
<p class="text-secondary mb-4">Perbarui informasi tugasmu.</p>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tugas.update', $tugas) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Judul Tugas</label>
                <input type="text" name="judul" class="form-control"
                       value="{{ old('judul', $tugas->judul) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mata Kuliah</label>
                <select name="mata_kuliah_id" class="form-select" required>
                    @foreach ($mataKuliah as $mk)
                        <option value="{{ $mk->id }}"
                            @selected(old('mata_kuliah_id', $tugas->mata_kuliah_id) == $mk->id)>
                            {{ $mk->kode }} - {{ $mk->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control"
                          rows="3">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Deadline</label>
                <input type="date" name="deadline" class="form-control"
                       value="{{ old('deadline', $tugas->deadline->format('Y-m-d')) }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Prioritas</label>
                <select name="prioritas" class="form-select" required>
                    @foreach (['Rendah', 'Sedang', 'Tinggi'] as $p)
                        <option value="{{ $p }}"
                            @selected(old('prioritas', $tugas->prioritas) === $p)>
                            {{ $p }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    @foreach ([
                        'Belum dikerjakan',
                        'Sedang dikerjakan',
                        'Selesai'
                    ] as $s)
                        <option value="{{ $s }}"
                            @selected(old('status', $tugas->status) === $s)>
                            {{ $s }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>

            <a href="{{ route('tugas.index') }}"
               class="btn btn-light border">
                Batal
            </a>
        </form>
    </div>
</div>
@endsection
