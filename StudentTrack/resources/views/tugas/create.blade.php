
@extends('layouts.app')

@section('title', 'Tambah Tugas - StudentTrack')

@section('content')
<h2 class="fw-bold mb-1">Tambah Tugas</h2>
<p class="text-secondary mb-4">Catat tugas dan deadline kuliahmu.</p>

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

        @if ($mataKuliah->isEmpty())
            <div class="alert alert-warning">
                Kamu belum memiliki mata kuliah.
                <a href="{{ route('mata-kuliah.create') }}">
                    Tambahkan mata kuliah dahulu.
                </a>
            </div>
        @else
            <form action="{{ route('tugas.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Judul Tugas</label>
                    <input type="text" name="judul" class="form-control"
                           value="{{ old('judul') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mata Kuliah</label>
                    <select name="mata_kuliah_id" class="form-select" required>
                        <option value="">Pilih mata kuliah</option>
                        @foreach ($mataKuliah as $mk)
                            <option value="{{ $mk->id }}"
                                @selected(old('mata_kuliah_id') == $mk->id)>
                                {{ $mk->kode }} - {{ $mk->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control"
                              rows="3">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deadline</label>
                    <input type="date" name="deadline" class="form-control"
                           value="{{ old('deadline') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Prioritas</label>
                    <select name="prioritas" class="form-select" required>
                        @foreach (['Rendah', 'Sedang', 'Tinggi'] as $p)
                            <option value="{{ $p }}"
                                @selected(old('prioritas', 'Sedang') === $p)>
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
                                @selected(old('status', 'Belum dikerjakan') === $s)>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Tugas
                </button>

                <a href="{{ route('tugas.index') }}"
                   class="btn btn-light border">
                    Batal
                </a>
            </form>
        @endif
    </div>
</div>
@endsection
