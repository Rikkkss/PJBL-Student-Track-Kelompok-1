
@extends('layouts.app')

@section('title', 'Edit Tugas - StudentTrack')

@section('content')
<div class="task-edit-page">

    {{-- HEADER --}}
    <div class="task-edit-header">
        <div>
            <div class="task-edit-eyebrow">
                <i class="bi bi-clipboard-check"></i>
                MANAJEMEN AKADEMIK
            </div>

            <h2>Edit Tugas</h2>
            <p>Perbarui informasi dan perkembangan tugas perkuliahanmu.</p>
        </div>

        <a href="{{ route('tugas.index') }}" class="task-edit-back">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="task-edit-layout">

        {{-- FORM CARD --}}
        <div class="task-edit-card">

            <div class="task-edit-card-header">
                <div class="task-edit-card-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h5>Perbarui Informasi Tugas</h5>
                    <p>Ubah informasi tugas sesuai kebutuhan.</p>
                </div>
            </div>

            <div class="task-edit-card-body">

                {{-- ERROR VALIDATION --}}
                @if ($errors->any())
                    <div class="task-edit-error">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <div>
                            <strong>Data belum dapat diperbarui.</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('tugas.update', $tugas) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- JUDUL --}}
                    <div class="task-edit-field">
                        <label for="judul">
                            Judul Tugas <span>*</span>
                        </label>

                        <div class="task-edit-input">
                            <i class="bi bi-card-heading"></i>
                            <input
                                type="text"
                                id="judul"
                                name="judul"
                                value="{{ old('judul', $tugas->judul) }}"
                                placeholder="Masukkan judul tugas"
                                required
                            >
                        </div>

                        <small>Masukkan judul tugas yang jelas.</small>

                        @error('judul')
                            <div class="task-edit-field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- MATA KULIAH --}}
                    <div class="task-edit-field">
                        <label for="mata_kuliah_id">
                            Mata Kuliah <span>*</span>
                        </label>

                        <div class="task-edit-select">
                            <i class="bi bi-book"></i>
                            <select id="mata_kuliah_id"
                                    name="mata_kuliah_id"
                                    required>
                                @foreach ($mataKuliah as $mk)
                                    <option
                                        value="{{ $mk->id }}"
                                        @selected(old('mata_kuliah_id', $tugas->mata_kuliah_id) == $mk->id)
                                    >
                                        {{ $mk->kode }} - {{ $mk->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <small>Pilih mata kuliah yang berkaitan dengan tugas.</small>

                        @error('mata_kuliah_id')
                            <div class="task-edit-field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="task-edit-field">
                        <label for="deskripsi">Deskripsi Tugas</label>

                        <div class="task-edit-textarea">
                            <i class="bi bi-text-paragraph"></i>
                            <textarea
                                id="deskripsi"
                                name="deskripsi"
                                rows="4"
                                placeholder="Tuliskan detail atau instruksi tugas..."
                            >{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                        </div>

                        <small>Perbarui keterangan tugas jika diperlukan.</small>

                        @error('deskripsi')
                            <div class="task-edit-field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DEADLINE --}}
                    <div class="task-edit-field">
                        <label for="deadline">
                            Deadline <span>*</span>
                        </label>

                        <div class="task-edit-input">
                            <i class="bi bi-calendar-event"></i>
                            <input
                                type="date"
                                id="deadline"
                                name="deadline"
                                value="{{ old('deadline', $tugas->deadline->format('Y-m-d')) }}"
                                required
                            >
                        </div>

                        <small>Perbarui tanggal pengumpulan tugas.</small>

                        @error('deadline')
                            <div class="task-edit-field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PRIORITAS DAN STATUS --}}
                    <div class="task-edit-row">

                        <div class="task-edit-field">
                            <label for="prioritas">
                                Prioritas <span>*</span>
                            </label>

                            <div class="task-edit-select">
                                <i class="bi bi-flag"></i>
                                <select id="prioritas" name="prioritas" required>
                                    @foreach (['Rendah', 'Sedang', 'Tinggi'] as $p)
                                        <option
                                            value="{{ $p }}"
                                            @selected(old('prioritas', $tugas->prioritas) === $p)
                                        >
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <small>Tingkat kepentingan tugas.</small>

                            @error('prioritas')
                                <div class="task-edit-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="task-edit-field">
                            <label for="status">
                                Status <span>*</span>
                            </label>

                            <div class="task-edit-select">
                                <i class="bi bi-arrow-repeat"></i>
                                <select id="status" name="status" required>
                                    @foreach ([
                                        'Belum dikerjakan',
                                        'Sedang dikerjakan',
                                        'Selesai'
                                    ] as $s)
                                        <option
                                            value="{{ $s }}"
                                            @selected(old('status', $tugas->status) === $s)
                                        >
                                            {{ $s }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <small>Perkembangan pengerjaan tugas.</small>

                            @error('status')
                                <div class="task-edit-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- ACTION --}}
                    <div class="task-edit-actions">
                        <a href="{{ route('tugas.index') }}"
                           class="task-edit-cancel">
                            Batal
                        </a>

                        <button type="submit" class="task-edit-submit">
                            <i class="bi bi-check2-circle"></i>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- INFORMATION PANEL --}}
        <div class="task-edit-info">

            <div class="task-edit-info-icon">
                <i class="bi bi-info-circle"></i>
            </div>

            <h5>Informasi Tugas</h5>
            <p>Ringkasan data tugas yang sedang kamu perbarui.</p>

            <div class="task-edit-preview">
                <span>JUDUL TUGAS</span>
                <strong>{{ $tugas->judul }}</strong>
            </div>

            <div class="task-edit-preview">
                <span>MATA KULIAH</span>
                <strong>{{ $tugas->mataKuliah->nama ?? '-' }}</strong>
            </div>

            <div class="task-edit-preview">
                <span>DEADLINE</span>
                <strong>{{ $tugas->deadline->format('d/m/Y') }}</strong>
            </div>

            <div class="task-edit-preview">
                <span>PRIORITAS</span>
                <strong>{{ $tugas->prioritas }}</strong>
            </div>

            <div class="task-edit-preview">
                <span>STATUS</span>
                <strong>{{ $tugas->status }}</strong>
            </div>

            <div class="task-edit-note">
                <i class="bi bi-shield-check"></i>
                <span>
                    Perubahan akan disimpan setelah kamu menekan
                    tombol Simpan Perubahan.
                </span>
            </div>

        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .task-edit-page {
        color: var(--text);
    }

    .task-edit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .task-edit-eyebrow {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 9px;
        color: var(--primary);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.3px;
    }

    .task-edit-header h2 {
        margin: 0 0 7px;
        color: var(--text);
        font-size: 27px;
        font-weight: 800;
        letter-spacing: -.8px;
    }

    .task-edit-header p {
        margin: 0;
        color: var(--muted);
        font-size: 12px;
    }

    .task-edit-back {
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

    .task-edit-back:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .task-edit-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 280px;
        gap: 20px;
        align-items: start;
    }

    .task-edit-card,
    .task-edit-info {
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 15px;
        background: var(--surface);
        box-shadow: 0 4px 15px rgba(20, 20, 50, .025);
    }

    .task-edit-card-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 21px 23px;
        border-bottom: 1px solid var(--border);
    }

    .task-edit-card-icon,
    .task-edit-info-icon {
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

    .task-edit-card-header h5 {
        margin: 0 0 5px;
        color: var(--text);
        font-size: 14px;
        font-weight: 800;
    }

    .task-edit-card-header p {
        margin: 0;
        color: var(--muted);
        font-size: 11px;
    }

    .task-edit-card-body {
        padding: 24px;
    }

    .task-edit-field {
        margin-bottom: 21px;
    }

    .task-edit-field label {
        display: block;
        margin-bottom: 9px;
        color: var(--text);
        font-size: 12px;
        font-weight: 700;
    }

    .task-edit-field label span {
        color: #e05261;
    }

    .task-edit-input,
    .task-edit-select,
    .task-edit-textarea {
        position: relative;
    }

    .task-edit-input > i,
    .task-edit-select > i,
    .task-edit-textarea > i {
        position: absolute;
        top: 14px;
        left: 14px;
        color: var(--muted);
        font-size: 15px;
        pointer-events: none;
    }

    .task-edit-input input,
    .task-edit-select select,
    .task-edit-textarea textarea {
        width: 100%;
        min-height: 45px;
        padding: 11px 14px 11px 41px;
        outline: none;
        border: 1px solid var(--border);
        border-radius: 9px;
        background: var(--surface);
        color: var(--text);
        font-family: inherit;
        font-size: 12px;
        transition: .2s;
    }

    .task-edit-select select {
        appearance: none;
        padding-right: 35px;
        cursor: pointer;
    }

    .task-edit-select::after {
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

    .task-edit-textarea textarea {
        min-height: 110px;
        resize: vertical;
        line-height: 1.6;
    }

    .task-edit-input input:focus,
    .task-edit-select select:focus,
    .task-edit-textarea textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(101, 88, 220, .10);
    }

    .task-edit-field small {
        display: block;
        margin-top: 7px;
        color: var(--muted);
        font-size: 10px;
    }

    .task-edit-field-error {
        margin-top: 6px;
        color: #e05261;
        font-size: 11px;
    }

    .task-edit-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 17px;
    }

    .task-edit-actions {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        padding-top: 20px;
        margin-top: 5px;
        border-top: 1px solid var(--border);
    }

    .task-edit-cancel,
    .task-edit-submit {
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

    .task-edit-cancel {
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--text);
    }

    .task-edit-cancel:hover {
        background: var(--surface-secondary);
        color: var(--text);
    }

    .task-edit-submit {
        border: 0;
        background: var(--primary);
        color: #fff;
    }

    .task-edit-submit:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 5px 13px rgba(101, 88, 220, .2);
    }

    .task-edit-error {
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

    .task-edit-error > i {
        font-size: 17px;
    }

    .task-edit-error strong {
        display: block;
        margin-bottom: 5px;
    }

    .task-edit-error ul {
        margin: 0;
        padding-left: 17px;
    }

    .task-edit-info {
        padding: 22px 19px;
    }

    .task-edit-info-icon {
        margin-bottom: 15px;
    }

    .task-edit-info h5 {
        margin: 0 0 8px;
        color: var(--text);
        font-size: 14px;
        font-weight: 800;
    }

    .task-edit-info > p {
        margin: 0 0 18px;
        color: var(--muted);
        font-size: 11px;
        line-height: 1.7;
    }

    .task-edit-preview {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 12px 0;
        border-top: 1px solid var(--border);
    }

    .task-edit-preview span {
        color: var(--muted);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .6px;
    }

    .task-edit-preview strong {
        color: var(--text);
        font-size: 12px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .task-edit-note {
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

    .task-edit-note i {
        color: #20a477;
        font-size: 14px;
    }

    @media (max-width: 991.98px) {
        .task-edit-layout {
            grid-template-columns: 1fr;
        }

        .task-edit-info {
            display: none;
        }
    }

    @media (max-width: 575.98px) {
        .task-edit-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .task-edit-header h2 {
            font-size: 23px;
        }

        .task-edit-back {
            width: 100%;
        }

        .task-edit-card-body {
            padding: 17px;
        }

        .task-edit-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .task-edit-actions {
            flex-direction: column-reverse;
        }

        .task-edit-cancel,
        .task-edit-submit {
            width: 100%;
        }
    }
</style>
@endpush
