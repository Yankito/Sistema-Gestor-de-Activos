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
    public $nombre_usuario;
    public $nombres;
    public $primer_apellido;
    public $segundo_apellido;
    public $supervisor;
    public $empresa;
    public $centro_costo;
    public $denominacion;
    public $titulo_puesto;
    public $ubicacion;

    protected $listeners = ['refreshModalValores', 'cerrarModalValores' => 'resetearModal'];

    public function mount()
    {
        $this->ubicaciones = Ubicacion::all();
        if($this->persona != NULL) {
            $this->rut = $this->persona->rut;
            $this->nombre_usuario = $this->persona->nombre_usuario;
            $this->nombres = $this->persona->nombres;
            $this->primer_apellido = $this->persona->primer_apellido;
            $this->segundo_apellido = $this->persona->segundo_apellido;
            $this->supervisor = $this->persona->supervisor;
            $this->empresa = $this->persona->empresa;
            $this->centro_costo = $this->persona->centro_costo;
            $this->denominacion = $this->persona->denominacion;
            $this->titulo_puesto = $this->persona->titulo_puesto;
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
        $this->rut = $this->persona->rut;
        $this->nombre_usuario = $this->persona->nombre_usuario;
        $this->nombres = $this->persona->nombres;
        $this->primer_apellido = $this->persona->primer_apellido;
        $this->segundo_apellido = $this->persona->segundo_apellido;
        $this->supervisor = $this->persona->supervisor;
        $this->empresa = $this->persona->empresa;
        $this->centro_costo = $this->persona->centro_costo;
        $this->denominacion = $this->persona->denominacion;
        $this->titulo_puesto = $this->persona->titulo_puesto;
        $this->ubicacion = $this->persona->ubicacion;
        $this->dispatch('$refresh');
    }

    public function updateValoresPersona(){
        $persona = Persona::findOrFail($this->persona->id);
        $persona->rut = $this->rut;
        $persona->nombre_usuario = $this->nombre_usuario;
        $persona->nombres = $this->nombres;
        $persona->primer_apellido = $this->primer_apellido;
        $persona->segundo_apellido = $this->segundo_apellido;
        $persona->supervisor = $this->supervisor;
        $persona->empresa = $this->empresa;
        $persona->centro_costo = $this->centro_costo;
        $persona->denominacion = $this->denominacion;
        $persona->titulo_puesto = $this->titulo_puesto;

        if ($persona->ubicacion != $this->ubicacion) {
            $persona->ubicacion = $this->ubicacion;

            $activos = Activo::where('responsable_de_activo', $persona->id)->get();
            foreach ($activos as $activo) {
                $activo->ubicacion = $this->ubicacion;
                $activo->update();
            }
        }
        try {
            $persona->update();
            $this->dispatch('refreshRow', $persona->id);
            $this->dispatch('cerrar-modal-valores', ['success' => true, 'mensaje' => 'Los cambios se han guardado correctamente.']);
        } catch (\Exception $e) {
            $this->dispatch('cerrar-modal-valores', ['success' => false, 'mensaje' => 'El rut ya se encuentra registrado.']);
        }

    }
    public function resetearModal()
    {
        $this->reset(['persona', 'rut', 'nombre_usuario', 'nombres', 'primer_apellido', 'segundo_apellido', 'supervisor', 'empresa', 'centro_costo', 'denominacion', 'titulo_puesto']);
    }
}
