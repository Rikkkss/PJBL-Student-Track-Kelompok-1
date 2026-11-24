
@extends('layouts.app')

@section('title', 'Mata Kuliah - StudentTrack')

@section('content')
<div class="mk-page">

    {{-- HEADER --}}
    <div class="mk-header">
        <div>
            <div class="mk-eyebrow">
                <i class="bi bi-journal-bookmark-fill"></i>
                AKADEMIK
            </div>
            <h2 class="mk-title">Mata Kuliah</h2>
            <p class="mk-subtitle">
                Kelola dan pantau seluruh mata kuliah yang sedang kamu pelajari.
            </p>
        </div>

        <a href="{{ route('mata-kuliah.create') }}" class="mk-add-button">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Mata Kuliah</span>
        </a>
    </div>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="mk-alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="mk-alert-close"
                    onclick="this.parentElement.remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    {{-- STATISTIK --}}
    <div class="mk-summary">
        <div class="mk-summary-icon">
            <i class="bi bi-journal-text"></i>
        </div>
        <div>
            <p>Total Mata Kuliah</p>
            <h3>{{ $mataKuliah->count() }}</h3>
            <span>Terdaftar dalam sistem</span>
        </div>
    </div>

    {{-- DAFTAR MATA KULIAH --}}
    <div class="mk-card">

        <div class="mk-card-header">
            <div>
                <h5>Daftar Mata Kuliah</h5>
                <p>Informasi mata kuliah yang telah ditambahkan.</p>
            </div>

            <div class="mk-total-badge">
                <i class="bi bi-collection"></i>
                {{ $mataKuliah->count() }} Data
            </div>
        </div>

        <div class="table-responsive">
            <table class="mk-table">
                <thead>
                    <tr>
                        <th class="mk-number">NO</th>
                        <th>KODE MATA KULIAH</th>
                        <th>NAMA MATA KULIAH</th>
                        <th>SKS</th>
                        <th>SEMESTER</th>
                        <th class="mk-action-heading">AKSI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($mataKuliah as $index => $mk)
                        <tr>
                            <td class="mk-number">
                                {{ $index + 1 }}
                            </td>

                            <td>
                                <span class="mk-code">
                                    {{ $mk->kode }}
                                </span>
                            </td>

                            <td>
                                <div class="mk-course-name">
                                    <div class="mk-course-icon">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <span>{{ $mk->nama }}</span>
                                </div>
                            </td>

                            <td>
                                <span class="mk-sks">
                                    {{ $mk->sks }} SKS
                                </span>
                            </td>

                            <td>
                                <span class="mk-semester">
                                    Semester {{ $mk->semester }}
                                </span>
                            </td>

                            <td>
                                <div class="mk-actions">
                                    <a href="{{ route('mata-kuliah.edit', $mk) }}"
                                       class="mk-edit"
                                       title="Edit mata kuliah">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>

                                    <form action="{{ route('mata-kuliah.destroy', $mk) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus mata kuliah {{ $mk->nama }}?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="mk-delete"
                                                title="Hapus mata kuliah">
                                            <i class="bi bi-trash3"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="mk-empty">
                                    <div class="mk-empty-icon">
                                        <i class="bi bi-journal-x"></i>
                                    </div>
                                    <h5>Belum Ada Mata Kuliah</h5>
                                    <p>
                                        Kamu belum menambahkan mata kuliah.
                                        Mulai dengan menambahkan data pertama.
                                    </p>
                                    <a href="{{ route('mata-kuliah.create') }}"
                                       class="mk-add-button">
                                        <i class="bi bi-plus-lg"></i>
                                        Tambah Mata Kuliah
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mk-card-footer">
            <span>
                Menampilkan <strong>{{ $mataKuliah->count() }}</strong>
                mata kuliah
            </span>
            <span class="mk-footer-status">
                <i class="bi bi-check-circle-fill"></i>
                Data tersimpan
            </span>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .mk-page {
        color: var(--text);
    }

    .mk-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 26px;
    }

    .mk-eyebrow {
        display: flex;
        align-items: center;
        gap: 7px;
        color: var(--primary);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.3px;
        margin-bottom: 9px;
    }

    .mk-title {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -1px;
        margin: 0 0 7px;
        color: var(--text);
    }

    .mk-subtitle {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .mk-add-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 12px 17px;
        border: 0;
        border-radius: 11px;
        background: var(--primary);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s ease;
        white-space: nowrap;
    }

    .mk-add-button:hover {
        color: #fff;
        filter: brightness(1.08);
        transform: translateY(-2px);
        box-shadow: 0 7px 18px rgba(101, 88, 220, .2);
    }

    .mk-summary {
        display: flex;
        align-items: center;
        gap: 17px;
        padding: 20px 23px;
        margin-bottom: 23px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 15px;
        box-shadow: 0 3px 12px rgba(20, 20, 50, .025);
    }

    .mk-summary-icon {
        width: 51px;
        height: 51px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: rgba(101, 88, 220, .12);
        color: var(--primary);
        font-size: 22px;
    }

    .mk-summary p {
        margin: 0 0 2px;
        color: var(--muted);
        font-size: 12px;
        font-weight: 600;
    }

    .mk-summary h3 {
        color: var(--text);
        font-size: 25px;
        font-weight: 800;
        margin: 0;
    }

    .mk-summary span {
        color: var(--muted);
        font-size: 11px;
    }

    .mk-card {
        overflow: hidden;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(20, 20, 50, .025);
    }

    .mk-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 21px 23px;
        border-bottom: 1px solid var(--border);
    }

    .mk-card-header h5 {
        color: var(--text);
        font-size: 15px;
        font-weight: 800;
        margin: 0 0 5px;
    }

    .mk-card-header p {
        color: var(--muted);
        font-size: 11px;
        margin: 0;
    }

    .mk-total-badge {
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

    .mk-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 720px;
    }

    .mk-table thead {
        background: var(--surface-secondary);
    }

    .mk-table th {
        padding: 15px 17px;
        color: var(--muted);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .6px;
        white-space: nowrap;
        border-bottom: 1px solid var(--border);
    }

    .mk-table td {
        padding: 15px 17px;
        color: var(--text);
        font-size: 12px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .mk-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .mk-table tbody tr {
        transition: background .15s ease;
    }

    .mk-table tbody tr:hover {
        background: var(--surface-secondary);
    }

    .mk-number {
        width: 55px;
        color: var(--muted) !important;
        text-align: center;
    }

    .mk-code {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 7px;
        background: rgba(101, 88, 220, .11);
        color: var(--primary);
        font-size: 11px;
        font-weight: 800;
    }

    .mk-course-name {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 190px;
        font-weight: 700;
    }

    .mk-course-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: var(--surface-secondary);
        color: var(--primary);
        font-size: 14px;
    }

    .mk-sks {
        color: var(--text);
        font-weight: 700;
        white-space: nowrap;
    }

    .mk-semester {
        display: inline-block;
        padding: 6px 9px;
        border-radius: 7px;
        background: var(--surface-secondary);
        color: var(--muted);
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .mk-action-heading {
        text-align: center;
    }

    .mk-actions {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
    }

    .mk-actions form {
        margin: 0;
    }

    .mk-edit, .mk-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 7px 10px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s ease;
        cursor: pointer;
        white-space: nowrap;
    }

    .mk-edit {
        color: var(--primary);
        background: rgba(101, 88, 220, .10);
        border: 1px solid rgba(101, 88, 220, .16);
    }

    .mk-edit:hover {
        color: #fff;
        background: var(--primary);
    }

    .mk-delete {
        color: #e05261;
        background: rgba(224, 82, 97, .08);
        border: 1px solid rgba(224, 82, 97, .15);
    }

    .mk-delete:hover {
        color: #fff;
        background: #e05261;
    }

    .mk-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 16px;
        margin-bottom: 20px;
        border: 1px solid rgba(25, 160, 105, .2);
        border-radius: 10px;
        background: rgba(25, 160, 105, .09);
        color: #15966a;
        font-size: 12px;
        font-weight: 600;
    }

    .mk-alert-close {
        margin-left: auto;
        border: 0;
        background: transparent;
        color: inherit;
        cursor: pointer;
    }

    .mk-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding: 14px 22px;
        border-top: 1px solid var(--border);
        color: var(--muted);
        font-size: 11px;
    }

    .mk-card-footer strong {
        color: var(--text);
    }

    .mk-footer-status {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #20a477;
        font-weight: 700;
    }

    .mk-empty {
        padding: 55px 20px;
        text-align: center;
    }

    .mk-empty-icon {
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

    .mk-empty h5 {
        color: var(--text);
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 7px;
    }

    .mk-empty p {
        max-width: 350px;
        margin: 0 auto 18px;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.7;
    }

    @media (max-width: 767.98px) {
        .mk-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .mk-title {
            font-size: 24px;
        }

        .mk-add-button {
            width: 100%;
        }

        .mk-card-header {
            align-items: flex-start;
        }

        .mk-card-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .mk-summary {
            padding: 17px;
        }
    }
</style>
@endpush
