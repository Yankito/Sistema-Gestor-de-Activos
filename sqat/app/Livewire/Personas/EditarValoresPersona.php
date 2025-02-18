<?php

namespace App\Livewire\Personas;

use Livewire\Component;
use App\Models\Persona;

class EditarValoresPersona extends Component
{
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

    protected $listeners = ['refreshModalValores', 'cerrarModalValores' => 'resetearModal'];

    public function mount()
    {
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
        $this->dispatch('$refresh');
    }

    public function resetearModal()
    {
        $this->reset(['persona', 'rut', 'nombre_usuario', 'nombres', 'primer_apellido', 'segundo_apellido', 'supervisor', 'empresa', 'centro_costo', 'denominacion', 'titulo_puesto']);
    }
}
