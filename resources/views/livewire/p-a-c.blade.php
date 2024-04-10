    <div class="container">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <span class="navbar-brand">Bloques del Plan de Administración de Carga</span>
                    </li>
                </ul>
                @php
                    if ($switch) {
                        $switch_color = "warning";
                        $switch_icon = "sun";
                        $switch_title = "Diurno";
                    } else {
                        $switch_color = "secondary";
                        $switch_icon = "moon";
                        $switch_title = "Nocturno";
                    }

                    if ($exclude) {
                        $exclude_color = "success";
                        $exclude_icon = "check";
                        $exclude_title = "Excluir circuitos de ayer";
                    } else {
                        $exclude_color = "danger";
                        $exclude_icon = "xmark";
                        $exclude_title = "No excluir circuitos de ayer";
                    }
                    
                @endphp
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <button wire:click="toogleExclude()" class="btn btn-{{ $exclude_color }}" title="{{ $exclude_title }}">
                            <i class="fa fa-{{ $exclude_icon }}"></i>
                        </button>
                    </li>
                    <li class="nav-item ms-2">
                        <button wire:click="toogleSwitch" class="btn btn-{{ $switch_color }}" title="{{ $switch_title }}">
                            <i class="fa fa-{{ $switch_icon }}"></i>
                        </button>
                    </li>
                    @if ($switch)    
                        <li class="nav-item ms-2">
                            <input type="number" wire:model="day_blocks" name="day_blocks" id="day_blocks" placeholder="Nº de bloques" class="form-control">
                        </li>
                        <li class="nav-item ms-2">
                            <input type="number" wire:model="day_power" name="day_power" id="day_power" placeholder="MW" class="form-control">
                        </li>
                    @else
                        <li class="nav-item ms-2">
                            <input type="number" wire:model="night_blocks" name="night_blocks" id="night_blocks" placeholder="Nº de bloques" class="form-control">
                        </li>
                        <li class="nav-item ms-2">
                            <input type="number" wire:model="night_power" name="night_power" id="night_power" placeholder="MW" class="form-control">
                        </li>
                    @endif
                    <li class="nav-item ms-2">
                        <a alt="Descargar planilla de Operaciones Manuales" class="btn btn-outline-success" href="{{ route('pac-blocks-xls', [$day_blocks, $day_power, $night_blocks, $night_power, $exclude]) }}"><i class="fa fa-file-excel"></i></a>
                        @if(($day_blocks && $day_power)||($night_blocks && $night_power))<button wire:click="sendPACMessage" class="btn btn-outline-primary"><i class="fa-brands fa-telegram"></i></button>@endif
                    </li>
                </ul>
            </div>
        </nav>
        <table class="table table-sm table-hover table-responsive table-striped">
        {{-- Bloque Diurno --}}
        @if ($day_blocks && $day_power)  
            @for ($i = 0; $i < $day_blocks; $i++)  
                <thead>
                    <tr>
                        <th colspan="3">BLOQUE DIURNO Nº {{ $i+1 }} <span class="text-warning"><i class="fa fa-sun"></i></span></th>
                    </tr>
                    <tr>
                        <th>Circuito</th>
                        <th>Carga</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($j = 0; $j < count($results); $j++)
                        @if (!$results[$j]['used'] && $results[$j]['day'] && $results[$j]['exclude'])
                            @if ($total + $results[$j]['load'] <= $day_power+1)   
                                <tr>
                                    <td>{{$results[$j]['voltage_level']}} kV {{rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $results[$j]['circuit']))))))))}}</td>
                                    <td>{{number_format($results[$j]['load'],2)}} MW</td>
                                </tr>
                                @php
                                    $total += $results[$j]['load'];
                                    $results[$j]['used'] = true;
                                @endphp
                            @endif   
                        @endif
                        @php
                            if ($total >= $day_power-0.9) {
                                break;
                            }
                        @endphp
                    @endfor
                    <tr>
                        <td class="text-end"><b>TOTAL</b></td>
                        <td><b>{{ number_format($total, 2) }} MW</b></td>
                    </tr>
                </tbody>
                @php
                    $total = 0;
                    @endphp
            @endfor
        @php
            $total = 0;
        @endphp
        @endif

        {{-- Bloque Nocturno --}}
        @if ($night_blocks && $night_power)  
            @for ($i = 0; $i < $night_blocks; $i++)  
                <thead>
                    <tr>
                        <th colspan="3">BLOQUE NOCTURNO Nº {{ $i+1 }} <span class="text-secondary"><i class="fa fa-moon"></i></span></th>
                    </tr>
                    <tr>
                        <th>Circuito</th>
                        <th>Carga</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($j = 0; $j < count($results); $j++)
                        @if (!$results[$j]['used'] && $results[$j]['night'] && $results[$j]['exclude'])
                            @if ($total + $results[$j]['load'] <= $night_power+1)   
                                <tr>
                                    <td>{{$results[$j]['voltage_level']}} kV {{rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $results[$j]['circuit']))))))))}}</td>
                                    <td>{{number_format($results[$j]['load'],2)}} MW</td>
                                </tr>
                                @php
                                    $total += $results[$j]['load'];
                                    $results[$j]['used'] = true;
                                @endphp
                            @endif   
                        @endif
                        @php
                            if ($total >= $night_power-0.9 && $total) {
                                break;
                            }
                        @endphp
                    @endfor
                    <tr>
                        <td class="text-end"><b>TOTAL</b></td>
                        <td><b>{{ number_format($total, 2) }} MW</b></td>
                    </tr>
                </tbody>
                @php
                    $total = 0;
                    @endphp
            @endfor
        @php
            $total = 0;
        @endphp
        @endif
        @if (($day_blocks && $day_power) || ($night_blocks && $night_power))
            
            <thead>
                <tr>
                    <th colspan="2">CIRCUITOS DISPONIBLES PARA ROTACIÓN</th>
                </tr>
                <tr>
                    <th>Circuito</th>
                    <th>Carga</th>
                </tr>
            </thead>
            <tbody>
            @for ($i = 0; $i < count($results); $i++)
                @if (!$results[$i]['used'])
                @php
                    $total += $results[$i]['load'];
                    
                @endphp
                    <tr>
                        <td>{{$results[$i]['voltage_level']}} kV {{rtrim(str_replace('(', '', str_replace(')', '', str_replace('138', '', str_replace('345', '', str_replace('KV', '', str_replace(',', '', str_replace('.', '', $results[$i]['circuit']))))))))}} @if ($results[$i]['day'])<span class="text-warning"><i class="fa fa-sun"></i></span>@endif @if ($results[$i]['night'])<span class="text-secondary"><i class="fa fa-moon"></i></span>@endif</td>
                        <td>{{number_format($results[$i]['load'],2)}} MW</td>
                    </tr>
                @endif
            @endfor
            <tr>
                <td class="text-end"><b>TOTAL</b></td>
                <td><b>{{ number_format($total, 2) }} MW</b></td>
            </tr>
            </tbody>
        @endif
        </table>
    </div>
