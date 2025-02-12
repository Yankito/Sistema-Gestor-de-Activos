<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activo;
use App\Models\Registro;
use App\Models\Persona;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\Auth;

class EditarActivo extends Component
{
    public $activo;
    public $personas;
    public $ubicaciones;
    public $responsable_de_activo;
    public $ubicacion;

    protected $listeners = ['refreshModal' => 'refreshModal', 'updateActivo'];

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
        return view('livewire.editar-activo');
    }

    public function refreshModal($activo)
    {
        $this->activo = Activo::find($activo['id']);
        $this->dispatch('$refresh');
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

    // Actualizar un activo existente
    public function updateActivo(){
        $activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')
            ->findOrFail($this->activo->id);

        if ($activo->responsable_de_activo != $this->responsable_de_activo) {
            $registroNuevoResponsable = new Registro();
            $registroNuevoResponsable->persona = $this->responsable_de_activo;
            $registroNuevoResponsable->activo = $activo->id;
            $registroNuevoResponsable->tipo_cambio = 'ASIGNACION';
            $registroNuevoResponsable->encargado_cambio = Auth::user()->id;
            $registroNuevoResponsable->save();

            $activo->estado = 4;
        }

        if ($activo->ubicacion != $this->ubicacion) {
            $activo->ubicacion = $this->ubicacion;

            $persona = Persona::find($this->responsable_de_activo);
            if ($persona) {
                $persona->ubicacion = $this->ubicacion;
                $persona->save();
            }
        }

        $activo->usuario_de_activo = $this->responsable_de_activo;
        $activo->responsable_de_activo = $this->responsable_de_activo;
        $activo->update();
        $this->dispatch('actualizarFila', $activo->id);
    }

    public function actualizarUbicacion($ubicacionId)
    {
        $this->ubicacion = $ubicacionId;
    }

}
