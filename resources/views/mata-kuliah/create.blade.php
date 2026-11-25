
@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah - StudentTrack')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Tambah Mata Kuliah</h2>
    <p class="text-secondary">Masukkan informasi mata kuliah baru.</p>
</div>

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

        <form action="{{ route('mata-kuliah.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Kode Mata Kuliah</label>
                <input type="text" name="kode"
                       class="form-control" value="{{ old('kode') }}"
                       placeholder="Contoh: TEK301" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Mata Kuliah</label>
                <input type="text" name="nama"
                       class="form-control" value="{{ old('nama') }}"
                       placeholder="Masukkan nama mata kuliah" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">SKS</label>
                    <input type="number" name="sks"
                           class="form-control" min="1" max="6"
                           value="{{ old('sks') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Semester</label>
                    <input type="number" name="semester"
                           class="form-control" min="1" max="8"
                           value="{{ old('semester') }}" required>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    Simpan Mata Kuliah
                </button>

                <a href="{{ route('mata-kuliah.index') }}"
                   class="btn btn-light border">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
