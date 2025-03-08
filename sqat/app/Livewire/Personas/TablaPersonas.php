<?php

namespace App\Livewire\Personas;

use Livewire\Component;
use App\Models\Persona;

class TablaPersonas extends Component
{
    public $personas;
    protected $listeners = ['eventoOrdenarPersonas' => 'ordenarPor'];


    public $sortColumn = 'user'; // Columna por defecto
    public $sortDirection = 'asc'; // Dirección por defecto

    public function mount()
    {
        // Cargar los activos cuando se monte el componente
        $this->cargarPersonas();
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

    private function cargarPersonas()
    {
        $this->personas = Persona::with('ubicacionRelation')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->get();
    }


    public function render()
    {
        return view('livewire.personas.tabla-personas');
    }
}
