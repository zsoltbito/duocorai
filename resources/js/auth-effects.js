function qs(sel, root = document) {
    return root.querySelector(sel);
}
function qsa(sel, root = document) {
    return [...root.querySelectorAll(sel)];
}

function attachPasswordToggle(form) {
    const toggle = qs("[data-password-toggle]", form);
    const input = qs("[data-password-input]", form);
    if (!toggle || !input) return;

    const iconEye = qs("[data-icon-eye]", toggle);
    const iconEyeOff = qs("[data-icon-eyeoff]", toggle);

    toggle.addEventListener("click", () => {
        const isPassword = input.getAttribute("type") === "password";
        input.setAttribute("type", isPassword ? "text" : "password");
        if (iconEye) iconEye.classList.toggle("hidden", !isPassword);
        if (iconEyeOff) iconEyeOff.classList.toggle("hidden", isPassword);
        input.focus();
    });
}

function attachFocusGlow(form) {
    qsa("input", form).forEach((i) => {
        i.addEventListener("focus", () => {
            form.classList.add("ring-2", "ring-indigo-500/30");
        });
        i.addEventListener("blur", () => {
            form.classList.remove("ring-2", "ring-indigo-500/30");
        });
    });
}

function attachShakeOnError(form) {
    // Laravel validation error blocks typically contain `.text-red-600` in Breeze,
    // but we rely on presence of `.auth-has-errors` marker.
    const hasErrors = !!qs("[data-auth-has-errors]", form);
    if (!hasErrors) return;

    form.classList.remove("auth-shake");
    // force reflow
    void form.offsetWidth;
    form.classList.add("auth-shake");
}

function attachSubmitLoading(form) {
    const btn = qs("[data-auth-submit]", form);
    const spinner = qs("[data-auth-spinner]", form);
    if (!btn) return;

    form.addEventListener("submit", () => {
        btn.disabled = true;
        btn.classList.add("opacity-70", "cursor-not-allowed");
        if (spinner) spinner.classList.remove("hidden");
    });
}

function attachCapsWarning(form) {
    const pwd = qs('input[type="password"]', form);
    const warn = qs("[data-caps-warning]", form);
    if (!pwd || !warn) return;

    pwd.addEventListener("keydown", (e) => {
        if (typeof e.getModifierState !== "function") return;
        const on = e.getModifierState("CapsLock");
        warn.classList.toggle("hidden", !on);
    });
}

function initAuthEffects() {
    const forms = qsa("[data-auth-form]");
    forms.forEach((form) => {
        attachPasswordToggle(form);
        attachFocusGlow(form);
        attachShakeOnError(form);
        attachSubmitLoading(form);
        attachCapsWarning(form);
    });
}

document.addEventListener("DOMContentLoaded", initAuthEffects);
