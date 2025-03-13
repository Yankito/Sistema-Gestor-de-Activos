<?php

namespace App\Livewire\Activos;

use Livewire\Component;
use App\Models\Activo;
use App\Models\Registro;
use App\Models\Persona;
use App\Models\Ubicacion;
use App\Models\Asignacion;
use Illuminate\Support\Facades\Auth;

class EditarEstadosActivo extends Component
{
    public $activo;
    public $personas;
    public $ubicaciones;
    public $responsable_de_activo;
    public $ubicacion;
    public $usuarios;

    protected $listeners = ['refreshModal' => 'refreshModal', 'updateActivo',
    'actualizarUbicacion' => 'actualizarUbicacion', 'cerrarModal' => 'resetearModal',
    'setResponsable' => 'actualizarResponsable', 'setUsuarios'=>'actualizarUsuarios', 'iniciarResponsable'];

    public function mount()
    {
        $this->personas = Persona::all();
        $this->ubicaciones = Ubicacion::all();
        if($this->activo != null) {
            $this->responsable_de_activo = $this->activo->responsable_de_activo;
            $this->ubicacion = $this->activo->responsable_de_activo->ubicacion;
        }
    }
    public function render()
    {
        if(isset($this->activo)) {
            $this->dispatch('iniciar');
            return view('livewire.activos.editar-estados-activo');
        } else {
            return view('livewire.activos.editar-estados-activo');
        }

    }

    public function refreshModal($activo)
    {
        $this->activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')->findOrFail($activo['id']);
        $this->responsable_de_activo = $this->activo->responsable_de_activo;
        $this->ubicacion = $this->activo->ubicacion;
        $this->usuarios = $this->activo->usuarioDeActivo->pluck('id')->toArray();
        if (in_array($this->responsable_de_activo, $this->usuarios)) {
            $this->usuarios = array_diff($this->usuarios, [$this->responsable_de_activo]);
        }
        $this->dispatch('$refresh');
        $this->dispatch('modal-cargado');
    }

    public function cambiarEstado($activo_id, $nuevo_estado){
        $activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')->findOrFail($activo_id);
        if( $activo->estado == 7){
            $activo->responsable_de_activo = null;
            $this->responsable_de_activo = null;
            $this->usuarios = [];
            $this->manejarAsignaciones($activo->id);
        }
        if( $nuevo_estado == 7){
            $registroAntiguoResponsable = new Registro();
            $registroAntiguoResponsable->persona = $activo->responsable_de_activo;
            $registroAntiguoResponsable->activo = $activo_id;
            $registroAntiguoResponsable->tipo_cambio = 'DESVINCULACION';
            $registroAntiguoResponsable->encargado_cambio = Auth::user()->id;
            $registroAntiguoResponsable->save();
        }
        $activo->estado = $nuevo_estado;
        $activo->update();

        $activoActualizado = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')->findOrFail($activo_id);
        // dispatchir evento para notificar a la interfaz que se actualizó el estado
        $this->dispatch('refreshRow', $activoActualizado);
        $this->dispatch('actualizarFila');
        $this->resetearModal();
        //$this->dispatch('$refresh');

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

        $activo->responsable_de_activo = $this->responsable_de_activo;
        $activo->update();

        $this->manejarAsignaciones($activo->id);

        $this->dispatch('refreshRow', $activo->id);
        $this->dispatch('cerrar-modal');
        //$this->limpiarDatos();
    }

    protected function manejarAsignaciones($activoId) {
        // Eliminar asignaciones anteriores para este activo (opcional, dependiendo de tu lógica)
        Asignacion::where('id_activo', $activoId)->delete();
        // Iterar sobre los usuarios y crear nuevas asignaciones
        if($this->usuarios == null){
            $this->usuarios = [];
        }
        if ($this->responsable_de_activo != null && !in_array($this->responsable_de_activo, $this->usuarios)) {
            array_push($this->usuarios, $this->responsable_de_activo);
        }

        foreach ($this->usuarios as $usuarioId) {
            Asignacion::create([
                'id_persona' => $usuarioId,
                'id_activo' => $activoId,
            ]);
        }
    }

    public function actualizarUbicacion($responsableId)
    {
        // Buscar la persona seleccionada
        $persona = Persona::with('ubicacionRelation')->find($responsableId);
        //dd($persona);
        // Si la persona tiene ubicación, actualizar la propiedad de Livewire
        if ($persona && $persona->ubicacion) {
            $this->ubicacion = $persona->ubicacionRelation->id;
        } else {
            $this->ubicacion = null;
        }
        $this->skipRender();

    }

    public function resetearModal()
    {
        $this->reset(['activo', 'responsable_de_activo', 'ubicacion', 'usuarios']);
    }

    public function actualizarResponsable($data)
    {
        $this->responsable_de_activo = $data;
        $this->dispatch('actualizarUbicacion', $data);

    }
    public function actualizarUsuarios($data)
    {
        $this->usuarios = $data;
        $this->skipRender();
    }

    public function iniciarResponsable($data)
    {
        $this->responsable_de_activo = $data;
    }
}
