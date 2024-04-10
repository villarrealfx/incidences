<?php

namespace App\Imports;

use App\Models\{Incidence, Circuit, Substation, ServiceCenter, Period, System, Cause, CircuitLoad, Subcause};
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow};

class IncidencesImport implements ToModel, WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $circuit = Circuit::where('name', '=', $row['circuit'])->first();
        $explode = explode('/', $row['date']);
        $year = explode(' ', $explode[2]);
        $date = $year[0] . '-' . $explode[1] . '-' . $explode[0];

        $period = Period::where('month', '=', $explode[1])->where('year', '=', $year[0])->first();
        if ($period == null) {
            $p = new Period();
            $p->month = $explode[1];
            $p->year = $year[0];
            $p->save();
        } else {
            $p = $period;
        }


        if ($circuit == null) {
            $substation = Substation::where('name', '=', $row['substation'])->first();
            if ($substation == null) {
                $ss = new Substation();
                $ss->name = $row['substation'];
                $ss->save();
            } else {
                $ss = $substation;
            }
            $service_center = ServiceCenter::where('name', '=', $row['service_center'])->first();
            if ($service_center == null) {
                $sc = new ServiceCenter();
                $sc->name = $row['service_center'];
                $sc->save();
            } else {
                $sc = $service_center;
            }
            $c = new Circuit();
            $c->name = $row['circuit'];
            $c->substation_id = $ss->id;
            $c->service_center_id = $sc->id;
            $c->save();
        } else {
            $c = $circuit;
        }

        if (is_numeric($row['start'])) {
            $result[0] = floor($row['start'] * 24);
            $result[1] = floor((($row['start'] * 24) - $result[0]) * 60);
            $result[2] = floor((((($row['start'] * 24) - $result[0]) * 60) - $result[1]) * 60);

            if ($result[2] == 59) {
                $result[2] = 0;
                $result[1]++;
            }

            if ($result[0] < 10) {
                $start = "0" . $result[0];
            } else {
                $start = $result[0];
            }

            if ($result[1] < 10) {
                $start.= ":0" . $result[1];
            } else {
                $start.= ":" . $result[1];
            }

            if ($result[2] < 10) {
                $start.= ":0" . $result[2];
            } else {
                $start.= ":" . $result[2];
            }
        } else {
            $start = str_replace(':00,', ':00', str_replace(',:', ':', $row['start']));
        }

        $system = System::whereName($row['system'])->first();
        if ($system == null) {
            $system = new System();
            $system->name = $row['system'];
            $system->save();
        }

        $cause_string = str_replace('?', 'Ñ', str_replace('ر', 'Ñ', $row['cause']));
        $cause = Cause::whereName($cause_string)->first();
        if ($cause == null) {
            $cause = new Cause();
            $cause->name = $cause_string;
            $cause->save();
        }
        $subcause_string = str_replace('?', 'Ñ', str_replace('ر', 'Ñ', $row['subcause']));
        $subcause = Subcause::whereName($subcause_string)->whereCause_id($cause->id)->first();
        if ($subcause == null) {
            $subcause = new Subcause();
            $subcause->name = $subcause_string;
            $subcause->cause_id = $cause->id;
            $subcause->save();
        }

        $this->saveCircuitLoad($row['load'], $date, $start, $c->id);

        $incidence = Incidence::where('date', '=', $date)->where('start', '=', $start)->where('circuit_id', '=', $c->id)->first();
        if ($incidence == null) {
            return new Incidence([
                'date' => $date,
                'system_id' => $system->id,
                'start' => $start,
                'duration' => $row['duration'],
                'load' => $row['load'],
                'frequency' => $row['frequency'],
                'average' => $row['average'],
                'tti' => $row['tti'],
                'signal' => $row['signal'],
                'cause_id' => $cause->id,
                'subcause_id' => $subcause->id,
                'observations' => $row['observations'],
                'circuit_id' => $c->id,
                'period_id' => $p->id,
                'service_center_id' => $c->service_center_id,
            ]);
        } else {
            $incidence->system_id = $system->id;
            $incidence->duration = $row['duration'];
            $incidence->load = $row['load'];
            $incidence->frequency = $row['frequency'];
            $incidence->average = $row['average'];
            $incidence->tti = $row['tti'];
            $incidence->signal = $row['signal'];
            $incidence->cause_id = $cause->id;
            $incidence->subcause_id = $subcause->id;
            $incidence->observations = $row['observations'];
            $incidence->period_id = $p->id;
            $incidence->service_center_id = $c->service_center_id;

            $incidence->save();
        }
    }

    public function saveCircuitLoad($load, $date, $start, $circuit_id)
    {
        $datetime = Carbon::parse($date.' '.$start)->format('Y-m-d H:i:s');
        $circuit_load = CircuitLoad::whereDatetime($datetime)->whereCircuit_id($circuit_id)->first();
        if (!$circuit_load) {
            $circuit_load = new CircuitLoad();
        }
        $circuit_load->load = $load;
        $circuit_load->datetime = $datetime;
        $circuit_load->circuit_id = $circuit_id;
        $circuit_load->save();
    }
}
