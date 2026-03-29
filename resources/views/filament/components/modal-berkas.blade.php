<div class="grid grid-cols-2 gap-6 p-4">

{{-- KIRI: Detail Pegawai --}}
<div class="flex flex-col gap-4">
    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
        Informasi Pegawai
    </p>

    {{-- Data Pribadi --}}
    <div class="grid grid-cols-2 gap-3">
        @foreach([
                'NIP' => ['value' => $record->nip, 'icon' => 'heroicon-o-identification'],
                'Nama' => ['value' => $record->nama, 'icon' => 'heroicon-o-user'],
                'Tanggal Lahir' => ['value' => $record->tgl_lahir ?? '-', 'icon' => 'heroicon-o-calendar'],
                'Tanggal Mutasi Masuk' => ['value' => $record->tgl_mutasi_masuk ?? '-', 'icon' => 'heroicon-o-calendar'],
                'Tanggal Mutasi Keluar' => ['value' => $record->tgl_mutasi_keluar ?? '-', 'icon' => 'heroicon-o-calendar'],
                'Jenis Kelamin' => ['value' => $record->jenis_kelamin, 'icon' => 'heroicon-o-user-circle'],
            ] as $label => $item)
                    <div class="flex flex-col gap-1">
                        <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                            <x-dynamic-component :component="$item['icon']" class="w-5 h-5" />
                            {{ $label }}
                        </p>
                        <p class="text-sm text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-800 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700">
                            {{ $item['value'] ?? '-' }}
                        </p>
                    </div>
        @endforeach
    </div>

    <div class="border-t border-gray-100 dark:border-gray-700"></div>

    {{-- Data Kepegawaian --}}
    <div class="grid grid-cols-2 gap-3">
        @foreach([
                'Jenis Pegawai' => ['value' => $record->jenis_pegawai, 'icon' => 'heroicon-o-briefcase'],
                'Nomor Berkas' => ['value' => $record->nomor_berkas, 'icon' => 'heroicon-o-folder'],
                'Lemari' => ['value' => $record->lemari, 'icon' => 'heroicon-o-archive-box'],
                'Hambalan' => ['value' => $record->hambalan, 'icon' => 'heroicon-o-squares-2x2'],
            ] as $label => $item)
                    <div class="flex flex-col gap-1">
                        <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                            <x-dynamic-component :component="$item['icon']" class="w-5 h-5" />
                            {{ $label }}
                        </p>
                        <p class="text-sm text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-800 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700">
                            {{ $item['value'] ?? '-' }}
                        </p>
                    </div>
        @endforeach
    </div>
</div>

    {{-- KANAN: File Berkas --}}
    <div class="flex flex-col gap-3">
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
            File Data Scan
        </p>

        @if(isset($error))
            <div class="p-3 rounded-lg border border-yellow-300 bg-yellow-50 dark:bg-yellow-900/20 dark:border-yellow-700 text-sm text-yellow-700 dark:text-yellow-300">
                {{ $error }}
            </div>

        @elseif(isset($danger))
            <div class="p-3 rounded-lg border border-red-300 bg-red-50 dark:bg-red-900/20 dark:border-red-700 text-sm text-red-700 dark:text-red-300">
                {{ $danger }}
            </div>

        @else
            @forelse($files as $file)
                <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center gap-2 min-w-0">
                        <x-heroicon-o-document class="w-4 h-4 shrink-0 text-gray-400 dark:text-gray-500" />
                        <span class="text-sm text-gray-800 dark:text-gray-200 truncate">
                            {{ basename($file) }}
                        </span>
                    </div>
                    <a href="{{ Storage::disk('public')->url($file) }}"
                       target="_blank"
                       class="shrink-0 ml-2 text-xs px-3 py-1.5 rounded-md border border-primary-300 dark:border-primary-700 text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-primary-900/20 hover:bg-primary-100 dark:hover:bg-primary-900/40 transition-colors no-underline">
                        Lihat
                    </a>
                </div>
            @empty
                <div class="p-3 rounded-lg border border-yellow-300 bg-yellow-50 dark:bg-yellow-900/20 dark:border-yellow-700 text-sm text-yellow-700 dark:text-yellow-300">
                    Tidak ada berkas ditemukan.
                </div>
            @endforelse

            @if(isset($files) && count($files) > 0)
                <div class="mt-1 px-3 py-2 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-300 dark:border-green-700 text-xs text-green-700 dark:text-green-300">
                    {{ count($files) }} berkas ditemukan
                </div>
            @endif
        @endif
    </div>

</div>