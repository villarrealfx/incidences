<?php

namespace App\Exports;

use App\Models\{Incidence, Period,ServiceCenter};
use Maatwebsite\Excel\Concerns\{Exportable,FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings,WithMapping,WithStyles};
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\{Alignment,NumberFormat,Border};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NDIExport implements FromCollection,WithHeadings,WithMapping,WithColumnFormatting,ShouldAutoSize,WithStyles,WithEvents
{
    use Exportable;

    public $cs;
    public $period;
    public $distribution_count;

    public function __construct(int $cs_id, int $period_id)
    {
        $this->cs = ServiceCenter::find($cs_id); // Datos del Centro de Servicio
        $this->cs->ndi = 0;
        $this->cs->programadas = 0;
        $this->cs->mantenimiento = 0;
        $this->cs->lluvia = 0;
        $this->cs->componentes = 0;
        $this->cs->vegetacion = 0;
        $this->cs->otros = 0;
        $this->cs->percent = 0;
        $this->period = Period::find($period_id); // Período a consultar
        $this->distribution_count = Incidence::whereSystem_id(1)->wherePeriod_id($this->period->id)->count();
    }

    /**
    * @return \Illuminate\Support\Headings
    */
    public function headings(): array
    {
        $months = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre',
        ];

        return [
            ['Análisis de Interrupciones (NDI) en Distribución Sucre por Centros de Servicio '.$months[$this->period->month-1].' '.$this->period->year],
            ['Centro de Servicio Tipo "'.$this->cs->type.'" '.$this->cs->name],
            [
                'Subestación',
                'Circuito',
                'NDI',
                'Programadas',
                'Falta de Mantenimiento',
                'Lluvias y Atmosféricas',
                'Componentes Dañados',
                'Vegetación',
                'Otros',
                '%',
            ],
        ];
    }

    /**
    * @return \Illuminate\Support\Maping
    */
    public function map($circuit): array
    {
        if ($this->distribution_count) {
            $this->cs->ndi += $circuit->ndi;
            $this->cs->programadas += $circuit->programadas;
            $this->cs->mantenimiento += $circuit->mantenimiento;
            $this->cs->lluvia += $circuit->lluvia;
            $this->cs->componentes += $circuit->componentes;
            $this->cs->vegetacion += $circuit->vegetacion;
            $this->cs->otros += $circuit->ndi - $circuit->programadas - $circuit->mantenimiento - $circuit->lluvia - $circuit->componentes - $circuit->vegetacion;
            $this->cs->percent += ($circuit->ndi * 100) / $this->distribution_count;
            return [
                rtrim(str_replace('(', '', str_replace(')', '', str_replace('115', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $circuit->substation->name))))))))) ." ". $circuit->substation->voltage_level, //Subestación (A)
                $circuit->voltage_level ." kV ". rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $circuit->name)))))))), // Circuito (B)
                strval($circuit->ndi), // NDI (C)
                strval($circuit->programadas), // Programadas (D)
                strval($circuit->mantenimiento), // Falta de Mantenimiento (E)
                strval($circuit->lluvia), // Lluvias y Atmosféricas (F)
                strval($circuit->componentes), // Componentes Dañados (G)
                strval($circuit->vegetacion), // Vegetación (H)
                strval($circuit->ndi - $circuit->programadas - $circuit->mantenimiento - $circuit->lluvia - $circuit->componentes - $circuit->vegetacion), // Otros (I)
                ($circuit->ndi * 100) / $this->distribution_count, // % (J)
            ];
        }
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $circuits = $this->cs->circuits;
        foreach ($circuits as $circuit) {
            $circuit->ndi = $circuit->incidences->where('system_id','=',1)->where('period_id','=',$this->period->id)->count();
            $circuit->programadas = $circuit->incidences->where('system_id','=',1)->where('period_id','=',$this->period->id)->where('cause_id','=',6)->count();
            $circuit->mantenimiento = $circuit->incidences->where('system_id','=',1)->where('period_id','=',$this->period->id)->where('cause_id','=',1)->count();
            $circuit->lluvia = $circuit->incidences->where('system_id','=',1)->where('period_id','=',$this->period->id)->where('cause_id','=',2)->count();
            $circuit->componentes = $circuit->incidences->where('system_id','=',1)->where('period_id','=',$this->period->id)->where('cause_id','=',3)->count();
            $circuit->vegetacion = $circuit->incidences->where('system_id','=',1)->where('period_id','=',$this->period->id)->where('cause_id','=',4)->count();
        }

        return $circuits->where('ndi','>',0)->sortByDesc('ndi');
    }

    public function styles(Worksheet $sheet)
    {
        $position_last = count($this->headings()[2]);

        $column = Coordinate::stringFromColumnIndex($position_last);
        $cells = "A1:{$column}1";
        $sheet->mergeCells($cells);
        $cells = "A2:{$column}2";
        $sheet->mergeCells($cells);
        $cells = "A1:{$column}3";

        $sheet->getStyle($cells)->getFont()->setBold(true);
        $sheet->getStyle($cells)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cells)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:J'.$this->collection()->count()+3+3)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }

    public function registerEvents(): array
{
    return [
        // Handle by a closure.
        AfterSheet::class => function(AfterSheet $event) {

            // last column as letter value (e.g., D)
            $last_column = Coordinate::stringFromColumnIndex(count($this->headings()[2]));

            // calculate last row + 1 (total results + header rows + column headings row + new row)
            $last_row = count($this->collection()) + 4;

            // set up a style array for cell formatting
            $style_text_center = [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ];

            // merge cells for full-width
            $event->sheet->mergeCells(sprintf('A%d:B%s',$last_row,$last_row));
            $event->sheet->mergeCells(sprintf('A%d:A%s',$last_row+1,$last_row+2));
            $event->sheet->mergeCells(sprintf('B%d:%s%d',$last_row+1,$last_column,$last_row+2));

            // assign cell values
            $event->sheet->setCellValue(sprintf('A%d', $last_row), 'POR DISTRIBUCIÓN');
            $event->sheet->setCellValue(sprintf('C%d', $last_row), $this->cs->ndi);
            $event->sheet->setCellValue(sprintf('D%d', $last_row), $this->cs->programadas);
            $event->sheet->setCellValue(sprintf('E%d', $last_row), $this->cs->mantenimiento);
            $event->sheet->setCellValue(sprintf('F%d', $last_row), $this->cs->lluvia);
            $event->sheet->setCellValue(sprintf('G%d', $last_row), $this->cs->componentes);
            $event->sheet->setCellValue(sprintf('H%d', $last_row), $this->cs->vegetacion);
            $event->sheet->setCellValue(sprintf('I%d', $last_row), $this->cs->otros);
            $event->sheet->setCellValue(sprintf('J%d', $last_row), number_format($this->cs->percent,2,','));

            $event->sheet->setCellValue(sprintf('A%d', $last_row+1), 'NOTA');
            $event->sheet->setCellValue(sprintf('B%d',$last_row+1),'LAS INTERRUPCIONES EN LOS CIRCUITOS DEL CENTRO DE SERVICIO "'.$this->cs->type.'" '.$this->cs->name.', REPRESENTAN EL '.number_format($this->cs->percent,2,',').'% DEL TOTAL DE LAS INTERRUPCIONES POR DISTRIBUCIÓN ('.$this->distribution_count.'), DONDE LAS CAUSAS MÁS RELEVANTES SON: FALTA DE MANTENIMIENTO, COMPONENTES DAÑADOS, VEGETACIÓN, ENTRE OTROS.');

            // assign cell styles
            $event->sheet->getStyle(sprintf('A%d',$last_row+1))->applyFromArray($style_text_center);
            $event->sheet->getStyle(sprintf('B%d',$last_row+1))->applyFromArray($style_text_center);
            $event->sheet->getStyle(sprintf('A%d:J%s',$last_row,$last_row))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $event->sheet->getStyle(sprintf('B%d:%s%d',$last_row+1,$last_column,$last_row+2))->getAlignment()->setWrapText(true);
            $event->sheet->getStyle('A'.($this->collection()->count()+3+1).':J'.($this->collection()->count()+3+3))->getFont()->setBold(true);
            $event->sheet->getStyle('A'.($this->collection()->count()+3+2).':J'.($this->collection()->count()+3+3))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $event->sheet->getStyle('A'.($this->collection()->count()+3+2).':J'.($this->collection()->count()+3+3))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        },
    ];
}
}
