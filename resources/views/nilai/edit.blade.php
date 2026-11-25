
@extends('layouts.app')

@section('title', 'Edit Nilai - StudentTrack')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('nilai.index') }}"
           class="text-decoration-none text-secondary">
            &larr; Kembali ke Nilai Akademik
        </a>

        <h2 class="fw-bold mt-3 mb-1">Edit Nilai</h2>
        <p class="text-secondary mb-0">
            Perbarui data penilaian mata kuliah.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali data berikut:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('nilai.update', ['nilai' => $nilai->id]) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="mata_kuliah_id"
                           class="form-label fw-semibold">
                        Mata Kuliah
                    </label>

                    <select name="mata_kuliah_id"
                            id="mata_kuliah_id"
                            class="form-select"
                            required>
                        <option value="">-- Pilih Mata Kuliah --</option>

                        @foreach ($mataKuliah as $mk)
                            <option value="{{ $mk->id }}"
                                @selected(old('mata_kuliah_id', $nilai->mata_kuliah_id) == $mk->id)>
                                {{ $mk->kode }} - {{ $mk->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="jenis_penilaian"
                           class="form-label fw-semibold">
                        Jenis Penilaian
                    </label>

                    <select name="jenis_penilaian"
                            id="jenis_penilaian"
                            class="form-select"
                            required>
                        <option value="">-- Pilih Jenis Penilaian --</option>

                        @foreach (['Kuis', 'Tugas', 'Praktikum', 'UTS', 'UAS', 'Lainnya'] as $jenis)
                            <option value="{{ $jenis }}"
                                @selected(old('jenis_penilaian', $nilai->jenis_penilaian) === $jenis)>
                                {{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nilai" class="form-label fw-semibold">
                        Nilai
                    </label>

                    <input type="number"
                           name="nilai"
                           id="nilai"
                           class="form-control"
                           min="0"
                           max="100"
                           step="0.01"
                           value="{{ old('nilai', $nilai->nilai) }}"
                           required>

                    <div class="form-text">
                        Masukkan nilai dari 0 sampai 100.
                    </div>
                </div>

                <div class="mb-4">
                    <label for="keterangan"
                           class="form-label fw-semibold">
                        Keterangan (opsional)
                    </label>

                    <textarea name="keterangan"
                              id="keterangan"
                              class="form-control"
                              rows="3"
                              placeholder="Contoh: UTS materi routing">{{ old('keterangan', $nilai->keterangan) }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('nilai.index') }}"
                       class="btn btn-light">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
