
@extends('layouts.app')

@section('title', 'Mata Kuliah - StudentTrack')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Mata Kuliah</h2>
        <p class="text-secondary mb-0">
            Kelola daftar mata kuliah semester kamu.
        </p>
    </div>

    <a href="{{ route('mata-kuliah.create') }}"
       class="btn btn-primary">
        + Tambah Mata Kuliah
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Semester</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($mataKuliah as $mk)
                        <tr>
                            <td>
                                <span class="badge text-bg-primary">
                                    {{ $mk->kode }}
                                </span>
                            </td>
                            <td class="fw-semibold">{{ $mk->nama }}</td>
                            <td>{{ $mk->sks }} SKS</td>
                            <td>Semester {{ $mk->semester }}</td>
                            <td class="text-center">
                                <a href="{{ route('mata-kuliah.edit', $mk) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form action="{{ route('mata-kuliah.destroy', $mk) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Yakin ingin menghapus mata kuliah ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <p class="text-secondary mb-1">
                                    Belum ada mata kuliah.
                                </p>
                                <span class="small">
                                    Klik tombol Tambah Mata Kuliah untuk memulai.
                                </span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="text-secondary small mt-3">
    Total mata kuliah: {{ $mataKuliah->count() }}
</div>
@endsection
