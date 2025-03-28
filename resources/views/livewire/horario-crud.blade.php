<div>
    @if($selectedUserId)
        <h2 class="text-2xl font-bold mb-4">Registos do Dia - {{ $users->find($selectedUserId)->name }}</h2>
        
        <!-- Formulário para Selecionar Datas -->
        <div class="mb-4 flex items-end space-x-2">
            <div>
                <label for="startDate" class="block font-semibold">Data de Início:</label>
                <input type="date" wire:model="startDate" class="border rounded px-2 py-1">
            </div>
        
            <div>
                <label for="endDate" class="block font-semibold">Data de Fim:</label>
                <input type="date" wire:model="endDate" class="border rounded px-2 py-1">
            </div>
        
            <button 
                wire:click="filterByDate" 
                class="bg-green-500 text-white px-4 py-2 rounded">
                Filtrar
            </button>
        </div>
        
        <table class="table-auto w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Dia</th>
                    <th class="border px-4 py-2">Hora de Entrada</th>
                    <th class="border px-4 py-2">Hora de Saída</th>
                    <th class="border px-4 py-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dailyRecords as $record)
                    @if(($record->type === 'entry') && (auth()->user()->role === 'manager' || auth()->user()->role === 'admin' || $record->user_id === auth()->user()->id))
                        @php
                            $nextExit = $dailyRecords->first(function($r) use($record) {
                                return $r->type === 'exit' && \Carbon\Carbon::parse($r->date_time)->gt(\Carbon\Carbon::parse($record->date_time));
                            });
                                                                                                                                                        
                            $entryTime = \Carbon\Carbon::parse($record->date_time)->format('H:i');
                            $exitTime = $nextExit ? \Carbon\Carbon::parse($nextExit->date_time)->format('H:i') : '—';
                            $day = \Carbon\Carbon::parse($record->date_time)->format('d/m/Y');
                            $totalMinutes = $nextExit 
                                ? \Carbon\Carbon::parse($record->date_time)->diffInMinutes(\Carbon\Carbon::parse($nextExit->date_time)) 
                                : \Carbon\Carbon::parse($record->date_time)->diffInMinutes(\Carbon\Carbon::now());

                            $hours = floor($totalMinutes / 60);
                            $minutes = $totalMinutes % 60;
                            $total = "{$hours}h{$minutes}min";
                        @endphp
                        <tr>
                            <td class="border px-4 py-2">{{ $day }}</td>
                            <td class="border px-4 py-2">{{ $entryTime }}</td>
                            <td class="border px-4 py-2">{{ $exitTime }}</td>
                            <td class="border px-4 py-2">{{ $total }}</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="4" class="border px-4 py-2 text-center">Sem registos neste período.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Tempo Total na Sala -->
        @php
            $totalTimeInMinutes = 0;
            foreach ($dailyRecords as $record) {
                if ($record->type === 'entry') {
                    $nextExit = $dailyRecords->first(function($r) use($record) {
                        return $r->type === 'exit' && \Carbon\Carbon::parse($r->date_time)->gt(\Carbon\Carbon::parse($record->date_time));
                    });

                    $totalTimeInMinutes += $nextExit 
                        ? \Carbon\Carbon::parse($record->date_time)->diffInMinutes(\Carbon\Carbon::parse($nextExit->date_time))
                        : \Carbon\Carbon::parse($record->date_time)->diffInMinutes(\Carbon\Carbon::now());
                }
            }
            $totalHours = floor($totalTimeInMinutes / 60);
            $totalMinutes = $totalTimeInMinutes % 60;
            $formattedTotalTime = "{$totalHours}h {$totalMinutes}min";
        @endphp
        <div class="mt-4 text-lg font-semibold">
            Tempo total na sala: {{ $formattedTotalTime }}
        </div>

        <button 
            wire:click="closeDailyRecords" 
            class="bg-red-500 text-white px-4 py-2 mt-4 rounded">
            Voltar
        </button>
    @else
        <h2 class="text-2xl font-bold mb-4">Registo de Horários dos Utilizadores</h2>
        <!-- Tabela de Utilizadores -->
        <table class="table-auto w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Utilizador</th>
                    <th class="border px-4 py-2">Hora de Entrada</th>
                    <th class="border px-4 py-2">Hora de Saída</th>
                    <th class="border px-4 py-2">Tempo na Sala</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Sala</th> <!-- Nova coluna para a sala -->
                    <th class="border px-4 py-2">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="border px-4 py-2">{{ $user->name }}</td>
                        <td class="border px-4 py-2">{{ $user->first_entry_time ?? 'Não registado' }}</td>
                        <td class="border px-4 py-2">{{ $user->last_exit_time ?? 'Não registado' }}</td>
                        <td class="border px-4 py-2">{{ $user->time_spent ?? 'Não registado' }}</td>
                        <td class="border px-4 py-2">{{ $user->status ?? 'Fora da sala' }}</td>
                        <td class="border px-4 py-2">{{ $user->room ?? 'Não registada' }}</td> <!-- Exibir a sala -->
                        <td class="border px-4 py-2">
                            <button 
                                wire:click="showDailyRecords({{ $user->id }})" 
                                class="bg-blue-500 text-white px-4 py-2 rounded">
                                Ver Registos
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
