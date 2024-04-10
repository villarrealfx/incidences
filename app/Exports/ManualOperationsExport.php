<?php

namespace App\Exports;

use App\Models\Incidence;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\{Exportable, FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping, WithStyles};
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\{Alignment as StyleAlignment, NumberFormat, Border};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ManualOperationsExport implements FromCollection,WithHeadings,WithMapping,WithColumnFormatting,ShouldAutoSize,WithStyles
{
    use Exportable;

    public $start; // Fecha de inicio para búsqueda
    public $finish; // Fecha de fin para búsqueda

    public function __construct(string $start, string $finish)
    {
        $this->start = $start;
        $this->finish = $finish;
    }
    /**
    * @return \Illuminate\Support\Headings
    */
    public function headings(): array
    {
        if ($this->start == $this->finish) {
            $date = $this->start;
        } else {
            $date = $this->start.' al '.$this->finish;
        }

        return [
            ['Operación Manual de Circuitos del '.$date ],
            [
                'Subestación',
                'Circuito',
                'Nomenclatura',
                'Tensión',
                'Carga',
                'Fecha de apertura',
                'Hora de apertura',
                'Fecha de cierre',
                'Hora de cierre',
                'Tiempo',
                'Tipo',
                'Observación',
            ]
        ];
    }
    /**
    * @return \Illuminate\Support\Maping
    */
    public function map($incidence): array
    {
        if ($incidence->circuit->voltage_level == 13.8) {
            $prefix = "D";
        } elseif ($incidence->circuit->voltage_level == 34.5) {
            $prefix = "B";
        }

        $real_power = ($incidence->load * $incidence->circuit->voltage_level * sqrt(3) * 0.9) / 1000;

        $explode = explode(":", $incidence->duration);
        $start = Carbon::parse($incidence->date.' '.$incidence->start);
        $finish = Carbon::parse($incidence->date.' '.$incidence->start);
        $finish->addSeconds($explode[2]);
        $finish->addMinutes($explode[1]);
        $finish->addHours($explode[0]);

        if ($incidence->cause_id == 6) {
            $type = "PROGRAMADO";
        } else {
            $type = "POR EMERGENCIA";
        }

        return [
            rtrim(str_replace('(', '', str_replace(')', '', str_replace('115', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $incidence->circuit->substation->name))))))))), // Subestación (A)
            rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $incidence->circuit->name)))))))), // Circuito (B)
            $prefix . '-' . $incidence->circuit->breaker, // Nomenclatura (C)
            $incidence->circuit->voltage_level, // Tensión (D)
            $real_power, // Carga (E)
            $start->format('d/m/Y'), // Fecha de apertura (F)
            $start->toTimeString(), // Hora de apertura (G)
            $finish->format('d/m/Y'), // Fecha de cierre (H)
            $finish->toTimeString(), // Hora de cierre (I)
            $incidence->duration, // Tiempo (J)
            $type, // Tipo (K)
            $incidence->observations, // Observación (L)
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return (new Incidence)->manualOperations()->whereBetween('date', [$this->start, $this->finish])->where('subcause_id', '!=', 31);
    }

    public function styles(Worksheet $sheet)
    {
        $position_last = count($this->headings()[1]);

        $column = Coordinate::stringFromColumnIndex($position_last);
        $cells = "A1:{$column}1";
        $sheet->mergeCells($cells);
        $cells = "A1:{$column}2";

        $sheet->getStyle($cells)->getFont()->setBold(true);
        $sheet->getStyle($cells)->getAlignment()->setVertical(StyleAlignment::VERTICAL_CENTER);
        $sheet->getStyle($cells)->getAlignment()->setHorizontal(StyleAlignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:L'.$this->collection()->count()+2)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }
}
