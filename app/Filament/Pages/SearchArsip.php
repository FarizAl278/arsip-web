<?php
namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SearchArsip extends Page implements HasTable
{
    use InteractsWithTable;
    protected string $view = 'filament.pages.search-arsip';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::MagnifyingGlass;

    protected function getTabs()
    {
        return [
            'pns'     => Tab::make('PNS')
                ->modifyQueryUsing(fn(Builder $query) => $query->from('tabel_pns')),

            'pensiun' => Tab::make('Pensiun')
                ->modifyQueryUsing(fn(Builder $query) => $query->from('tabel_pensiun')),
        ];
    }

    public function getTableRecordKey($record): string
    {
        $activeTab = request()->query('tab', 'pns');

        // Sesuaikan dengan primary key masing-masing tabel
        return match ($activeTab) {
            'pns'     => (string) $record->id,
            'pensiun' => (string) $record->id, // atau primary key lain
            default   => (string) $record->id,
        };
    }

    public function table(Table $table): Table
    {
        $activeTab = request()->query('tab', 'pns');

        return $table
            ->query($this->getQueryForTab($activeTab))
            ->columns($this->getColumnsForTab($activeTab))
            ->filters([
                // filters lu disini
            ])
            ->persistSearchInSession()
            ->queryStringIdentifier('arsip')
            // ->persistTabInQueryString()

            ->actions([
                // actions lu disini
            ]);
    }

    protected function getQueryForTab(string $tab): Builder
    {
        return match ($tab) {
            'pns'     => \App\Models\PNS::query(),
            'pensiun' => \App\Models\Pensiun::query(),
            default   => \App\Models\PNS::query(),
        };
    }

    protected function getColumnsForTab(string $tab): array
    {
        return match ($tab) {
            'pns'     => [
                TextColumn::make('NIP')->label('NIP')->searchable(),
                TextColumn::make('NAMA')->label('Nama')->searchable(),
                // kolom lain untuk PNS
            ],
            'pensiun' => [
                TextColumn::make('NIP')->label('NIP')->searchable(),
                TextColumn::make('NAMA')->label('Nama')->searchable(),
                // kolom lain untuk Pensiun
            ],
            default   => [],
        };
    }
}
