<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class GenericExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithStrictNullComparison
{
    //protected $collections;

    public function __construct(Collection $collections, array $headers = [], array $formats = [])
    {
        $this->collections = $collections;
        $this->headers = $headers;
        $this->formats = $formats;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->collections;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function columnFormats(): array
    {
        return $this->formats;
    }
}
