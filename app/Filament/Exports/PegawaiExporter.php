<?php

namespace App\Filament\Exports;

use App\Models\Pegawai;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class PegawaiExporter extends Exporter
{
    protected static ?string $model = Pegawai::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nip')
                ->formatStateUsing(fn(mixed $state) => "\t" . (string) $state),
            ExportColumn::make('nama'),
            ExportColumn::make('thn_angkat'),
            ExportColumn::make('tgl_lahir'),
            ExportColumn::make('jenis_kelamin'),
            ExportColumn::make('unit_pegawai'),
            ExportColumn::make('sts_pegawai'),
            ExportColumn::make('nomor_berkas'),
            ExportColumn::make('lemari'),
            ExportColumn::make('hambalan'),
            ExportColumn::make('jenis_pegawai'),
            ExportColumn::make('TMT'),
            ExportColumn::make('tgl_pensiun'),
            ExportColumn::make('masa_kerja'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your pegawai export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
