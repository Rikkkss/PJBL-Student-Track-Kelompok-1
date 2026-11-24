
@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah - StudentTrack')

@section('content')
<div class="mk-form-page">

    {{-- HEADER --}}
    <div class="mk-form-header">
        <div>
            <div class="mk-form-eyebrow">
                <i class="bi bi-journal-plus"></i>
                AKADEMIK
            </div>

            <h2>Tambah Mata Kuliah</h2>
            <p>Tambahkan informasi mata kuliah baru ke dalam daftar akademikmu.</p>
        </div>

        <a href="{{ route('mata-kuliah.index') }}" class="mk-back-button">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="mk-form-layout">

        {{-- FORM CARD --}}
        <div class="mk-form-card">

            <div class="mk-form-card-header">
                <div class="mk-form-card-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h5>Informasi Mata Kuliah</h5>
                    <p>Lengkapi seluruh informasi berikut.</p>
                </div>
            </div>

            <div class="mk-form-card-body">

                {{-- VALIDATION ERROR --}}
                @if ($errors->any())
                    <div class="mk-error-alert">
                        <div class="mk-error-icon">
                            <i class="bi bi-exclamation-circle-fill"></i>
                        </div>
                        <div>
                            <strong>Data belum dapat disimpan</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('mata-kuliah.store') }}" method="POST">
                    @csrf

                    {{-- KODE --}}
                    <div class="mk-field">
                        <label for="kode">
                            Kode Mata Kuliah
                            <span>*</span>
                        </label>

                        <div class="mk-input-wrapper">
                            <i class="bi bi-upc-scan"></i>
                            <input
                                type="text"
                                id="kode"
                                name="kode"
                                value="{{ old('kode') }}"
                                placeholder="Contoh: TEK301"
                                maxlength="20"
                                required
                            >
                        </div>
                        <small>Masukkan kode unik mata kuliah.</small>
                        @error('kode')
                            <div class="mk-field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NAMA --}}
                    <div class="mk-field">
                        <label for="nama">
                            Nama Mata Kuliah
                            <span>*</span>
                        </label>

                        <div class="mk-input-wrapper">
                            <i class="bi bi-book"></i>
                            <input
                                type="text"
                                id="nama"
                                name="nama"
                                value="{{ old('nama') }}"
                                placeholder="Contoh: Pemrograman Web"
                                maxlength="100"
                                required
                            >
                        </div>
                        <small>Masukkan nama lengkap mata kuliah.</small>
                        @error('nama')
                            <div class="mk-field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SKS DAN SEMESTER --}}
                    <div class="mk-form-row">

                        <div class="mk-field">
                            <label for="sks">
                                Jumlah SKS
                                <span>*</span>
                            </label>

                            <div class="mk-input-wrapper">
                                <i class="bi bi-clock-history"></i>
                                <input
                                    type="number"
                                    id="sks"
                                    name="sks"
                                    min="1"
                                    max="6"
                                    value="{{ old('sks') }}"
                                    placeholder="Contoh: 3"
                                    required
                                >
                            </div>
                            <small>Rentang 1 sampai 6 SKS.</small>
                            @error('sks')
                                <div class="mk-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mk-field">
                            <label for="semester">
                                Semester
                                <span>*</span>
                            </label>

                            <div class="mk-input-wrapper">
                                <i class="bi bi-calendar3"></i>
                                <input
                                    type="number"
                                    id="semester"
                                    name="semester"
                                    min="1"
                                    max="8"
                                    value="{{ old('semester') }}"
                                    placeholder="Contoh: 3"
                                    required
                                >
                            </div>
                            <small>Rentang semester 1 sampai 8.</small>
                            @error('semester')
                                <div class="mk-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- ACTION --}}
                    <div class="mk-form-actions">
                        <a href="{{ route('mata-kuliah.index') }}" class="mk-cancel-button">
                            Batal
                        </a>

                        <button type="submit" class="mk-submit-button">
                            <i class="bi bi-check2-circle"></i>
                            Simpan Mata Kuliah
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- INFORMATION PANEL --}}
        <div class="mk-info-panel">
            <div class="mk-info-icon">
                <i class="bi bi-lightbulb"></i>
            </div>

            <h5>Informasi Pengisian</h5>
            <p>
                Pastikan informasi mata kuliah yang dimasukkan sudah benar
                sebelum menyimpan data.
            </p>

            <div class="mk-info-item">
                <div><i class="bi bi-upc-scan"></i></div>
                <section>
                    <strong>Kode Mata Kuliah</strong>
                    <span>Gunakan kode mata kuliah yang unik.</span>
                </section>
            </div>

            <div class="mk-info-item">
                <div><i class="bi bi-clock"></i></div>
                <section>
                    <strong>Jumlah SKS</strong>
                    <span>Isi sesuai bobot SKS mata kuliah.</span>
                </section>
            </div>

            <div class="mk-info-item">
                <div><i class="bi bi-calendar-check"></i></div>
                <section>
                    <strong>Semester</strong>
                    <span>Sesuaikan dengan semester mata kuliah.</span>
                </section>
            </div>

            <div class="mk-info-note">
                <i class="bi bi-shield-check"></i>
                <span>Pastikan tidak ada kesalahan penulisan sebelum menyimpan.</span>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .mk-form-page {
        color: var(--text);
    }

    .mk-form-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 25px;
    }

    .mk-form-eyebrow {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 9px;
        color: var(--primary);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.3px;
    }

    .mk-form-header h2 {
        margin: 0 0 7px;
        color: var(--text);
        font-size: 27px;
        font-weight: 800;
        letter-spacing: -.8px;
    }

    .mk-form-header p {
        margin: 0;
        color: var(--muted);
        font-size: 12px;
    }

    .mk-back-button {
        display: inline-flex;
        align-items: center;
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

    .mk-back-button:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .mk-form-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 280px;
        gap: 20px;
        align-items: start;
    }

    .mk-form-card {
        overflow: hidden;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(20, 20, 50, .025);
    }

    .mk-form-card-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 21px 23px;
        border-bottom: 1px solid var(--border);
    }

    .mk-form-card-icon {
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

    .mk-form-card-header h5 {
        margin: 0 0 5px;
        color: var(--text);
        font-size: 14px;
        font-weight: 800;
    }

    .mk-form-card-header p {
        margin: 0;
        color: var(--muted);
        font-size: 11px;
    }

    .mk-form-card-body {
        padding: 24px;
    }

    .mk-field {
        margin-bottom: 21px;
    }

    .mk-field label {
        display: block;
        margin-bottom: 9px;
        color: var(--text);
        font-size: 12px;
        font-weight: 700;
    }

    .mk-field label span {
        color: #e05261;
    }

    .mk-input-wrapper {
        position: relative;
    }

    .mk-input-wrapper > i {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        color: var(--muted);
        font-size: 15px;
        pointer-events: none;
    }

    .mk-input-wrapper input {
        width: 100%;
        height: 45px;
        padding: 0 14px 0 41px;
        outline: none;
        border: 1px solid var(--border);
        border-radius: 9px;
        background: var(--surface);
        color: var(--text);
        font-family: inherit;
        font-size: 12px;
        transition: .2s;
    }

    .mk-input-wrapper input::placeholder {
        color: var(--muted);
        opacity: .7;
    }

    .mk-input-wrapper input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(101, 88, 220, .10);
    }

    .mk-field small {
        display: block;
        margin-top: 7px;
        color: var(--muted);
        font-size: 10px;
    }

    .mk-field-error {
        margin-top: 6px;
        color: #e05261;
        font-size: 11px;
    }

    .mk-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 17px;
    }

    .mk-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        padding-top: 20px;
        margin-top: 5px;
        border-top: 1px solid var(--border);
    }

    .mk-cancel-button,
    .mk-submit-button {
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

    .mk-cancel-button {
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--text);
    }

    .mk-cancel-button:hover {
        background: var(--surface-secondary);
        color: var(--text);
    }

    .mk-submit-button {
        border: 0;
        background: var(--primary);
        color: #fff;
    }

    .mk-submit-button:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 5px 13px rgba(101, 88, 220, .2);
    }

    .mk-error-alert {
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

    .mk-error-icon {
        font-size: 17px;
    }

    .mk-error-alert strong {
        display: block;
        margin-bottom: 5px;
    }

    .mk-error-alert ul {
        margin: 0;
        padding-left: 17px;
    }

    .mk-info-panel {
        padding: 22px 19px;
        border: 1px solid var(--border);
        border-radius: 15px;
        background: var(--surface);
    }

    .mk-info-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 43px;
        height: 43px;
        margin-bottom: 15px;
        border-radius: 12px;
        background: rgba(101, 88, 220, .12);
        color: var(--primary);
        font-size: 20px;
    }

    .mk-info-panel h5 {
        margin: 0 0 8px;
        color: var(--text);
        font-size: 14px;
        font-weight: 800;
    }

    .mk-info-panel > p {
        margin: 0 0 20px;
        color: var(--muted);
        font-size: 11px;
        line-height: 1.7;
    }

    .mk-info-item {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 13px 0;
        border-top: 1px solid var(--border);
    }

    .mk-info-item > div {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 31px;
        height: 31px;
        border-radius: 8px;
        background: var(--surface-secondary);
        color: var(--primary);
        font-size: 13px;
    }

    .mk-info-item section {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .mk-info-item strong {
        color: var(--text);
        font-size: 11px;
    }

    .mk-info-item span {
        color: var(--muted);
        font-size: 10px;
        line-height: 1.5;
    }

    .mk-info-note {
        display: flex;
        gap: 9px;
        padding: 12px;
        margin-top: 13px;
        border-radius: 9px;
        background: var(--surface-secondary);
        color: var(--muted);
        font-size: 10px;
        line-height: 1.6;
    }

    .mk-info-note i {
        color: #20a477;
        font-size: 14px;
    }

    @media (max-width: 991.98px) {
        .mk-form-layout {
            grid-template-columns: 1fr;
        }

        .mk-info-panel {
            display: none;
        }
    }

    @media (max-width: 575.98px) {
        .mk-form-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .mk-form-header h2 {
            font-size: 23px;
        }

        .mk-back-button {
            width: 100%;
            justify-content: center;
        }

        .mk-form-card-body {
            padding: 17px;
        }

        .mk-form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .mk-form-actions {
            flex-direction: column-reverse;
        }

        .mk-cancel-button,
        .mk-submit-button {
            width: 100%;
        }
    }
</style>
@endpush
