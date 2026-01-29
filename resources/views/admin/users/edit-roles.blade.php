<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Rollen toewijzen: {{ $user->name }}</h2></x-slot>
    <div class="py-6"><div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.users.update-roles', $user) }}">
            @csrf
            <div class="bg-white p-6">
                @foreach($roles as $role)
                <div class="mb-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="mr-2" {{ $user->roles->contains($role) ? 'checked' : '' }}>
                        {{ $role->display_name ?? $role->name }}
                    </label>
                </div>
                @endforeach
                <div class="mt-4">
                    <button class="px-4 py-2 bg-blue-600 text-white">Opslaan</button>
                    <a href="{{ route('admin.users.index') }}" class="ml-2 px-4 py-2 bg-gray-300">Annuleren</a>
                </div>
            </div>
        </form>
    </div></div>
</x-app-layout>