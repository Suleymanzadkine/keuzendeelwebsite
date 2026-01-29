<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Meldingen</h2></x-slot>
    <div class="py-6"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6">
            <div class="mb-4">
                <form method="POST" action="{{ route('notifications.mark-all-read') }}">@csrf<button class="px-3 py-1 bg-gray-200">Markeer alle als gelezen</button></form>
            </div>
            @if(session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif
            <ul>
                @foreach($notifications as $notification)
                <li class="border-t py-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-sm font-semibold">{{ $notification->data['title'] ?? 'Melding' }}</div>
                            <div class="text-sm text-gray-700">{{ $notification->data['message'] ?? '' }}</div>
                            @if(!empty($notification->data['action_url']))
                            <a href="{{ $notification->data['action_url'] }}" class="text-blue-600 text-sm">Bekijk</a>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                    @if(!$notification->read_at)
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">@csrf<button class="mt-2 px-3 py-1 bg-green-600 text-white text-sm">Markeer als gelezen</button></form>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    </div></div>
</x-app-layout>