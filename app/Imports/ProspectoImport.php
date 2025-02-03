<?php

namespace App\Imports;

use App\Models\Prospecto;
use App\Models\Importacion;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProspectoImport implements ToModel, WithStartRow
{

    private $rows = 0;
    public $errors = [];
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
                throw new Exception('El campo "Correo" está vacío');
            }

            // Verifica si el email ya está registrado
            if (Prospecto::where('correo', $row[3])->exists()) {
                throw new Exception('Correo duplicado.');
            }

            $idCarrera = null; // Valor por defecto en caso de no tener carrera
            if (!empty($row[6])) {
                $nombreCarrera = $row[6]; // Nombre de la carrera desde el Excel
                $idCarrera = \App\Models\Carrera::where('nombre', $nombreCarrera)->value('id'); // Buscar el id de la carrera
            }
            
            // Verificar errores
            if (!empty($this->errors)) {
                return null; 
            }


            return  new Prospecto([
                'nombre' => $row[0], 
                'apellido' => $row[1], 
                'fecha_nac' => $row[2], 
                'correo' => $row[3], 
                'telefono' => $row[4], 
                'procedencia' => $row[5],             
                'carrera_id' => $idCarrera, 
                'fecha_registro' => Carbon::now(),
                'user_id' => auth()->user()->id,
            ]);

        } catch (Exception $e) {
            // Almacenar el error y la fila en la que ocurrió
            $this->errors[] = [
                'row' => $this->rows  + 1,
                'message' => $e->getMessage()
            ];
            return null;
        }
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
