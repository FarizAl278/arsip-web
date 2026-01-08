<x-filament-panels::page>
    <x-filament::tabs>
        <x-filament::tabs.item 
            :active="$activeTab === 'pns'"
            wire:click="$set('activeTab', 'pns')"
        >
            PNS/CPNS Aktif
        </x-filament::tabs.item>
        
        <x-filament::tabs.item 
            :active="$activeTab === 'tetap'"
            wire:click="$set('activeTab', 'tetap')"
        >
            Non PNS Tetap
        </x-filament::tabs.item>
        
        <x-filament::tabs.item 
            :active="$activeTab === 'kontrak'"
            wire:click="$set('activeTab', 'kontrak')"
        >
            Non PNS Kontrak
        </x-filament::tabs.item>
        
        <x-filament::tabs.item 
            :active="$activeTab === 'pensiun'"
            wire:click="$set('activeTab', 'pensiun')"
        >
            Pensiun
        </x-filament::tabs.item>
    </x-filament::tabs>

    <div class="mt-6">
        @if($activeTab === 'pns')
            @livewire('arsip-pns-table')
        @elseif($activeTab === 'tetap')
            @livewire('arsip-tetap-table')
        @elseif($activeTab === 'kontrak')
            @livewire('arsip-kontrak-table')
        @elseif($activeTab === 'pensiun')
            @livewire('arsip-pensiun-table')
        @endif
    </div>
</x-filament-panels::page>