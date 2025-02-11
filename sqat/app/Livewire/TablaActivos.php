<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activo;
use App\Models\Registro;
use Illuminate\Support\Facades\Auth;


class TablaActivos extends Component
{
    public $activos;

    public function mount()
    {
        // Cargar los activos cuando se monte el componente
        $this->activos = Activo::with('estadoRelation')->get();
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
        return response()->json([
            'success' => true,
            'activoModificado'=> $activoActualizado
        ]);

    }

    public function render()
    {
        return view('livewire.tabla-activos');
    }
}
