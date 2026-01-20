<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kies je Keuzedelen
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('electives.store') }}" method="POST">
                        @csrf

                        <div class="space-y-4 mb-6">
                            @forelse ($electives as $elective)
                                <label class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                    <input 
                                        type="checkbox" 
                                        name="electives[]" 
                                        value="{{ $elective->id }}"
                                        @if(in_array($elective->id, $userElectives)) checked @endif
                                        class="mt-1 w-4 h-4 text-blue-600 rounded"
                                    />
                                    <div class="ml-3">
                                        <p class="font-semibold text-gray-900">{{ $elective->name }}</p>
                                        @if ($elective->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $elective->description }}</p>
                                        @endif
                                    </div>
                                </label>
                            @empty
                                <p class="text-gray-500">Geen keuzedelen beschikbaar.</p>
                            @endforelse
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Opslaan
                            </button>
                            <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                                Annuleren
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
