<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activo;
use App\Models\Persona;
use App\Models\Ubicacion;
use App\Models\Estado;

class DashboardFiltros extends Component
{
    protected $listeners = ['actualizarAtributo'];
    public $cantidadActivos;
    public $filtro;
    public $conteoValores;
    public $atributos;

    public function mount()
    {
        $this->cantidadActivos = Activo::count();
        $this->atributos = $this->obtenerAtributos();
    }
    public function render()
    {
        $cantidadPersonas = Persona::count();
        $cantidadUbicaciones = Ubicacion::count();

        $activos = Activo::all();
        $ubicaciones = Ubicacion::all();
        // Pasar el usuario a la vista
        return view('livewire.dashboard-filtros',compact(
        'cantidadPersonas','cantidadUbicaciones','activos',
        'ubicaciones'));
    }

    public function actualizarAtributo($atributo)
    {
        if (in_array($atributo, $this->atributos)) {
            $valores = Activo::pluck($atributo)->toArray();
            $this->conteoValores = array_count_values($valores);
            if($atributo === "estado"){
                foreach($this->conteoValores as $key => $value){
                    $estado = Estado::find($key);
                    $this->conteoValores[$estado->nombre_estado] = $this->conteoValores[$key];
                    unset($this->conteoValores[$key]);
                }
            }
            else if($atributo === "ubicacion"){
                foreach($this->conteoValores as $key => $value){
                    $ubicacion = Ubicacion::find($key);
                    $this->conteoValores[$ubicacion->sitio] = $this->conteoValores[$key];
                    unset($this->conteoValores[$key]);
                }
            }
            $this->filtro = $atributo;
        }

        $this->dispatch('$refresh');
    }

    public function obtenerAtributos(){
        $atributos = array_keys(Activo::first()->getAttributes());
        $atributos = array_diff($atributos, ['id', 'nro_serie', 'usuario_de_activo',
        'responsable_de_activo','precio','justificacion_doble_activo','created_at', 'updated_at']);
        return $atributos;
    }

    public function calcularActivosPorEstados(){
        $estados = [];
        $estadosDisponibles = Estado::all();
        foreach ($estadosDisponibles as $estado) {
            $estados[$estado->nombre_estado] = [
            'cantidad' => Activo::where('estado', $estado->id)->count(),
            'descripcion' => $estado->descripcion
            ];
        }
        return $estados;
    }

}
