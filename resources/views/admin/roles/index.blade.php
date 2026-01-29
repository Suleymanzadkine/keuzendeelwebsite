<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Rollen</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 bg-green-600 text-white rounded">Nieuwe rol</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="w-full">
                        <thead><tr><th>Naam</th><th>Weergave</th><th>Acties</th></tr></thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr class="border-t">
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->display_name }}</td>
                                <td>
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="text-blue-600">Bewerken</a>
                                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 ml-2" onclick="return confirm('Wissen?')">Verwijderen</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>