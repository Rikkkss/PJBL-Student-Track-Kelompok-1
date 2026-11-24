
@extends('layouts.app')

@section('title', 'Tambah Nilai - StudentTrack')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('nilai.index') }}"
           class="text-decoration-none text-secondary">
            &larr; Kembali ke Nilai Akademik
        </a>

        <h2 class="fw-bold mt-3 mb-1">Tambah Nilai</h2>
        <p class="text-secondary mb-0">
            Masukkan hasil penilaian mata kuliahmu.
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

            @if ($mataKuliah->isEmpty())
                <div class="alert alert-warning mb-0">
                    <h6 class="fw-bold">Belum ada mata kuliah</h6>
                    <p>
                        Tambahkan mata kuliah terlebih dahulu
                        sebelum memasukkan nilai.
                    </p>

                    <a href="{{ route('mata-kuliah.create') }}"
                       class="btn btn-primary">
                        Tambah Mata Kuliah
                    </a>
                </div>
            @else
                <form action="{{ route('nilai.store') }}"
                      method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="mata_kuliah_id"
                               class="form-label fw-semibold">
                            Mata Kuliah
                        </label>

                        <select name="mata_kuliah_id"
                                id="mata_kuliah_id"
                                class="form-select @error('mata_kuliah_id') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Mata Kuliah --</option>

                            @foreach ($mataKuliah as $mk)
                                <option value="{{ $mk->id }}"
                                    @selected(old('mata_kuliah_id') == $mk->id)>
                                    {{ $mk->kode }} - {{ $mk->nama }}
                                </option>
                            @endforeach
                        </select>

                        @error('mata_kuliah_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jenis_penilaian"
                               class="form-label fw-semibold">
                            Jenis Penilaian
                        </label>

                        <select name="jenis_penilaian"
                                id="jenis_penilaian"
                                class="form-select @error('jenis_penilaian') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Jenis Penilaian --</option>

                            @foreach (['Kuis', 'Tugas', 'Praktikum', 'UTS', 'UAS', 'Lainnya'] as $jenis)
                                <option value="{{ $jenis }}"
                                    @selected(old('jenis_penilaian') === $jenis)>
                                    {{ $jenis }}
                                </option>
                            @endforeach
                        </select>

                        @error('jenis_penilaian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nilai"
                               class="form-label fw-semibold">
                            Nilai
                        </label>

                        <input type="number"
                               name="nilai"
                               id="nilai"
                               class="form-control @error('nilai') is-invalid @enderror"
                               min="0"
                               max="100"
                               step="0.01"
                               value="{{ old('nilai') }}"
                               placeholder="Contoh: 85 atau 87.5"
                               required>

                        <div class="form-text">
                            Masukkan nilai dari 0 sampai 100.
                        </div>

                        @error('nilai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="keterangan"
                               class="form-label fw-semibold">
                            Keterangan
                            <span class="text-secondary fw-normal">
                                (opsional)
                            </span>
                        </label>

                        <textarea name="keterangan"
                                  id="keterangan"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Contoh: Kuis materi routing">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            Simpan Nilai
                        </button>

                        <a href="{{ route('nilai.index') }}"
                           class="btn btn-light">
                            Batal
                        </a>
                    </div>
                </form>
            @endif

        </div>
    </div>
</div>
@endsection
