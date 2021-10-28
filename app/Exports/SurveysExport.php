<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SurveysExport implements FromArray, WithHeadings, ShouldAutoSize {

    protected $survey;
    protected $headings;

    public function __construct(array $survey, array $headings)
    {
        $this->survey = $survey;
        $this->headings = $headings;

    }

    public function array(): array
    {
        return $this->survey;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function headings(): array {
        return $this->headings;

    }
}

