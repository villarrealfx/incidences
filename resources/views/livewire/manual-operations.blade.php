@php
    use Carbon\Carbon;
@endphp
<div class="container">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <span class="navbar-brand">Operación Manual de los Circuitos</span>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item navbar-brand" wire:loading>
                    Cargando datos...
                </li>
                <li class="nav-item">
                    <label for="period_id" class="navbar-brand">Nº de Operaciones: </label>
                </li>
                <li class="nav-item">
                    <label for="period_id" class="navbar-brand"><strong>{{ $incidences->count() }}</strong></label>
                </li>
                <li class="nav-item">
                    <label for="period_id" class="navbar-brand">Fecha Inicio: </label>
                </li>
                <li class="nav-item">
                    <input class="form-control" type="date" wire:model="start_date" name="start_date" id="start_date" max="{{ $now; }}">
                </li>
                <li class="nav-item">
                    <label for="period_id" class="navbar-brand">Fecha Fin: </label>
                </li>
                <li class="nav-item">
                    <input class="form-control" type="date" wire:model="finish_date" name="finish_date" id="finish_date" min="{{ $start_date }}" max="{{ $now; }}">
                </li>
                <li class="nav-item">
                    <a alt="Descargar planilla de Operaciones Manuales" class="btn btn-success" href="{{ route('export.manual-operations', [$start_date, $finish_date]) }}"><i class="fa fa-file-excel"></i></a>
                </li>
            </ul>
        </div>
    </nav>
    <table class="table table-sm table-hover table-responsive table-striped" id="operaciones-manuales">
        <thead class="bg-info">
            <th class="bg-primary text-white">Subestación</th>
            <th class="bg-primary text-white">Circuito</th>
            <th class="bg-primary text-white">Nomenclatura</th>
            <th class="bg-primary text-white">Tensión</th>
            <th class="bg-primary text-white">Carga</th>
            <th class="bg-primary text-white" colspan="2">Apertura</th>
            <th class="bg-primary text-white" colspan="2">Cierre</th>
            <th class="bg-primary text-white">Tiempo</th>
            {{--<th class="bg-primary text-white">MWh</th>--}}
            <th class="bg-primary text-white">Tipo</th>
            <th class="bg-primary text-white">Observación</th>
        </thead>
        <tbody>
            @if ($incidences->count())
                @foreach ($incidences as $incidence)
                <tr>
                    <td>{{ rtrim(str_replace('(', '', str_replace(')', '', str_replace('115', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $incidence->circuit->substation->name))))))))) }}</td>
                    <td>{{ rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $incidence->circuit->name)))))))) }}</td>
                    @php
                        if ($incidence->circuit->voltage_level == 13.8) {
                            $prefix = "D";
                        } elseif ($incidence->circuit->voltage_level == 34.5) {
                            $prefix = "B";
                        }                        
                    @endphp
                    <td>{{ $prefix .'-'. $incidence->circuit->breaker }}</td>
                    <td>{{ number_format($incidence->circuit->voltage_level, 1, ",") }}</td>
                    @php
                        $active_power = ($incidence->load * $incidence->circuit->voltage_level * sqrt(3) * 0.9) / 1000;
                    @endphp
                    <td>{{ number_format($active_power, 2, ",") }}</td>
                    <td>{{ $incidence->date }}</td>
                    <td>{{ $incidence->start }}</td>
                    @php
                        $explode = explode(":", $incidence->duration);
                        $finish = Carbon::parse($incidence->date.' '.$incidence->start);
                        $finish->addSeconds($explode[2]);
                        $finish->addMinutes($explode[1]);
                        $finish->addHours($explode[0]);
                    @endphp
                    <td>{{ $finish->toDateString() }}</td>
                    <td>{{ $finish->toTimeString() }}</td>
                    <td>{{ $incidence->duration }}</td>
                    @php
                        $time = $explode[0] + ($explode[1] / 60) + ($explode[2] / 3600);
                    @endphp
                    {{--<td>{{ number_format($time * $active_power, 2, ",") }}</td>--}}
                    <td>
                        @if ($incidence->cause_id == 6)
                            PROGRAMADO
                        @else
                            POR EMERGENCIA
                        @endif
                    </td>
                    <td>{{ $incidence->observations }}</td>
                </tr>
                @endforeach
            @else
            <tr>
                <td colspan="12">No se han encontrado operaciones manuales</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>