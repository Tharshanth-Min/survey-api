<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\Queue\ShouldQueue;

class SurveysExport implements FromArray, WithHeadings, ShouldAutoSize, ShouldQueue {

    use Exportable;

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


    public function headings(): array {
        return $this->headings;

    }
}
