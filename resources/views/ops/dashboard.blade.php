<x-app-layout>
    <div class="px-3 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between mb-4 gap-3">
            <div>
                <div class="text-xl font-semibold">Operations Dashboard</div>
                <div class="text-xs opacity-70">UI baseline (következő commit: live snapshot)</div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
            <div class="xl:col-span-2 rounded-2xl bg-white/5 border border-white/10 backdrop-blur p-4">
                <div class="font-semibold">Futó feladatok</div>
                <div class="text-xs opacity-70 mt-1">Még nincs bekötve.</div>
                <div class="mt-4 text-sm opacity-70">—</div>
            </div>

            <div class="rounded-2xl bg-white/5 border border-white/10 backdrop-blur p-4">
                <div class="font-semibold">Hibák</div>
                <div class="text-xs opacity-70 mt-1">Még nincs bekötve.</div>
                <div class="mt-4 text-sm opacity-70">—</div>
            </div>

            <div class="xl:col-span-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur p-4">
                <div class="font-semibold">Utolsó futások</div>
                <div class="text-xs opacity-70 mt-1">Még nincs bekötve.</div>
                <div class="mt-4 text-sm opacity-70">—</div>
            </div>
        </div>
    </div>
</x-app-layout>
