<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keuzedeel Bewerken') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('keuzedelen.update', $keuzedeel) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Naam -->
                        <div class="mb-6">
                            <label for="naam" class="block text-sm font-medium text-gray-700 mb-2">
                                Naam
                            </label>
                            <input type="text" id="naam" name="naam" value="{{ old('naam', $keuzedeel->naam) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('naam') border-red-500 @enderror"
                                   required>
                            @error('naam')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Beschrijving -->
                        <div class="mb-6">
                            <label for="beschrijving" class="block text-sm font-medium text-gray-700 mb-2">
                                Beschrijving
                            </label>
                            <textarea id="beschrijving" name="beschrijving" rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('beschrijving') border-red-500 @enderror"
                                      required>{{ old('beschrijving', $keuzedeel->beschrijving) }}</textarea>
                            @error('beschrijving')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Periode -->
                        <div class="mb-6">
                            <label for="periode" class="block text-sm font-medium text-gray-700 mb-2">
                                Periode
                            </label>
                            <input type="text" id="periode" name="periode" value="{{ old('periode', $keuzedeel->periode) }}" 
                                   placeholder="bijv. Q1, Q2, Q3, Q4"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('periode') border-red-500 @enderror"
                                   required>
                            @error('periode')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Allow multiple enrollments -->
                        <div class="mb-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="allow_multiple" value="1" class="mr-2" {{ old('allow_multiple', $keuzedeel->allow_multiple) ? 'checked' : '' }}>
                                Meerdere keren volgen toegestaan
                            </label>
                        </div>

                        <!-- Minimaal Deelnemers -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="min_deelnemers" class="block text-sm font-medium text-gray-700 mb-2">
                                    Minimaal Deelnemers
                                </label>
                                <input type="number" id="min_deelnemers" name="min_deelnemers" value="{{ old('min_deelnemers', $keuzedeel->min_deelnemers) }}" 
                                       min="1"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('min_deelnemers') border-red-500 @enderror"
                                       required>
                                @error('min_deelnemers')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Maximaal Deelnemers -->
                            <div>
                                <label for="max_deelnemers" class="block text-sm font-medium text-gray-700 mb-2">
                                    Maximaal Deelnemers
                                </label>
                                <input type="number" id="max_deelnemers" name="max_deelnemers" value="{{ old('max_deelnemers', $keuzedeel->max_deelnemers) }}" 
                                       min="1"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_deelnemers') border-red-500 @enderror"
                                       required>
                                @error('max_deelnemers')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4">
                            <button type="submit" class="px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                Opslaan
                            </button>
                            <a href="{{ route('keuzedelen.show', $keuzedeel) }}" class="px-6 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-400 transition">
                                Annuleren
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
