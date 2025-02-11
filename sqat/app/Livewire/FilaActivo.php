<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activo;
use App\Models\Registro;
use Illuminate\Support\Facades\Auth;



class FilaActivo extends Component
{
    public $activo;
    protected $listeners = ['refreshRow' => 'refreshRow'];

    public function mount($activo)
    {
        $this->activo = $activo;
    }

    public function render()
    {
        return view('livewire.fila-activo');
    }

    public function refreshRow($id)
    {
        if ($this->activo->id == $id) {
            $this->dispatch('$refresh');
        }
    }

    public function cambiarEstado($activo_id, $nuevo_estado){
        $activo = Activo::with('estadoRelation')->findOrFail($activo_id);
        if( $activo->estado == 7){
            $activo->usuario_de_activo = NULL;
            $activo->responsable_de_activo = NULL;
        }
        if( $nuevo_estado == 7){
            $registroAntiguoResponsable = new Registro();
            $registroAntiguoResponsable->persona = $activo->responsable_de_activo;
            $registroAntiguoResponsable->activo = $activo_id;
            $registroAntiguoResponsable->tipo_cambio = 'DESVINCULACION';
            $registroAntiguoResponsable->encargado_cambio = Auth::user()->id;
            $registroAntiguoResponsable->save();
            $activo->responsable_de_activo = NULL;
            $activo->usuario_de_activo = NULL;
        }
        $activo->estado = $nuevo_estado;
        $activo->update();

        $activoActualizado = Activo::with('estadoRelation')->findOrFail($activo_id);
        // dispatchir evento para notificar a la interfaz que se actualizó el estado
        $this->dispatch('actualizarFila', $activoActualizado);

    }
}
