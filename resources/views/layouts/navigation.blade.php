<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        {{ __('Admin') }}
                                        <svg class="fill-current h-4 w-4 ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.roles.index')">{{ __('Rollen') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.users.index')">{{ __('Gebruikers') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('logs.index')">{{ __('Logboeken') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('keuzedelen.create')">{{ __('Keuzedeel toevoegen') }}</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <div class="flex items-center gap-4">
                    {{-- Notifications bell --}}
                    <div class="relative">
                        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                        <button @click="open = ! open" x-data="{ showNotif: false }" onclick="document.getElementById('notif-dropdown').classList.toggle('hidden')" class="relative inline-flex items-center p-2 rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if($unread > 0)
                            <span class="absolute -top-1 -end-1 inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-xs bg-red-600 text-white">{{ $unread }}</span>
                            @endif
                        </button>
                        <div id="notif-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border rounded shadow-lg z-50">
                            <div class="p-3">
                                <div class="font-semibold mb-2">Meldingen</div>
                                @foreach(auth()->user()->notifications()->orderBy('created_at','desc')->limit(5)->get() as $notification)
                                <div class="border-t pt-2 pb-2">
                                    <div class="text-sm font-semibold">{{ $notification->data['title'] ?? 'Melding' }}</div>
                                    <div class="text-sm text-gray-700">{{ $notification->data['message'] ?? '' }}</div>
                                    <div class="flex gap-2 mt-2">
                                        @if(!empty($notification->data['action_url']))
                                        <a href="{{ $notification->data['action_url'] }}" class="text-blue-600 text-sm">Bekijk</a>
                                        @endif
                                        @if(!$notification->read_at)
                                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">@csrf<button class="text-xs text-green-600">Markeer gelezen</button></form>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                <div class="mt-2 text-center">
                                    <a href="{{ route('notifications.index') }}" class="text-sm text-gray-600">Alle meldingen</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>

                @else
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                    {{ __('Log In') }}
                </a>
                <a href="{{ route('register') }}" class="ms-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                    {{ __('Register') }}
                </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @auth
                @if(auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')">
                    {{ __('Rollen') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Gebruikers') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('logs.index')" :active="request()->routeIs('logs.*')">
                    {{ __('Logboeken') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('keuzedelen.create')" :active="request()->routeIs('keuzedelen.create')">
                    {{ __('Keuzedeel toevoegen') }}
                </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4 space-y-2">
                <a href="{{ route('login') }}" class="block text-sm font-medium text-gray-500 hover:text-gray-700">
                    {{ __('Log In') }}
                </a>
                <a href="{{ route('register') }}" class="block text-sm font-medium text-gray-500 hover:text-gray-700">
                    {{ __('Register') }}
                </a>
            </div>
        </div>
        @endauth
    </div>
</nav>
