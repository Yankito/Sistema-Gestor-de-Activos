<?php

namespace App\Livewire\Activos;

use Livewire\Component;
use App\Models\Activo;
use App\Models\Registro;
use App\Models\Persona;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



class FilaActivo extends Component
{
    public $activo;
    public $usuario_de_activo;
    public $responsable_de_activo;
    public $ubicacion;
    protected $listeners = ['refreshRow' => 'refreshRow', 'updateActivo'];

    public function mount($activo)
    {
        $this->activo = $activo;
        $this->usuario_de_activo = $activo->usuario_de_activo;
        $this->responsable_de_activo = $activo->responsable_de_activo;
        $this->ubicacion = $activo->ubicacion;
    }

    public function render()
    {
        return view('livewire.activos.fila-activo');
    }

    public function refreshRow($id)
    {
        if ($this->activo->id == $id) {
            $this->activo = Activo::with('estadoRelation', 'usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation')->findOrFail($id);
            $this->dispatch('$refresh');
        }
    }

    public function editarActivo($id){
        $activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')->findOrFail($id);
        $this->dispatch('refreshModal', $activo);
    }
    public function editarActivoValores($id){
        $activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')->findOrFail($id);
        $this->dispatch('refreshModalValores', $activo);
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

        // dispatchir evento para notificar a la interfaz que se actualizó el estado
        $this->dispatch('actualizarFila');
        $this->dispatch('refreshRow', $activo_id);

    }

    public function editarDatos($id){
        $activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')->findOrFail($id);
        $this->dispatch('cargarModal', $activo);
    }


}
