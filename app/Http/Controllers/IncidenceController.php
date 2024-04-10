<?php

namespace App\Http\Controllers;

use App\Exports\{GeneralNDIExport, ManualOperationsExport, NDIExport, NDIYearExport, GeneralNDIYearExport};
use Maatwebsite\Excel\Excel;
use Illuminate\Http\Request;
use App\Imports\{IncidencesImport};
use App\Models\{Period, ServiceCenter, Incidence, Cause, Subcause, System};

class IncidenceController extends Controller
{
    public function import()
    {
        $incidences = (new IncidencesImport)->import(request()->file('file'));

        return redirect('/incidences')->with('success', 'Archivo importado con éxito');
    }

    public function upload()
    {
        return view('import');
    }

    public function exportManualOperations($from, $to)
    {
        if ($to != $from) {
            $filename = "operaciones-manuales-desde-" . $from . "-hasta-" . $to;
        } else {
            $filename = "operaciones-manuales-" . $to;
        }

        return (new ManualOperationsExport($from, $to))->download($filename, Excel::XLS);
    }

    public function exportNDI($cs, $period_id)
    {
        $service_center = ServiceCenter::find($cs);
        $period = Period::find($period_id);
        $months = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
        $filename = $service_center->name.'-NDI-'.$months[$period->month-1].'-'.$period->year;

        return (new NDIExport($service_center->id, $period->id))->download($filename, Excel::XLS);
    }

    public function exportGeneralNDI($period_id)
    {
        $period = Period::find($period_id);
        $months = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
        $filename = 'NDI-GENERAL-'.$months[$period->month-1].'-'.$period->year;

        return (new GeneralNDIExport($period->id))->download($filename, Excel::XLS);

    }

    public function exportNDIYear($cs, $year)
    {
        $service_center = ServiceCenter::find($cs);
        $filename = $service_center->name.'-NDI-AÑO-'.$year;

        return (new NDIYearExport($service_center->id, $year))->download($filename, Excel::XLS);
    }

    public function exportGeneralNDIYear($year)
    {
        $filename = 'NDI-GENERAL-AÑO-'.$year;

        return (new GeneralNDIYearExport($year))->download($filename, Excel::XLS);

    }

    public function fillSubCausesData()
    {
        $database = Incidence::get();

        foreach ($database as $data) {
            $cause = Cause::whereName($data->subcause->cause->name)->first();
            if ($cause == null) {
                $cause = new Cause();
                $cause->name = $data->subcause->cause->name;
                $cause->save();
            }
            $subcause = Subcause::whereName($data->subcause->name)->whereCause_id($cause->id)->first();
            if ($subcause == null) {
                $subcause = new Subcause();
                $subcause->name = $data->subcause->name;
                $subcause->cause_id = $cause->id;
                $subcause->save();
            }
            if ($data->cause_id != $cause->id || $data->subcause_id != $subcause->id) {
                $data->cause_id = $cause->id;
                $data->subcause_id = $subcause->id;
                $data->save();
            }
        }

        dd($database);
    }

    public function fillSystemsData()
    {
        $database = Incidence::get();

        foreach ($database as $data) {
            $system = System::whereName($data->system)->first();
            if ($system == null) {
                $system = new System();
                $system->name = $data->system;
                $system->save();
            }
            if ($data->system_id != $system->id) {
                $data->system_id = $system->id;
                $data->save();
            }
        }

        dd($database);
    }

    public function index()
    {
        return view('incidences');
    }

    public function ndi()
    {
        return view('ndi');
    }

    public function ndi_year()
    {
        return view('ndi-year');
    }

    public function charts()
    {
        return view('charts');
    }

    public function manual_operations()
    {
        return view('manual-operations');
    }

    public function systems()
    {
        return view('systems');
    }

    public function causes()
    {
        return view('causes');
    }

    public function periods()
    {
        return view('periods');
    }
}
