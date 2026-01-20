<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mijn dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <p class="text-lg">
                        {{ __('Welkom,') }}
                        <span class="font-semibold">
                            {{ auth()->user()->name }}
                        </span>
                    </p>

                    <p class="text-sm text-gray-600">
                        {{ __('Dit is jouw persoonlijke dashboard. Je ziet deze pagina alleen als je bent ingelogd.') }}
                    </p>

                    <div class="border-t pt-4 mt-4">
                        <h3 class="font-semibold mb-2">
                            {{ __('Snelle links') }}
                        </h3>

                        <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                            <li>
                                <a href="{{ route('electives.index') }}" class="text-indigo-600 hover:underline">
                                    {{ __('Kies je keuzedelen') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile.edit') }}" class="text-indigo-600 hover:underline">
                                    {{ __('Profiel bewerken') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/') }}" class="text-indigo-600 hover:underline">
                                    {{ __('Terug naar de homepage') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
