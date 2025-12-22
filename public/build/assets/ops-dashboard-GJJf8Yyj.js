(()=>{const x=document.querySelector("[data-ops-dashboard]");if(!x)return;const _=x.getAttribute("data-snapshot-url"),p=document.getElementById("ops-now"),v=document.getElementById("ops-running"),o=document.getElementById("ops-errors"),u=document.getElementById("ops-recent"),h=document.getElementById("ops-tasks"),m=document.getElementById("ops-last");function s(t){return String(t!=null?t:"").replaceAll("&","&amp;").replaceAll("<","&lt;").replaceAll(">","&gt;").replaceAll('"',"&quot;").replaceAll("'","&#039;")}function d(t){var n;return`<span class="inline-flex items-center px-2 py-0.5 rounded-full border text-xs ${(n={success:"bg-emerald-500/15 border-emerald-500/30 text-emerald-100",failed:"bg-red-500/15 border-red-500/30 text-red-100",running:"bg-indigo-500/15 border-indigo-500/30 text-indigo-100",skipped:"bg-amber-500/15 border-amber-500/30 text-amber-100"}[t])!=null?n:"bg-white/10 border-white/15 text-white/80"}">${s(t)}</span>`}function k(t){return t!=null&&t.length?t.map(e=>{var c,l;const a=e.is_stale?'<span class="ml-2 text-xs text-amber-200">⚠ stale heartbeat</span>':"",n=`${s((c=e.task)==null?void 0:c.name)} <span class="text-xs text-white/50">(${s((l=e.task)==null?void 0:l.task_key)})</span>`,i=e.heartbeat_at?s(e.heartbeat_at):"—",r=e.started_at?s(e.started_at):"—";return`
                <div class="rounded-2xl bg-white/5 border border-white/10 p-3">
                    <div class="flex items-center justify-between gap-3">
                        <div class="font-semibold">${n}${a}</div>
                        ${d("running")}
                    </div>
                    <div class="mt-2 text-xs text-white/60 flex gap-3 flex-wrap">
                        <div>Started: <span class="text-white/80">${r}</span></div>
                        <div>Heartbeat: <span class="text-white/80">${i}</span></div>
                        <div>RunID: <span class="text-white/80">#${s(e.id)}</span></div>
                    </div>
                </div>
            `}).join(""):'<div class="text-sm text-white/60">Nincs futó task.</div>'}function y(t){return t!=null&&t.length?t.map(e=>{var a,n;return`
                <div class="rounded-2xl bg-white/5 border border-white/10 p-3">
                    <div class="flex items-center justify-between gap-3">
                        <div class="font-semibold">${s((a=e.task)==null?void 0:a.name)} <span class="text-xs text-white/50">(${s((n=e.task)==null?void 0:n.task_key)})</span></div>
                        ${d("failed")}
                    </div>
                    <div class="mt-2 text-xs text-white/70 whitespace-pre-wrap">${s(e.error)}</div>
                    <div class="mt-2 text-[11px] text-white/50">At: ${s(e.started_at)}</div>
                </div>
            `}).join(""):'<div class="text-sm text-white/60">Nincs friss hiba.</div>'}function j(t){return t!=null&&t.length?`
            <div class="space-y-2">
                ${t.map(e=>{var i,r;const a=e.duration_ms!=null?`${s(e.duration_ms)} ms`:"—",n=e.finished_at?s(e.finished_at):"—";return`
                        <div class="rounded-2xl bg-white/5 border border-white/10 p-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="font-semibold">
                                    ${s((i=e.task)==null?void 0:i.name)}
                                    <span class="text-xs text-white/50">(${s((r=e.task)==null?void 0:r.task_key)})</span>
                                </div>
                                ${d(e.status)}
                            </div>
                            <div class="mt-2 text-xs text-white/60 flex gap-3 flex-wrap">
                                <div>Finished: <span class="text-white/80">${n}</span></div>
                                <div>Duration: <span class="text-white/80">${a}</span></div>
                                <div>RunID: <span class="text-white/80">#${s(e.id)}</span></div>
                            </div>
                            ${e.error?`<div class="mt-2 text-xs text-red-200 whitespace-pre-wrap">${s(e.error)}</div>`:""}
                        </div>
                    `}).join("")}
            </div>
        `:'<div class="text-sm text-white/60">Még nincs futás.</div>'}function I(t){return t!=null&&t.length?`
            <div class="space-y-2">
                ${t.map(e=>{const a=e.is_enabled?'<span class="text-xs text-emerald-200">● enabled</span>':'<span class="text-xs text-white/40">● disabled</span>',n=e.next_run_at?s(e.next_run_at):"—",i=e.last_run_at?s(e.last_run_at):"—";return`
                        <div class="rounded-2xl bg-white/5 border border-white/10 p-3">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="font-semibold">${s(e.name)} <span class="text-xs text-white/50">(${s(e.task_key)})</span></div>
                                    <div class="text-xs text-white/60 mt-1 flex gap-3 flex-wrap">
                                        <span>${a}</span>
                                        <span>schedule: <span class="text-white/80">${s(e.schedule_type)}</span></span>
                                        <span>handler: <span class="text-white/80">${s(e.handler)}</span></span>
                                    </div>
                                </div>
                                ${e.last_status?d(e.last_status):d("—")}
                            </div>

                            <div class="mt-2 text-xs text-white/60 flex gap-3 flex-wrap">
                                <div>Last: <span class="text-white/80">${i}</span></div>
                                <div>Next: <span class="text-white/80">${n}</span></div>
                            </div>

                            ${e.last_error?`<div class="mt-2 text-xs text-red-200 whitespace-pre-wrap">${s(e.last_error)}</div>`:""}
                        </div>
                    `}).join("")}
            </div>
        `:'<div class="text-sm text-white/60">Nincs task a DB-ben.</div>'}function L(t){var n,i,r,c,l,b,f,g;const e=(r=(n=t.running)==null?void 0:n[0])!=null?r:(i=t.recent_runs)==null?void 0:i[0];if(!e)return'<div class="text-sm text-white/60">Nincs még aktivitás.</div>';const a=((c=e.progress)!=null?c:[]).slice(-4).map($=>`<div class="text-xs text-white/70">
                <span class="text-white/50">${s($.time)}:</span> ${s($.message)}
            </div>`).join("");return`
            <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                <div class="flex items-center justify-between gap-3">
                    <div class="font-semibold">Last activity</div>
                    <div class="text-xs text-white/60">RunID: #${s(e.id)}</div>
                </div>
                <div class="mt-2 text-sm text-white/80">
                    ${s((b=(l=e.task)==null?void 0:l.name)!=null?b:"—")} <span class="text-xs text-white/50">(${s((g=(f=e.task)==null?void 0:f.task_key)!=null?g:"")})</span>
                </div>
                <div class="mt-3 space-y-1">
                    ${a||'<div class="text-sm text-white/60">Nincs progress adat.</div>'}
                </div>
            </div>
        `}async function w(){var t,e;try{const a=await fetch(_,{headers:{Accept:"application/json"}});if(!a.ok)throw new Error(`HTTP ${a.status}`);const n=await a.json();p&&(p.textContent=(t=n.now)!=null?t:"—"),v&&(v.innerHTML=k(n.running)),o&&(o.innerHTML=y(n.errors)),u&&(u.innerHTML=j(n.recent_runs)),h&&(h.innerHTML=I(n.tasks)),m&&(m.innerHTML=L(n))}catch(a){const n=`Snapshot error: ${(e=a==null?void 0:a.message)!=null?e:a}`;o&&(o.innerHTML=`<div class="rounded-2xl bg-red-500/10 border border-red-500/20 p-3 text-sm text-red-100">${s(n)}</div>`)}}w(),setInterval(w,2e3)})();
