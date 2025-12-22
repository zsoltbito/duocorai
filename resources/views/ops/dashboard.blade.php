<x-app-layout>
    <div class="px-3 sm:px-6 lg:px-8 py-4" data-ops-dashboard data-snapshot-url="{{ route('ops.snapshot') }}">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div>
                <div class="text-xl font-semibold">Operations Dashboard</div>
                <div class="text-xs text-white/60 mt-1">
                    Live snapshot • <span class="text-white/80" id="ops-now">—</span>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('dashboard') }}"
                   class="px-3 py-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition text-sm">
                    Laravel dashboard
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
            {{-- Last activity box --}}
            <div class="xl:col-span-3">
                <div id="ops-last"></div>
            </div>

            {{-- Running --}}
            <div class="xl:col-span-2 rounded-2xl bg-white/5 border border-white/10 backdrop-blur p-4">
                <div class="flex items-center justify-between">
                    <div class="font-semibold">Futó feladatok</div>
                    <div class="text-xs text-white/60">2s polling</div>
                </div>
                <div class="mt-3 space-y-2" id="ops-running">
                    <div class="text-sm text-white/60">Töltés...</div>
                </div>
            </div>

            {{-- Errors --}}
            <div class="rounded-2xl bg-white/5 border border-white/10 backdrop-blur p-4">
                <div class="font-semibold">Hibák</div>
                <div class="mt-3 space-y-2" id="ops-errors">
                    <div class="text-sm text-white/60">Töltés...</div>
                </div>
            </div>

            {{-- Recent runs --}}
            <div class="xl:col-span-2 rounded-2xl bg-white/5 border border-white/10 backdrop-blur p-4">
                <div class="font-semibold">Utolsó futások</div>
                <div class="mt-3" id="ops-recent">
                    <div class="text-sm text-white/60">Töltés...</div>
                </div>
            </div>

            {{-- Tasks list --}}
            <div class="rounded-2xl bg-white/5 border border-white/10 backdrop-blur p-4">
                <div class="font-semibold">Taskok</div>
                <div class="mt-3" id="ops-tasks">
                    <div class="text-sm text-white/60">Töltés...</div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/ops-dashboard.js'])
</x-app-layout>
