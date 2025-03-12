<?php

namespace App\Livewire\Personas;

use App\Models\Activo;
use Livewire\Component;
use App\Models\Persona;
use App\Models\Ubicacion;


class EditarValoresPersona extends Component
{
    public $ubicaciones;
    public $persona;
    public $rut;
    public $user;
    public $nombre_completo;
    public $nombre_empresa;
    public $fecha_ing;
    public $fecha_ter;
    public $cargo;
    public $ubicacion;
    public $correo;
    public $estado_empleado;

    protected $listeners = ['refreshModalValores', 'cerrarModalValores' => 'resetearModal'];

    public function asignarVariables()
    {
        $this->rut = $this->persona->rut;
        $this->user = $this->persona->user;
        $this->nombre_completo = $this->persona->nombre_completo;
        $this->nombre_empresa = $this->persona->nombre_empresa;
        $this->cargo = $this->persona->cargo;
        $this->correo = $this->persona->correo;
        $this->fecha_ing = $this->persona->fecha_ing;
        $this->fecha_ter = $this->persona->fecha_ter;
        $this->ubicacion = $this->persona->ubicacion;
        $this->estado_empleado = $this->persona->estado_empleado;
    }

    public function mount()
    {
        $this->ubicaciones = Ubicacion::all();
        if($this->persona != NULL) {
            $this->asignarVariables();
        }
    }

    public function render()
    {
        if(isset($this->persona)) {
            $this->dispatch('modal-valores-cargado');
            return view('livewire.personas.editar-valores-persona');
        } else {
            return view('livewire.personas.editar-valores-persona');
        }
    }

    public function refreshModalValores($persona)
    {
        $this->persona = Persona::findOrFail($persona['id']);
        $this->asignarVariables();
        $this->dispatch('$refresh');
    }

    public function updateValoresPersona(){
        $persona = Persona::findOrFail($this->persona->id);
        $persona->rut = $this->rut;
        $persona->user = $this->user;
        $persona->nombre_completo = $this->nombre_completo;
        $persona->nombre_empresa = $this->nombre_empresa;
        $persona->cargo = $this->cargo;
        $persona->correo = $this->correo;
        $persona->fecha_ing = $this->fecha_ing;
        $persona->fecha_ter = $this->fecha_ter;
        if ($persona->ubicacion != $this->ubicacion) {
            $persona->ubicacion = $this->ubicacion;

            $activos = Activo::where('responsable_de_activo', $persona->id)->get();
            foreach ($activos as $activo) {
                $activo->ubicacion = $this->ubicacion;
                $activo->update();
            }
        }
        if($persona->estado_empleado != $this->estado_empleado){
            if($this->estado_empleado == 0){
                $persona->estado_empleado = $this->estado_empleado;
                $persona->fecha_ter = date('Y-m-d');
            }
            else{
                $persona->estado_empleado = $this->estado_empleado;
                $persona->fecha_ter = NULL;
            }
        }
        try {
            $persona->update();
            $this->dispatch('refreshRowPersonas', $persona->id);
            $this->dispatch('cerrar-modal-valores', ['success' => true, 'mensaje' => 'Los cambios se han guardado correctamente.']);
        } catch (\Exception $e) {
            $this->dispatch('cerrar-modal-valores', ['success' => false, 'mensaje' => 'El rut o user ya se encuentra registrado.']);
        }

    }
    public function resetearModal()
    {
        $this->reset(['persona', 'rut', 'user', 'nombre_completo', 'nombre_empresa', 'cargo', 'correo', 'fecha_ing','fecha_ter', 'ubicacion', 'estado_empleado']);
    }
}
?>
