<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class MutasiAction
{
    public static function recordAction(): Action
    {
        return Action::make('mutasi')
            ->label('Mutasi')
            ->icon('heroicon-o-arrows-right-left')
            ->color('warning')
            ->form(fn($record) => [
                Placeholder::make('info_pegawai')
                    ->label('Data Pegawai')
                    ->content(fn() => view('filament.components.info-pegawai', ['record' => $record])),

                Select::make('jenis_pegawai')
                    ->label('Mutasi ke Jenis Pegawai')
                    ->options([
                        'Pns'             => 'PNS',
                        'Non Pns Tetap'   => 'Non PNS Tetap',
                        'Non Pns Kontrak' => 'Non PNS Kontrak',
                        'Pensiun'         => 'Pensiun',
                    ])
                    ->default($record->jenis_pegawai)
                    ->required(),
            ])
            ->requiresConfirmation()
            ->modalHeading('Mutasi Jenis Pegawai')
            ->modalDescription('Pastikan data sudah benar sebelum melanjutkan mutasi.')
            ->modalSubmitActionLabel('Ya, Mutasi Sekarang')
            ->modalCancelActionLabel('Batal')
            ->action(function ($record, array $data) {
                $record->update(['jenis_pegawai' => $data['jenis_pegawai']]);

                \Filament\Notifications\Notification::make()
                    ->title('Mutasi Berhasil')
                    ->body("Pegawai {$record->nama} berhasil dimutasi ke {$data['jenis_pegawai']}.")
                    ->success()
                    ->send();
            });
    }

    public static function bulkAction(): BulkAction
    {
        return BulkAction::make('mutasi_bulk')
            ->label('Mutasi Jenis Pegawai')
            ->icon('heroicon-o-arrows-right-left')
            ->color('warning')
            ->before(function (Collection $records, BulkAction $action) {
                if ($records->count() > 20) {
                    Notification::make()
                        ->title('Terlalu Banyak Data')
                        ->body("Maksimal 10 pegawai yang bisa dimutasi sekaligus. Kamu memilih {$records->count()} pegawai.")
                        ->danger()
                        ->send();

                    $action->cancel();
                }
            })
            ->form(fn(Collection $records) => [
                Placeholder::make('preview_pegawai')
                    ->label('')
                    ->content(fn() => view('filament.components.preview-pegawai-bulk', ['records' => $records])),

                Select::make('jenis_pegawai')
                    ->label('Mutasi ke Jenis Pegawai')
                    ->options([
                        'Pns'             => 'PNS',
                        'Non Pns Tetap'   => 'Non PNS Tetap',
                        'Non Pns Kontrak' => 'Non PNS Kontrak',
                        'Pensiun'         => 'Pensiun',
                    ])
                    ->required(),
            ])
            ->requiresConfirmation()
            ->modalHeading('Mutasi Jenis Pegawai (Bulk)')
            ->modalDescription('Pastikan data sudah benar sebelum melanjutkan mutasi.')
            ->modalSubmitActionLabel('Ya, Mutasi Semua')
            ->modalCancelActionLabel('Batal')
            ->action(function (Collection $records, array $data) {
                $records->each->update(['jenis_pegawai' => $data['jenis_pegawai']]);

                Notification::make()
                    ->title('Mutasi Bulk Berhasil')
                    ->body("{$records->count()} pegawai berhasil dimutasi ke {$data['jenis_pegawai']}.")
                    ->success()
                    ->send();
            })
            ->deselectRecordsAfterCompletion();
    }
}
