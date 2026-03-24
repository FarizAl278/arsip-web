<?php

namespace App\Filament\Exports;

use App\Models\Layanan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class LayananExporter extends Exporter
{
    protected static ?string $model = Layanan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('kode_berkas')
                ->formatStateUsing(fn(mixed $state) => "\t" . (string) $state),
            ExportColumn::make('status_berkas'),
            ExportColumn::make('sifat_layanan'),
            ExportColumn::make('sifat_lain'),
            ExportColumn::make('berkas_layanan'),
            ExportColumn::make('berkas_lain'),
            ExportColumn::make('nama'),
            ExportColumn::make('subdit'),
            ExportColumn::make('unit_kerja'),
            ExportColumn::make('seksi'),
            ExportColumn::make('operator'),
            ExportColumn::make('tanggal'),
            ExportColumn::make('keluar'),
            ExportColumn::make('kembali'),
            ExportColumn::make('internal'),
            ExportColumn::make('jenis_pegawai'),

        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your layanan export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
