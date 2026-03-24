<?php

namespace App\Filament\Exports;

use App\Models\BerkasMasuk;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class BerkasMasukExporter extends Exporter
{
    protected static ?string $model = BerkasMasuk::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nip')
                ->formatStateUsing(fn(mixed $state) => "\t" . (string) $state),
            ExportColumn::make('nomor_berkas')
                ->formatStateUsing(fn(mixed $state) => "\t" . (string) $state),
            ExportColumn::make('perihal'),
            ExportColumn::make('tgl_berkas'),
            ExportColumn::make('asal_berkas'),
            ExportColumn::make('tgl_agenda'),
            ExportColumn::make('tahun'),
            ExportColumn::make('nama_penyimpan'),
            ExportColumn::make('locate'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your berkas masuk export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
