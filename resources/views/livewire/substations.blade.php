<div class="container">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <span class="navbar-brand">Subestaciones</span>
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
                        <span class="text-info" wire:click="sortData('substations.id')" style="cursor:pointer;">
                            ID
                            @if ($str_filter == 'substations.id')
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
                        <span class="text-info" wire:click="sortData('substations.name')" style="cursor:pointer;">
                            Nombre
                            @if ($str_filter == 'substations.name')
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
                        <span class="text-info" wire:click="sortData('substations.level')" style="cursor:pointer;">
                            Nivel
                            @if ($str_filter == 'substations.level')
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
                        <span class="text-info" wire:click="sortData('substations.voltage_level')" style="cursor:pointer;">
                            Nivel de Tensión
                            @if ($str_filter == 'substations.voltage_level')
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
                @if ($substations->count())
                    @foreach ($substations as $substation)
                        <tr>
                            <td>{{ $substation->id }}</td>
                            <td>{{ $substation->name }}</td>
                            <td>{{ $substation->level }}</td>
                            <td>{{ $substation->voltage_level }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="">
                                    @if ($confirming === $substation->id)
                                        <button wire:click="cancelDelete({{ $substation->id }})" class="btn btn-outline-warning" title="Cancelar">
                                            <i class="fa fa-rectangle-xmark"></i>
                                        </button>
                                        <button wire:click="kill({{ $substation->id }})" class="btn btn-outline-danger" title="Eliminar">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                    @else
                                        <button wire:click="edit({{ $substation->id }})" class="btn btn-outline-warning">
                                            <i class="fa fa-pen-to-square"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $substation->id }})" class="btn btn-outline-danger">
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
            {{ $substations->appends(['search' => $search])->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="ssModal" tabindex="-1" aria-labelledby="ssModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="scModalLabel">Subestación</h1>
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
                            placeholder="Nombre de la Subestación"
                            >
                    </div>
                    <div class="mb-3">
                        <label for="level" class="form-label">Nivel</label>
                        <select class="form-control @error('level') is-invalid @enderror" wire:model="level" name="level" id="level">
                            <option value="Transmisión">Transmisión</option>
                            <option value="Distribución">Distribución</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="voltage_level" class="form-label">Nivel de Tensión</label>
                        <input
                            type="text"
                            wire:model="voltage_level"
                            class="form-control @error('voltage_level') is-invalid @enderror"
                            id="voltage_level"
                            placeholder="Niveles de tensión de la subestación"
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
