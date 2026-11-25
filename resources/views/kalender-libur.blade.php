
@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h2 class="fw-bold mb-1">Kalender Hari Libur</h2>
            <p class="text-muted mb-0">
                Informasi hari libur nasional Indonesia
            </p>
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary mt-2">
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Pilih Tahun --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('kalender-libur.index') }}"
                  method="GET"
                  class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label for="tahun" class="form-label fw-semibold">
                        Pilih Tahun
                    </label>

                    <select name="tahun" id="tahun" class="form-select">
                        @for ($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                            <option value="{{ $i }}"
                                {{ (int) $tahun === $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        Tampilkan Kalender
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- Informasi --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Tahun</p>
                    <h3 class="fw-bold mb-0">{{ $tahun }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Hari Libur</p>
                    <h3 class="fw-bold mb-0">{{ count($libur) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Negara</p>
                    <h3 class="fw-bold mb-0">Indonesia</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Pesan Error --}}
    @if ($error)
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endif

    {{-- Tabel Hari Libur --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Daftar Hari Libur Nasional {{ $tahun }}</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No.</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Nama Hari Libur</th>
                            <th>Nama Internasional</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($libur as $item)
                            <tr>
                                <td class="ps-3">{{ $loop->iteration }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item['date'])->translatedFormat('d F Y') }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item['date'])->translatedFormat('l') }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $item['localName'] ?? $item['name'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $item['name'] ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    @if (!$error)
                                        <p class="text-muted mb-0">
                                            Belum ada data hari libur untuk tahun {{ $tahun }}.
                                        </p>
                                    @else
                                        <p class="text-muted mb-0">
                                            Data belum dapat ditampilkan.
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <div class="text-muted small mt-3">
        Sumber data: Nager.Date Public Holidays API
    </div>

</div>
@endsection
