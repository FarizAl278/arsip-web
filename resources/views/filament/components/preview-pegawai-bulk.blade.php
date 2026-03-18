<div class="text-sm space-y-1">
    <p class="font-semibold text-gray-500 mb-2">Pegawai yang akan dimutasi ({{ $records->count() }} orang):</p>
    <ul class="divide-y divide-gray-100 max-h-48 overflow-y-auto border rounded-lg">
        @foreach ($records as $record)
            <li class="flex justify-between px-3 py-2">
                <span>{{ $record->nama }}</span>
                <span class="text-gray-400">{{ $record->jenis_pegawai }}</span>
            </li>
        @endforeach
    </ul>
</div>