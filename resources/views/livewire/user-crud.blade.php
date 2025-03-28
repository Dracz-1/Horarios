<div class="max-w-7xl mx-auto px-6 py-8">
    <h2 class="text-3xl font-bold mb-6">Lista de Utilizadores</h2>

    @if (session()->has('message'))
        <div class="p-4 rounded-lg shadow-md mb-6 border border-green-300">
            {{ session('message') }}
        </div>
    @endif

    <!-- Formulário de Edição -->
    @if($isEdit)
        <div class="mb-8 p-6 rounded-lg shadow-lg border border-gray-200">
            <h3 class="text-xl font-semibold mb-4">Editar Utilizador</h3>

            <form wire:submit.prevent="update">
                <div class="mb-4">
                    <label for="name" class="block font-medium">Nome</label>
                    <input type="text" wire:model="name" class="dark:bg-gray-700 w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block font-medium">Email</label>
                    <input type="email" wire:model="email" class="dark:bg-gray-700 w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="role" class="block font-medium">Função</label>
                    <select wire:model="role" class="dark:bg-gray-700 w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                        <option value="user">Utilizador</option>
                        <option value="admin">Administrador</option>
                        <option value="manager">Gestor</option>
                    </select>
                    @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label for="id_card" class="block font-medium">Cartão (UID)</label>
                    <select wire:model="id_card" class="dark:bg-gray-700 w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                        <option value="" selected>-- Selecione um Cartão --</option>
                        @foreach($cards as $card)
                            <option value="{{ $card->id }}">{{ $card->uid }}</option>
                        @endforeach
                    </select>
                    @error('id_card') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex space-x-4 mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">Atualizar</button>
                    <button type="button" wire:click="resetFields" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg shadow hover:bg-gray-400">Cancelar</button>
                </div>
            </form>
        </div>
    @endif

    <!-- Tabela de Utilizadores -->
    <div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200 pb-8 mt-8">
        <table class="w-full border-collapse text-sm text-left">
            <thead>
                <tr>
                    <th class="border px-6 py-3">Nome</th>
                    <th class="border px-6 py-3">Email</th>
                    <th class="border px-6 py-3">Função</th>
                    <th class="border px-6 py-3">Cartão</th>
                    <th class="border px-6 py-3">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="">
                        <td class="border px-6 py-4">{{ $user->name }}</td>
                        <td class="border px-6 py-4">{{ $user->email }}</td>
                        <td class="border px-6 py-4">
                            @php
                                $roleLabels = [
                                    'admin' => 'Administrador',
                                    'manager' => 'Gestor',
                                    'user' => 'Utilizador',
                                ];
                            @endphp
                            {{ $roleLabels[$user->role] ?? 'Desconhecido' }}
                        </td>
                        <td class="border px-6 py-4">
                            {{ optional($user->card)->uid ?? 'Sem cartão' }}
                        </td>
                        <td class="border px-6 py-4 text-center">
                            <button wire:click="edit({{ $user->id }})" class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-400">Editar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Sem utilizadores.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
