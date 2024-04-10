<div class="container">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <span class="navbar-brand">Bancos de Transformadores</span>
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
                <li class="nav-item me-2">
                    <select wire:model="circuit" name="circuit" id="circuit" class="form-control ms-2">
                        <option value="0">Seleccione un circuito</option>
                        @foreach ($circuits as $circuit)
                            <option value="{{ $circuit->id }}">{{ $circuit->voltage_level ." kV ". rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $circuit->name)))))))) }}</option>
                        @endforeach
                    </select>
                </li>
                <li class="nav-item me-2">
                    <select wire:model="circuit_id" name="circuit_id" id="circuit_id" class="form-control ms-2">
                        <option value="0">Escoga un cortacorriente</option>
                        @foreach ($fuse_cutouts->where('circuit_id','=',$circuit) as $fuse_cutout)
                            <option value="{{ $fuse_cutout->id }}">{{ $fuse_cutout->name }}</option>
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
                        <span class="text-info" wire:click="sortData('transformer_banks.id')" style="cursor:pointer;">
                            ID
                            @if ($str_filter == 'transformer_banks.id')
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
                        <span class="text-info" wire:click="sortData('transformer_banks.connection_group')" style="cursor:pointer;">
                            Grupo de Conexión
                            @if ($str_filter == 'transformer_banks.connection_group')
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
                        <span class="text-info" wire:click="sortData('transformer_banks.private')" style="cursor:pointer;">
                            ¿Privado?
                            @if ($str_filter == 'transformer_banks.private')
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
                        <span class="text-info" wire:click="sortData('transformer_banks.fuse_cutout_id')" style="cursor:pointer;">
                            Cortacorriente
                            @if ($str_filter == 'transformer_banks.fuse_cutout_id')
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
                        <span class="text-info">
                            Capacidad
                        </span>
                    </th>
                    <th>
                        <span class="text-info" wire:click="sortData('transformer_banks.observations')" style="cursor:pointer;">
                            Observaciones
                            @if ($str_filter == 'transformer_banks.observations')
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
                @if ($transformer_banks->count())
                    @foreach ($transformer_banks as $transformer_bank)
                        <tr>
                            <td>{{ $transformer_bank->id }}</td>
                            <td>{{ $transformer_bank->connection_group }}</td>
                            <td>
                                @if ($transformer_bank->private)
                                    <i class="text-info fa-solid fa-square-check" title="PRIVADO"></i>
                                    @else
                                    <i class="text-success fa-solid fa-square" title="PÚBLICO"></i>
                                    @endif
                            </td>
                            <td>{{ $transformer_bank->fuse_cutouts->name }}</td>
                            <td>{{ $transformer_bank->capacities }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="">
                                    @if ($confirming === $transformer_bank->id)
                                        <button wire:click="cancelDelete({{ $transformer_bank->id }})" class="btn btn-outline-warning" title="Cancelar">
                                            <i class="fa fa-rectangle-xmark"></i>
                                        </button>
                                        <button wire:click="kill({{ $transformer_bank->id }})" class="btn btn-outline-danger" title="Eliminar">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                    @else
                                        <button wire:click="edit({{ $transformer_bank->id }}, true)" class="btn btn-outline-success" title="Ver">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button wire:click="edit({{ $transformer_bank->id }}, false)" class="btn btn-outline-warning" title="Editar">
                                            <i class="fa fa-pen-to-square"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $transformer_bank->id }})" class="btn btn-outline-danger" title="Eliminar">
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
            {{ $transformer_banks->appends(['search' => $search])->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="tbModal" tabindex="-1" aria-labelledby="tbModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tbModalLabel">Banco de Transformadores</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="circuit_id" class="form-label">Circuito</label>
                        <select class="form-control @error('circuit_id') is-invalid @enderror" wire:model="circuit_id" name="circuit_id" id="circuit_id" @disabled($readonly)>
                                <option value="0" selected>Seleccione un circuito</option>
                            @foreach ($circuits as $circuit)
                                <option value="{{ $circuit->id }}">{{ $circuit->voltage_level ." kV ". rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $circuit->name)))))))) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fuse_cutout_id" class="form-label">Cortacorriente</label>
                        <select class="form-control @error('fuse_cutout_id') is-invalid @enderror" wire:model="fuse_cutout_id" name="fuse_cutout_id" id="fuse_cutout_id" @disabled($readonly)>
                                <option value="0" selected>Seleccione un cortacorriente</option>
                            @foreach ($fuse_cutouts as $fuse_cutout)
                                <option value="{{ $fuse_cutout->id }}">{{ $fuse_cutout->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col">
                                <label for="connection_group" class="form-label">Grupo de conexión</label>
                                <input wire:model="connection_group" type="text" name="connection_group" id="connection_group" class="form-control @error('connection_group') is-invalid @enderror" placeholder="Introduzca el grupo de conexión del banco" @readonly($readonly)>
                            </div>
                            <div class="col">
                                <label for="private" class="form-label">¿Privado?</label>
                                <select wire:model="private" name="private" id="private" class="form-control @error('private') is-invalid @enderror" @disabled($readonly)>
                                    <option value="0">PÚBLICO</option>
                                    <option value="1">PRIVADO</option>
                                </select>
                            </div>
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
