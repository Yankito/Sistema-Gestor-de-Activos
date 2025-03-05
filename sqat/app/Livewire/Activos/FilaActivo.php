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

    public function editarActivo($id)
    {
        $activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')->findOrFail($id);
        $this->dispatch('refreshModal', $activo);
    }

    public function editarActivoValores($id)
    {
        $activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')->findOrFail($id);
        $this->dispatch('refreshModalValores', $activo);
    }

    public function cambiarEstado($activo_id, $nuevo_estado)
    {
        // Mapeo de estados numéricos a valores de ENUM
        $estados = [
            1 => 'ADQUIRIDO',
            2 => 'PREPARACION',
            3 => 'DISPONIBLE',
            4 => 'ASIGNADO',
            5 => 'PERDIDO',
            6 => 'ROBADO',
            7 => 'DEVUELTO',
            8 => 'PARA_BAJA',
            9 => 'DONADO',
            10 => 'VENDIDO',
        ];
    
        // Obtener el activo
        $activo = Activo::with('estadoRelation')->findOrFail($activo_id);
    
        // Guardar el estado anterior
        $estado_anterior = $activo->estado;
    
        // Actualizar el estado del activo
        $activo->estado = $nuevo_estado;
        $activo->update();
    
        // Crear un registro para el cambio de estado
        $registroCambioEstado = new Registro();
        $registroCambioEstado->persona = $activo->responsable_de_activo; // Persona asociada al activo (puede ser NULL)
        $registroCambioEstado->activo = $activo_id;
        $registroCambioEstado->tipo_cambio = $estados[$nuevo_estado]; // Mapear el estado numérico a un valor de ENUM
        $registroCambioEstado->encargado_cambio = Auth::user()->id; // Usuario que realizó el cambio
        $registroCambioEstado->save();
    
        // Notificar a la interfaz que se actualizó el estado
        $this->dispatch('actualizarFila');
        $this->dispatch('refreshRow', $activo_id);
    }

    public function editarDatos($id)
    {
        $activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')->findOrFail($id);
        $this->dispatch('cargarModal', $activo);
    }
}