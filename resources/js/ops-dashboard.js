(() => {
    const root = document.querySelector("[data-ops-dashboard]");
    if (!root) return;

    const snapshotUrl = root.getAttribute("data-snapshot-url");
    const elNow = document.getElementById("ops-now");
    const elRunning = document.getElementById("ops-running");
    const elErrors = document.getElementById("ops-errors");
    const elRecent = document.getElementById("ops-recent");
    const elTasks = document.getElementById("ops-tasks");
    const elLast = document.getElementById("ops-last");

    function esc(s) {
        return String(s ?? "")
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;");
    }

    function badge(status) {
        const m = {
            success: "bg-emerald-500/15 border-emerald-500/30 text-emerald-100",
            failed: "bg-red-500/15 border-red-500/30 text-red-100",
            running: "bg-indigo-500/15 border-indigo-500/30 text-indigo-100",
            skipped: "bg-amber-500/15 border-amber-500/30 text-amber-100",
        };
        const cls = m[status] ?? "bg-white/10 border-white/15 text-white/80";
        return `<span class="inline-flex items-center px-2 py-0.5 rounded-full border text-xs ${cls}">${esc(status)}</span>`;
    }

    function renderRunning(list) {
        if (!list?.length)
            return `<div class="text-sm text-white/60">Nincs futó task.</div>`;

        return list
            .map((r) => {
                const stale = r.is_stale
                    ? `<span class="ml-2 text-xs text-amber-200">⚠ stale heartbeat</span>`
                    : "";
                const title = `${esc(r.task?.name)} <span class="text-xs text-white/50">(${esc(r.task?.task_key)})</span>`;
                const hb = r.heartbeat_at ? esc(r.heartbeat_at) : "—";
                const started = r.started_at ? esc(r.started_at) : "—";

                return `
                <div class="rounded-2xl bg-white/5 border border-white/10 p-3">
                    <div class="flex items-center justify-between gap-3">
                        <div class="font-semibold">${title}${stale}</div>
                        ${badge("running")}
                    </div>
                    <div class="mt-2 text-xs text-white/60 flex gap-3 flex-wrap">
                        <div>Started: <span class="text-white/80">${started}</span></div>
                        <div>Heartbeat: <span class="text-white/80">${hb}</span></div>
                        <div>RunID: <span class="text-white/80">#${esc(r.id)}</span></div>
                    </div>
                </div>
            `;
            })
            .join("");
    }

    function renderErrors(list) {
        if (!list?.length)
            return `<div class="text-sm text-white/60">Nincs friss hiba.</div>`;

        return list
            .map((e) => {
                return `
                <div class="rounded-2xl bg-white/5 border border-white/10 p-3">
                    <div class="flex items-center justify-between gap-3">
                        <div class="font-semibold">${esc(e.task?.name)} <span class="text-xs text-white/50">(${esc(e.task?.task_key)})</span></div>
                        ${badge("failed")}
                    </div>
                    <div class="mt-2 text-xs text-white/70 whitespace-pre-wrap">${esc(e.error)}</div>
                    <div class="mt-2 text-[11px] text-white/50">At: ${esc(e.started_at)}</div>
                </div>
            `;
            })
            .join("");
    }

    function renderRecent(list) {
        if (!list?.length)
            return `<div class="text-sm text-white/60">Még nincs futás.</div>`;

        return `
            <div class="space-y-2">
                ${list
                    .map((r) => {
                        const dur =
                            r.duration_ms != null
                                ? `${esc(r.duration_ms)} ms`
                                : "—";
                        const finished = r.finished_at
                            ? esc(r.finished_at)
                            : "—";
                        return `
                        <div class="rounded-2xl bg-white/5 border border-white/10 p-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="font-semibold">
                                    ${esc(r.task?.name)}
                                    <span class="text-xs text-white/50">(${esc(r.task?.task_key)})</span>
                                </div>
                                ${badge(r.status)}
                            </div>
                            <div class="mt-2 text-xs text-white/60 flex gap-3 flex-wrap">
                                <div>Finished: <span class="text-white/80">${finished}</span></div>
                                <div>Duration: <span class="text-white/80">${dur}</span></div>
                                <div>RunID: <span class="text-white/80">#${esc(r.id)}</span></div>
                            </div>
                            ${r.error ? `<div class="mt-2 text-xs text-red-200 whitespace-pre-wrap">${esc(r.error)}</div>` : ""}
                        </div>
                    `;
                    })
                    .join("")}
            </div>
        `;
    }

    function renderTasks(list) {
        if (!list?.length)
            return `<div class="text-sm text-white/60">Nincs task a DB-ben.</div>`;

        return `
            <div class="space-y-2">
                ${list
                    .map((t) => {
                        const enabled = t.is_enabled
                            ? `<span class="text-xs text-emerald-200">● enabled</span>`
                            : `<span class="text-xs text-white/40">● disabled</span>`;

                        const next = t.next_run_at ? esc(t.next_run_at) : "—";
                        const last = t.last_run_at ? esc(t.last_run_at) : "—";

                        return `
                        <div class="rounded-2xl bg-white/5 border border-white/10 p-3">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="font-semibold">${esc(t.name)} <span class="text-xs text-white/50">(${esc(t.task_key)})</span></div>
                                    <div class="text-xs text-white/60 mt-1 flex gap-3 flex-wrap">
                                        <span>${enabled}</span>
                                        <span>schedule: <span class="text-white/80">${esc(t.schedule_type)}</span></span>
                                        <span>handler: <span class="text-white/80">${esc(t.handler)}</span></span>
                                    </div>
                                </div>
                                ${t.last_status ? badge(t.last_status) : badge("—")}
                            </div>

                            <div class="mt-2 text-xs text-white/60 flex gap-3 flex-wrap">
                                <div>Last: <span class="text-white/80">${last}</span></div>
                                <div>Next: <span class="text-white/80">${next}</span></div>
                            </div>

                            ${t.last_error ? `<div class="mt-2 text-xs text-red-200 whitespace-pre-wrap">${esc(t.last_error)}</div>` : ""}
                        </div>
                    `;
                    })
                    .join("")}
            </div>
        `;
    }

    function renderLastBox(data) {
        // Külön “utolsó érintett” box: legyen a legfrissebb running, ha nincs, akkor a legfrissebb recent.
        const last = data.running?.[0] ?? data.recent_runs?.[0];
        if (!last)
            return `<div class="text-sm text-white/60">Nincs még aktivitás.</div>`;

        const lines = (last.progress ?? [])
            .slice(-4)
            .map((p) => {
                return `<div class="text-xs text-white/70">
                <span class="text-white/50">${esc(p.time)}:</span> ${esc(p.message)}
            </div>`;
            })
            .join("");

        return `
            <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                <div class="flex items-center justify-between gap-3">
                    <div class="font-semibold">Last activity</div>
                    <div class="text-xs text-white/60">RunID: #${esc(last.id)}</div>
                </div>
                <div class="mt-2 text-sm text-white/80">
                    ${esc(last.task?.name ?? "—")} <span class="text-xs text-white/50">(${esc(last.task?.task_key ?? "")})</span>
                </div>
                <div class="mt-3 space-y-1">
                    ${lines || `<div class="text-sm text-white/60">Nincs progress adat.</div>`}
                </div>
            </div>
        `;
    }

    async function refresh() {
        try {
            const res = await fetch(snapshotUrl, {
                headers: { Accept: "application/json" },
            });

            if (!res.ok) throw new Error(`HTTP ${res.status}`);

            const data = await res.json();

            if (elNow) elNow.textContent = data.now ?? "—";
            if (elRunning) elRunning.innerHTML = renderRunning(data.running);
            if (elErrors) elErrors.innerHTML = renderErrors(data.errors);
            if (elRecent) elRecent.innerHTML = renderRecent(data.recent_runs);
            if (elTasks) elTasks.innerHTML = renderTasks(data.tasks);
            if (elLast) elLast.innerHTML = renderLastBox(data);
        } catch (e) {
            const msg = `Snapshot error: ${e?.message ?? e}`;
            if (elErrors) {
                elErrors.innerHTML = `<div class="rounded-2xl bg-red-500/10 border border-red-500/20 p-3 text-sm text-red-100">${esc(msg)}</div>`;
            }
        }
    }

    refresh();
    setInterval(refresh, 2000);
})();
