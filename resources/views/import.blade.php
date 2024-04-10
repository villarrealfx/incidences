@extends('layouts.app')

@section('content')
    @php
        setlocale(LC_ALL, 'es_ES');
        $date = Carbon\Carbon::now();
    @endphp
    <div class="container">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm mb-3">
            <div class="container">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <span class="navbar-brand">Importar archivo Incidencias SIAR</span>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="{{ asset('plantilla-importar-incidencias.xlsx') }}" class="btn btn-outline-success" title="Plantilla"><i class="fa-solid fa-file-arrow-down"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-control">
                <div class="form-group mt-2 mb-2">
                    <div class="form-control mb-2 text-left">
                        <label for="formFile" class="form-label">Elija un archivo</label>
                        <input type="file" name="file" id="formFile" class="form-control mb-1" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-outline-primary">Importar Archivo <i class="fa-solid fa-file-arrow-up"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
