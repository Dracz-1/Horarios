<x-layouts.app :title="__('Todos')">
    <!-- Verificar se existe uma mensagem de erro -->
    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded-md mb-4">
            {{ session('error') }}
        </div>
    @endif
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-background shadow-md">
            
            <!-- Botão de Login -->
            <div class="flex justify-end p-4">
                @guest
                <a href="{{ route('login') }}" class="rounded-lg bg-gray-800 px-5 py-2 text-primary-foreground font-medium shadow-md hover:bg-gray-500 transition duration-300">
                    {{ __('Login') }}
                </a>
                @endguest
            </div>  

            <!-- Componente Livewire -->
            <livewire:todos-crud />
        </div>
    </div>
</x-layouts.app>
