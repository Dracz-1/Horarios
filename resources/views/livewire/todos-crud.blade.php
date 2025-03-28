<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6 text-center">
        Utilizadores Presentes na Sala
    </h1>

    <div class="overflow-x-auto bg-background shadow-lg rounded-xl ">
        <table class="w-full border border-border rounded-lg overflow-hidden">
            <thead class="bg-muted text-muted-foreground">
                <tr>
                    <th class="p-4 text-left font-semibold border">Nome</th>
                    <th class="p-4 text-left font-semibold">Sala</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border border">
                @foreach($users as $user)
                <tr class="hover:bg-muted transition duration-300">
                    <td class="p-4 border">{{ $user->name }}</td>
                    <td class="p-4 border">{{ $user->location_name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
