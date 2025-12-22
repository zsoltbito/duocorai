<x-guest-layout>
    @php
        $hasErrors = $errors->any();
    @endphp

    <div class="w-full max-w-md">
        <div class="mb-5 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 rounded-2xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center">
                    {{-- bolt icon --}}
                    <svg class="h-5 w-5 text-indigo-300" viewBox="0 0 24 24" fill="none">
                        <path d="M13 2L3 14h8l-1 8 11-12h-8l0-8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xl font-semibold tracking-wide">Operations AI</div>
                    <div class="text-xs text-white/60">Login • Real-time ops • Ticket automation</div>
                </div>
            </div>

            <div class="hidden sm:flex gap-2">
                <span class="auth-chip">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2l7 4v6c0 5-3 9-7 10-4-1-7-5-7-10V6l7-4Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M9.5 12l1.8 1.8L15.5 9.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Secure
                </span>
                <span class="auth-chip">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none">
                        <path d="M4 13c2-6 14-6 16 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M12 13v7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M8 21h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    Live
                </span>
            </div>
        </div>

        <div class="rounded-3xl bg-white/10 border border-white/20 shadow-2xl backdrop-blur-xl overflow-hidden">
            <div class="p-6 sm:p-7">
                <div class="mb-5">
                    <h1 class="text-2xl font-semibold">Belépés</h1>
                    <p class="text-sm text-white/60 mt-1">
                        Kezeld a futó taskokat, mailbotot, monitoringot és a ticketeket.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 px-4 py-3 text-sm text-emerald-100">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($hasErrors)
                    <div class="mb-4 rounded-2xl bg-red-500/10 border border-red-500/20 px-4 py-3 text-sm text-red-100">
                        <div class="font-semibold mb-1">Hopp, ez most nem ment át.</div>
                        <div class="text-white/70">Ellenőrizd az email/jelszó párost, és próbáld újra.</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4" data-auth-form>
                    @csrf

                    @if ($hasErrors)
                        <div data-auth-has-errors></div>
                    @endif

                    <div>
                        <label class="block text-xs text-white/60 mb-2">Email</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                {{-- mail icon --}}
                                <svg class="h-4 w-4 text-white/50" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="1.8" />
                                    <path d="M4 7l8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <input
                                class="auth-input pl-11"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="te@ceg.hu"
                            >
                        </div>
                        @error('email')
                            <div class="mt-2 text-xs text-red-200">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs text-white/60 mb-2">Jelszó</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                {{-- lock icon --}}
                                <svg class="h-4 w-4 text-white/50" viewBox="0 0 24 24" fill="none">
                                    <path d="M7 11V8a5 5 0 0110 0v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M6 11h12v10H6V11Z" stroke="currentColor" stroke-width="1.8" />
                                </svg>
                            </div>

                            <input
                                class="auth-input pl-11 pr-12"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                data-password-input
                            >

                            <button type="button"
                                    class="absolute inset-y-0 right-0 px-4 text-white/60 hover:text-white transition"
                                    data-password-toggle
                                    aria-label="Jelszó megjelenítése/elrejtése">
                                {{-- eye icon --}}
                                <svg data-icon-eye class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                    <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M12 15a3 3 0 100-6 3 3 0 000 6Z" stroke="currentColor" stroke-width="1.8"/>
                                </svg>
                                {{-- eye-off icon --}}
                                <svg data-icon-eyeoff class="h-4 w-4 hidden" viewBox="0 0 24 24" fill="none">
                                    <path d="M3 3l18 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M10.6 10.6a2.5 2.5 0 003.5 3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M6.5 6.6C4 8.5 2 12 2 12s4 7 10 7c2 0 3.7-.7 5.2-1.7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M14.1 9.2C13.5 9 12.8 8.9 12 8.9c-2.5 0-4.1 1.2-5.2 2.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M19.5 17.4C21.3 15.7 22 12 22 12s-4-7-10-7c-1.2 0-2.3.2-3.2.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-2 text-xs text-amber-100/80 hidden" data-caps-warning>
                            ⚠️ Caps Lock be van kapcsolva.
                        </div>

                        @error('password')
                            <div class="mt-2 text-xs text-red-200">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="inline-flex items-center gap-2 text-white/70">
                            <input type="checkbox" name="remember" class="rounded border-white/20 bg-black/30">
                            Emlékezz rám
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-white/70 hover:text-white underline underline-offset-4" href="{{ route('password.request') }}">
                                Elfelejtetted?
                            </a>
                        @endif
                    </div>

                    <button class="auth-btn auth-btn-primary" type="submit" data-auth-submit>
                        <span class="inline-flex items-center justify-center gap-2">
                            <svg data-auth-spinner class="h-4 w-4 hidden animate-spin" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2a10 10 0 1010 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Belépés
                        </span>
                    </button>

                    <div class="pt-2 text-sm text-white/70">
                        Nincs még fiókod?
                        <a class="text-white hover:text-indigo-200 underline underline-offset-4" href="{{ route('register') }}">
                            Regisztráció
                        </a>
                    </div>
                </form>
            </div>

            <div class="px-6 sm:px-7 py-4 border-t border-white/10 bg-black/20">
                <div class="text-xs text-white/60 flex items-center justify-between">
                    <span>v1 • Ops stack</span>
                    <span class="hidden sm:inline">Tip: Enter után azonnal indul a submit</span>
                </div>
            </div>
        </div>

        <div class="mt-5 text-center text-xs text-white/40">
            “Stabil, zajmentes, auditálható üzemeltetés.”
        </div>
    </div>
</x-guest-layout>
