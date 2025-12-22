(() => {
    const canvas = document.getElementById("bg-canvas");
    if (!canvas) {
        return;
    }

    const ctx = canvas.getContext("2d");
    let w, h;
    const particles = [];
    const COUNT = 120;

    function resize() {
        w = canvas.width = window.innerWidth;
        h = canvas.height = window.innerHeight;
    }

    window.addEventListener("resize", resize);
    resize();

    for (let i = 0; i < COUNT; i++) {
        particles.push({
            x: Math.random() * w,
            y: Math.random() * h,
            vx: (Math.random() - 0.5) * 0.3,
            vy: (Math.random() - 0.5) * 0.3,
        });
    }

    function loop() {
        ctx.clearRect(0, 0, w, h);

        // optional subtle grid
        ctx.fillStyle = "rgba(255,255,255,0.02)";
        for (let y = 0; y < h; y += 40) ctx.fillRect(0, y, w, 1);
        for (let x = 0; x < w; x += 40) ctx.fillRect(x, 0, 1, h);

        particles.forEach((p) => {
            p.x += p.vx;
            p.y += p.vy;

            if (p.x < 0 || p.x > w) p.vx *= -1;
            if (p.y < 0 || p.y > h) p.vy *= -1;

            ctx.fillStyle = "rgba(120,170,255,0.6)";
            ctx.fillRect(p.x, p.y, 2, 2);
        });

        requestAnimationFrame(loop);
    }

    loop();
})();
