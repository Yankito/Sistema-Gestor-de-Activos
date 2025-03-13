<?php

namespace App\Livewire\Personas;

use Livewire\Component;
use App\Models\Persona;

class FilaPersona extends Component
{
    public $persona;
    protected $listeners = ['refreshRowPersonas' => 'refreshRowPersonas'];

    public function mount($persona){
        $this->persona = $persona;
    }
    public function render()
    {
        return view('livewire.personas.fila-persona');
    }

    public function refreshRowPersonas($id)
    {
        if ($this->persona->id == $id) {
            $this->persona = Persona::with('ubicacionRelation')->findOrFail($id);
            $this->dispatch('$refresh');
        }
    }

    public function editarPersona($id){
        $persona = Persona::with('ubicacionRelation')->findOrFail($id);
        $this->dispatch('refreshModalValores', $persona);
    }
}
