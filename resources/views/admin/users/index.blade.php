<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Gebruikers</h2></x-slot>
    <div class="py-6"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6">
            <table class="w-full">
                <thead><tr><th>Naam</th><th>Email</th><th>Rollen</th><th>Acties</th></tr></thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-t">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                        <td><a href="{{ route('admin.users.edit-roles', $user) }}" class="text-blue-600">Rollen</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div></div>
</x-app-layout>