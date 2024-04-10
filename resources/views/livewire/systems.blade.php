<div class="container">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <span class="navbar-brand">Sistemas</span>
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
        <table class="table table-striped table-hover table-responsive">
            <thead>
                <tr>
                    <th>
                        <span class="text-info" wire:click="sortData('systems.id')" style="cursor:pointer;">
                            ID
                            @if ($str_filter == 'systems.id')
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
                        <span class="text-info" wire:click="sortData('systems.name')" style="cursor:pointer;">
                            Nombre
                            @if ($str_filter == 'systems.name')
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
                            Incidencias
                        </span>
                    </th>
                    <th>
                        <span class="text-info" wire:click="sortData('systems.active')" style="cursor:pointer;">
                            Activo
                            @if ($str_filter == 'systems.active')
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
                @if ($systems->count())
                    @foreach ($systems as $system)
                        <tr>
                            <td>{{ $system->id }}</td>
                            <td>{{ $system->name }}</td>
                            <td>{{ $system->incidences->count() }}</td>
                            <td>
                                <a wire:click="toggleActive({{ $system->id }})"  style="cursor: pointer">
                                    @if ($system->active)
                                    <i class="text-success fa-solid fa-square-check"></i>
                                    @else
                                    <i class="text-danger fa-solid fa-square"></i>
                                    @endif
                                </a>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="">
                                    @if ($confirming === $system->id)
                                        <button wire:click="cancelDelete({{ $system->id }})" class="btn btn-outline-warning" title="Cancelar">
                                            <i class="fa fa-rectangle-xmark"></i>
                                        </button>
                                        <button wire:click="kill({{ $system->id }})" class="btn btn-outline-danger" title="Eliminar">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                    @else
                                        <button wire:click="edit({{ $system->id }})" class="btn btn-outline-warning">
                                            <i class="fa fa-pen-to-square"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $system->id }})" class="btn btn-outline-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
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
            {{ $systems->appends(['search' => $search])->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="syModal" tabindex="-1" aria-labelledby="syModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="syModalLabel">Sistema</h1>
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
                            placeholder="Nombre del Sistema"
                            >
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

