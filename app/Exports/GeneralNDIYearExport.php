<?php

namespace App\Exports;

use App\Models\{Incidence,Period};
use Maatwebsite\Excel\Concerns\{Exportable, ShouldAutoSize, WithHeadings, WithStyles};
use PhpOffice\PhpSpreadsheet\Style\{Alignment,Border};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralNDIYearExport implements WithHeadings,WithStyles,ShouldAutoSize
{
    use Exportable;

    public $year;

    public function __construct(int $year)
    {
        $this->year = $year;
    }

    public function headings(): array
    {
        $incidences = Incidence::whereBetween('date', [$this->year.'-01-01', $this->year.'-12-31'])->get();

        return [
            ['CENTRO ESTADAL DE DESPACHO SUCRE'],
            ['ESTADO SUCRE - AÑO '.$this->year],
            ['SISTEMA', 'NDI'],
            ['DISTRIBUCIÓN', $incidences->where('system_id', '=', 1)->count()],
            ['TRANSMISIÓN', $incidences->where('system_id', '=', 2)->count()],
            ['TOTAL GENERAL', $incidences->count()],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $cells = "A1:B1";
        $sheet->mergeCells($cells);
        $cells = "A2:B2";
        $sheet->mergeCells($cells);
        $cells = "A1:B6";

        $sheet->getStyle($cells)->getFont()->setBold(true);
        $sheet->getStyle($cells)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cells)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:B6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }
}
