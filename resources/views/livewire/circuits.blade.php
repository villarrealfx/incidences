<div class="container">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <span class="navbar-brand">Circuitos</span>
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
                    <input wire:model="search" class="form-control mr-sm-2" type="search" placeholder="Búsqueda" aria-label="Search">
                </li>
                <li class="nav-item">
                    <button wire:click="clear" class="btn btn-outline-danger ms-2"><i class="fa fa-broom"></i></button>
                    <button type="button" class="btn btn-outline-primary" wire:click="toogleModal()"><i class="fa fa-plus"></i></button>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <table class="table table-sm table-striped table-hover table-responsive">
            <thead>
                <tr>
                    {{--<th>
                        <span class="text-info" wire:click="sortData('circuits.id')" style="cursor:pointer;">
                            ID
                            @if ($str_filter == 'circuits.id')
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
                    </th>--}}
                    <th>
                        <span class="text-info" wire:click="sortData('circuits.name')" style="cursor:pointer;">
                            Nombre
                            @if ($str_filter == 'circuits.name')
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
                    {{--<th>
                        <span class="text-info" wire:click="sortData('circuits.voltage_level')" style="cursor:pointer;">
                            Tensión
                            @if ($str_filter == 'circuits.voltage_level')
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
                    </th>--}}
                    <th>
                        <span class="text-info" wire:click="sortData('circuits.breaker')" style="cursor:pointer;">
                            Interruptor
                            @if ($str_filter == 'circuits.breaker')
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
                        <span class="text-info" wire:click="sortData('circuits.substation_id')" style="cursor:pointer;">
                            Subestación
                            @if ($str_filter == 'circuits.substation_id')
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
                    {{--<th>
                        <span class="text-info" wire:click="sortData('circuits.service_center_id')" style="cursor:pointer;">
                            Centro de Servicio
                            @if ($str_filter == 'circuits.service_center_id')
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
                    </th>--}}
                    <th>
                        <span class="text-info" wire:click="sortData('circuits.priority')" style="cursor:pointer;">
                            ¿Prioritario?
                            @if ($str_filter == 'circuits.priority')
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
                        <span class="text-info" wire:click="sortData('circuits.attended')" style="cursor:pointer;">
                            ¿Atendido?
                            @if ($str_filter == 'circuits.attended')
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
                        <span class="text-info" wire:click="sortData('circuits.day')" style="cursor:pointer;">
                            ¿Diurno?
                            @if ($str_filter == 'circuits.day')
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
                        <span class="text-info" wire:click="sortData('circuits.night')" style="cursor:pointer;">
                            ¿Nocturno?
                            @if ($str_filter == 'circuits.night')
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
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if ($circuits->count())
                    @foreach ($circuits as $circuit)
                        @php
                            if ($circuit->voltage_level == 13.8) {
                                $prefix = "D";
                            } elseif ($circuit->voltage_level == 34.5) {
                                $prefix = "B";
                            }
                        @endphp
                        <tr>
                            {{--<td>{{ $circuit->id }}</td>--}}
                            <td>{{ $circuit->voltage_level ." kV ". rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $circuit->name)))))))) }}</td>
                            {{--<td>{{ $circuit->voltage_level }} kV</td>--}}
                            <td>{{ $prefix.$circuit->breaker }}</td>
                            <td>{{ $circuit->substation->name }}</td>
                            {{--<td>{{ $circuit->serviceCenter->name }}</td>--}}
                            <td>
                                <a wire:click="tooglePriority({{ $circuit->id }})"  style="cursor: pointer">
                                    @if ($circuit->priority)
                                    <i class="text-success fa-solid fa-square-check"></i>
                                    @else
                                    <i class="text-danger fa-solid fa-square"></i>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a wire:click="toogleAttention({{ $circuit->id }})"  style="cursor: pointer">
                                    @if ($circuit->attended)
                                    <i class="text-success fa-solid fa-square-check"></i>
                                    @else
                                    <i class="text-danger fa-solid fa-square"></i>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a wire:click="toogleDay({{ $circuit->id }})"  style="cursor: pointer">
                                    @if ($circuit->day)
                                    <i class="text-success fa-solid fa-square-check"></i>
                                    @else
                                    <i class="text-danger fa-solid fa-square"></i>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a wire:click="toogleNight({{ $circuit->id }})"  style="cursor: pointer">
                                    @if ($circuit->night)
                                    <i class="text-success fa-solid fa-square-check"></i>
                                    @else
                                    <i class="text-danger fa-solid fa-square"></i>
                                    @endif
                                </a>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="">
                                    @if ($confirming === $circuit->id)
                                        <button wire:click="cancelDelete({{ $circuit->id }})" class="btn btn-outline-warning" title="Cancelar">
                                            <i class="fa fa-rectangle-xmark"></i>
                                        </button>
                                        <button wire:click="kill({{ $circuit->id }})" class="btn btn-outline-danger" title="Eliminar">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                    @else
                                        <button wire:click="edit({{ $circuit->id }})" class="btn btn-outline-warning">
                                            <i class="fa fa-pen-to-square"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $circuit->id }})" class="btn btn-outline-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8"><span class="text-muted">No hay resultados para la búsqueda "{{ $search }}" en la pagina {{ $page }} al mostrar {{ $perPage }}</span></td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="bg-white px-4 py-3 border-top border-gray-200 sm:px-6 paginator-block">
            {{ $circuits->appends(['search' => $search])->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="crModal" tabindex="-1" aria-labelledby="crModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="scModalLabel">Circuito</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input
                            type="text"
                            wire:model="name"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            placeholder="Nombre del Circuito"
                            >
                    </div>
                    <div class="mb-3">
                        <label for="voltage_level" class="form-label">Nivel de Tensión</label>
                        <select class="form-control @error('voltage_level') is-invalid @enderror" wire:model="voltage_level" name="voltage_level" id="voltage_level">
                            <option value="" selected>Seleccione una opción</option>
                            <option value="13.8">13.8 kV</option>
                            <option value="34.5">34.5 kV</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="breaker" class="form-label"></label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">
                                @if ($voltage_level == 13.8)
                                    D
                                @elseif ($voltage_level == 34.5)
                                    B
                                @endif
                            </span>
                            <input
                            type="text"
                            wire:model="breaker"
                            class="form-control @error('breaker') is-invalid @enderror"
                            id="breaker"
                            placeholder="Nomenclatura"
                            >
                          </div>
                    </div>
                    <div class="mb-3">
                        <label for="substation_id" class="form-label">Subestación</label>
                        <select wire:model="substation_id" name="substation_id" id="substation_id" class="form-control @error('substation_id') is-invalid @enderror">
                            <option value="0">Seleccione una opción</option>
                            @foreach ($substations as $substation)
                                <option value="{{ $substation->id }}">{{ $substation->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="service_center_id" class="form-label">Centro de Servicio</label>
                        <select wire:model="service_center_id" name="service_center_id" id="service_center_id" class="form-control @error('service_center_id') is-invalid @enderror">
                            <option value="0">Seleccione una opción</option>
                            @foreach ($centers as $center)
                                <option value="{{ $center->id }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Circuito Padre</label>
                        <select wire:model="parent_id" name="parent_id" id="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                            <option value="0">Seleccione una opción</option>
                            @foreach ($cs->where('id','!=',$item_id)->where('voltage_level','>',$voltage_level) as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    @if ($item_id)
                        <button type="button" class="btn btn-primary" wire:click="save" title="Actualizar">
                            <i class="fa fa-arrow-up-from-bracket"></i>
                        </button>
                    @else
                        <button type="button" class="btn btn-primary" wire:click="save" title="Guardar">
                            <i class="fa fa-floppy-disk"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
