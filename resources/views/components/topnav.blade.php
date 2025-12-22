<nav class="sticky top-0 z-40 w-full backdrop-blur-xl bg-black/60 border-b border-white/10">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-14 flex items-center justify-between gap-3">

            {{-- Left --}}
            <div class="flex items-center gap-4">
                <a href="{{ route('ops.dashboard') }}"
                   class="flex items-center gap-2 text-sm font-semibold tracking-wide hover:text-indigo-300 transition">
                    <span class="h-8 w-8 rounded-xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center">
                        {{-- bolt --}}
                        <svg class="h-4 w-4 text-indigo-300" viewBox="0 0 24 24" fill="none">
                            <path d="M13 2L3 14h8l-1 8 11-12h-8l0-8Z"
                                  stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="hidden sm:inline">Operations AI</span>
                </a>

                {{-- Primary nav --}}
                <div class="hidden md:flex items-center gap-1">
                    <x-topnav-link href="{{ route('ops.dashboard') }}" :active="request()->routeIs('ops.*')">
                        🖥 Ops
                    </x-topnav-link>

                    <x-topnav-link href="{{ route('scheduler.index') }}" :active="request()->routeIs('scheduler.*')">
                        ⏱ Scheduler
                    </x-topnav-link>

                    <x-topnav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        📊 Dashboard
                    </x-topnav-link>
                </div>
            </div>

            {{-- Right --}}
            <div class="flex items-center gap-2">
                <span class="hidden sm:inline text-xs text-white/60">
                    {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/15 transition text-xs">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Mobile nav --}}
        <div class="md:hidden pb-3">
            <div class="flex gap-2">
                <x-topnav-link href="{{ route('ops.dashboard') }}" :active="request()->routeIs('ops.*')" mobile>
                    Ops
                </x-topnav-link>

                <x-topnav-link href="{{ route('scheduler.index') }}" :active="request()->routeIs('scheduler.*')" mobile>
                    Scheduler
                </x-topnav-link>

                <x-topnav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" mobile>
                    Dashboard
                </x-topnav-link>
            </div>
        </div>
    </div>
</nav>
