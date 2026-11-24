@extends('layouts.app')

@section('title', 'Nilai Akademik - StudentTrack')

@section('content')
<div class="container-fluid px-0">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Nilai Akademik</h2>
            <p class="text-secondary mb-0">Pantau dan kelola hasil belajarmu.</p>
        </div>

        <a href="{{ route('nilai.create') }}" class="btn btn-primary">
            + Tambah Nilai
        </a>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-secondary mb-2">Rata-rata Semua Nilai</p>
                    <h2 class="fw-bold text-primary mb-1">
                        {{ $rataRata !== null
                            ? number_format((float) $rataRata, 2, ',', '.')
                            : '-' }}
                    </h2>
                    <small class="text-secondary">Dari {{ $nilai->total() }} catatan nilai</small>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-secondary mb-2">Mata Kuliah Dinilai</p>
                    <h2 class="fw-bold mb-1">{{ $totalMataKuliah }}</h2>
                    <small class="text-secondary">Mata kuliah yang memiliki catatan nilai</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Import dan Export --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Kelola Data Excel</h5>
                    <small class="text-secondary">
                        Import atau unduh seluruh catatan nilai.
                    </small>
                </div>

                <a href="{{ route('nilai.export') }}" class="btn btn-outline-success">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </a>
            </div>

            <hr>

            <form action="{{ route('nilai.import') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="row g-2 align-items-end">

                @csrf

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Pilih File Excel</label>
                    <input type="file"
                           name="file"
                           class="form-control"
                           accept=".xlsx,.xls,.csv"
                           required>
                    <small class="text-secondary">
                        Format: XLSX, XLS, atau CSV. Maksimal 5 MB.
                    </small>
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-upload me-1"></i>
                        Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Daftar Nilai --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 p-md-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Nilai</h5>
                    <small class="text-secondary">
                        Total {{ $nilai->total() }} catatan nilai.
                    </small>
                </div>

                {{-- Sorting --}}
                <form method="GET"
                      action="{{ route('nilai.index') }}"
                      class="d-flex flex-wrap gap-2">

                    <select name="sort" class="form-select">
                        <option value="created_at" {{ $sort === 'created_at' ? 'selected' : '' }}>
                            Tanggal Input
                        </option>
                        <option value="mata_kuliah" {{ $sort === 'mata_kuliah' ? 'selected' : '' }}>
                            Mata Kuliah
                        </option>
                        <option value="jenis_penilaian" {{ $sort === 'jenis_penilaian' ? 'selected' : '' }}>
                            Jenis Penilaian
                        </option>
                        <option value="nilai" {{ $sort === 'nilai' ? 'selected' : '' }}>
                            Nilai
                        </option>
                    </select>

                    <select name="direction" class="form-select">
                        <option value="desc" {{ $direction === 'desc' ? 'selected' : '' }}>
                            Menurun
                        </option>
                        <option value="asc" {{ $direction === 'asc' ? 'selected' : '' }}>
                            Menaik
                        </option>
                    </select>

                    <button type="submit" class="btn btn-outline-primary">
                        Urutkan
                    </button>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Mata Kuliah</th>
                            <th>Jenis Penilaian</th>
                            <th>Nilai</th>
                            <th>Keterangan</th>
                            <th>Tanggal Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($nilai as $item)
                            <tr>
                                <td>{{ $nilai->firstItem() + $loop->index }}</td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $item->mataKuliah->nama ?? 'Mata kuliah tidak ditemukan' }}
                                    </div>
                                    @if ($item->mataKuliah)
                                        <small class="text-secondary">
                                            {{ $item->mataKuliah->kode }}
                                        </small>
                                    @endif
                                </td>

                                <td>{{ $item->jenis_penilaian }}</td>

                                <td>
                                    <span class="badge
                                        {{ (float) $item->nilai >= 80
                                            ? 'text-bg-success'
                                            : ((float) $item->nilai >= 60
                                                ? 'text-bg-warning'
                                                : 'text-bg-danger') }}">
                                        {{ number_format((float) $item->nilai, 2, ',', '.') }}
                                    </span>
                                </td>

                                <td>{{ $item->keterangan ?: '-' }}</td>

                                <td>
                                    <small class="text-secondary">
                                        {{ $item->created_at?->format('d/m/Y H:i') ?? '-' }}
                                    </small>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('nilai.edit', $item) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>

                                        <form action="{{ route('nilai.destroy', ['nilai' => $item->id]) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus nilai ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <h6 class="fw-semibold">Belum Ada Data Nilai</h6>
                                    <p class="text-secondary mb-3">
                                        Tambahkan nilai pertamamu untuk mulai memantau hasil belajar.
                                    </p>
                                    <a href="{{ route('nilai.create') }}" class="btn btn-primary">
                                        + Tambah Nilai
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($nilai->hasPages())
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                    <small class="text-secondary">
                        Menampilkan {{ $nilai->firstItem() }}–{{ $nilai->lastItem() }}
                        dari {{ $nilai->total() }} data
                    </small>

                    {{ $nilai->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
