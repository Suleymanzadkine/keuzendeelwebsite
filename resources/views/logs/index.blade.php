<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Logboeken') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Systeemlogboek</h3>
                        <div class="space-x-2">
                            <a href="{{ route('logs.download') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Downloaden
                            </a>
                            <form method="POST" action="{{ route('logs.clear') }}" style="display:inline;">
                                @csrf
                                <button type="submit" onclick="return confirm('Weet je zeker dat je alle logs wilt verwijderen? Dit kan niet ongedaan gemaakt worden.')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Wissen
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($message)
                        <p class="text-gray-500 text-center py-8">{{ $message }}</p>
                    @else
                        <div class="overflow-x-auto border border-gray-200 rounded">
                            <div class="space-y-0 font-mono text-xs bg-gray-900 text-gray-100 p-4 rounded">
                                @forelse($logs as $log)
                                    @php
                                        // Determine log level
                                        $level = 'info';
                                        if (str_contains(strtoupper($log), 'ERROR') || str_contains(strtoupper($log), 'CRITICAL')) {
                                            $level = 'error';
                                        } elseif (str_contains(strtoupper($log), 'WARNING') || str_contains(strtoupper($log), 'ALERT')) {
                                            $level = 'warning';
                                        } elseif (str_contains(strtoupper($log), 'DEBUG')) {
                                            $level = 'debug';
                                        }
                                    @endphp
                                    
                                    <div class="py-2 border-b border-gray-700 last:border-b-0 
                                        @if($level === 'error') text-red-400 @endif
                                        @if($level === 'warning') text-yellow-400 @endif
                                        @if($level === 'debug') text-gray-500 @endif
                                        break-all whitespace-pre-wrap">{{ $log }}</div>
                                @empty
                                    <p class="text-gray-500">Geen logs beschikbaar</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-sm text-gray-600">
                                Totaal weergegeven: <strong>{{ count($logs) }}</strong> entry/entries (meest recente eerst)
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
