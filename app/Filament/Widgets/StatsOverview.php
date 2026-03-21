<?php

namespace App\Filament\Widgets;

use App\Models\Pegawai;
use App\Models\Layanan;
use App\Models\BerkasMasuk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $now        = Carbon::now();
        $bulanIni   = $now->month;
        $tahunIni   = $now->year;
        $bulanLalu  = $now->copy()->subMonth();

        // ── Pegawai ──────────────────────────────────────────────────
        $totalPegawai    = Pegawai::count();
        $pegawaiAktif    = Pegawai::where('jenis_pegawai', '!=', 'Pensiun')->count();
        $pegawaiPensiun  = $totalPegawai - $pegawaiAktif;
        $pctAktif        = $totalPegawai > 0
            ? round(($pegawaiAktif / $totalPegawai) * 100, 1)
            : 0;

        // sparkline: jumlah pegawai aktif per bulan (6 bulan terakhir)
        $chartPegawai = collect(range(5, 0))->map(function ($offset) {
            $month = Carbon::now()->subMonths($offset);
            return Pegawai::where('jenis_pegawai', '!=', 'Pensiun')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', '<=', $month->month)
                ->count();
        })->toArray();

        // ── Layanan ───────────────────────────────────────────────────
        $totalLayanan      = Layanan::count();
        $layananBulanIni   = Layanan::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->count();
        $layananBulanLalu  = Layanan::whereMonth('tanggal', $bulanLalu->month)
            ->whereYear('tanggal', $bulanLalu->year)
            ->count();
        $trendLayanan      = $layananBulanLalu > 0
            ? round((($layananBulanIni - $layananBulanLalu) / $layananBulanLalu) * 100, 1)
            : 0;

        // sparkline: layanan 6 bulan terakhir
        $chartLayanan = collect(range(5, 0))->map(function ($offset) {
            $month = Carbon::now()->subMonths($offset);
            return Layanan::whereMonth('tanggal', $month->month)
                ->whereYear('tanggal', $month->year)
                ->count();
        })->toArray();

        // ── Berkas Masuk ──────────────────────────────────────────────
        $totalBerkas     = BerkasMasuk::count();
        $berkasBulanIni  = BerkasMasuk::whereMonth('tgl_berkas', $bulanIni)
            ->whereYear('tgl_berkas', $tahunIni)
            ->count();
        $berkasBulanLalu = BerkasMasuk::whereMonth('tgl_berkas', $bulanLalu->month)
            ->whereYear('tgl_berkas', $bulanLalu->year)
            ->count();
        $trendBerkas     = $berkasBulanLalu > 0
            ? round((($berkasBulanIni - $berkasBulanLalu) / $berkasBulanLalu) * 100, 1)
            : 0;

        // sparkline: berkas 6 bulan terakhir
        $chartBerkas = collect(range(5, 0))->map(function ($offset) {
            $month = Carbon::now()->subMonths($offset);
            return BerkasMasuk::whereMonth('tgl_berkas', $month->month)
                ->whereYear('tgl_berkas', $month->year)
                ->count();
        })->toArray();

        // ── Helper: format trend description ─────────────────────────
        $trendDesc = function (float $pct, string $label): string {
            if ($pct > 0) {
                return "↑ {$pct}% dari {$label}";
            }
            if ($pct < 0) {
                return "↓ " . abs($pct) . "% dari {$label}";
            }
            return "Sama seperti {$label}";
        };

        $trendColor = fn(float $pct): string => match (true) {
            $pct > 0  => 'success',
            $pct < 0  => 'danger',
            default   => 'gray',
        };

        return [

            // ── 1. Total Pegawai ──────────────────────────────────────
            Stat::make('Total Pegawai', number_format($totalPegawai))
                ->description("{$pegawaiAktif} aktif · {$pegawaiPensiun} pensiun")
                ->descriptionIcon('heroicon-m-users')
                ->chart($chartPegawai)
                ->color('primary'),

            // ── 2. Pegawai Aktif ──────────────────────────────────────
            Stat::make('Pegawai Aktif', number_format($pegawaiAktif))
                ->description("{$pctAktif}% dari total pegawai")
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart($chartPegawai)
                ->color('success'),

            // ── 3. Total Layanan ──────────────────────────────────────
            Stat::make('Total Layanan', number_format($totalLayanan))
                ->description('Semua layanan tercatat')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),

            // ── 4. Layanan Bulan Ini ──────────────────────────────────
            Stat::make('Layanan ' . $now->translatedFormat('F Y'), number_format($layananBulanIni))
                ->description($trendDesc($trendLayanan, 'bulan lalu'))
                ->descriptionIcon(
                    $trendLayanan >= 0
                        ? 'heroicon-m-arrow-trending-up'
                        : 'heroicon-m-arrow-trending-down'
                )
                ->chart($chartLayanan)
                ->color($trendColor($trendLayanan)),

            // ── 5. Total Berkas Masuk ─────────────────────────────────
            Stat::make('Total Berkas Masuk', number_format($totalBerkas))
                ->description('Seluruh arsip berkas masuk')
                ->descriptionIcon('heroicon-m-inbox-stack')
                ->color('warning'),

            // ── 6. Berkas Bulan Ini ───────────────────────────────────
            Stat::make('Berkas ' . $now->translatedFormat('F Y'), number_format($berkasBulanIni))
                ->description($trendDesc($trendBerkas, 'bulan lalu'))
                ->descriptionIcon(
                    $trendBerkas >= 0
                        ? 'heroicon-m-arrow-trending-up'
                        : 'heroicon-m-arrow-trending-down'
                )
                ->chart($chartBerkas)
                ->color($trendColor($trendBerkas)),
        ];
    }
}
