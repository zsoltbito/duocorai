<x-guest-layout>
    @php
        $hasErrors = $errors->any();
    @endphp

    <div class="w-full max-w-md">
        <div class="mb-5 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 rounded-2xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center">
                    {{-- user-plus icon --}}
                    <svg class="h-5 w-5 text-indigo-300" viewBox="0 0 24 24" fill="none">
                        <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M9 11a4 4 0 100-8 4 4 0 000 8Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M19 8v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M22 11h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xl font-semibold tracking-wide">Operations AI</div>
                    <div class="text-xs text-white/60">Register • gyors hozzáférés, tiszta audit</div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-white/10 border border-white/20 shadow-2xl backdrop-blur-xl overflow-hidden">
            <div class="p-6 sm:p-7">
                <div class="mb-5">
                    <h1 class="text-2xl font-semibold">Regisztráció</h1>
                    <p class="text-sm text-white/60 mt-1">
                        Készíts hozzáférést az üzemeltetési vezérlőpulthoz.
                    </p>
                </div>

                @if ($hasErrors)
                    <div class="mb-4 rounded-2xl bg-red-500/10 border border-red-500/20 px-4 py-3 text-sm text-red-100">
                        <div class="font-semibold mb-1">Valami hiányzik / nem stimmel.</div>
                        <div class="text-white/70">Nézd át a mezőket, és próbáld újra.</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4" data-auth-form>
                    @csrf

                    @if ($hasErrors)
                        <div data-auth-has-errors></div>
                    @endif

                    <div>
                        <label class="block text-xs text-white/60 mb-2">Név</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                {{-- id-badge icon --}}
                                <svg class="h-4 w-4 text-white/50" viewBox="0 0 24 24" fill="none">
                                    <path d="M7 7h10v10H7V7Z" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M9 11h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M9 14h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <input
                                class="auth-input pl-11"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Például: Zsolt"
                            >
                        </div>
                        @error('name')
                            <div class="mt-2 text-xs text-red-200">{{ $message }}</div>
                        @enderror
                    </div>

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
                                autocomplete="new-password"
                                placeholder="Legalább 8 karakter"
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

                    <div>
                        <label class="block text-xs text-white/60 mb-2">Jelszó megerősítése</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                {{-- check-circle icon --}}
                                <svg class="h-4 w-4 text-white/50" viewBox="0 0 24 24" fill="none">
                                    <path d="M22 12a10 10 0 11-20 0 10 10 0 0120 0Z" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M8 12l2.4 2.4L16 8.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>

                            <input
                                class="auth-input pl-11"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Ugyanaz a jelszó"
                            >
                        </div>
                        @error('password_confirmation')
                            <div class="mt-2 text-xs text-red-200">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="auth-btn auth-btn-primary" type="submit" data-auth-submit>
                        <span class="inline-flex items-center justify-center gap-2">
                            <svg data-auth-spinner class="h-4 w-4 hidden animate-spin" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2a10 10 0 1010 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Fiók létrehozása
                        </span>
                    </button>

                    <div class="pt-2 text-sm text-white/70">
                        Van már fiókod?
                        <a class="text-white hover:text-indigo-200 underline underline-offset-4" href="{{ route('login') }}">
                            Belépés
                        </a>
                    </div>
                </form>
            </div>

            <div class="px-6 sm:px-7 py-4 border-t border-white/10 bg-black/20">
                <div class="text-xs text-white/60 flex items-center justify-between">
                    <span>Gyors start</span>
                    <span class="hidden sm:inline">Utána jön a dashboard</span>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
