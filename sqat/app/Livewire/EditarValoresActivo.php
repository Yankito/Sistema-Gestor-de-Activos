<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activo;
use App\Models\Registro;
use App\Models\Persona;
use App\Models\Ubicacion;

class EditarValoresActivo extends Component
{
    public $activo;
    public $personas;
    public $ubicaciones;
    public $responsable_de_activo;
    public $ubicacion;

    protected $listeners = ['refreshModalValores', 'cerrarModalValores' => 'resetearModal'];

    public function mount()
    {
        $this->personas = Persona::all();
        $this->ubicaciones = Ubicacion::all();

        if($this->activo != NULL) {
            $this->responsable_de_activo = $this->activo->responsable_de_activo;
            $this->ubicacion = $this->activo->responsable_de_activo->ubicacion;
        }
    }
    public function render()
    {
        if(isset($this->activo)) {
            $this->dispatch('modal-valores-cargado');
            return view('livewire.editar-valores-activo');
        } else {
            return view('livewire.editar-valores-activo');
        }
    }

    public function refreshModalValores($activo)
    {
        $this->activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')->findOrFail($activo['id']);
        $this->responsable_de_activo = $this->activo->responsable_de_activo;
        $this->ubicacion = $this->activo->ubicacion;
        $this->dispatch('$refresh');
    }

    public function updateValoresActivo(){
        $activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')
            ->findOrFail($this->activo->id);

        if ($activo->ubicacion != $this->ubicacion) {
            $activo->ubicacion = $this->ubicacion;

            $persona = Persona::find($this->responsable_de_activo);
            if ($persona) {
                $persona->ubicacion = $this->ubicacion;
                $persona->save();
            }
        }
        $activo->update();
        $this->dispatch('refreshRow', $activo->id);
        $this->dispatch('cerrar-modal-valores');

    }

    public function resetearModal()
    {
        $this->reset(['activo', 'responsable_de_activo', 'ubicacion']);
    }

}
