<?php

namespace App\Imports;

use App\Models\Cliente;
use App\Models\Evento;
use App\Models\EventoCliente;
use App\Models\Importacion;
use Exception;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ClientesImport implements ToModel, WithStartRow
{

    private $rows = 0;
    public $errors = [];

    public function __construct(Importacion $importacion, $evento){
        $this->importacion = $importacion; 
        $this->evento = $evento;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        
        if (!isset($row[0])) {
            return null;
        }

        ++$this->rows;

        try {
            // Verificar si la fila tiene un valor en la columna requerida
            if (empty($row[3])) { 
                throw new Exception('El campo "email" está vacío');
            }

            // Verifica si el email ya está registrado
            if (Cliente::where('email', $row[3])->exists()) {
                throw new Exception('Email duplicado.');
            }

            // Verificar errores
            if (!empty($this->errors)) {
                return null; 
            }

            return  new Cliente([
                'nombres' => $row[0], 
                'apellidopaterno' => $row[1], 
                'apellidomaterno' => $row[2], 
                'email' => $row[3], 
                'telefono' => $row[4], 
                //'idevento' => $row[5], 
                'carrera' => $row[5],             
                'colegio' => $row[6], 
                'anioegreso' => $row[7],
                'anioegreso' => $row[7],
                'uuidimportacion' => $this->importacion->uuidimportacion,
                'idcampania' => $this->importacion->idcampania,
            ]);

    } catch (Exception $e) {
        // Almacenar el error y la fila en la que ocurrió
        $this->errors[] = [
            'row' => $this->rows  + 1,
            'message' => $e->getMessage()
        ];
        return null;
    }

        /* $eventos = new EventoCliente([
            'idcliente' => $cliente->idcliente,
            'idevento' => $this->evento,            
        ]); */
        
        //return dd([$cliente->idcliente, $eventos]);

        //return dd($this->idevento);
    }
    public function startRow(): int
    {
        return 2;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }


}
