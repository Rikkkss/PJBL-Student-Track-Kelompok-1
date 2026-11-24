
@extends('layouts.app')

@section('title', 'Kalender Libur - StudentTrack')

@section('content')

<div class="holiday-page">

    {{-- HEADER --}}
    <div class="holiday-header">
        <div>
            <div class="holiday-eyebrow">
                <i class="bi bi-calendar2-week-fill"></i>
                KALENDER AKADEMIK
            </div>
            <h2>Kalender Libur</h2>
            <p>Temukan jadwal hari libur nasional Indonesia dan rencanakan aktivitas perkuliahanmu.</p>
        </div>

        <div class="holiday-header-icon">
            <i class="bi bi-calendar-heart"></i>
        </div>
    </div>

    {{-- FILTER TAHUN --}}
    <div class="holiday-filter-card">
        <div class="holiday-filter-heading">
            <div class="holiday-filter-icon">
                <i class="bi bi-funnel"></i>
            </div>
            <div>
                <h5>Filter Kalender</h5>
                <p>Pilih tahun untuk melihat daftar hari libur nasional.</p>
            </div>
        </div>

        <form action="{{ route('kalender-libur.index') }}"
              method="GET"
              class="holiday-filter-form">

            <div class="holiday-year-field">
                <label for="tahun">Tahun Kalender</label>
                <select name="tahun" id="tahun" class="form-select">
                    @for ($i = 2026; $i <= 2031; $i++)
                        <option value="{{ $i }}"
                            {{ (int) $tahun === $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="holiday-filter-button">
                <i class="bi bi-search"></i>
                Tampilkan Kalender
            </button>
        </form>
    </div>

    {{-- ERROR API --}}
    @if ($error)
        <div class="holiday-alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Data belum dapat dimuat</strong>
                <p>{{ $error }}</p>
            </div>
        </div>
    @endif

    {{-- HARI LIBUR BERIKUTNYA --}}
    @if ($liburMendatang)
        <div class="holiday-next-card">
            <div class="holiday-next-content">
                <div class="holiday-next-label">
                    <span class="holiday-live-dot"></span>
                    HARI LIBUR BERIKUTNYA
                </div>

                <h3>{{ $liburMendatang['name'] }}</h3>

                <div class="holiday-next-date">
                    <i class="bi bi-calendar-event"></i>
                    {{ \Carbon\Carbon::parse($liburMendatang['date'])
                        ->locale('id')->translatedFormat('l, d F Y') }}
                </div>

                <span class="holiday-next-caption">
                    Jadwalkan aktivitasmu sebelum hari libur tiba.
                </span>
            </div>

            <div class="holiday-next-illustration">
                <i class="bi bi-calendar2-heart-fill"></i>
            </div>
        </div>
    @endif

    {{-- RINGKASAN --}}
    <div class="holiday-stat-grid">

        <div class="holiday-stat-card">
            <div class="holiday-stat-icon purple">
                <i class="bi bi-calendar3"></i>
            </div>
            <div>
                <span>TAHUN KALENDER</span>
                <h3>{{ $tahun }}</h3>
                <p>Periode yang dipilih</p>
            </div>
        </div>

        <div class="holiday-stat-card">
            <div class="holiday-stat-icon red">
                <i class="bi bi-calendar2-x"></i>
            </div>
            <div>
                <span>TOTAL HARI LIBUR</span>
                <h3>{{ $libur->count() }}</h3>
                <p>Hari libur nasional</p>
            </div>
        </div>

    </div>

    {{-- DAFTAR HARI LIBUR --}}
    <div class="holiday-table-card">

        <div class="holiday-table-header">
            <div>
                <h5>Daftar Hari Libur Nasional</h5>
                <p>Informasi tanggal merah Indonesia tahun {{ $tahun }}.</p>
            </div>

            <span class="holiday-count-badge">
                <i class="bi bi-calendar-check"></i>
                {{ $libur->count() }} Hari
            </span>
        </div>

        <div class="table-responsive">
            <table class="holiday-table">
                <thead>
                    <tr>
                        <th class="holiday-number">NO</th>
                        <th>TANGGAL</th>
                        <th>NAMA HARI LIBUR</th>
                        <th>KATEGORI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($libur as $item)
                        <tr>
                            <td class="holiday-number">
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                <div class="holiday-date-cell">
                                    <div class="holiday-date-icon">
                                        <i class="bi bi-calendar-event"></i>
                                    </div>
                                    <span>
                                        {{ \Carbon\Carbon::parse($item['date'])
                                            ->locale('id')
                                            ->translatedFormat('d F Y') }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <strong class="holiday-name">
                                    {{ $item['name'] }}
                                </strong>
                            </td>

                            <td>
                                <span class="holiday-category">
                                    <span></span>
                                    Libur Nasional
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="holiday-empty">
                                    <div class="holiday-empty-icon">
                                        <i class="bi bi-calendar2-minus"></i>
                                    </div>
                                    <h5>Belum Ada Data Hari Libur</h5>
                                    <p>
                                        Data hari libur untuk tahun {{ $tahun }}
                                        belum tersedia atau belum berhasil dimuat.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="holiday-table-footer">
            <i class="bi bi-info-circle"></i>
            Menampilkan {{ $libur->count() }} hari libur nasional pada tahun {{ $tahun }}.
        </div>
    </div>

    {{-- SUMBER DATA --}}
    <div class="holiday-source">
        <div class="holiday-source-icon">
            <i class="bi bi-database-check"></i>
        </div>
        <div>
            <strong>Sumber Informasi</strong>
            <p>
                Data diperoleh dari Nager.Date API.
                Data diperbarui melalui cache setiap 12 jam.
            </p>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    .holiday-page {
        color: var(--text);
    }

    .holiday-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 25px;
    }

    .holiday-eyebrow {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 9px;
        color: var(--primary);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.2px;
    }

    .holiday-header h2 {
        margin: 0 0 7px;
        color: var(--text);
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -1px;
    }

    .holiday-header p {
        max-width: 570px;
        margin: 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.7;
    }

    .holiday-header-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 62px;
        height: 62px;
        border-radius: 18px;
        background: rgba(101, 88, 220, .11);
        color: var(--primary);
        font-size: 28px;
    }

    .holiday-filter-card,
    .holiday-table-card {
        overflow: hidden;
        margin-bottom: 20px;
        border: 1px solid var(--border);
        border-radius: 15px;
        background: var(--surface);
        box-shadow: 0 4px 15px rgba(20, 20, 50, .025);
    }

    .holiday-filter-card {
        padding: 21px 23px;
    }

    .holiday-filter-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .holiday-filter-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(101, 88, 220, .11);
        color: var(--primary);
        font-size: 18px;
    }

    .holiday-filter-heading h5 {
        margin: 0 0 5px;
        color: var(--text);
        font-size: 14px;
        font-weight: 800;
    }

    .holiday-filter-heading p {
        margin: 0;
        color: var(--muted);
        font-size: 11px;
    }

    .holiday-filter-form {
        display: flex;
        align-items: flex-end;
        gap: 12px;
    }

    .holiday-year-field {
        width: 230px;
    }

    .holiday-year-field label {
        display: block;
        margin-bottom: 7px;
        color: var(--text);
        font-size: 11px;
        font-weight: 700;
    }

    .holiday-year-field select {
        height: 41px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background-color: var(--surface);
        color: var(--text);
        font-family: inherit;
        font-size: 11px;
    }

    .holiday-filter-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 41px;
        padding: 0 17px;
        border: 0;
        border-radius: 8px;
        background: var(--primary);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s;
    }

    .holiday-filter-button:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
    }

    .holiday-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid rgba(224, 82, 97, .2);
        border-radius: 11px;
        background: rgba(224, 82, 97, .08);
        color: #e05261;
        font-size: 12px;
    }

    .holiday-alert > i {
        font-size: 18px;
    }

    .holiday-alert strong {
        display: block;
        margin-bottom: 4px;
    }

    .holiday-alert p {
        margin: 0;
        line-height: 1.6;
    }

    .holiday-next-card {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        overflow: hidden;
        min-height: 185px;
        padding: 27px 30px;
        margin-bottom: 20px;
        border-radius: 15px;
        background: linear-gradient(115deg, #5144c8 0%, #7767e8 65%, #9586f4 100%);
        color: #fff;
    }

    .holiday-next-content {
        position: relative;
        z-index: 1;
    }

    .holiday-next-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        color: rgba(255, 255, 255, .82);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .holiday-live-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #7ff0ba;
        box-shadow: 0 0 0 4px rgba(127, 240, 186, .15);
    }

    .holiday-next-card h3 {
        margin: 0 0 11px;
        color: #fff;
        font-size: 23px;
        font-weight: 800;
    }

    .holiday-next-date {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, .95);
        font-size: 12px;
        font-weight: 600;
    }

    .holiday-next-caption {
        display: block;
        margin-top: 12px;
        color: rgba(255, 255, 255, .72);
        font-size: 10px;
    }

    .holiday-next-illustration {
        position: absolute;
        right: 45px;
        top: 50%;
        color: rgba(255, 255, 255, .14);
        font-size: 115px;
        transform: translateY(-50%) rotate(-10deg);
    }

    .holiday-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .holiday-stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 19px;
        border: 1px solid var(--border);
        border-radius: 13px;
        background: var(--surface);
    }

    .holiday-stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 45px;
        height: 45px;
        border-radius: 12px;
        font-size: 20px;
    }

    .holiday-stat-icon.purple {
        background: rgba(101, 88, 220, .11);
        color: var(--primary);
    }

    .holiday-stat-icon.red {
        background: rgba(224, 82, 97, .11);
        color: #e05261;
    }

    .holiday-stat-card span {
        color: var(--muted);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .6px;
    }

    .holiday-stat-card h3 {
        margin: 4px 0;
        color: var(--text);
        font-size: 23px;
        font-weight: 800;
    }

    .holiday-stat-card p {
        margin: 0;
        color: var(--muted);
        font-size: 10px;
    }

    .holiday-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 21px 23px;
        border-bottom: 1px solid var(--border);
    }

    .holiday-table-header h5 {
        margin: 0 0 5px;
        color: var(--text);
        font-size: 15px;
        font-weight: 800;
    }

    .holiday-table-header p {
        margin: 0;
        color: var(--muted);
        font-size: 11px;
    }

    .holiday-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 11px;
        border-radius: 8px;
        background: rgba(101, 88, 220, .10);
        color: var(--primary);
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .holiday-table {
        width: 100%;
        border-collapse: collapse;
    }

    .holiday-table thead {
        background: var(--surface-secondary);
    }

    .holiday-table th {
        padding: 14px 18px;
        border-bottom: 1px solid var(--border);
        color: var(--muted);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .6px;
        white-space: nowrap;
    }

    .holiday-table td {
        padding: 15px 18px;
        border-bottom: 1px solid var(--border);
        color: var(--text);
        font-size: 11px;
        vertical-align: middle;
    }

    .holiday-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .holiday-table tbody tr:hover {
        background: var(--surface-secondary);
    }

    .holiday-number {
        width: 55px;
        color: var(--muted) !important;
        text-align: center;
    }

    .holiday-date-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #e05261;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .holiday-date-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 31px;
        height: 31px;
        border-radius: 8px;
        background: rgba(224, 82, 97, .10);
        font-size: 13px;
    }

    .holiday-name {
        color: var(--text);
        font-size: 11px;
        font-weight: 700;
    }

    .holiday-category {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 10px;
        border-radius: 7px;
        background: rgba(224, 82, 97, .09);
        color: #e05261;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .holiday-category span {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #e05261;
    }

    .holiday-empty {
        padding: 48px 20px;
        text-align: center;
    }

    .holiday-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        margin: 0 auto 14px;
        border-radius: 18px;
        background: var(--surface-secondary);
        color: var(--muted);
        font-size: 25px;
    }

    .holiday-empty h5 {
        margin-bottom: 7px;
        color: var(--text);
        font-size: 14px;
        font-weight: 800;
    }

    .holiday-empty p {
        max-width: 370px;
        margin: auto;
        color: var(--muted);
        font-size: 11px;
        line-height: 1.7;
    }

    .holiday-table-footer {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 13px 20px;
        border-top: 1px solid var(--border);
        color: var(--muted);
        font-size: 10px;
    }

    .holiday-table-footer i {
        color: var(--primary);
        font-size: 13px;
    }

    .holiday-source {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 18px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
    }

    .holiday-source-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 37px;
        height: 37px;
        border-radius: 10px;
        background: rgba(32, 164, 119, .10);
        color: #20a477;
        font-size: 17px;
    }

    .holiday-source strong {
        display: block;
        margin-bottom: 4px;
        color: var(--text);
        font-size: 11px;
        font-weight: 800;
    }

    .holiday-source p {
        margin: 0;
        color: var(--muted);
        font-size: 10px;
        line-height: 1.6;
    }

    @media (max-width: 767.98px) {
        .holiday-header {
            align-items: flex-start;
        }

        .holiday-header h2 {
            font-size: 24px;
        }

        .holiday-header-icon {
            width: 48px;
            height: 48px;
            font-size: 22px;
        }

        .holiday-filter-form {
            align-items: stretch;
            flex-direction: column;
        }

        .holiday-year-field {
            width: 100%;
        }

        .holiday-filter-button {
            width: 100%;
        }

        .holiday-stat-grid {
            grid-template-columns: 1fr;
        }

        .holiday-next-card {
            min-height: 170px;
            padding: 23px 20px;
        }

        .holiday-next-card h3 {
            max-width: 75%;
            font-size: 19px;
        }

        .holiday-next-illustration {
            right: 15px;
            font-size: 85px;
        }

        .holiday-table-header {
            align-items: flex-start;
        }

        .holiday-table th,
        .holiday-table td {
            padding: 13px 12px;
        }
    }
</style>
@endpush
