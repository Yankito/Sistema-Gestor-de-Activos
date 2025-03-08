<?php

namespace App\Livewire\Activos;

use Livewire\Component;
use App\Models\Activo;

class TablaActivos extends Component
{
    protected $listeners = ['eventoOrdenarPersonas' => 'ordenarPor'];
    public $activos;
    public $sortColumn = 'nro_serie'; // Columna por defecto
    public $sortDirection = 'asc'; // Dirección por defecto

    public function mount()
    {
        $this->cargarActivos();
    }

    public function ordenarPor($columna)
    {
        //dd($columna);
        // Si la columna seleccionada es la misma, cambia la dirección
        if ($this->sortColumn === $columna) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Si se cambia de columna, inicia en orden ascendente
            $this->sortColumn = $columna;
            $this->sortDirection = 'asc';
        }

        // Recargar los datos ordenados
        $this->cargarActivos();
    }

    private function cargarActivos()
    {
        $this->activos = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation', 'tipoDeActivo')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->get();
    }

    public function render()
    {
        return view('livewire.activos.tabla-activos', [
            'activos' => $this->activos,
        ]);
    }
}
