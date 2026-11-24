
@extends('layouts.app')

@section('title', 'Nilai Akademik - StudentTrack')

@section('content')
<div class="grade-page">

    {{-- HEADER --}}
    <div class="grade-header">
        <div>
            <div class="grade-eyebrow">
                <i class="bi bi-mortarboard-fill"></i>
                AKADEMIK
            </div>
            <h2>Nilai Akademik</h2>
            <p>Pantau, evaluasi, dan kelola hasil belajar perkuliahanmu.</p>
        </div>

        <a href="{{ route('nilai.create') }}" class="grade-add-button">
            <i class="bi bi-plus-lg"></i>
            Tambah Nilai
        </a>
    </div>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="grade-alert success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="grade-alert danger">
            <i class="bi bi-exclamation-circle-fill"></i>
            <div>
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" onclick="this.parentElement.remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    {{-- RINGKASAN --}}
    <div class="grade-summary-grid">

        <div class="grade-summary-card">
            <div class="grade-summary-top">
                <div class="grade-summary-icon purple">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <span>PERFORMA AKADEMIK</span>
            </div>

            <div class="grade-summary-value">
                <h3>
                    {{ $rataRata !== null
                        ? number_format((float) $rataRata, 2, ',', '.')
                        : '-' }}
                </h3>
                <span class="grade-unit">/ 100</span>
            </div>

            <p>Rata-rata semua catatan nilai</p>
        </div>

        <div class="grade-summary-card">
            <div class="grade-summary-top">
                <div class="grade-summary-icon green">
                    <i class="bi bi-journal-check"></i>
                </div>
                <span>AKTIVITAS AKADEMIK</span>
            </div>

            <div class="grade-summary-value">
                <h3>{{ $totalMataKuliah }}</h3>
                <span class="grade-unit">Mata Kuliah</span>
            </div>

            <p>Mata kuliah yang memiliki catatan nilai</p>
        </div>

    </div>

    {{-- IMPORT EXPORT --}}
    <div class="grade-excel-card">

        <div class="grade-excel-header">
            <div class="grade-excel-heading">
                <div class="grade-excel-icon">
                    <i class="bi bi-file-earmark-spreadsheet"></i>
                </div>
                <div>
                    <h5>Kelola Data Excel</h5>
                    <p>Import data nilai atau unduh laporan dalam format Excel.</p>
                </div>
            </div>

            <a href="{{ route('nilai.export') }}" class="grade-export-button">
                <i class="bi bi-download"></i>
                Export Excel
            </a>
        </div>

        <div class="grade-excel-divider"></div>

        <form action="{{ route('nilai.import') }}"
              method="POST"
              enctype="multipart/form-data"
              class="grade-import-form">

            @csrf

            <div class="grade-file-field">
                <label for="gradeFile">
                    <i class="bi bi-upload"></i>
                    Import File Nilai
                </label>

                <input
                    type="file"
                    name="file"
                    id="gradeFile"
                    accept=".xlsx,.xls,.csv"
                    required
                >

                <small>
                    Format XLSX, XLS, atau CSV. Maksimal 5 MB.
                </small>
            </div>

            <button type="submit" class="grade-import-button">
                <i class="bi bi-cloud-arrow-up"></i>
                Import Data
            </button>
        </form>
    </div>

    {{-- DAFTAR NILAI --}}
    <div class="grade-table-card">

        <div class="grade-table-header">
            <div>
                <h5>Daftar Nilai</h5>
                <p>
                    Total {{ $nilai->total() }} catatan nilai akademik.
                </p>
            </div>

            <div class="grade-total-badge">
                <i class="bi bi-collection"></i>
                {{ $nilai->total() }} Data
            </div>
        </div>

        {{-- SORTING --}}
        <div class="grade-filter-area">
            <form method="GET"
                  action="{{ route('nilai.index') }}"
                  class="grade-sort-form">

                <div class="grade-sort-field">
                    <label for="gradeSort">Urutkan berdasarkan</label>
                    <select name="sort" id="gradeSort">
                        <option value="created_at"
                            {{ $sort === 'created_at' ? 'selected' : '' }}>
                            Tanggal Input
                        </option>

                        <option value="mata_kuliah"
                            {{ $sort === 'mata_kuliah' ? 'selected' : '' }}>
                            Mata Kuliah
                        </option>

                        <option value="jenis_penilaian"
                            {{ $sort === 'jenis_penilaian' ? 'selected' : '' }}>
                            Jenis Penilaian
                        </option>

                        <option value="nilai"
                            {{ $sort === 'nilai' ? 'selected' : '' }}>
                            Nilai
                        </option>
                    </select>
                </div>

                <div class="grade-sort-field">
                    <label for="gradeDirection">Urutan</label>
                    <select name="direction" id="gradeDirection">
                        <option value="desc"
                            {{ $direction === 'desc' ? 'selected' : '' }}>
                            Menurun
                        </option>

                        <option value="asc"
                            {{ $direction === 'asc' ? 'selected' : '' }}>
                            Menaik
                        </option>
                    </select>
                </div>

                <button type="submit" class="grade-sort-button">
                    <i class="bi bi-arrow-down-up"></i>
                    Terapkan
                </button>
            </form>
        </div>

        {{-- TABEL --}}
        <div class="table-responsive">
            <table class="grade-table">
                <thead>
                    <tr>
                        <th class="grade-no">NO</th>
                        <th>MATA KULIAH</th>
                        <th>JENIS PENILAIAN</th>
                        <th>NILAI</th>
                        <th>KETERANGAN</th>
                        <th>TANGGAL INPUT</th>
                        <th class="grade-action-heading">AKSI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($nilai as $item)
                        <tr>
                            <td class="grade-no">
                                {{ $nilai->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="grade-course">
                                    <div class="grade-course-icon">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <div>
                                        <strong>
                                            {{ $item->mataKuliah->nama ?? 'Mata kuliah tidak ditemukan' }}
                                        </strong>

                                        @if ($item->mataKuliah)
                                            <span>{{ $item->mataKuliah->kode }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="grade-assessment">
                                    {{ $item->jenis_penilaian }}
                                </span>
                            </td>

                            <td>
                                @php
                                    $score = (float) $item->nilai;
                                @endphp

                                <span class="grade-score
                                    {{ $score >= 80 ? 'high' : ($score >= 60 ? 'medium' : 'low') }}">
                                    {{ number_format($score, 2, ',', '.') }}
                                </span>
                            </td>

                            <td>
                                <span class="grade-description">
                                    {{ $item->keterangan ?: '-' }}
                                </span>
                            </td>

                            <td>
                                <div class="grade-date">
                                    <i class="bi bi-calendar3"></i>
                                    <span>
                                        {{ $item->created_at?->format('d/m/Y H:i') ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="grade-actions">
                                    <a href="{{ route('nilai.edit', $item) }}"
                                       class="grade-edit"
                                       title="Edit nilai">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('nilai.destroy', ['nilai' => $item->id]) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus nilai ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="grade-delete"
                                                title="Hapus nilai">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="grade-empty">
                                    <div class="grade-empty-icon">
                                        <i class="bi bi-clipboard2-data"></i>
                                    </div>
                                    <h5>Belum Ada Data Nilai</h5>
                                    <p>
                                        Tambahkan nilai pertamamu untuk mulai
                                        memantau hasil belajar.
                                    </p>
                                    <a href="{{ route('nilai.create') }}"
                                       class="grade-add-button">
                                        <i class="bi bi-plus-lg"></i>
                                        Tambah Nilai
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($nilai->hasPages())
            <div class="grade-pagination">
                <small>
                    Menampilkan
                    <strong>{{ $nilai->firstItem() }}–{{ $nilai->lastItem() }}</strong>
                    dari <strong>{{ $nilai->total() }}</strong> data
                </small>

                {{ $nilai->appends(request()->query())->links() }}
            </div>
        @endif

    </div>

</div>
@endsection

@push('styles')
<style>
    .grade-page {
        color: var(--text);
    }

    .grade-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
    }

    .grade-eyebrow {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 9px;
        color: var(--primary);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.3px;
    }

    .grade-header h2 {
        margin: 0 0 7px;
        color: var(--text);
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -1px;
    }

    .grade-header p {
        margin: 0;
        color: var(--muted);
        font-size: 12px;
    }

    .grade-add-button,
    .grade-export-button,
    .grade-import-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        border: 0;
        border-radius: 9px;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s;
        cursor: pointer;
        white-space: nowrap;
    }

    .grade-add-button {
        background: var(--primary);
        color: #fff;
    }

    .grade-add-button:hover {
        color: #fff;
        filter: brightness(1.08);
        transform: translateY(-2px);
    }

    .grade-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .grade-summary-card {
        padding: 21px;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: var(--surface);
        box-shadow: 0 3px 12px rgba(20, 20, 50, .025);
    }

    .grade-summary-top {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 18px;
    }

    .grade-summary-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        font-size: 19px;
    }

    .grade-summary-icon.purple {
        background: rgba(101, 88, 220, .12);
        color: var(--primary);
    }

    .grade-summary-icon.green {
        background: rgba(32, 164, 119, .12);
        color: #20a477;
    }

    .grade-summary-top > span {
        color: var(--muted);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .6px;
    }

    .grade-summary-value {
        display: flex;
        align-items: baseline;
        gap: 8px;
    }

    .grade-summary-value h3 {
        margin: 0;
        color: var(--text);
        font-size: 29px;
        font-weight: 800;
    }

    .grade-unit {
        color: var(--muted);
        font-size: 11px;
        font-weight: 600;
    }

    .grade-summary-card > p {
        margin: 7px 0 0;
        color: var(--muted);
        font-size: 11px;
    }

    .grade-excel-card,
    .grade-table-card {
        overflow: hidden;
        margin-bottom: 22px;
        border: 1px solid var(--border);
        border-radius: 15px;
        background: var(--surface);
        box-shadow: 0 4px 15px rgba(20, 20, 50, .025);
    }

    .grade-excel-card {
        padding: 21px 23px;
    }

    .grade-excel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .grade-excel-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .grade-excel-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 43px;
        height: 43px;
        border-radius: 12px;
        background: rgba(32, 164, 119, .12);
        color: #20a477;
        font-size: 20px;
    }

    .grade-excel-heading h5 {
        margin: 0 0 5px;
        color: var(--text);
        font-size: 14px;
        font-weight: 800;
    }

    .grade-excel-heading p {
        margin: 0;
        color: var(--muted);
        font-size: 11px;
    }

    .grade-export-button {
        border: 1px solid rgba(32, 164, 119, .25);
        background: rgba(32, 164, 119, .09);
        color: #20a477;
    }

    .grade-export-button:hover {
        background: #20a477;
        color: #fff;
    }

    .grade-excel-divider {
        height: 1px;
        margin: 19px 0;
        background: var(--border);
    }

    .grade-import-form {
        display: flex;
        align-items: flex-end;
        gap: 12px;
    }

    .grade-file-field {
        flex: 1;
        min-width: 0;
    }

    .grade-file-field label {
        display: block;
        margin-bottom: 9px;
        color: var(--text);
        font-size: 11px;
        font-weight: 700;
    }

    .grade-file-field label i {
        margin-right: 5px;
        color: var(--primary);
    }

    .grade-file-field input {
        width: 100%;
        min-height: 42px;
        padding: 7px 10px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--surface);
        color: var(--text);
        font-family: inherit;
        font-size: 11px;
    }

    .grade-file-field small {
        display: block;
        margin-top: 7px;
        color: var(--muted);
        font-size: 10px;
    }

    .grade-import-button {
        min-height: 42px;
        background: #20a477;
        color: #fff;
    }

    .grade-import-button:hover {
        background: #178b64;
        color: #fff;
    }

    .grade-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 21px 23px;
        border-bottom: 1px solid var(--border);
    }

    .grade-table-header h5 {
        margin: 0 0 5px;
        color: var(--text);
        font-size: 15px;
        font-weight: 800;
    }

    .grade-table-header p {
        margin: 0;
        color: var(--muted);
        font-size: 11px;
    }

    .grade-total-badge {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 8px 11px;
        border-radius: 8px;
        background: var(--surface-secondary);
        color: var(--text);
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .grade-filter-area {
        padding: 15px 23px;
        border-bottom: 1px solid var(--border);
        background: var(--surface);
    }

    .grade-sort-form {
        display: flex;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 10px;
    }

    .grade-sort-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .grade-sort-field label {
        color: var(--muted);
        font-size: 10px;
        font-weight: 700;
    }

    .grade-sort-field select {
        min-width: 155px;
        height: 37px;
        padding: 0 10px;
        border: 1px solid var(--border);
        border-radius: 8px;
        outline: none;
        background: var(--surface);
        color: var(--text);
        font-family: inherit;
        font-size: 11px;
    }

    .grade-sort-field select:focus {
        border-color: var(--primary);
    }

    .grade-sort-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        height: 37px;
        padding: 0 14px;
        border: 1px solid var(--primary);
        border-radius: 8px;
        background: rgba(101, 88, 220, .09);
        color: var(--primary);
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
    }

    .grade-sort-button:hover {
        background: var(--primary);
        color: #fff;
    }

    .grade-table {
        width: 100%;
        min-width: 970px;
        border-collapse: collapse;
    }

    .grade-table thead {
        background: var(--surface-secondary);
    }

    .grade-table th {
        padding: 14px 13px;
        border-bottom: 1px solid var(--border);
        color: var(--muted);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .grade-table td {
        padding: 14px 13px;
        border-bottom: 1px solid var(--border);
        color: var(--text);
        font-size: 11px;
        vertical-align: middle;
    }

    .grade-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .grade-table tbody tr:hover {
        background: var(--surface-secondary);
    }

    .grade-no {
        width: 45px;
        color: var(--muted) !important;
        text-align: center;
    }

    .grade-course {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 165px;
    }

    .grade-course-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 33px;
        height: 33px;
        border-radius: 9px;
        background: rgba(101, 88, 220, .10);
        color: var(--primary);
        font-size: 14px;
    }

    .grade-course > div:last-child {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .grade-course strong {
        color: var(--text);
        font-size: 11px;
        font-weight: 700;
    }

    .grade-course span {
        color: var(--muted);
        font-size: 10px;
    }

    .grade-assessment {
        display: inline-block;
        padding: 6px 9px;
        border-radius: 7px;
        background: var(--surface-secondary);
        color: var(--text);
        font-size: 10px;
        font-weight: 600;
    }

    .grade-score {
        display: inline-block;
        min-width: 60px;
        padding: 7px 9px;
        border-radius: 7px;
        text-align: center;
        font-size: 11px;
        font-weight: 800;
    }

    .grade-score.high {
        background: rgba(32, 164, 119, .12);
        color: #20a477;
    }

    .grade-score.medium {
        background: rgba(233, 154, 50, .13);
        color: #d28a20;
    }

    .grade-score.low {
        background: rgba(224, 82, 97, .11);
        color: #e05261;
    }

    .grade-description {
        display: block;
        max-width: 180px;
        overflow: hidden;
        color: var(--muted);
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .grade-date {
        display: flex;
        align-items: center;
        gap: 7px;
        color: var(--muted);
        font-size: 10px;
        white-space: nowrap;
    }

    .grade-date i {
        font-size: 13px;
    }

    .grade-action-heading {
        text-align: center;
    }

    .grade-actions {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
    }

    .grade-actions form {
        margin: 0;
    }

    .grade-edit,
    .grade-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 31px;
        height: 31px;
        border-radius: 8px;
        font-size: 13px;
        text-decoration: none;
        cursor: pointer;
        transition: .2s;
    }

    .grade-edit {
        border: 1px solid rgba(101, 88, 220, .17);
        background: rgba(101, 88, 220, .10);
        color: var(--primary);
    }

    .grade-edit:hover {
        background: var(--primary);
        color: #fff;
    }

    .grade-delete {
        border: 1px solid rgba(224, 82, 97, .15);
        background: rgba(224, 82, 97, .08);
        color: #e05261;
    }

    .grade-delete:hover {
        background: #e05261;
        color: #fff;
    }

    .grade-empty {
        padding: 55px 20px;
        text-align: center;
    }

    .grade-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 65px;
        height: 65px;
        margin: 0 auto 16px;
        border-radius: 20px;
        background: var(--surface-secondary);
        color: var(--primary);
        font-size: 27px;
    }

    .grade-empty h5 {
        margin-bottom: 7px;
        color: var(--text);
        font-size: 15px;
        font-weight: 800;
    }

    .grade-empty p {
        max-width: 350px;
        margin: 0 auto 18px;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.7;
    }

    .grade-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 15px 22px;
        border-top: 1px solid var(--border);
    }

    .grade-pagination > small {
        color: var(--muted);
        font-size: 10px;
    }

    .grade-pagination strong {
        color: var(--text);
    }

    .grade-pagination nav p {
        margin: 0;
    }

    .grade-pagination nav > div:first-child {
        display: none;
    }

    .grade-pagination nav > div:last-child {
        margin: 0;
    }

    .grade-pagination .pagination {
        margin: 0;
    }

    .grade-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px 15px;
        margin-bottom: 18px;
        border-radius: 10px;
        font-size: 11px;
    }

    .grade-alert.success {
        border: 1px solid rgba(32, 164, 119, .2);
        background: rgba(32, 164, 119, .09);
        color: #20a477;
    }

    .grade-alert.danger {
        border: 1px solid rgba(224, 82, 97, .2);
        background: rgba(224, 82, 97, .08);
        color: #e05261;
    }

    .grade-alert ul {
        margin: 5px 0 0;
        padding-left: 17px;
    }

    .grade-alert button {
        margin-left: auto;
        border: 0;
        background: transparent;
        color: inherit;
        cursor: pointer;
    }

    @media (max-width: 767.98px) {
        .grade-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .grade-header h2 {
            font-size: 24px;
        }

        .grade-add-button {
            width: 100%;
        }

        .grade-summary-grid {
            grid-template-columns: 1fr;
        }

        .grade-excel-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .grade-export-button {
            width: 100%;
        }

        .grade-import-form {
            align-items: stretch;
            flex-direction: column;
        }

        .grade-import-button {
            width: 100%;
        }

        .grade-table-header {
            align-items: flex-start;
        }

        .grade-pagination {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
@endpush
