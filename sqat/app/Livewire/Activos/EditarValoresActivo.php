<?php

namespace App\Livewire\Activos;

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
    public $nro_serie;
    public $marca;
    public $modelo;
    public $precio;
    public $tipo_de_activo;

    protected $listeners = ['refreshModalValores', 'cerrarModalValores' => 'resetearModal', 'actualizarUbicacion', 'setResponsable' => 'actualizarResponsable'];

    public function mount()
    {
        $this->personas = Persona::all();
        $this->ubicaciones = Ubicacion::all();

        if($this->activo != NULL) {
            $this->responsable_de_activo = $this->activo->responsable_de_activo;
            $this->ubicacion = $this->activo->responsable_de_activo->ubicacion;
            $this->nro_serie = $this->activo->nro_serie;
            $this->marca = $this->activo->marca;
            $this->modelo = $this->activo->modelo;
            $this->precio = $this->activo->precio;
            $this->tipo_de_activo = $this->activo->tipo_de_activo;
        }
    }
    public function render()
    {
        if(isset($this->activo)) {
            $this->dispatch('modal-valores-cargado');
            return view('livewire.activos.editar-valores-activo');
        } else {
            return view('livewire.activos.editar-valores-activo');
        }
    }

    public function refreshModalValores($activo)
    {
        $this->activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')->findOrFail($activo['id']);
        $this->responsable_de_activo = $this->activo->responsable_de_activo;
        $this->ubicacion = $this->activo->ubicacion;
        $this->nro_serie = $this->activo->nro_serie;
        $this->marca = $this->activo->marca;
        $this->modelo = $this->activo->modelo;
        $this->precio = $this->activo->precio;
        $this->tipo_de_activo = $this->activo->tipo_de_activo;
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
        $activo->nro_serie = $this->nro_serie;
        $activo->marca = $this->marca;
        $activo->modelo = $this->modelo;
        $activo->precio = $this->precio;
        $activo->tipo_de_activo = $this->tipo_de_activo;
        $activo->responsable_de_activo = $this->responsable_de_activo;
        $activo->usuario_de_activo = $this->responsable_de_activo;
        //dd($this->responsable_de_activo);
        try {
            $activo->update();
            $this->dispatch('refreshRow', $activo->id);
            $this->dispatch('cerrar-modal-valores', ['success' => true, 'mensaje' => 'Los cambios se han guardado correctamente.']);
        } catch (\Exception $e) {
            $this->dispatch('cerrar-modal-valores', ['success' => false, 'mensaje' => 'El número de serie ya se encuentra registrado.']);
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
        $this->dispatch('$refresh');
    }

    public function resetearModal()
    {
        $this->reset(['activo', 'responsable_de_activo', 'ubicacion']);
    }

    public function actualizarResponsable($data)
    {
        $this->responsable_de_activo = $data;
        $this->dispatch('actualizarUbicacion', $data);
        $this->dispatch('$refresh');
    }

}
