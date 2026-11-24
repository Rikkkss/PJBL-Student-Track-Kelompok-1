
@extends('layouts.app')

@section('title', 'Edit Mata Kuliah - StudentTrack')

@section('content')
<div class="mk-edit-page">

    {{-- HEADER --}}
    <div class="mk-edit-header">
        <div>
            <div class="mk-edit-eyebrow">
                <i class="bi bi-journal-check"></i>
                AKADEMIK
            </div>

            <h2>Edit Mata Kuliah</h2>
            <p>Perbarui informasi mata kuliah yang telah terdaftar.</p>
        </div>

        <a href="{{ route('mata-kuliah.index') }}" class="mk-edit-back">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="mk-edit-layout">

        {{-- FORM --}}
        <div class="mk-edit-card">

            <div class="mk-edit-card-header">
                <div class="mk-edit-card-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h5>Perbarui Informasi</h5>
                    <p>Ubah data pada kolom yang ingin diperbarui.</p>
                </div>
            </div>

            <div class="mk-edit-card-body">

                {{-- ERROR VALIDATION --}}
                @if ($errors->any())
                    <div class="mk-edit-error">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <div>
                            <strong>Periksa kembali data yang dimasukkan.</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('mata-kuliah.update', $mataKuliah) }}"
                      method="POST">
                    @csrf
                    @method('PUT')

                    {{-- KODE --}}
                    <div class="mk-edit-field">
                        <label for="kode">
                            Kode Mata Kuliah <span>*</span>
                        </label>

                        <div class="mk-edit-input">
                            <i class="bi bi-upc-scan"></i>
                            <input
                                type="text"
                                id="kode"
                                name="kode"
                                value="{{ old('kode', $mataKuliah->kode) }}"
                                placeholder="Contoh: TEK301"
                                maxlength="20"
                                required
                            >
                        </div>

                        <small>Gunakan kode unik untuk setiap mata kuliah.</small>

                        @error('kode')
                            <div class="mk-edit-field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NAMA --}}
                    <div class="mk-edit-field">
                        <label for="nama">
                            Nama Mata Kuliah <span>*</span>
                        </label>

                        <div class="mk-edit-input">
                            <i class="bi bi-book"></i>
                            <input
                                type="text"
                                id="nama"
                                name="nama"
                                value="{{ old('nama', $mataKuliah->nama) }}"
                                placeholder="Masukkan nama mata kuliah"
                                maxlength="100"
                                required
                            >
                        </div>

                        <small>Masukkan nama lengkap mata kuliah.</small>

                        @error('nama')
                            <div class="mk-edit-field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SKS DAN SEMESTER --}}
                    <div class="mk-edit-row">

                        <div class="mk-edit-field">
                            <label for="sks">
                                Jumlah SKS <span>*</span>
                            </label>

                            <div class="mk-edit-input">
                                <i class="bi bi-clock-history"></i>
                                <input
                                    type="number"
                                    id="sks"
                                    name="sks"
                                    min="1"
                                    max="6"
                                    value="{{ old('sks', $mataKuliah->sks) }}"
                                    required
                                >
                            </div>

                            <small>Rentang 1–6 SKS.</small>

                            @error('sks')
                                <div class="mk-edit-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mk-edit-field">
                            <label for="semester">
                                Semester <span>*</span>
                            </label>

                            <div class="mk-edit-input">
                                <i class="bi bi-calendar3"></i>
                                <input
                                    type="number"
                                    id="semester"
                                    name="semester"
                                    min="1"
                                    max="8"
                                    value="{{ old('semester', $mataKuliah->semester) }}"
                                    required
                                >
                            </div>

                            <small>Rentang semester 1–8.</small>

                            @error('semester')
                                <div class="mk-edit-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- ACTION --}}
                    <div class="mk-edit-actions">
                        <a href="{{ route('mata-kuliah.index') }}"
                           class="mk-edit-cancel">
                            Batal
                        </a>

                        <button type="submit" class="mk-edit-submit">
                            <i class="bi bi-check2-circle"></i>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- INFORMATION PANEL --}}
        <div class="mk-edit-info">

            <div class="mk-edit-info-icon">
                <i class="bi bi-info-circle"></i>
            </div>

            <h5>Informasi Mata Kuliah</h5>
            <p>Berikut merupakan data mata kuliah yang sedang kamu edit.</p>

            <div class="mk-edit-preview">
                <span>KODE MATA KULIAH</span>
                <strong>{{ $mataKuliah->kode }}</strong>
            </div>

            <div class="mk-edit-preview">
                <span>NAMA MATA KULIAH</span>
                <strong>{{ $mataKuliah->nama }}</strong>
            </div>

            <div class="mk-edit-preview-row">
                <div class="mk-edit-preview">
                    <span>SKS</span>
                    <strong>{{ $mataKuliah->sks }} SKS</strong>
                </div>

                <div class="mk-edit-preview">
                    <span>SEMESTER</span>
                    <strong>{{ $mataKuliah->semester }}</strong>
                </div>
            </div>

            <div class="mk-edit-note">
                <i class="bi bi-shield-check"></i>
                <span>
                    Perubahan data akan diterapkan setelah kamu menekan
                    tombol Simpan Perubahan.
                </span>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .mk-edit-page {
        color: var(--text);
    }

    .mk-edit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .mk-edit-eyebrow {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 9px;
        color: var(--primary);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.3px;
    }

    .mk-edit-header h2 {
        margin: 0 0 7px;
        color: var(--text);
        font-size: 27px;
        font-weight: 800;
        letter-spacing: -.8px;
    }

    .mk-edit-header p {
        margin: 0;
        color: var(--muted);
        font-size: 12px;
    }

    .mk-edit-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 9px;
        background: var(--surface);
        color: var(--text);
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s;
        white-space: nowrap;
    }

    .mk-edit-back:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .mk-edit-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 280px;
        gap: 20px;
        align-items: start;
    }

    .mk-edit-card,
    .mk-edit-info {
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 15px;
        background: var(--surface);
        box-shadow: 0 4px 15px rgba(20, 20, 50, .025);
    }

    .mk-edit-card-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 21px 23px;
        border-bottom: 1px solid var(--border);
    }

    .mk-edit-card-icon,
    .mk-edit-info-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 43px;
        height: 43px;
        border-radius: 12px;
        background: rgba(101, 88, 220, .12);
        color: var(--primary);
        font-size: 19px;
    }

    .mk-edit-card-header h5 {
        margin: 0 0 5px;
        color: var(--text);
        font-size: 14px;
        font-weight: 800;
    }

    .mk-edit-card-header p {
        margin: 0;
        color: var(--muted);
        font-size: 11px;
    }

    .mk-edit-card-body {
        padding: 24px;
    }

    .mk-edit-field {
        margin-bottom: 21px;
    }

    .mk-edit-field label {
        display: block;
        margin-bottom: 9px;
        color: var(--text);
        font-size: 12px;
        font-weight: 700;
    }

    .mk-edit-field label span {
        color: #e05261;
    }

    .mk-edit-input {
        position: relative;
    }

    .mk-edit-input > i {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        color: var(--muted);
        font-size: 15px;
        pointer-events: none;
    }

    .mk-edit-input input {
        width: 100%;
        height: 45px;
        padding: 0 14px 0 41px;
        border: 1px solid var(--border);
        border-radius: 9px;
        outline: none;
        background: var(--surface);
        color: var(--text);
        font-family: inherit;
        font-size: 12px;
        transition: .2s;
    }

    .mk-edit-input input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(101, 88, 220, .10);
    }

    .mk-edit-input input::placeholder {
        color: var(--muted);
        opacity: .7;
    }

    .mk-edit-field small {
        display: block;
        margin-top: 7px;
        color: var(--muted);
        font-size: 10px;
    }

    .mk-edit-field-error {
        margin-top: 6px;
        color: #e05261;
        font-size: 11px;
    }

    .mk-edit-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 17px;
    }

    .mk-edit-actions {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        padding-top: 20px;
        margin-top: 5px;
        border-top: 1px solid var(--border);
    }

    .mk-edit-cancel,
    .mk-edit-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 0 17px;
        border-radius: 9px;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: .2s;
    }

    .mk-edit-cancel {
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--text);
    }

    .mk-edit-cancel:hover {
        background: var(--surface-secondary);
        color: var(--text);
    }

    .mk-edit-submit {
        border: 0;
        background: var(--primary);
        color: #fff;
    }

    .mk-edit-submit:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 5px 13px rgba(101, 88, 220, .2);
    }

    .mk-edit-error {
        display: flex;
        gap: 12px;
        padding: 14px;
        margin-bottom: 22px;
        border: 1px solid rgba(224, 82, 97, .2);
        border-radius: 10px;
        background: rgba(224, 82, 97, .08);
        color: #e05261;
        font-size: 11px;
    }

    .mk-edit-error > i {
        font-size: 17px;
    }

    .mk-edit-error strong {
        display: block;
        margin-bottom: 5px;
    }

    .mk-edit-error ul {
        margin: 0;
        padding-left: 17px;
    }

    .mk-edit-info {
        padding: 22px 19px;
    }

    .mk-edit-info-icon {
        margin-bottom: 15px;
    }

    .mk-edit-info h5 {
        margin: 0 0 8px;
        color: var(--text);
        font-size: 14px;
        font-weight: 800;
    }

    .mk-edit-info > p {
        margin: 0 0 18px;
        color: var(--muted);
        font-size: 11px;
        line-height: 1.7;
    }

    .mk-edit-preview {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 13px 0;
        border-top: 1px solid var(--border);
    }

    .mk-edit-preview span {
        color: var(--muted);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .6px;
    }

    .mk-edit-preview strong {
        color: var(--text);
        font-size: 12px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .mk-edit-preview-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .mk-edit-note {
        display: flex;
        gap: 9px;
        padding: 12px;
        margin-top: 12px;
        border-radius: 9px;
        background: var(--surface-secondary);
        color: var(--muted);
        font-size: 10px;
        line-height: 1.6;
    }

    .mk-edit-note i {
        color: #20a477;
        font-size: 14px;
    }

    @media (max-width: 991.98px) {
        .mk-edit-layout {
            grid-template-columns: 1fr;
        }

        .mk-edit-info {
            display: none;
        }
    }

    @media (max-width: 575.98px) {
        .mk-edit-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .mk-edit-header h2 {
            font-size: 23px;
        }

        .mk-edit-back {
            width: 100%;
        }

        .mk-edit-card-body {
            padding: 17px;
        }

        .mk-edit-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .mk-edit-actions {
            flex-direction: column-reverse;
        }

        .mk-edit-cancel,
        .mk-edit-submit {
            width: 100%;
        }
    }
</style>
@endpush
