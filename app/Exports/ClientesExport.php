<?php

namespace App\Exports;

use App\Models\Campania;
use App\Models\Cliente;
use App\Models\Evento;
use App\Util\FilterManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientesExport implements WithMultipleSheets
{
    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function sheets(): array
    {
        return [
            0 => new ClienteCampaniaSheet($this->request),
            1 => new ClienteEventoSheet($this->request),
        ];

        

        //return dd($this->request);

        /* $sheets = [1,2];

        return $sheets; */

        /* return collect(range(1,2))->map( {
            return new ClienteCampaniaSheet::all;
        })->toArray(); */

        
    }
}
