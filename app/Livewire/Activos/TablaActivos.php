<?php

namespace App\Livewire\Activos;

use Livewire\Component;
use App\Models\Activo;

class TablaActivos extends Component
{
    protected $listeners = ['refrescarTabla' => 'cargarActivos'];
    public $activos;

    public function mount()
    {
        $this->cargarActivos();
    }

    private function cargarActivos()
    {
        $this->activos = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation', 'tipoDeActivo')->get();
    }

    public function render()
    {
        return view('livewire.activos.tabla-activos', [
            'activos' => $this->activos,
        ]);
    }
}
?>
