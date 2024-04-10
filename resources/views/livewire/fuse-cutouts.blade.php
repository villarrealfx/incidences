<div class="container">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <span class="navbar-brand">Cortacorrientes</span>
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
                <li class="nav-item me-2">
                    <select wire:model="circuit_id" name="circuit_id" id="circuit_id" class="form-control ms-2">
                        <option value="0">Seleccione un circuito</option>
                        @foreach ($circuits as $circuit)
                            <option value="{{ $circuit->id }}">{{ $circuit->voltage_level ." kV ". rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $circuit->name)))))))) }}</option>
                        @endforeach
                    </select>
                </li>
                <li class="nav-item">
                    <button type="button" class="btn btn-outline-danger ms-2" wire:click="clear()" title="Limpiar"><i class="fa fa-broom"></i></button>
                    <button type="button" class="btn btn-outline-primary ms-1" wire:click="create()" title="Agregar nuevo"><i class="fa fa-plus"></i></button>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <table class="table table-striped table-hover table-responsive">
            <thead>
                <tr>
                    <th>
                        <span class="text-info" wire:click="sortData('fuse_cutouts.id')" style="cursor:pointer;">
                            ID
                            @if ($str_filter == 'fuse_cutouts.id')
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
                        <span class="text-info" wire:click="sortData('fuse_cutouts.name')" style="cursor:pointer;">
                            Nombre
                            @if ($str_filter == 'fuse_cutouts.name')
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
                        <span class="text-info" wire:click="sortData('fuse_cutouts.circuit_id')" style="cursor:pointer;">
                            Principal
                            @if ($str_filter == 'fuse_cutouts.circuit_id')
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
                        <span class="text-info" wire:click="sortData('fuse_cutouts.status')" style="cursor:pointer;">
                            Estado
                            @if ($str_filter == 'fuse_cutouts.status')
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
                        <span class="text-info" wire:click="sortData('fuse_cutouts.operative')" style="cursor:pointer;">
                            Operativa
                            @if ($str_filter == 'fuse_cutouts.operative')
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
                        <span class="text-info" wire:click="sortData('fuse_cutouts.fuse')" style="cursor:pointer;">
                            Fusible
                            @if ($str_filter == 'fuse_cutouts.fuse')
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
                        <span class="text-info" wire:click="sortData('fuse_cutouts.observations')" style="cursor:pointer;">
                            Observaciones
                            @if ($str_filter == 'fuse_cutouts.observations')
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
                @if ($fuse_cutouts->count())
                    @foreach ($fuse_cutouts as $fuse_cutout)
                        <tr>
                            <td>{{ $fuse_cutout->id }}</td>
                            <td>{{ $fuse_cutout->name }}</td>
                            <td>{{ $fuse_cutout->circuit->name }}</td>
                            <td>
                                @if ($fuse_cutout->status)
                                    <p class="text-success"><b>CERRADO</b></p>
                                @else
                                    <p class="text-danger"><b>ABIERTO</b></p>
                                @endif
                            </td>
                            <td>
                                @if ($fuse_cutout->operative)
                                    <i class="text-success fa-solid fa-square-check" title="OPERATIVO"></i>
                                    @else
                                    <i class="text-danger fa-solid fa-square" title="NO OPERATIVO"></i>
                                    @endif
                            </td>
                            <td>{{ $fuse_cutout->fuse }}</td>
                            <td>{{ $fuse_cutout->observations }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="">
                                    @if ($confirming === $fuse_cutout->id)
                                        <button wire:click="cancelDelete({{ $fuse_cutout->id }})" class="btn btn-outline-warning" title="Cancelar">
                                            <i class="fa fa-rectangle-xmark"></i>
                                        </button>
                                        <button wire:click="kill({{ $fuse_cutout->id }})" class="btn btn-outline-danger" title="Eliminar">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                    @else
                                        <button wire:click="edit({{ $fuse_cutout->id }}, true)" class="btn btn-outline-success" title="Ver">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button wire:click="edit({{ $fuse_cutout->id }}, false)" class="btn btn-outline-warning" title="Editar">
                                            <i class="fa fa-pen-to-square"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $fuse_cutout->id }})" class="btn btn-outline-danger" title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="12"><span class="text-muted">No hay resultados para la búsqueda "{{ $search }}" en la pagina {{ $page }} al mostrar {{ $perPage }}</span></td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="bg-white px-4 py-3 border-top border-gray-200 sm:px-6 paginator-block">
            {{ $fuse_cutouts->appends(['search' => $search])->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="ccModal" tabindex="-1" aria-labelledby="ccModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ccModalLabel">Cortacorriente</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="circuit_one_id" class="form-label">Circuito</label>
                        <select class="form-control @error('circuit_one_id') is-invalid @enderror" wire:model="circuit_one_id" name="circuit_one_id" id="circuit_one_id" @disabled($readonly)>
                                <option value="0" selected>Seleccione un circuito</option>
                            @foreach ($circuits as $circuit)
                                <option value="{{ $circuit->id }}">{{ $circuit->voltage_level ." kV ". rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $circuit->name)))))))) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre/Código</label>
                        <input wire:model="name" type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Introduzca el código o nombre del equipo" @readonly($readonly)>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Ubicación</label>
                        <textarea wire:model="address" class="form-control @error('address') is-invalid @enderror" name="address" id="address" cols="30" rows="3" @readonly($readonly)></textarea>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="status" class="form-label">Estado</label>
                            <select wire:model="status" name="status" id="status" class="form-control @error('status') is-invalid @enderror" @disabled($readonly)>
                                <option value="0">ABIERTO</option>
                                <option value="1">CERRADO</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="operative" class="form-label">¿Operativo?</label>
                            <input class="form-check-input @error('operative') is-invalid @enderror" type="checkbox" name="operative" id="operative" wire:model="operative" @disabled($readonly)>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="fuse" class="form-label">Fusible</label>
                            <input class="form-control @error('fuse') is-invalid @enderror" type="number" name="fuse" id="fuse" wire:model="fuse" @readonly($readonly)>
                        </div>
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
