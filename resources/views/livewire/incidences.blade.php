<div class="container">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <span class="navbar-brand">Incidencias</span>
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
                </li>
                <li class="nav-item">
                    <button wire:click="clear" class="btn btn-outline-danger ms-2" title="Limpiar"><i class="fa fa-broom"></i></button>
                    <button wire:click="create" class="btn btn-outline-success" title="Crear nueva"><i class="fa fa-add"></i></button>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <table class="table table-striped table-hover table-responsive">
            <thead>
                <tr>
                    <th>
                        <span class="text-info" wire:click="sortData('incidences.id')" style="cursor:pointer;">
                            ID
                            @if ($str_filter == 'incidences.id')
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
                        <span class="text-info" wire:click="sortData('incidences.circuit_id')" style="cursor:pointer;">
                            Circuito
                            @if ($str_filter == 'incidences.circuit_id')
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
                        <span class="text-info" wire:click="sortData('incidences.date')" style="cursor:pointer;">
                            Fecha
                            @if ($str_filter == 'incidences.date')
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
                        <span class="text-info" wire:click="sortData('incidences.start')" style="cursor:pointer;">
                            Inicio
                            @if ($str_filter == 'incidences.start')
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
                        <span class="text-info" wire:click="sortData('incidences.duration')" style="cursor:pointer;">
                            Duración
                            @if ($str_filter == 'incidences.duration')
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
                        <span class="text-info" wire:click="sortData('incidences.load')" style="cursor:pointer;">
                            Carga
                            @if ($str_filter == 'incidences.load')
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
                        <span class="text-info" wire:click="sortData('incidences.signal')" style="cursor:pointer;">
                            Señal
                            @if ($str_filter == 'incidences.signal')
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
                        <span class="text-info" wire:click="sortData('incidences.cause_id')" style="cursor:pointer;">
                            Causa
                            @if ($str_filter == 'incidences.cause')
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
                        <span class="text-info" wire:click="sortData('incidences.subcause_id')" style="cursor:pointer;">
                            Subcausa
                            @if ($str_filter == 'incidences.subcause')
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
                @if ($incidences->count())
                    @foreach ($incidences as $incidence)
                        <tr>
                            <td>{{ $incidence->id }}</td>
                            <td>{{ $incidence->circuit->voltage_level ." kV ". rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $incidence->circuit->name)))))))) }}</td>
                            <td>{{ $incidence->date }}</td>
                            <td>{{ $incidence->start }}</td>
                            <td>{{ $incidence->duration }}</td>
                            <td>{{ $incidence->load }}</td>
                            <td>{{ $incidence->signal }}</td>
                            <td>{{ $incidence->cause->name }}</td>
                            <td>{{ $incidence->subcause->name }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="">
                                    @if ($confirming === $incidence->id)
                                        <button wire:click="cancelDelete({{ $incidence->id }})" class="btn btn-outline-warning" title="Cancelar">
                                            <i class="fa fa-rectangle-xmark"></i>
                                        </button>
                                        <button wire:click="kill({{ $incidence->id }})" class="btn btn-outline-danger" title="Eliminar">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                    @else
                                        <button wire:click="edit({{ $incidence->id }}, true)" class="btn btn-outline-success" title="Ver">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button wire:click="edit({{ $incidence->id }}, false)" class="btn btn-outline-warning" title="Editar">
                                            <i class="fa fa-pen-to-square"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $incidence->id }})" class="btn btn-outline-danger" title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="10"><span class="text-muted">No hay resultados para la búsqueda "{{ $search }}" en la pagina {{ $page }} al mostrar {{ $perPage }}</span></td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="bg-white px-4 py-3 border-top border-gray-200 sm:px-6 paginator-block">
            {{ $incidences->appends(['search' => $search])->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="inModal" tabindex="-1" aria-labelledby="inModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="scModalLabel">Incidencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="circuit_id" class="form-label">Circuito</label>
                        <select class="form-control @error('circuit_id') is-invalid @enderror" wire:model="circuit_id" name="circuit_id" id="circuit_id" @disabled($readonly)>
                                <option value="" selected>Seleccione una opción</option>
                            @foreach ($circuits as $circuit)
                                <option value="{{ $circuit->id }}">{{ $circuit->voltage_level ." kV ". rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $circuit->name)))))))) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="system_id" class="form-label">Sistema</label>
                        <select name="system_id" id="system_id" wire:model="system_id" class="form-control @error('system_id') is-invalid @enderror" @disabled($readonly)>
                                <option value="">Selecciona una opción</option>
                            @foreach ($systems as $system)
                                <option value="{{ $system->id }}">{{ $system->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="date" class="form-label">Fecha</label>
                            <input class="form-control @error('date') is-invalid @enderror" type="date" name="date" id="date" wire:model="date" placeholder="Introduzca la fecha de la incidencia" @readonly($readonly)>
                        </div>
                        <div class="col">
                            <label for="start" class="form-label">Hora de inicio</label>
                            <input class="form-control @error('start') is-invalid @enderror" type="time" name="start" id="start" wire:model="start" placeholder="Introduzca la hora de la incidencia" @readonly($readonly)>
                        </div>
                        <div class="col">
                            <label for="duration" class="form-label">Duración</label>
                            <input class="form-control @error('duration') is-invalid @enderror" type="text" name="duration" id="duration" wire:model="duration" placeholder="Introduzca la hora de la incidencia" @readonly($readonly)>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="load" class="form-label">Carga</label>
                            <input class="form-control @error('load') is-invalid @enderror" type="number" name="load" id="load" wire:model="load" placeholder="Introduzca la fecha de la incidencia" @readonly($readonly)>
                        </div>
                        <div class="col">
                            <label for="frequency" class="form-label">Frecuencia</label>
                            <input class="form-control @error('frequency') is-invalid @enderror" type="number" name="frequency" id="frequency" wire:model="frequency" placeholder="Introduzca la hora de la incidencia" @readonly($readonly)>
                        </div>
                        <div class="col">
                            <label for="average" class="form-label">Promedio</label>
                            <input class="form-control @error('average') is-invalid @enderror" type="number" name="average" id="average" wire:model="average" placeholder="Introduzca la hora de la incidencia" @readonly($readonly)>
                        </div>
                        <div class="col">
                            <label for="tti" class="form-label">TTI</label>
                            <input class="form-control @error('tti') is-invalid @enderror" type="number" name="tti" id="tti" wire:model="tti" placeholder="Introduzca la hora de la incidencia" @readonly($readonly)>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="signal" class="form-label">Señal</label>
                            <input class="form-control @error('signal') is-invalid @enderror" type="text" name="signal" id="signal" wire:model="signal" @readonly($readonly)>
                        </div>
                        <div class="col">
                            <label for="cause_id" class="form-label">Causa</label>
                            <select name="cause_id" id="cause_id" wire:model="cause_id" class="form-control @error('cause_id') is-invalid @enderror" @disabled($readonly)>
                                    <option value="">Selecciona una opción</option>
                                @foreach ($causes as $cause)
                                    <option value="{{ $cause->id }}">{{ $cause->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="subcause_id" class="form-label">Subcausa</label>
                        <select name="subcause_id" id="subcause_id" wire:model="subcause_id" class="form-control @error('subcause_id') is-invalid @enderror" @disabled($readonly)>
                                <option value="">Selecciona una opción</option>
                            @foreach ($subcauses as $subcause)
                                <option value="{{ $subcause->id }}">{{ $subcause->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="observations" class="form-label">Observaciones</label>
                        <textarea wire:model="observations" class="form-control @error('observations') is-invalid @enderror" name="observations" id="observations" cols="30" rows="3" @readonly($readonly)></textarea>
                    </div>
                </div>
                @if(!$readonly)
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
                @endif
            </div>
        </div>
    </div>
</div>
