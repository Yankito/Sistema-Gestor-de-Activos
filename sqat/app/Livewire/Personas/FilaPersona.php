<?php

namespace App\Livewire\Personas;

use Livewire\Component;

class FilaPersona extends Component
{
    public $persona;

    public function mount($persona){
        $this->persona = $persona;
    }
    public function render()
    {
        return view('livewire.personas.fila-persona');
    }

    public function refreshRowPersona($id)
    {
        if ($this->persona->id == $id) {
            $this->dispatch('$refresh');
        }
    }
}
