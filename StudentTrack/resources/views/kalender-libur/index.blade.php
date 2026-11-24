
@extends('layouts.app')

@section('title', 'Kalender Libur - StudentTrack')

@section('content')

<div class="mb-4">
    <h2 class="fw-bold">Kalender Tanggal Merah</h2>
    <p class="text-muted">
        Informasi hari libur nasional Indonesia berdasarkan API.
    </p>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('kalender-libur.index') }}"
              method="GET"
              class="row g-3 align-items-end">

            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Pilih Tahun
                </label>

                <select name="tahun" class="form-select">
                    @for ($i = 2026; $i <= 2031; $i++)
                        <option value="{{ $i }}"
                            {{ $tahun == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-3">
                <button class="btn btn-primary w-100">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

@if ($error)
    <div class="alert alert-danger">
        {{ $error }}
    </div>
@endif

@if ($liburMendatang)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <span class="badge bg-success mb-2">
                Hari Libur Berikutnya
            </span>

            <h4 class="fw-bold">
                {{ $liburMendatang['name'] }}
            </h4>

            <h5 class="text-primary">
                {{ \Carbon\Carbon::parse($liburMendatang['date'])
                    ->locale('id')->translatedFormat('l, d F Y') }}
            </h5>
        </div>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">

        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-bold mb-0">
                Daftar Hari Libur {{ $tahun }}
            </h5>

            <span class="badge bg-primary">
                {{ $libur->count() }} Hari
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Hari Libur</th>
                        <th>Kategori</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($libur as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <span class="text-danger fw-semibold">
                                    {{ \Carbon\Carbon::parse($item['date'])
                                        ->locale('id')
                                        ->translatedFormat('d F Y') }}
                                </span>
                            </td>

                            <td>
                                {{ $item['name'] }}
                            </td>

                            <td>
                                <span class="badge bg-danger">
                                    Libur Nasional
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"
                                class="text-center text-muted py-4">
                                Belum ada data hari libur.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="text-muted small mt-3">
    Sumber data: Nager.Date API.
    Data diperbarui melalui cache setiap 12 jam.
</div>

@endsection
