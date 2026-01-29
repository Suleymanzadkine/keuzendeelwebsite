<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $keuzedeel->naam }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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

            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('keuzedelen.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    ← Terug
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Description -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-3">Beschrijving</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $keuzedeel->beschrijving }}</p>
                    </div>

                    <!-- Information -->
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-lg font-semibold mb-3">Informatie</h3>
                            <dl class="space-y-2 text-sm">
                                <div>
                                    <dt class="font-semibold text-gray-700">Periode:</dt>
                                    <dd class="text-gray-600">{{ $keuzedeel->periode }}</dd>
                                </div>
                                <div>
                                    <dt class="font-semibold text-gray-700">Minimaal deelnemers:</dt>
                                    <dd class="text-gray-600">{{ $keuzedeel->min_deelnemers }}</dd>
                                </div>
                                <div>
                                    <dt class="font-semibold text-gray-700">Maximaal deelnemers:</dt>
                                    <dd class="text-gray-600">{{ $keuzedeel->max_deelnemers }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-3">Inschrijvingen</h3>
                            <dl class="space-y-2 text-sm">
                                <div>
                                    <dt class="font-semibold text-gray-700">Ingeschreven:</dt>
                                    <dd class="text-gray-600">
                                        <span class="font-semibold">{{ $keuzedeel->aantalIngeschreven() }}</span> / {{ $keuzedeel->max_deelnemers }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="font-semibold text-gray-700">Status:</dt>
                                    <dd class="text-gray-600">
                                        @if($keuzedeel->isVol())
                                        <span class="text-red-600 font-semibold">Vol</span>
                                        @else
                                        <span class="text-green-600 font-semibold">Beschikbaar</span>
                                        @endif
                                    </dd>
                                </div>
                                @if(auth()->user() && auth()->user()->is_admin)
                                <div>
                                    <dt class="font-semibold text-gray-700">Activering:</dt>
                                    <dd class="text-gray-600">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $keuzedeel->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $keuzedeel->is_active ? 'Actief' : 'Inactief' }}
                                        </span>
                                    </dd>
                                </div>
                                @if($keuzedeel->isBelowMinimum())
                                <div>
                                    <dt class="font-semibold text-gray-700">Let op:</dt>
                                    <dd class="text-gray-600">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800">Te weinig inschrijvingen ({{ $keuzedeel->aantalIngeschreven() }} / {{ $keuzedeel->min_deelnemers }})</span>
                                    </dd>
                                </div>
                                @endif
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @php
                    $user = auth()->user();
                    $isIngeschreven = $user->actieveInschrijvingen()
                        ->where('keuzedeel_id', $keuzedeel->id)
                        ->exists();
                    $isAfgerond = $user->heeftKeuzedeelAfgerond($keuzedeel->id);
                    $heeftPeriode = $user->heeftInschrijvingVoorPeriode($keuzedeel->periode);
                    @endphp

                    <div class="border-t pt-6">
                        @if($isIngeschreven)
                        <p class="text-green-600 font-semibold mb-4">✓ Je bent ingeschreven voor dit keuzedeel</p>
                        <form method="POST" action="{{ route('keuzedelen.uitschrijven', $keuzedeel) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-6 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 transition">
                                Uitschrijven
                            </button>
                        </form>
                        @elseif($isAfgerond)
                        <p class="text-blue-600 font-semibold">✓ Je hebt dit keuzedeel al afgerond</p>
                        @elseif($keuzedeel->isVol())
                        <p class="text-red-600 font-semibold">Dit keuzedeel is vol</p>
                        @elseif($heeftPeriode)
                        <p class="text-yellow-600 font-semibold">Je hebt al een keuzedeel in periode {{ $keuzedeel->periode }}</p>
                        @else
                        <form method="POST" action="{{ route('keuzedelen.inschrijven', $keuzedeel) }}">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 transition shadow focus:outline-none" style="background-color:#16a34a;color:#ffffff">
                                Inschrijven
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Participants List -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Ingeschrevenen ({{ count($keuzedeel->actieveInschrijvingen) }})</h3>
                    
                    @if($keuzedeel->actieveInschrijvingen->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Naam</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Email</th>
                                    @if(auth()->user()->is_admin)
                                    <th class="px-4 py-2 text-left text-sm font-semibold">Acties</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @if($keuzedeel->allow_multiple)
                                    @php
                                        $grouped = $keuzedeel->actieveInschrijvingen->groupBy('user_id');
                                    @endphp
                                    @foreach($grouped as $userId => $inschrijvingen)
                                    @php $user = $inschrijvingen->first()->user; @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm">{{ $user->name }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $user->email }} @if(count($inschrijvingen) > 1)<span class="text-xs text-gray-500"> ({{ count($inschrijvingen) }})</span>@endif</td>
                                        @if(auth()->user()->is_admin)
                                        <td class="px-4 py-2 text-sm">
                                            <form method="POST" action="{{ route('inschrijvingen.verwijderen.user', [$keuzedeel, $user]) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold" onclick="return confirm('Zeker weten dat je alle inschrijvingen van deze leerling wilt verwijderen?')">
                                                    Verwijderen
                                                </button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                @else
                                    @foreach($keuzedeel->actieveInschrijvingen as $inschrijving)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm">{{ $inschrijving->user->name }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $inschrijving->user->email }}</td>
                                        @if(auth()->user()->is_admin)
                                        <td class="px-4 py-2 text-sm">
                                            <form method="POST" action="{{ route('inschrijvingen.verwijderen', $inschrijving) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold" onclick="return confirm('Zeker weten dat je deze leerling wilt verwijderen?')">
                                                    Verwijderen
                                                </button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-gray-600">Nog geen inschrijvingen</p>
                    @endif
                </div>
            </div>

            <!-- Admin Actions -->
            @if(auth()->user()->is_admin)
            <div class="mt-8 flex gap-4 flex-wrap">
                <a href="{{ route('keuzedelen.edit', $keuzedeel) }}" class="px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 transition">
                    Bewerken
                </a>
                <form method="POST" action="{{ route('keuzedelen.toggle-active', $keuzedeel) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="px-6 py-2 {{ $keuzedeel->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} border border-transparent rounded-md font-semibold text-white transition">
                        {{ $keuzedeel->is_active ? '⊘ Deactiveren' : '✓ Activeren' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('keuzedelen.destroy', $keuzedeel) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 transition" onclick="return confirm('Zeker weten dat je dit keuzedeel wilt verwijderen?')">
                        Verwijderen
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
