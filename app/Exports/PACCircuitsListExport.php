<?php

namespace App\Exports;

use App\Models\{Incidence, Circuit};
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\{Exportable, FromArray, FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping, WithStyles};
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\{Alignment as StyleAlignment, NumberFormat, Border};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PACCircuitsListExport implements FromArray,WithHeadings,WithMapping,WithColumnFormatting,ShouldAutoSize,WithStyles
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Headings
    */
    public function headings(): array
    {
        return [
            ['Listado de Circuitos para el Plan de Administración de Carga' ],
            [
                'Tensión',
                'Circuito',
                'Día',
                'Fecha',
                'Hora',
                'Carga',
            ]
        ];
    }
    /**
    * @return \Illuminate\Support\Maping
    */
    public function map($result): array
    {
        $carbon = new Carbon($result['date'].' '.$result['time'], 'America/Caracas');
        $real_power = number_format($result['load'] * $result['voltage_level'] * sqrt(3) * 0.9 / 1000, 2);

        return [
            $result['voltage_level']." kV", // Tensión (A)
            rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $result['circuit'])))))))), // Circuito (B)
            ucfirst($carbon->isoFormat('dddd')),
            $carbon->format('d-m-Y'), // Fecha de apertura (D)
            $carbon->toTimeString(), // Hora de apertura (E)
            $real_power." MW", // Fecha de cierre (F)
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }
    /**
    * @return \Illuminate\Support\Array
    */
    public function array(): array
    {
        $circuits = Circuit::get();
        foreach ($circuits as $circuit) {
            $query = Incidence::whereCircuit_id($circuit->id)->whereSubcause_id(31)->orderBy('date', 'DESC')->orderBy('start', 'DESC')->first();
            if ($query) {

                $array['voltage_level'] = $circuit->voltage_level;
                $array['circuit'] = $circuit->name;
                $array['date'] = $query->date;
                $array['time'] = $query->start;
                $array['load'] = $query->load;
                $array['order'] = strtotime($query->date." ".$query->time);

                $data[] = $array;
            }

            $results = $this->array_orderby($data, 'order', SORT_ASC);
        }

        return $results;
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
        $sheet->getStyle('A1:F'.count($this->array())+2)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }

    public function array_orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
                }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }
}
