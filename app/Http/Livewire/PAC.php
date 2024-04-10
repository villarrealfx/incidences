<?php

namespace App\Http\Livewire;

use App\Models\{Circuit, CircuitLoad, Incidence};
use Carbon\Carbon;
use Livewire\Component;
use stdClass;
use Telegram\Bot\Laravel\Facades\Telegram;

class PAC extends Component
{
    public $day_blocks;
    public $night_blocks;
    public $day_power;
    public $night_power;
    public $total = 0;
    public $switch = true;
    public $results = array();
    public $exclude = true;

    public function render()
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
            $this->results = $results;
        }
        return view('livewire.p-a-c');
    }

    public function toogleSwitch()
    {
        $this->switch = !$this->switch;
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

    public function sendPACMessage()
    {
        $html = '<b>C.E.D. SUCRE</b>'.PHP_EOL;
        $html .= '<b>FECHA: </b>'.Carbon::now()->format('d/m/Y').PHP_EOL.PHP_EOL;
        $html .= '<b>BLOQUES DEL PLAN DE ADMINISTRACIÓN DE CARGA</b>'.PHP_EOL.PHP_EOL;

        //Bloque Diurno
        if ($this->day_blocks && $this->day_power) {
            $html .= '<b>DIURNO: DE 12 A 16</b>'.PHP_EOL;
            for ($i=0; $i < $this->day_blocks; $i++) {
                $total = 0;
                $html .= '<b>BLOQUE DIURNO Nº'.($i+1).' - '.$this->day_power.'MW</b>'.PHP_EOL;
                for ($j = 0; $j < count($this->results); $j++) {
                    if (!$this->results[$j]['used'] && $this->results[$j]['day'] && $this->results[$j]['exclude']) {
                        if ($total + $this->results[$j]['load'] <= $this->day_power+1) {
                            $html .= $this->results[$j]['voltage_level']." kV ".rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $this->results[$j]['circuit'])))))))).PHP_EOL;
                            $total += $this->results[$j]['load'];
                            $this->results[$j]['used'] = true;
                        }
                    }
                    if ($total >= $this->day_power-0.9) {
                        break;
                    }
                }
            }
        }

        //Bloque Nocturno
        if ($this->night_blocks && $this->night_power) {
            $html .= PHP_EOL;
            $html .= '<b>NOCTURNO: DE 18 A 22</b>'.PHP_EOL;
            for ($i=0; $i < $this->night_blocks; $i++) {
                $total = 0;
                $html .= '<b>BLOQUE NOCTURNO Nº'.($i+1).' - '.$this->night_power.'MW</b>'.PHP_EOL;
                for ($j = 0; $j < count($this->results); $j++) {
                    if (!$this->results[$j]['used'] && $this->results[$j]['night'] && $this->results[$j]['exclude']) {
                        if ($total + $this->results[$j]['load'] <= $this->night_power+1) {
                            $html .= $this->results[$j]['voltage_level']." kV ".rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $this->results[$j]['circuit'])))))))).PHP_EOL;
                            $total += $this->results[$j]['load'];
                            $this->results[$j]['used'] = true;
                        }
                    }
                    if ($total >= $this->night_power-0.9) {
                        break;
                    }
                }
            }
        }

        $html .= PHP_EOL;
        $html .= '<b>INFORMACIÓN ESTRICTAMENTE CONFIDENCIAL. PROHIBIDA SU DIVULGACIÓN.</b>'.PHP_EOL;
        $html .= PHP_EOL.'<b>LOS MENCIONADOS BLOQUES PUEDEN ESTAR SUJETOS A CAMBIOS PREVIO ACUERDO</b>';

        $chat_id = config('telegram.pac_group', '-4008707595'); //Grupo del PAC

        $response = Telegram::bot('mybot')->sendMessage([
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $html,
        ]);
    }

    public function toogleExclude()
    {
        $this->exclude = !$this->exclude;
    }
}
