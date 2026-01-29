<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Rol bewerken</h2></x-slot>
    <div class="py-6"><div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf @method('PATCH')
            <div class="mb-4"><label>Naam</label><input name="name" value="{{ $role->name }}" class="w-full border px-3 py-2" required></div>
            <div class="mb-4"><label>Weergave</label><input name="display_name" value="{{ $role->display_name }}" class="w-full border px-3 py-2"></div>
            <button class="px-4 py-2 bg-blue-600 text-white">Opslaan</button>
        </form>
    </div></div>
</x-app-layout>