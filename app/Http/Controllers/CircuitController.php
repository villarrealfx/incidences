<?php

namespace App\Http\Controllers;

use App\Models\Circuit;
use App\Models\Incidence;
use Illuminate\Http\Request;
use App\Exports\{PACCircuitsListExport, PACExport};
use Carbon\Carbon;
use Maatwebsite\Excel\Excel;
use Telegram\Bot\Laravel\Facades\Telegram;

class CircuitController extends Controller
{
    public function index()
    {
        return view('circuits');
    }

    public function disconnectors()
    {
        return view('disconnectors');
    }

    public function fuseCutouts()
    {
        return view('fuse-cutouts');
    }

    public function banks()
    {
        return view('transformer-banks');
    }

    public function distributionTransformers()
    {
        return view('distribution-transformers');
    }

    public function pacCircuits()
    {
        $circuits = Circuit::get();
        foreach ($circuits as $circuit) {
            $query = Incidence::whereCircuit_id($circuit->id)->whereSubcause_id(31)->orderBy('date', 'DESC')->orderBy('start', 'DESC')->first();
            if ($query) {

                $carbon = new Carbon($query->date.' '.$query->start, 'America/Caracas');

                $array['voltage_level'] = $circuit->voltage_level;
                $array['circuit'] = $circuit->name;
                $array['day'] = ucfirst($carbon->isoFormat('dddd'));
                $array['date'] = $carbon->format('d-m-Y');
                $array['time'] = $carbon->toTimeString();
                $array['load'] = $query->load;
                $array['order'] = strtotime($query->date." ".$query->time);

                $data[] = $array;
            }

            $results = $this->array_orderby($data, 'order', SORT_ASC);
        }
        return view('pac_circuits', ['results' => $results]);
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

    public function exportPACCircuitsList()
    {
        $filename = 'LISTADO-CIRCUITOS-PAC-'.Carbon::now()->format('d-m-Y');

        return (new PACCircuitsListExport())->download($filename, Excel::XLS);

    }

    public function pacBlocks()
    {
        return view('pac');
    }

    public function exportPACBlocks($day_blocks=0, $day_power=0, $night_blocks=0, $night_power=0, $exclude=false)
    {
        $filename = 'BLOQUES-PAC-'.Carbon::now()->format('d-m-Y')."-".$day_blocks."-DIA-".$day_power."MW"."-".$night_blocks."-NOCHE-".$night_power."MW";

        return (new PACExport($day_blocks, $day_power, $night_blocks, $night_power, $exclude))->download($filename, Excel::XLS);
    }

    public function circuitLoads()
    {
        return view('circuit-loads');
    }
}
