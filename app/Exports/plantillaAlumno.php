<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class plantillaAlumno implements WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        $currentDate = now()->format('Y-m-d');

        $cabecera = [
            'Nombre *',
            'Apellidos *',
            'Fecha Nacimiento',
            'Correo *',
            'Celular *',
            'Procedencia *',
            'Curso de Interes',

        ];

        $guia = [
            'Laura Elizabeth',
            'Aguilar',
            $currentDate,
            'Correo@gmail.com',
            '912345678',
            'Órganico',
            'Diseño capilar',
        ];

        return array($cabecera,$guia);
    }


     //darle stylo a las columnas o filas
    public function styles(Worksheet $sheet)
    {
        return [
        1    => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'ffffff'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => '835da0'],
                ],                
            ],                
        ];
    }

    //dar un tamaño de espacio automatico a cada columna del excel
    public function AutoSize(): array
    {
        return [];
    }

    // Definir eventos para agregar la validación de datos
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Aplicar formato personalizado para fechas en la columna "I"
                $sheet->getStyle('C2:C900')
                    ->getNumberFormat()
                    ->setFormatCode('yyyy-mm-dd');
                
            },
        ];
    }

    /**
     * Aplica la validación de datos a un rango específico de celdas.
     *
     * @param Worksheet $sheet
     * @param string $column
     * @param string $formula1
     * @param int $startRow
     * @param int $endRow
     * @return void
     */
    private function applyDataValidation(Worksheet $sheet, string $column, string $formula1, int $startRow, int $endRow): void
    {
        $validation = new DataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowDropDown(true);
        $validation->setFormula1($formula1);

        for ($row = $startRow; $row <= $endRow; $row++) {
            $cell = $column . $row;
            $sheet->getCell($cell)->setDataValidation(clone $validation);
        }
    }

    
}
