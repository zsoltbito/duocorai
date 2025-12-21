const canvas = document.getElementById("bg-canvas");
if (canvas) {
    const ctx = canvas.getContext("2d");
    let w, h;
    let mouse = { x: -9999, y: -9999 };
    const particles = [];
    const N = 130;

    function resize() {
        w = canvas.width = window.innerWidth;
        h = canvas.height = window.innerHeight;
    }
    window.addEventListener("resize", resize);
    resize();

    window.addEventListener("mousemove", (e) => {
        mouse.x = e.clientX;
        mouse.y = e.clientY;
    });

    for (let i = 0; i < N; i++) {
        particles.push({
            x: Math.random() * w,
            y: Math.random() * h,
            vx: (Math.random() - 0.5) * 0.35,
            vy: (Math.random() - 0.5) * 0.35,
        });
    }

    function dist(a, b) {
        const dx = a.x - b.x;
        const dy = a.y - b.y;
        return Math.sqrt(dx * dx + dy * dy);
    }

    function tick() {
        ctx.clearRect(0, 0, w, h);

        // grid
        ctx.fillStyle = "rgba(255,255,255,0.02)";
        for (let y = 0; y < h; y += 40) ctx.fillRect(0, y, w, 1);
        for (let x = 0; x < w; x += 40) ctx.fillRect(x, 0, 1, h);

        // move
        for (const p of particles) {
            p.x += p.vx;
            p.y += p.vy;
            if (p.x < 0 || p.x > w) p.vx *= -1;
            if (p.y < 0 || p.y > h) p.vy *= -1;
        }

        // links
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const a = particles[i],
                    b = particles[j];
                const d = dist(a, b);
                if (d < 120) {
                    const alpha = (1 - d / 120) * 0.25;
                    ctx.strokeStyle = `rgba(120,170,255,${alpha})`;
                    ctx.beginPath();
                    ctx.moveTo(a.x, a.y);
                    ctx.lineTo(b.x, b.y);
                    ctx.stroke();
                }
            }
        }

        // particles
        for (const p of particles) {
            const dm = Math.sqrt((p.x - mouse.x) ** 2 + (p.y - mouse.y) ** 2);
            const glow = dm < 180 ? (1 - dm / 180) * 0.6 : 0.15;
            ctx.fillStyle = `rgba(120,170,255,${glow})`;
            ctx.fillRect(p.x, p.y, 2, 2);
        }

        requestAnimationFrame(tick);
    }

    tick();
}
