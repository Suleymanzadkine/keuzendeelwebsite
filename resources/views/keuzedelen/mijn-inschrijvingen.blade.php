<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mijn inschrijvingen</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($inschrijvingen->isEmpty())
                    <p class="text-gray-600">Je hebt nog geen inschrijvingen.</p>
                    @else
                    <ul class="space-y-4">
                        @foreach($inschrijvingen as $inschrijving)
                        @php $k = $inschrijving->keuzedeel; @endphp
                        <li class="border rounded p-4">
                            <div class="flex justify-between items-start gap-4">
                                <div>
                                    <h3 class="text-lg font-semibold">{{ $k->naam }}</h3>
                                    <p class="text-gray-600 text-sm">{{ Str::limit($k->beschrijving, 200) }}</p>
                                    <div class="mt-2 text-sm text-gray-700">
                                        <span class="font-semibold">Periode:</span> {{ $k->periode }}
                                    </div>
                                    <div class="mt-1 text-sm">
                                        <span class="font-semibold">Status:</span>
                                        @if($inschrijving->status === 'ingeschreven')
                                        <span class="text-green-600 font-semibold">Ingeschreven</span>
                                        @elseif($inschrijving->status === 'afgerond')
                                        <span class="text-blue-600 font-semibold">Afgerond</span>
                                        @else
                                        <span class="text-gray-600">Geannuleerd</span>
                                        @endif
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500">Aangemaakt: {{ $inschrijving->created_at->format('d-m-Y H:i') }}</div>
                                </div>

                                <div class="flex flex-col items-end gap-2">
                                    <a href="{{ route('keuzedelen.show', $k) }}" class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">Bekijk</a>

                                    @if($inschrijving->status === 'ingeschreven')
                                    <form method="POST" action="{{ route('keuzedelen.uitschrijven', $k) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded shadow hover:bg-red-700" onclick="return confirm('Weet je zeker dat je wilt uitschrijven?')">Uitschrijven</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>