
@extends('layouts.app')

@section('title', 'Daftar Tugas - StudentTrack')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Daftar Tugas</h2>
        <p class="text-secondary mb-0">
            Kelola tugas dan deadline perkuliahanmu.
        </p>
    </div>

    <a href="{{ route('tugas.create') }}" class="btn btn-primary">
        + Tambah Tugas
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Judul Tugas</th>
                        <th>Mata Kuliah</th>
                        <th>Deadline</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tugas as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <strong>{{ $item->judul }}</strong>
                                <div class="small text-muted">
                                    {{ $item->deskripsi }}
                                </div>
                            </td>

                            <td>
                                {{ $item->mataKuliah->nama ?? '-' }}
                            </td>

                            <td>
                                {{ $item->deadline->format('d/m/Y') }}
                            </td>

                            <td>
                                @if ($item->prioritas == 'Tinggi')
                                    <span class="badge bg-danger">Tinggi</span>
                                @elseif ($item->prioritas == 'Sedang')
                                    <span class="badge bg-warning text-dark">Sedang</span>
                                @else
                                    <span class="badge bg-success">Rendah</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $item->status }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('tugas.edit', $item) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form action="{{ route('tugas.destroy', $item) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus tugas ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <p class="text-muted mb-2">
                                    Belum ada tugas yang ditambahkan.
                                </p>
                                <a href="{{ route('tugas.create') }}"
                                   class="btn btn-primary btn-sm">
                                    Tambah Tugas Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
