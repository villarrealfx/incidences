<div class="container">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <span class="navbar-brand">Períodos</span>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-2">
                    <select wire:model="perPage" class="form-control outline-none text-sm text-muted">
                        <option value="5"> 5 por página</option>
                        <option value="10"> 10 por página</option>
                        <option value="15"> 15 por página</option>
                        <option value="25"> 25 por página</option>
                        <option value="50"> 50 por página</option>
                        <option value="100"> 100 por página</option>
                    </select>
                </li>
                <li class="nav-item">
                    <input wire:model="search" class="form-control" type="search" placeholder="Búsqueda" aria-label="Search">
                <li class="nav-item">
                {{--</li>
                    <button type="button" class="btn btn-outline-danger ms-2" title="Limpiar"><i class="fa fa-broom"></i></button>
                    <button type="button" class="btn btn-outline-primary ms-1" wire:click="toogleModal()" title="Agregar nuevo"><i class="fa fa-plus"></i></button>
                </li>--}}
            </ul>
        </div>
    </nav>
    <div class="container">
        <table class="table table-striped table-hover table-responsive">
            <thead>
                <tr>
                    <th>
                        <span class="text-info" wire:click="sortData('periods.id')" style="cursor:pointer;">
                            ID
                            @if ($str_filter == 'periods.id')
                                <small>
                                    @if ($updown == 'asc')
                                        <i class="fa fa-arrow-circle-up"></i>
                                    @endif
                                    @if ($updown == 'desc')
                                        <i class="fa fa-arrow-circle-down"></i>
                                    @endif
                                </small>
                            @endif
                        </span>
                    </th>
                    <th>
                        <span class="text-info" wire:click="sortData('periods.month')" style="cursor:pointer;">
                            Mes
                            @if ($str_filter == 'periods.month')
                                <small>
                                    @if ($updown == 'asc')
                                        <i class="fa fa-arrow-circle-up"></i>
                                    @endif
                                    @if ($updown == 'desc')
                                        <i class="fa fa-arrow-circle-down"></i>
                                    @endif
                                </small>
                            @endif
                        </span>
                    </th>
                    <th>
                        <span class="text-info" wire:click="sortData('periods.year')" style="cursor:pointer;">
                            Año
                            @if ($str_filter == 'periods.year')
                                <small>
                                    @if ($updown == 'asc')
                                        <i class="fa fa-arrow-circle-up"></i>
                                    @endif
                                    @if ($updown == 'desc')
                                        <i class="fa fa-arrow-circle-down"></i>
                                    @endif
                                </small>
                            @endif
                        </span>
                    </th>
                    @foreach ($systems as $system)
                        <th class="text-center">
                            <span class="text-warning">
                                {{ $system->name }}
                            </span>
                        </th>
                    @endforeach
                    <th class="text-end text-warning">Total</th>
                </tr>
            </thead>
            <tbody>
                @if ($periods->count())
                    @foreach ($periods as $period)
                        <tr>
                            <td>{{ $period->id }}</td>
                            <td>{{ $months[$period->month-1] }}</td>
                            <td>{{ $period->year }}</td>
                            @foreach ($systems as $system)
                                <td class="text-center">{{ $period->incidences->where('system_id','=',$system->id)->count() }}</td>
                            @endforeach
                            <td class="text-end">{{ $period->incidences->count() }}</td>
                            {{--<td class="text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="">
                                    @if ($confirming === $period->id)
                                        <button wire:click="cancelDelete({{ $period->id }})" class="btn btn-outline-warning" title="Cancelar">
                                            <i class="fa fa-rectangle-xmark"></i>
                                        </button>
                                        <button wire:click="kill({{ $period->id }})" class="btn btn-outline-danger" title="Eliminar">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                    @else
                                        <button wire:click="edit({{ $period->id }})" class="btn btn-outline-warning" title="Editar">
                                            <i class="fa fa-pen-to-square"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $period->id }})" class="btn btn-outline-danger" title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>--}}
                    @endforeach
                @else
                    <tr>
                        <td colspan="5"><span class="text-muted">No hay resultados para la búsqueda "{{ $search }}" en la pagina {{ $page }} al mostrar {{ $perPage }}</span></td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="bg-white px-4 py-3 border-top border-gray-200 sm:px-6 paginator-block">
            {{ $periods->appends(['search' => $search])->links() }}
        </div>
    </div>
