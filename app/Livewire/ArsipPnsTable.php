<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PNS;

class ArsipPnsTable extends Component
{
    use WithPagination;
    
    public $search = '';
    
    protected $paginationTheme = 'tailwind';
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $data = PNS::query()
            ->when($this->search, function($query) {
                $query->where('NIP', 'like', '%' . $this->search . '%')
                      ->orWhere('NAMA', 'like', '%' . $this->search . '%')
                      ->orWhere('NOMORBERKAS', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
            
        return view('livewire.arsip-pns-table', [
            'data' => $data
        ]);
    }
}