<?php

namespace App\Livewire\Activos;

use Livewire\Component;
use App\Models\Activo;
use App\Models\Registro;
use Illuminate\Support\Facades\Auth;


class TablaActivos extends Component
{
    public $activos;

    protected $listeners = ['refreshFila'];

    public function mount()
    {
        // Cargar los activos cuando se monte el componente
        $this->activos = Activo::with('estadoRelation')->get();
    }


    public function render()
    {
        return view('livewire.activos.tabla-activos', [
            'activos' => $this->activos,
        ]);
    }
}
