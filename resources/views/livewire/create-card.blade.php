<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Criar Novo Cartão</h1>

    @if(session()->has('success'))
        <div class="bg-green-500 text-white p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="p-6 rounded-lg shadow-md">
        <div class="mb-4">
            <label for="uid" class="block font-bold mb-2">Número do Cartão (12 dígitos)</label>
            <input type="text" id="uid" wire:model="uid" maxlength="12" pattern="\d{12}" required
                   class="w-full p-2 border border-gray-300 rounded-lg" placeholder="Ex: 123456789012">
            @error('uid') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Criar Cartão</button>
    </form>
</div>
