<?php

namespace App\Livewire\Personas;

use Livewire\Component;
use App\Models\Persona;

class TablaPersonas extends Component
{
    public $personas;
    protected $listeners = [];


    public function mount()
    {
        // Cargar los activos cuando se monte el componente
        $this->cargarPersonas();
    }

    private function cargarPersonas()
    {
        $this->personas = Persona::with('ubicacionRelation')->get();
    }


    public function render()
    {
        return view('livewire.personas.tabla-personas');
    }
}
?>
