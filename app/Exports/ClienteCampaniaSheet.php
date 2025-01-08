<?php

namespace App\Exports;

use App\Models\Campania;
use App\Models\Cliente;
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

class ClienteCampaniaSheet implements FromCollection,WithStyles, ShouldAutoSize, WithMapping, WithHeadings, WithTitle
{
    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $filters = FilterManager::getFilters($this->request, new Cliente());
        $filtersCampania = FilterManager::getFilters($this->request, new Campania());
        $consultas =  Cliente::where($filters)
        ->with('eventoCliente','campania','distrito')
        /*Cmbiar momentaneo*/
        /* ->where('idcampania','=',10) */
        ->whereHas('campania', function ($query) use ($filtersCampania) {
            $query->where($filtersCampania);
        });

        if($this->request->exists('keyimportado')){
            $consultas->whereNotNull('uuidimportacion');
        }

        if ($this->request->has('celinvalid')) {
            $consultas->where('idtipoatencion', '!=', 4);
        }

         //filtro de fechas input buscar por fechas
         if ($this->request->exists('fechadesde')) {
            $fechadesde = Carbon::createFromFormat('d-m-Y', $this->request->fechadesde)->toDateString();
            $consultas->whereDate('fecharegistro', '>=', $fechadesde); //pasamos como consulta el campo fecharegistro
        }
        if ($this->request->exists('fechahasta')) {
            $fechahasta = Carbon::createFromFormat('d-m-Y', $this->request->fechahasta)->toDateString();
            $consultas->whereDate('fecharegistro', '<=', $fechahasta); //pasasmos como consulta el campos fecharegistro
        } 
        
        return $consultas
        ->orderBy('idcliente', 'DESC')
        ->get();
        //return Cliente::all();
    }

    /**
    * @var Invoice $invoice
    */
    public function map($model): array
    {        

        return [
            $model->idcliente,
            $model->nombres,
            $model->apellidopaterno,
            $model->apellidomaterno,
            $model->email,
            $model->telefono,
            $model->fecha,
            $model->asistencianame,
            $model->fechapresencia,          
            $model->carrera,
            $model->colegio,
            $model->anioegreso,
            $model->procedencia,
            $model->utm,
            $model->campaign_content,
            $model->campaign_medium,
            $model->campaign_name,
            $model->campaign_source,
            $model->campaign_term,
            $model->statename,
            $model->idcampania,
            $model->campania->nombrecampania,
        ];
        //return dd($model);
    }

    public function headings(): array
    {
        return [
            'Codigo',
            'Nombres',
            'Apellido Paterno',
            'Apellido Materno',
            'Email',
            'Telefono',
            'Fecha Registro',
            'Asistencia',
            'Fecha Asistencia',        
            'Carrera',            
            'Sede',
            'Año de Egreso',
            'Procedencia',
            'Utm',
            'Campaign_content',
            'Campaign_medium',
            'Campaign_name',
            'Campaign_source',
            'Campaign_term',
            'Estado',
            'idcampania',
            'Campaña',
        ];
    }

    //darle stylo a las columnas o filas
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
        return "Campaña";
    }
}
