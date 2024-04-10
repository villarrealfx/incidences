<div class="container">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <span class="navbar-brand">Reporte de Incidencias</span>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item navbar-brand" wire:loading>
                    Cargando datos...
                </li>
                <li class="nav-item">
                    <label for="period_id" class="navbar-brand">Período: </label>
                </li>
                <li class="nav-item">
                    <select wire:model="period_id" class="form-control outline-none text-sm text-muted">
                        @foreach ($periods as $key)
                            <option value={{ $key->id }}>{{ $key->month ."-". $key->year }}</option>
                        @endforeach
                    </select>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col text-center bg-warning">
                <h1 class="align-middle">
                    Centro Estadal de Despacho (C.E.D.) Sucre
                </h1>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <table class="table table-hover table-sm" id="general-ndi">
                    <thead>
                        <tr>
                            <th class="text-center">
                                Estado Sucre - {{ $months[($period->month - 1)] }} {{ $period->year }}
                            </th>
                            {{--<th class="text-right"><button class="btn btn-success" onclick="exportTableToExcel('general-ndi', 'GENERAL-NDI-{{ $months[$period->month -1] }}-{{ $period->year }}')"><i class="fa fa-file-excel"></i></button></th>--}}
                            <th class="text-right"><a class="btn btn-success" href="{{ route('export.general.ndi', ['period_id' => $period->id]) }}"><i class="fa fa-file-excel"></i></a></th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>
                                Sistema
                            </th>
                            <th>
                                NDI
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Transmisión</td>
                            <td>{{ $transmission_count }}</td>
                        </tr>
                        <tr>
                            <td>Distribución</td>
                            <td>{{ $distribution_count }}</td>
                        </tr>
                        <tr>
                            <td>Total General</td>
                            <td>{{ $total_count }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col align-middle text-center bg-warning">
                <h1>
                    Análisis de Interrupciones (NDI) en Distribución Sucre por Centros de Servicios {{ $months[$period->month -1] }} {{ $period->year }}
                </h1>
            </div>
        </div>
        @if ($distribution_count)
            @foreach ($centers as $center)
                <div class="row">
                    <div class="col">
                        <table class="table table-responsive table-sm table-hover table-bordered" id="{{ $center->name }}-ndi">
                            <thead>
                                <tr>
                                    <th class="text-center bg-warning" colspan="9">Centro de Servicio Tipo "{{ $center->type }}" {{ $center->name }}</th>
                                    {{--<th class="align-middle bg-warning"><button class="btn btn-block btn-success" onclick="exportTableToExcel('{{ $center->name }}-ndi', '{{ $center->name }}-NDI-{{ $months[$period->month -1] }}-{{ $period->year }}')"><i class="fa fa-file-excel"></i></button></th>--}}
                                    <th class="align-middle bg-warning"><a class="btn btn-block btn-success" href="{{ route('export.ndi', ['cs' => $center->id, 'period' => $period->id]) }}"><i class="fa fa-file-excel"></i></a></th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th>Subestación</th>
                                    <th>Circuito</th>
                                    <th>NDI</th>
                                    <th>Programadas</th>
                                    <th>Falta de Mantenimiento</th>
                                    <th>Lluvias y Atmosféricas</th>
                                    <th>Componentes dañados</th>
                                    <th>Vegetación</th>
                                    <th>Otros</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $circuits = $center->circuits;
                                    foreach ($circuits as $circuit) {
                                        $circuit->ndi = $circuit->incidences->where('period_id', '=', $period->id)->where('system_id', '=', 1)->count();
                                    }
                                    //dd($circuits->sortByDesc('ndi'));
                                @endphp
                                @foreach ($circuits->sortByDesc('ndi') as $circuit)
                                    @if ($circuit->ndi > 0)
                                    @php
                                        $ndi = $circuit->ndi;
                                        $programadas = $center->incidences->where('circuit_id', '=', $circuit->id)->where('cause_id', '=', 6)->where('period_id', '=', $period->id)->where('system_id', '=', 1)->count();
                                        $mantenimiento = $center->incidences->where('circuit_id', '=', $circuit->id)->where('cause_id', '=', 1)->where('period_id', '=', $period->id)->where('system_id', '=', 1)->count();
                                        $lluvia = $center->incidences->where('circuit_id', '=', $circuit->id)->where('cause_id', '=', 2)->where('period_id', '=', $period->id)->where('system_id', '=', 1)->count();
                                        $dano = $center->incidences->where('circuit_id', '=', $circuit->id)->where('cause_id', '=', 3)->where('period_id', '=', $period->id)->where('system_id', '=', 1)->count();
                                        $vegetacion = $center->incidences->where('circuit_id', '=', $circuit->id)->where('cause_id', '=', 4)->where('period_id', '=', $period->id)->where('system_id', '=', 1)->count();
                                    @endphp
                                        <tr>
                                            <td>{{ rtrim(str_replace('(', '', str_replace(')', '', str_replace('115', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $circuit->substation->name))))))))) ." ". $circuit->substation->voltage_level }}</td>
                                            <td>{{ $circuit->voltage_level ." kV ". rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $circuit->name)))))))) }}</td>
                                            <td>{{ $ndi }}</td>
                                            <td>{{ $programadas }}</td>
                                            <td>{{ $mantenimiento }}</td>
                                            <td>{{ $lluvia }}</td>
                                            <td>{{ $dano }}</td>
                                            <td>{{ $vegetacion }}</td>
                                            <td>{{ $ndi - $programadas - $mantenimiento - $lluvia - $dano - $vegetacion }}</td>
                                            <td>{{ number_format(($ndi * 100) / $distribution_count, 2) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            @php
                                $ndi = $center->incidences->where('period_id', '=', $period->id)->where('system_id', '=', 1)->count();
                                $programadas = $center->incidences->where('cause_id', '=', 6)->where('period_id', '=', $period->id)->where('system_id', '=', 1)->count();
                                $mantenimiento = $center->incidences->where('cause_id', '=', 1)->where('period_id', '=', $period->id)->where('system_id', '=', 1)->count();
                                $lluvia = $center->incidences->where('cause_id', '=', 2)->where('period_id', '=', $period->id)->where('system_id', '=', 1)->count();
                                $dano = $center->incidences->where('cause_id', '=', 3)->where('period_id', '=', $period->id)->where('system_id', '=', 1)->count();
                                $vegetacion = $center->incidences->where('cause_id', '=', 4)->where('period_id', '=', $period->id)->where('system_id', '=', 1)->count();
                            @endphp
                            <thead>
                                <th class="bg-warning" colspan="2">Por Distribución</th>
                                <th class="bg-warning">{{ $ndi }}</th>
                                <th class="bg-warning">{{ $programadas }}</th>
                                <th class="bg-warning">{{ $mantenimiento }}</th>
                                <th class="bg-warning">{{ $lluvia }}</th>
                                <th class="bg-warning">{{ $dano }}</th>
                                <th class="bg-warning">{{ $vegetacion }}</th>
                                <th class="bg-warning">{{ $ndi - $programadas - $mantenimiento - $lluvia - $dano - $vegetacion }}</th>
                                <th class="bg-warning">{{ number_format(($ndi * 100) / $distribution_count, 2) }}</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>NOTA</strong></td>
                                    <td colspan="9">
                                        <strong>LAS INTERRUPCIONES EN LOS CIRCUITOS DEL CENTRO DE SERVCIO "{{ $center->type }}" {{ $center->name }}, REPRESENTA EL {{ number_format(($ndi * 100) / $distribution_count, 2) }}% DEL TOTAL DE LAS INTERRUPCIONES POR DISTRIBUCIÓN ({{ $distribution_count }}), DONDE LAS CAUSAS MÁS RELEVANTES SON: FALTA DE MANTENIMIENTO, COMPONENTES DAÑADOS, VEGETACIÓN, ENTRE OTROS.</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
<script>
    function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';

    // Create download link element
    downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);

    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

        // Setting the file name
        downloadLink.download = filename;

        //triggering the function
        downloadLink.click();
    }
}
</script>
