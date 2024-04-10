<div class="container">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <span class="navbar-brand">Cargas</span>
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
                    <input wire:model="search" type="text" name="search" id="search" class="form-control" placeholder="Buscar">
                </li>
                <li class="nav-item ms-2">
                    <button wire:click="clear" class="btn btn-outline-danger ms-2"><i class="fa fa-broom"></i></button>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <table class="table table-striped table-hover table-responsive">
            <thead>
                <tr>
                    <th>
                        <span class="text-info">
                            Circuito
                        </span>
                    </th>
                    <th>
                        <span class="text-info">
                            Carga (AMP)
                        </span>
                    </th>
                    <th>
                        <span class="text-info">
                            Carga (MW)
                        </span>
                    </th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if ($circuits->count())
                    @foreach ($circuits as $circuit)
                        <tr>
                            <td>{{ $circuit->voltage_level ." kV ". rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $circuit->name)))))))) }}</td>
                            <td>{{ number_format($circuit->load,0) }}</td>
                            <td>{{ number_format($circuit->load*$circuit->voltage_level*sqrt(3)*0.9/1000,2) }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="">
                                    <button wire:click="toogleModalCL({{ $circuit->id }})" class="btn btn-outline-success" title="Cargas">
                                        <i class="fa fa-diagram-successor"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5"><span class="text-muted">No hay resultados para la búsqueda "{{ $search }}" en la pagina {{ $page }} al mostrar {{ $perPage }}</span></td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="bg-white px-4 py-3 border-top border-gray-200 sm:px-6 paginator-block">
            {{ $circuits->appends(['search' => $search])->links() }}
        </div>
    </div>

    <div wire:ignore.self class="modal modal-lg fade" id="clModal" tabindex="-1" aria-labelledby="clModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="clModalLabel">Cargas</h1><button wire:click="createCircuitLoad({{ $item_id }})" class="btn btn-sm btn-outline-success ms-2"><i class="fa fa-add"></i></button>
                    <code class="ms-4">{{ $voltage ." kV ". rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $name)))))))) }}</code>
                    <button type="button" wire:click="clear()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <table class="table table-striped table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Carga (AMP)</th>
                                    <th>Fecha y Hora</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($loads->count())
                                    @foreach ($loads as $load)
                                        <tr>
                                            <td>{{ $load->id }}</td>
                                            <td>
                                                @if ($editing === $load->id)
                                                    <input type="number" name="load" id="load" class="form-control" wire:model="load">
                                                @else
                                                    {{ $load->load }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($editing === $load->id)
                                                    <input type="datetime-local" name="datetime" id="datetime" class="form-control" wire:model="datetime">
                                                @else
                                                    {{ $load->datetime }}
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm" role="group" aria-label="">
                                                    @if ($confirmation === $load->id)
                                                        <button wire:click="cancelKilling({{ $load->id }})" class="btn btn-outline-warning" title="Cancelar">
                                                            <i class="fa fa-rectangle-xmark"></i>
                                                        </button>
                                                        <button wire:click="killCL({{ $load->id }})" class="btn btn-outline-danger" title="Eliminar">
                                                            <i class="fa fa-ban"></i>
                                                        </button>
                                                    @elseif ($editing === $load->id)
                                                        <button wire:click="cancelEditing({{ $load->id }})" class="btn btn-outline-warning" title="Cancelar">
                                                            <i class="fa fa-rectangle-xmark"></i>
                                                        </button>
                                                        <button wire:click="saveCL({{ $load->id }})" class="btn btn-outline-success" title="Guardar">
                                                            <i class="fa fa-save"></i>
                                                        </button>
                                                    @else
                                                        <button wire:click="editCL({{ $load->id }})" class="btn btn-outline-warning">
                                                            <i class="fa fa-pen-to-square"></i>
                                                        </button>
                                                        <button wire:click="confirmKilling({{ $load->id }})" class="btn btn-outline-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4"><span class="text-muted">No hay cargas registradas</span></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
</div>
