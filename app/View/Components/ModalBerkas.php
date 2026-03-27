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
    public function __construct()
    {
        $basePath = 'DataScan';
        $folders = Storage::disk('public')->directories($basePath);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal-berkas');
    }
}
