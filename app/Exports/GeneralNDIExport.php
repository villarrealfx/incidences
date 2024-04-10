<?php

namespace App\Exports;

use App\Models\{Incidence,Period};
use Maatwebsite\Excel\Concerns\{Exportable, ShouldAutoSize, WithHeadings, WithStyles};
use PhpOffice\PhpSpreadsheet\Style\{Alignment,Border};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralNDIExport implements WithHeadings,WithStyles,ShouldAutoSize
{
    use Exportable;

    public $period;

    public function __construct(int $period_id)
    {
        $this->period = Period::find($period_id);
    }

    public function headings(): array
    {
        $incidences = Incidence::wherePeriod_id($this->period->id)->get();

        $months = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

        return [
            ['CENTRO ESTADAL DE DESPACHO SUCRE'],
            ['ESTADO SUCRE - '.$months[$this->period->month-1].' '.$this->period->year],
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
