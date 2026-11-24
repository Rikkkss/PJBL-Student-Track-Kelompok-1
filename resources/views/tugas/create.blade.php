
@extends('layouts.app')

@section('title', 'Tambah Tugas - StudentTrack')

@section('content')
<div class="task-form-page">

    {{-- HEADER --}}
    <div class="task-form-header">
        <div>
            <div class="task-form-eyebrow">
                <i class="bi bi-clipboard-plus"></i>
                MANAJEMEN AKADEMIK
            </div>

            <h2>Tambah Tugas</h2>
            <p>Catat tugas, tentukan prioritas, dan atur deadline perkuliahanmu.</p>
        </div>

        <a href="{{ route('tugas.index') }}" class="task-back-button">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="task-form-layout">

        {{-- FORM CARD --}}
        <div class="task-form-card">

            <div class="task-form-card-header">
                <div class="task-form-card-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h5>Informasi Tugas</h5>
                    <p>Lengkapi informasi tugas yang ingin ditambahkan.</p>
                </div>
            </div>

            <div class="task-form-card-body">

                {{-- ERROR VALIDATION --}}
                @if ($errors->any())
                    <div class="task-error-alert">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <div>
                            <strong>Data belum dapat disimpan.</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- EMPTY COURSE --}}
                @if ($mataKuliah->isEmpty())
                    <div class="task-warning-alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>
                            <strong>Belum ada mata kuliah</strong>
                            <p>
                                Kamu perlu menambahkan mata kuliah terlebih dahulu
                                sebelum membuat tugas.
                            </p>
                            <a href="{{ route('mata-kuliah.create') }}">
                                <i class="bi bi-plus-circle"></i>
                                Tambahkan Mata Kuliah
                            </a>
                        </div>
                    </div>
                @else

                    <form action="{{ route('tugas.store') }}" method="POST">
                        @csrf

                        {{-- JUDUL --}}
                        <div class="task-form-field">
                            <label for="judul">
                                Judul Tugas <span>*</span>
                            </label>

                            <div class="task-input-wrapper">
                                <i class="bi bi-card-heading"></i>
                                <input
                                    type="text"
                                    id="judul"
                                    name="judul"
                                    value="{{ old('judul') }}"
                                    placeholder="Contoh: Laporan Praktikum Jaringan"
                                    required
                                >
                            </div>

                            <small>Masukkan judul tugas dengan jelas.</small>

                            @error('judul')
                                <div class="task-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- MATA KULIAH --}}
                        <div class="task-form-field">
                            <label for="mata_kuliah_id">
                                Mata Kuliah <span>*</span>
                            </label>

                            <div class="task-select-wrapper">
                                <i class="bi bi-book"></i>
                                <select
                                    id="mata_kuliah_id"
                                    name="mata_kuliah_id"
                                    required
                                >
                                    <option value="">Pilih mata kuliah</option>

                                    @foreach ($mataKuliah as $mk)
                                        <option
                                            value="{{ $mk->id }}"
                                            @selected(old('mata_kuliah_id') == $mk->id)
                                        >
                                            {{ $mk->kode }} - {{ $mk->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <small>Pilih mata kuliah yang berkaitan dengan tugas.</small>

                            @error('mata_kuliah_id')
                                <div class="task-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DESKRIPSI --}}
                        <div class="task-form-field">
                            <label for="deskripsi">Deskripsi Tugas</label>

                            <div class="task-textarea-wrapper">
                                <i class="bi bi-text-paragraph"></i>
                                <textarea
                                    id="deskripsi"
                                    name="deskripsi"
                                    rows="4"
                                    placeholder="Tuliskan detail atau instruksi tugas..."
                                >{{ old('deskripsi') }}</textarea>
                            </div>

                            <small>Tambahkan keterangan jika diperlukan.</small>

                            @error('deskripsi')
                                <div class="task-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DEADLINE --}}
                        <div class="task-form-field">
                            <label for="deadline">
                                Deadline <span>*</span>
                            </label>

                            <div class="task-input-wrapper">
                                <i class="bi bi-calendar-event"></i>
                                <input
                                    type="date"
                                    id="deadline"
                                    name="deadline"
                                    value="{{ old('deadline') }}"
                                    required
                                >
                            </div>

                            <small>Tentukan tanggal terakhir pengumpulan tugas.</small>

                            @error('deadline')
                                <div class="task-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- PRIORITAS DAN STATUS --}}
                        <div class="task-form-row">

                            <div class="task-form-field">
                                <label for="prioritas">
                                    Prioritas <span>*</span>
                                </label>

                                <div class="task-select-wrapper">
                                    <i class="bi bi-flag"></i>
                                    <select id="prioritas" name="prioritas" required>
                                        @foreach (['Rendah', 'Sedang', 'Tinggi'] as $p)
                                            <option
                                                value="{{ $p }}"
                                                @selected(old('prioritas', 'Sedang') === $p)
                                            >
                                                {{ $p }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <small>Tentukan tingkat kepentingan tugas.</small>

                                @error('prioritas')
                                    <div class="task-field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="task-form-field">
                                <label for="status">
                                    Status <span>*</span>
                                </label>

                                <div class="task-select-wrapper">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <select id="status" name="status" required>
                                        @foreach ([
                                            'Belum dikerjakan',
                                            'Sedang dikerjakan',
                                            'Selesai'
                                        ] as $s)
                                            <option
                                                value="{{ $s }}"
                                                @selected(old('status', 'Belum dikerjakan') === $s)
                                            >
                                                {{ $s }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <small>Pilih status pengerjaan tugas.</small>

                                @error('status')
                                    <div class="task-field-error">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- BUTTON --}}
                        <div class="task-form-actions">
                            <a href="{{ route('tugas.index') }}" class="task-cancel-button">
                                Batal
                            </a>

                            <button type="submit" class="task-submit-button">
                                <i class="bi bi-check2-circle"></i>
                                Simpan Tugas
                            </button>
                        </div>

                    </form>
                @endif
            </div>
        </div>

        {{-- INFORMATION PANEL --}}
        <div class="task-info-panel">
            <div class="task-info-icon">
                <i class="bi bi-lightbulb"></i>
            </div>

            <h5>Tips Mengelola Tugas</h5>
            <p>
                Atur informasi tugas dengan benar agar aktivitas perkuliahan
                lebih terorganisasi.
            </p>

            <div class="task-info-item">
                <div><i class="bi bi-calendar-check"></i></div>
                <section>
                    <strong>Atur Deadline</strong>
                    <span>Catat tanggal pengumpulan agar tidak terlewat.</span>
                </section>
            </div>

            <div class="task-info-item">
                <div><i class="bi bi-flag"></i></div>
                <section>
                    <strong>Tentukan Prioritas</strong>
                    <span>Dahulukan tugas dengan tingkat prioritas tinggi.</span>
                </section>
            </div>

            <div class="task-info-item">
                <div><i class="bi bi-arrow-repeat"></i></div>
                <section>
                    <strong>Perbarui Status</strong>
                    <span>Sesuaikan status dengan perkembangan pengerjaan.</span>
                </section>
            </div>

            <div class="task-info-note">
                <i class="bi bi-info-circle-fill"></i>
                <span>
                    Pastikan mata kuliah dan deadline sudah sesuai
                    sebelum menyimpan tugas.
                </span>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .task-form-page {
        color: var(--text);
    }

    .task-form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .task-form-eyebrow {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 9px;
        color: var(--primary);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.3px;
    }

    .task-form-header h2 {
        margin: 0 0 7px;
        color: var(--text);
        font-size: 27px;
        font-weight: 800;
        letter-spacing: -.8px;
    }

    .task-form-header p {
        margin: 0;
        color: var(--muted);
        font-size: 12px;
    }

    .task-back-button {
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

    .task-back-button:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .task-form-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 280px;
        gap: 20px;
        align-items: start;
    }

    .task-form-card,
    .task-info-panel {
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 15px;
        background: var(--surface);
        box-shadow: 0 4px 15px rgba(20, 20, 50, .025);
    }

    .task-form-card-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 21px 23px;
        border-bottom: 1px solid var(--border);
    }

    .task-form-card-icon,
    .task-info-icon {
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

    .task-form-card-header h5 {
        margin: 0 0 5px;
        color: var(--text);
        font-size: 14px;
        font-weight: 800;
    }

    .task-form-card-header p {
        margin: 0;
        color: var(--muted);
        font-size: 11px;
    }

    .task-form-card-body {
        padding: 24px;
    }

    .task-form-field {
        margin-bottom: 21px;
    }

    .task-form-field label {
        display: block;
        margin-bottom: 9px;
        color: var(--text);
        font-size: 12px;
        font-weight: 700;
    }

    .task-form-field label span {
        color: #e05261;
    }

    .task-input-wrapper,
    .task-select-wrapper,
    .task-textarea-wrapper {
        position: relative;
    }

    .task-input-wrapper > i,
    .task-select-wrapper > i,
    .task-textarea-wrapper > i {
        position: absolute;
        top: 14px;
        left: 14px;
        color: var(--muted);
        font-size: 15px;
        pointer-events: none;
    }

    .task-input-wrapper input,
    .task-select-wrapper select,
    .task-textarea-wrapper textarea {
        width: 100%;
        min-height: 45px;
        padding: 11px 14px 11px 41px;
        outline: none;
        border: 1px solid var(--border);
        border-radius: 9px;
        background-color: var(--surface);
        color: var(--text);
        font-family: inherit;
        font-size: 12px;
        transition: .2s;
    }

    .task-select-wrapper select {
        appearance: none;
        padding-right: 35px;
        cursor: pointer;
    }

    .task-select-wrapper::after {
        content: '';
        position: absolute;
        top: 17px;
        right: 14px;
        width: 8px;
        height: 8px;
        border-right: 1.5px solid var(--muted);
        border-bottom: 1.5px solid var(--muted);
        transform: rotate(45deg);
        pointer-events: none;
    }

    .task-textarea-wrapper textarea {
        min-height: 110px;
        resize: vertical;
        line-height: 1.6;
    }

    .task-input-wrapper input:focus,
    .task-select-wrapper select:focus,
    .task-textarea-wrapper textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(101, 88, 220, .10);
    }

    .task-input-wrapper input::placeholder,
    .task-textarea-wrapper textarea::placeholder {
        color: var(--muted);
        opacity: .7;
    }

    .task-form-field small {
        display: block;
        margin-top: 7px;
        color: var(--muted);
        font-size: 10px;
    }

    .task-field-error {
        margin-top: 6px;
        color: #e05261;
        font-size: 11px;
    }

    .task-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 17px;
    }

    .task-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        padding-top: 20px;
        margin-top: 5px;
        border-top: 1px solid var(--border);
    }

    .task-cancel-button,
    .task-submit-button {
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

    .task-cancel-button {
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--text);
    }

    .task-cancel-button:hover {
        background: var(--surface-secondary);
        color: var(--text);
    }

    .task-submit-button {
        border: 0;
        background: var(--primary);
        color: #fff;
    }

    .task-submit-button:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 5px 13px rgba(101, 88, 220, .2);
    }

    .task-error-alert,
    .task-warning-alert {
        display: flex;
        gap: 12px;
        padding: 14px;
        margin-bottom: 22px;
        border-radius: 10px;
        font-size: 11px;
    }

    .task-error-alert {
        border: 1px solid rgba(224, 82, 97, .2);
        background: rgba(224, 82, 97, .08);
        color: #e05261;
    }

    .task-warning-alert {
        border: 1px solid rgba(233, 154, 50, .25);
        background: rgba(233, 154, 50, .09);
        color: #d28a20;
    }

    .task-error-alert > i,
    .task-warning-alert > i {
        font-size: 17px;
    }

    .task-error-alert strong,
    .task-warning-alert strong {
        display: block;
        margin-bottom: 6px;
    }

    .task-error-alert ul {
        margin: 0;
        padding-left: 17px;
    }

    .task-warning-alert p {
        margin: 0 0 10px;
        line-height: 1.6;
    }

    .task-warning-alert a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: inherit;
        font-weight: 800;
        text-decoration: underline;
    }

    .task-info-panel {
        padding: 22px 19px;
    }

    .task-info-icon {
        margin-bottom: 15px;
    }

    .task-info-panel h5 {
        margin: 0 0 8px;
        color: var(--text);
        font-size: 14px;
        font-weight: 800;
    }

    .task-info-panel > p {
        margin: 0 0 20px;
        color: var(--muted);
        font-size: 11px;
        line-height: 1.7;
    }

    .task-info-item {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 13px 0;
        border-top: 1px solid var(--border);
    }

    .task-info-item > div {
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

    .task-info-item section {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .task-info-item strong {
        color: var(--text);
        font-size: 11px;
    }

    .task-info-item span {
        color: var(--muted);
        font-size: 10px;
        line-height: 1.5;
    }

    .task-info-note {
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

    .task-info-note i {
        color: var(--primary);
        font-size: 14px;
    }

    @media (max-width: 991.98px) {
        .task-form-layout {
            grid-template-columns: 1fr;
        }

        .task-info-panel {
            display: none;
        }
    }

    @media (max-width: 575.98px) {
        .task-form-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .task-form-header h2 {
            font-size: 23px;
        }

        .task-back-button {
            width: 100%;
        }

        .task-form-card-body {
            padding: 17px;
        }

        .task-form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .task-form-actions {
            flex-direction: column-reverse;
        }

        .task-cancel-button,
        .task-submit-button {
            width: 100%;
        }
    }
</style>
@endpush
