<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keuzedelen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
            @endif

            <!-- Link to my registrations -->
            <div class="mb-6">
                <a href="{{ route('mijn-inschrijvingen') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    Mijn Inschrijvingen
                </a>
            </div>

            <!-- Keuzedelen Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($keuzedelen as $keuzedeel)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">{{ $keuzedeel->naam }}</h3>
                        
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($keuzedeel->beschrijving, 100) }}</p>

                        <div class="space-y-2 text-sm mb-4">
                            <p><strong>Periode:</strong> {{ $keuzedeel->periode }}</p>
                            <p><strong>Ingeschreven:</strong> <span class="font-semibold">{{ $keuzedeel->aantalIngeschreven() }} / {{ $keuzedeel->max_deelnemers }}</span></p>
                            <p><strong>Minimaal:</strong> {{ $keuzedeel->min_deelnemers }}</p>
                        </div>

                        @php
                        $isIngeschreven = auth()->user()->actieveInschrijvingen()
                            ->where('keuzedeel_id', $keuzedeel->id)
                            ->exists() ?? false;
                        $isAfgerond = auth()->user()->heeftKeuzedeelAfgerond($keuzedeel->id) ?? false;
                        $heeftPeriode = auth()->user()->heeftInschrijvingVoorPeriode($keuzedeel->periode) ?? false;
                        @endphp

                        <div class="flex gap-2 mt-4">
                            <a href="{{ route('keuzedelen.show', $keuzedeel) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                Details
                            </a>

                            @if($isIngeschreven)
                            <form method="POST" action="{{ route('keuzedelen.uitschrijven', $keuzedeel) }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                                    Uitschrijven
                                </button>
                            </form>
                            @elseif($isAfgerond)
                            <button disabled class="flex-1 px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed">
                                Afgerond
                            </button>
                            @elseif($keuzedeel->isVol())
                            <button disabled class="flex-1 px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed">
                                Vol
                            </button>
                            @elseif($heeftPeriode)
                            <button disabled class="flex-1 px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed" title="Je hebt al een keuzedeel in periode {{ $keuzedeel->periode }}">
                                Al Ingeschreven
                            </button>
                            @else
                            <form method="POST" action="{{ route('keuzedelen.inschrijven', $keuzedeel) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                                    Inschrijven
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($keuzedelen->isEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <p>Geen keuzedelen beschikbaar.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
