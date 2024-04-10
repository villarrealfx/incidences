@extends('layouts.app')

@section('content')
    <div class="container">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <span class="navbar-brand">Listado de Circuitos para PAC</span>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a alt="Descargar planilla de Operaciones Manuales" class="btn btn-outline-success" href="{{ route('pac-list-xls') }}"><i class="fa fa-file-excel"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
        <table class="table table-sm table-hover table-responsive table-striped">
            <thead>
                <tr>
                    <th>Tensión</th>
                    <th>Circuito</th>
                    <th>Día</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Carga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $result)
                    <tr>
                        <td>{{$result['voltage_level']}} kV</td>
                        <td>{{$result['circuit']}}</td>
                        <td>{{$result['day']}}</td>
                        <td>{{$result['date']}}</td>
                        <td>{{$result['time']}}</td>
                        <td>{{number_format($result['load'] * $result['voltage_level'] * sqrt(3) * 0.9 / 1000,2)}} MW</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
