<x-filament-panels::page>
    <x-filament::tabs>
        <x-filament::tabs.item
            :active="request()->query('tab') === 'pns' || !request()->query('tab')"
            tag="a"
            href="{{ route('filament.admin.pages.search-arsip', ['tab' => 'pns']) }}"
        >
            PNS
        </x-filament::tabs.item>

        <x-filament::tabs.item
            :active="request()->query('tab') === 'pensiun'"
            tag="a"
            href="{{ route('filament.admin.pages.search-arsip', ['tab' => 'pensiun']) }}"
        >
            Pensiun
        </x-filament::tabs.item>
    </x-filament::tabs>

    <div class="mt-4">
        {{ $this->table }}
    </div>
</x-filament-panels::page>