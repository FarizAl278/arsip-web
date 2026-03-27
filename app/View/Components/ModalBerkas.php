<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class ModalBerkas extends Component
{
    /**
     * Create a new component instance.
     */

    public $files;
    public $error = null;
    public $danger = null;
    public $record;


    public function __construct($record)
    {
        $this->record = $record;

        $basePath = 'DataScan';
        $folders = Storage::disk('public')->directories($basePath);
        $keyword = $record->nip;

        $matchedFolder = collect($folders)->first(
            fn($folder) => str_contains(strtolower($folder), strtolower($keyword))
        );

        if (!$matchedFolder) {
            $this->error = 'Folder tidak ditemukan untuk NIP: ' . $keyword;
            $this->files = [];
            return;
        }

        $files = Storage::disk('public')->files($matchedFolder);

        if (empty($files)) {
            $this->danger = 'Folder ditemukan, tapi tidak ada file di dalamnya.';
            $this->files = [];
            return;
        }

        $this->files = $files;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('filament.components.modal-berkas');
    }
}
