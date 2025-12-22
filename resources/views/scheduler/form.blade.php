<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-4 max-w-3xl">
        <h1 class="text-xl font-semibold mb-4">
            {{ $mode === 'create' ? 'Új task' : 'Task szerkesztése' }}
        </h1>

        <form method="POST"
              action="{{ $mode === 'create' ? route('scheduler.store') : route('scheduler.update', $task) }}"
              class="space-y-4">
            @csrf
            @if($mode === 'edit') @method('PUT') @endif

            <div>
                <label class="text-xs text-white/60">Név</label>
                <input name="name" value="{{ old('name', $task->name) }}" class="auth-input">
            </div>

            <div>
                <label class="text-xs text-white/60">Task key</label>
                <input name="task_key" value="{{ old('task_key', $task->task_key) }}" class="auth-input">
            </div>

            <div>
                <label class="text-xs text-white/60">Handler</label>
                <select name="handler" class="auth-input">
                    <option value="artisan" @selected($task->handler === 'artisan')>artisan</option>
                    <option value="job" @selected($task->handler === 'job')>job</option>
                </select>
            </div>

            <div>
                <label class="text-xs text-white/60">Artisan command</label>
                <input name="command" value="{{ old('command', $task->command) }}" class="auth-input">
            </div>

            <div>
                <label class="text-xs text-white/60">Schedule</label>
                <select name="schedule_type" class="auth-input">
                    @foreach(['everyMinute','everyFiveMinutes','hourly','daily'] as $s)
                        <option value="{{ $s }}" @selected($task->schedule_type === $s)>
                            {{ $s }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs text-white/60">Timeout (sec)</label>
                    <input name="timeout_seconds" type="number"
                           value="{{ old('timeout_seconds', $task->timeout_seconds ?? 120) }}"
                           class="auth-input">
                </div>

                <div>
                    <label class="text-xs text-white/60">Overlap lock (sec)</label>
                    <input name="overlap_lock_seconds" type="number"
                           value="{{ old('overlap_lock_seconds', $task->overlap_lock_seconds ?? 300) }}"
                           class="auth-input">
                </div>
            </div>

            <div class="pt-3 flex gap-3">
                <button class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 transition">
                    Mentés
                </button>

                <a href="{{ route('scheduler.index') }}"
                   class="px-5 py-2 rounded-xl bg-white/10 hover:bg-white/20 transition">
                    Mégse
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
