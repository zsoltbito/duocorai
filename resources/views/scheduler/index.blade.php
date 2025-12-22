<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-semibold">Scheduler</h1>
                <p class="text-xs text-white/60">DB-vezérelt feladatok</p>
            </div>

            <a href="{{ route('scheduler.create') }}"
               class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 transition text-sm">
                + Új task
            </a>
        </div>

        @if(session('ok'))
            <div class="mb-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-4 py-3 text-sm">
                {{ session('ok') }}
            </div>
        @endif

        <div class="space-y-3">
            @foreach($tasks as $task)
                <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <div class="font-semibold">
                                {{ $task->name }}
                                <span class="text-xs text-white/50">({{ $task->task_key }})</span>
                            </div>

                            <div class="text-xs text-white/60 mt-1 flex gap-3 flex-wrap">
                                <span>{{ $task->handler }}</span>
                                <span>{{ $task->schedule_type }}</span>
                                <span>next: {{ $task->next_run_at ?? '—' }}</span>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('scheduler.runNow', $task) }}">
                                @csrf
                                <button class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-xs">
                                    ▶ Run now
                                </button>
                            </form>

                            <form method="POST" action="{{ route('scheduler.toggle', $task) }}">
                                @csrf
                                <button class="px-3 py-1.5 rounded-lg text-xs
                                    {{ $task->is_enabled ? 'bg-emerald-600/20' : 'bg-white/10' }}">
                                    {{ $task->is_enabled ? 'Enabled' : 'Disabled' }}
                                </button>
                            </form>

                            <a href="{{ route('scheduler.edit', $task) }}"
                               class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-xs">
                                ✎ Edit
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
