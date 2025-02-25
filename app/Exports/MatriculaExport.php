<?php

namespace App\Exports;

use App\Models\Matricula;
use App\Util\FilterManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MatriculaExport implements FromCollection,WithStyles, ShouldAutoSize, WithMapping, WithHeadings, WithTitle
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $filters = FilterManager::getFilters($this->request, new Matricula());

        $query = Matricula::where($filters)
        ->with('user','carrera','aula','alumno','ciclo','turno');

        if ($this->request->exists('fechadesde')) {
            $fechadesde = Carbon::createFromFormat('d-m-Y', $this->request->fechadesde)->toDateString();
            $query->whereDate('fecha', '>=', $fechadesde);
        }

        if ($this->request->exists('fechahasta')) {
            $fechahasta = Carbon::createFromFormat('d-m-Y', $this->request->fechahasta)->toDateString();
            $query->whereDate('fecha', '<=', $fechahasta);
        }

        $matricula = $query->orderBy('id', 'DESC')->get();

        return $matricula;
    }

    public function map($model): array
    {        

        return [
            $model->id,
            $model->alumno->nombre,
            $model->alumno->apellido,
            $model->alumno->dni,
            $model->alumno->celular,
            $model->detalle,
            $model->aula->nombre_aula ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            'Codigo Matricula',
            'Nombres',
            'Apellidos',          
            'dni',
            'Telefono',
            'Matricula',
            'Aula',
            
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
           1    => [
               'font' => [
                   'bold' => true,                   
               ],          
           ],                
        ];
    }

    //dar un tamaño de espacio automatico a cada columna del excel
    public function AutoSize(): array
    {
        return [];
    }

    public function title(): string
    {
        return "Matricula - Alumnos";
    }
}
