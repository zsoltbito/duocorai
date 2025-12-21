<x-guest-layout>
    <div class="w-full max-w-md p-8 rounded-2xl bg-white/10 border border-white/20 shadow-2xl backdrop-blur-xl">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Operations AI</h1>
            <p class="text-sm opacity-70">Valós idejű üzemeltetés + automatizált ticketing</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <x-text-input id="email" class="block w-full bg-black/40 border-white/15 rounded-xl"
                              type="email" name="email" :value="old('email')" required autofocus
                              placeholder="Email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-text-input id="password" class="block w-full bg-black/40 border-white/15 rounded-xl"
                              type="password" name="password" required
                              placeholder="Jelszó" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between text-sm opacity-80">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="rounded">
                    Emlékezz rám
                </label>

                @if (Route::has('password.request'))
                    <a class="underline" href="{{ route('password.request') }}">
                        Elfelejtetted?
                    </a>
                @endif
            </div>

            <button class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 transition font-semibold">
                Belépés
            </button>
        </form>

        <div class="mt-6 text-xs opacity-60">
            Secure • AI-powered • Real-time
        </div>
    </div>
</x-guest-layout>
