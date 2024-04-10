<?php

namespace App\Exports;

use App\Models\{Circuit, CircuitLoad, Incidence};
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\{Exportable, ShouldAutoSize, WithHeadings, WithStyles};
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\{Border, Alignment};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use stdClass;

class PACExport implements WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    public $day_blocks;
    public $night_blocks;
    public $day_power;
    public $night_power;
    public $block_position = array();
    public $heading_position = array();
    public $total_position = array();
    public $exclude;

    public function __construct(int $day_blocks, float $day_power, int $night_blocks, float $night_power, bool $exclude)
    {
        $this->day_blocks = $day_blocks;
        $this->day_power = $day_power;
        $this->night_blocks = $night_blocks;
        $this->night_power = $night_power;
        $this->exclude = $exclude;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        $circuits = Circuit::wherePriority(false)->whereAttended(true)->get();
        foreach ($circuits as $circuit) {
            $amp = 0;
            $loop = 0;
            $loads = CircuitLoad::whereCircuit_id($circuit->id)->orderBy('datetime', 'DESC')->take(10)->get();
            foreach ($loads as $load) {
                $amp += $load->load;
                $loop++;
            }
            if ($loop) {
                $circuit->load = $amp / $loop;
            } else {
                $circuit->load = 0;
            }
        }
        foreach ($circuits as $circuit) {
            $query = Incidence::whereCircuit_id($circuit->id)->whereSubcause_id(31)->orderBy('date', 'DESC')->orderBy('start', 'DESC')->first();
            $query_one = Incidence::whereCircuit_id($circuit->id)->where('duration','>','01:29:59')->orderBy('date', 'DESC')->orderBy('start', 'DESC')->first();

            if ($query && $query_one) {
                if (($query_one->date > $query->date) || ($query_one->date == $query->date && $query_one->time > $query->time)) {
                    $query = $query_one;
                }
            } elseif (!$query && $query_one) {
                $query = $query_one;
            } elseif (!$query && !$query_one) {
                $query = Incidence::whereCircuit_id($circuit->id)->orderBy('date', 'DESC')->orderBy('start', 'DESC')->first();
            }

            if (!Incidence::whereCircuit_id($circuit->id)->count()) {
                $query = new stdClass();
                $query->date = '0001-01-01';
                $query->start = '00:00:00';
            }

            if ($query && $circuit->load) {

                $carbon = new Carbon($query->date.' '.$query->start, 'America/Caracas');
                $e = true;
                if ($this->exclude) {
                    $e = $carbon->format('Y-m-d') < Carbon::now()->add(-1, 'day')->format('Y-m-d');
                }

                $array['voltage_level'] = $circuit->voltage_level;
                $array['circuit'] = $circuit->name;
                $array['day'] = ucfirst($carbon->isoFormat('dddd'));
                $array['date'] = $carbon->format('d-m-Y');
                $array['time'] = $carbon->toTimeString();
                $array['load'] = $circuit->load * $circuit->voltage_level * sqrt(3) * 0.9 / 1000;
                $array['order'] = strtotime($query->date." ".$query->start);
                $array['used'] = false;
                $array['day'] = $circuit->day;
                $array['night'] = $circuit->night;
                $array['exclude'] = $e;

                $data[] = $array;
            }

        }
        if (isset($data) && count($data)) {
            $results = $this->array_orderby($data, 'order', SORT_ASC);
            return $results;
        } else {
            return array();
        }
    }

    public function headings(): array
    {
        $return = [
            ['PLAN DE ADMINISTRACIÓN DE CARGA TENTATIVO - '.Carbon::now()->format('d-m-Y')],
        ];

        $array = $this->array();

        //Bloque Diurno
        if ($this->day_blocks && $this->day_power) {
            for ($i=0; $i < $this->day_blocks; $i++) {
                $total = 0;
                $return[] = ['BLOQUE DIURNO Nº'.$i+1];
                $this->block_position[] = count($return);
                $return[] = ['CIRCUITO', 'CARGA (MW)'];
                $this->heading_position[] = count($return);
                for ($j=0; $j < count($array); $j++) {
                    if (!$array[$j]['used'] && $array[$j]['day'] && $array[$j]['exclude']) {
                        if ($total + $array[$j]['load'] <= $this->day_power+1) {
                            $return[] = [$array[$j]['voltage_level']." kV ".rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $array[$j]['circuit'])))))))), number_format($array[$j]['load'], 2)];
                            $total += $array[$j]['load'];
                            $array[$j]['used'] = true;
                        }
                    }
                    if ($total >= $this->day_power-0.9) {
                        break;
                    }
                }
                $return[] = ['TOTAL', number_format($total,2)];
                $this->total_position[] = count($return);
            }
        }

        //Bloque Nocturno
        if ($this->night_blocks && $this->night_power) {
            for ($i=0; $i < $this->night_blocks; $i++) {
                $total = 0;
                $return[] = ['BLOQUE NOCTURNO Nº'.$i+1];
                $this->block_position[] = count($return);
                $return[] = ['CIRCUITO', 'CARGA (MW)'];
                $this->heading_position[] = count($return);
                for ($j=0; $j < count($array); $j++) {
                    if (!$array[$j]['used'] && $array[$j]['night'] && $array[$j]['exclude']) {
                        if ($total + $array[$j]['load'] <= $this->night_power+1) {
                            $return[] = [$array[$j]['voltage_level']." kV ".rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $array[$j]['circuit'])))))))), number_format($array[$j]['load'], 2)];
                            $total += $array[$j]['load'];
                            $array[$j]['used'] = true;
                        }
                    }
                    if ($total >= $this->night_power-0.9) {
                        break;
                    }
                }
                $return[] = ['TOTAL', number_format($total,2)];
                $this->total_position[] = count($return);
            }
        }

        $return[] = ['CIRCUITOS DISPONIBLES PARA ROTACIÓN'];
        $this->block_position[] = count($return);
        $return[] = ['CIRCUITO', 'CARGA (MW)'];
        $this->heading_position[] = count($return);

        $total = 0;
        for ($i=0; $i < count($array); $i++) {
            if (!$array[$i]['used']) {
                if ($array[$i]['day'] && !$array[$i]['night']) {
                    $switch = "DIURNO";
                } elseif (!$array[$i]['day'] && $array[$i]['night']) {
                    $switch = "NOCTURNO";
                } elseif ($array[$i]['day'] && $array[$i]['night']) {
                    $switch = "AMBOS";
                }

                $return[] = [$array[$i]['voltage_level']." kV ".rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $array[$i]['circuit'])))))))), number_format($array[$i]['load'], 2), $switch];
                $total += $array[$i]['load'];
            }
        }

        $return[] = ['TOTAL', number_format($total, 2)];
        $this->total_position[] = count($return);

        return $return;
    }

    public function styles(Worksheet $sheet)
    {
        $position_last = count($this->headings()[2]);

        $column = Coordinate::stringFromColumnIndex($position_last);
        $cells = "A1:{$column}1";
        $sheet->mergeCells($cells);
        $sheet->getStyle($cells)->getFont()->setBold(true);
        $sheet->getStyle($cells)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cells)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        foreach ($this->block_position as $position) {
            $cells = "A{$position}:{$column}{$position}";
            $sheet->mergeCells($cells);
            $sheet->getStyle($cells)->getFont()->setBold(true);
            $sheet->getStyle($cells)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($cells)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
        foreach ($this->heading_position as $position) {
            $cells = "A{$position}:{$column}{$position}";
            $sheet->getStyle($cells)->getFont()->setBold(true);
        }
        foreach ($this->total_position as $position) {
            $cells = "A{$position}:{$column}{$position}";
            $sheet->getStyle($cells)->getFont()->setBold(true);
        }

        $sheet->getStyle('A1:'.$column.count($this->array())+($this->day_blocks*3)+($this->night_blocks*3)+1+3)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
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
