
@extends('layouts.app')

@section('title', 'Daftar Tugas - StudentTrack')

@section('content')
<div class="task-page">

    {{-- HEADER --}}
    <div class="task-header">
        <div>
            <div class="task-eyebrow">
                <i class="bi bi-list-check"></i>
                MANAJEMEN AKADEMIK
            </div>

            <h2>Daftar Tugas</h2>
            <p>Kelola tugas, pantau deadline, dan atur prioritas perkuliahanmu.</p>
        </div>

        <a href="{{ route('tugas.create') }}" class="task-add-button">
            <i class="bi bi-plus-lg"></i>
            Tambah Tugas
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
        <div class="task-alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    {{-- SUMMARY --}}
    <div class="task-summary-grid">

        <div class="task-summary-card">
            <div class="task-summary-icon purple">
                <i class="bi bi-journal-text"></i>
            </div>
            <div>
                <span>Total Tugas</span>
                <h3>{{ $tugas->count() }}</h3>
                <small>Tugas terdaftar</small>
            </div>
        </div>

        <div class="task-summary-card">
            <div class="task-summary-icon orange">
                <i class="bi bi-flag"></i>
            </div>
            <div>
                <span>Prioritas Tinggi</span>
                <h3>{{ $tugas->where('prioritas', 'Tinggi')->count() }}</h3>
                <small>Perlu diperhatikan</small>
            </div>
        </div>

        <div class="task-summary-card">
            <div class="task-summary-icon green">
                <i class="bi bi-check2-circle"></i>
            </div>
            <div>
                <span>Tugas Selesai</span>
                <h3>{{ $tugas->where('status', 'Selesai')->count() }}</h3>
                <small>Telah diselesaikan</small>
            </div>
        </div>

    </div>

    {{-- TASK TABLE --}}
    <div class="task-card">

        <div class="task-card-header">
            <div>
                <h5>Semua Tugas</h5>
                <p>Daftar tugas perkuliahan yang perlu kamu kelola.</p>
            </div>

            <div class="task-count">
                <i class="bi bi-collection"></i>
                {{ $tugas->count() }} Data
            </div>
        </div>

        <div class="table-responsive">
            <table class="task-table">
                <thead>
                    <tr>
                        <th class="task-no">NO</th>
                        <th>INFORMASI TUGAS</th>
                        <th>MATA KULIAH</th>
                        <th>DEADLINE</th>
                        <th>PRIORITAS</th>
                        <th>STATUS</th>
                        <th class="task-action-heading">AKSI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tugas as $item)
                        <tr>
                            <td class="task-no">
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                <div class="task-title">
                                    <div class="task-title-icon">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $item->judul }}</strong>
                                        <span>
                                            {{ $item->deskripsi ?: 'Tidak ada deskripsi' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="task-course">
                                    <i class="bi bi-book"></i>
                                    <span>{{ $item->mataKuliah->nama ?? '-' }}</span>
                                </div>
                            </td>

                            <td>
                                <div class="task-deadline">
                                    <i class="bi bi-calendar-event"></i>
                                    <span>{{ $item->deadline->format('d/m/Y') }}</span>
                                </div>
                            </td>

                            <td>
                                @if ($item->prioritas == 'Tinggi')
                                    <span class="task-priority high">
                                        <i class="bi bi-arrow-up-circle-fill"></i>
                                        Tinggi
                                    </span>
                                @elseif ($item->prioritas == 'Sedang')
                                    <span class="task-priority medium">
                                        <i class="bi bi-dash-circle-fill"></i>
                                        Sedang
                                    </span>
                                @else
                                    <span class="task-priority low">
                                        <i class="bi bi-arrow-down-circle-fill"></i>
                                        Rendah
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if ($item->status == 'Selesai')
                                    <span class="task-status completed">
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ $item->status }}
                                    </span>
                                @elseif ($item->status == 'Belum dikerjakan')
                                    <span class="task-status pending">
                                        <i class="bi bi-clock-fill"></i>
                                        {{ $item->status }}
                                    </span>
                                @else
                                    <span class="task-status other">
                                        <i class="bi bi-arrow-repeat"></i>
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="task-actions">
                                    <a href="{{ route('tugas.edit', $item) }}"
                                       class="task-edit"
                                       title="Edit tugas">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('tugas.destroy', $item) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus tugas {{ $item->judul }}?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="task-delete"
                                                title="Hapus tugas">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="task-empty">
                                    <div class="task-empty-icon">
                                        <i class="bi bi-clipboard2-x"></i>
                                    </div>
                                    <h5>Belum Ada Tugas</h5>
                                    <p>
                                        Kamu belum menambahkan tugas perkuliahan.
                                        Tambahkan tugas pertamamu untuk mulai mengelola deadline.
                                    </p>
                                    <a href="{{ route('tugas.create') }}" class="task-add-button">
                                        <i class="bi bi-plus-lg"></i>
                                        Tambah Tugas Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="task-card-footer">
            <span>
                Menampilkan <strong>{{ $tugas->count() }}</strong> tugas
            </span>
            <span class="task-footer-status">
                <i class="bi bi-check-circle-fill"></i>
                Data tersimpan
            </span>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .task-page {
        color: var(--text);
    }

    .task-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
    }

    .task-eyebrow {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 9px;
        color: var(--primary);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.3px;
    }

    .task-header h2 {
        margin: 0 0 7px;
        color: var(--text);
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -1px;
    }

    .task-header p {
        margin: 0;
        color: var(--muted);
        font-size: 12px;
    }

    .task-add-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 12px 17px;
        border: 0;
        border-radius: 10px;
        background: var(--primary);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s;
        white-space: nowrap;
    }

    .task-add-button:hover {
        color: #fff;
        filter: brightness(1.08);
        transform: translateY(-2px);
        box-shadow: 0 7px 18px rgba(101, 88, 220, .2);
    }

    .task-summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 22px;
    }

    .task-summary-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 19px;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: var(--surface);
        box-shadow: 0 3px 12px rgba(20, 20, 50, .025);
    }

    .task-summary-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 46px;
        height: 46px;
        border-radius: 13px;
        font-size: 20px;
    }

    .task-summary-icon.purple {
        color: var(--primary);
        background: rgba(101, 88, 220, .12);
    }

    .task-summary-icon.orange {
        color: #e99a32;
        background: rgba(233, 154, 50, .13);
    }

    .task-summary-icon.green {
        color: #20a477;
        background: rgba(32, 164, 119, .12);
    }

    .task-summary-card span {
        display: block;
        margin-bottom: 3px;
        color: var(--muted);
        font-size: 11px;
        font-weight: 600;
    }

    .task-summary-card h3 {
        margin: 0;
        color: var(--text);
        font-size: 23px;
        font-weight: 800;
    }

    .task-summary-card small {
        color: var(--muted);
        font-size: 10px;
    }

    .task-card {
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 15px;
        background: var(--surface);
        box-shadow: 0 4px 15px rgba(20, 20, 50, .025);
    }

    .task-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 21px 23px;
        border-bottom: 1px solid var(--border);
    }

    .task-card-header h5 {
        margin: 0 0 5px;
        color: var(--text);
        font-size: 15px;
        font-weight: 800;
    }

    .task-card-header p {
        margin: 0;
        color: var(--muted);
        font-size: 11px;
    }

    .task-count {
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

    .task-table {
        width: 100%;
        min-width: 950px;
        border-collapse: collapse;
    }

    .task-table thead {
        background: var(--surface-secondary);
    }

    .task-table th {
        padding: 15px 14px;
        border-bottom: 1px solid var(--border);
        color: var(--muted);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .task-table td {
        padding: 15px 14px;
        border-bottom: 1px solid var(--border);
        color: var(--text);
        font-size: 11px;
        vertical-align: middle;
    }

    .task-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .task-table tbody tr {
        transition: background .15s;
    }

    .task-table tbody tr:hover {
        background: var(--surface-secondary);
    }

    .task-no {
        width: 45px;
        color: var(--muted) !important;
        text-align: center;
    }

    .task-title {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 180px;
    }

    .task-title-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: rgba(101, 88, 220, .10);
        color: var(--primary);
        font-size: 15px;
    }

    .task-title > div:last-child {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }

    .task-title strong {
        color: var(--text);
        font-size: 11px;
        font-weight: 700;
    }

    .task-title span {
        max-width: 200px;
        overflow: hidden;
        color: var(--muted);
        font-size: 10px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .task-course,
    .task-deadline {
        display: flex;
        align-items: center;
        gap: 7px;
        min-width: 110px;
    }

    .task-course i,
    .task-deadline i {
        color: var(--muted);
        font-size: 13px;
    }

    .task-course span,
    .task-deadline span {
        color: var(--text);
        font-weight: 600;
        white-space: nowrap;
    }

    .task-priority,
    .task-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 6px 9px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .task-priority.high {
        color: #e05261;
        background: rgba(224, 82, 97, .11);
    }

    .task-priority.medium {
        color: #d28a20;
        background: rgba(233, 154, 50, .13);
    }

    .task-priority.low {
        color: #20a477;
        background: rgba(32, 164, 119, .11);
    }

    .task-status.completed {
        color: #20a477;
        background: rgba(32, 164, 119, .11);
    }

    .task-status.pending {
        color: #d28a20;
        background: rgba(233, 154, 50, .13);
    }

    .task-status.other {
        color: var(--primary);
        background: rgba(101, 88, 220, .11);
    }

    .task-action-heading {
        text-align: center;
    }

    .task-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
    }

    .task-actions form {
        margin: 0;
    }

    .task-edit,
    .task-delete {
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

    .task-edit {
        border: 1px solid rgba(101, 88, 220, .17);
        background: rgba(101, 88, 220, .10);
        color: var(--primary);
    }

    .task-edit:hover {
        background: var(--primary);
        color: #fff;
    }

    .task-delete {
        border: 1px solid rgba(224, 82, 97, .15);
        background: rgba(224, 82, 97, .08);
        color: #e05261;
    }

    .task-delete:hover {
        background: #e05261;
        color: #fff;
    }

    .task-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding: 14px 22px;
        border-top: 1px solid var(--border);
        color: var(--muted);
        font-size: 11px;
    }

    .task-card-footer strong {
        color: var(--text);
    }

    .task-footer-status {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #20a477;
        font-weight: 700;
    }

    .task-alert {
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

    .task-alert button {
        margin-left: auto;
        border: 0;
        background: transparent;
        color: inherit;
        cursor: pointer;
    }

    .task-empty {
        padding: 55px 20px;
        text-align: center;
    }

    .task-empty-icon {
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

    .task-empty h5 {
        margin-bottom: 7px;
        color: var(--text);
        font-size: 15px;
        font-weight: 800;
    }

    .task-empty p {
        max-width: 370px;
        margin: 0 auto 18px;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.7;
    }

    @media (max-width: 767.98px) {
        .task-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .task-header h2 {
            font-size: 24px;
        }

        .task-add-button {
            width: 100%;
        }

        .task-summary-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .task-card-header {
            align-items: flex-start;
        }

        .task-card-footer {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
@endpush
