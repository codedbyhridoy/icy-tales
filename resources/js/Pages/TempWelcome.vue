<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const year = new Date().getFullYear();
</script>

<template>
    <Head title="Welcome" />
    <main class="container">
        <section class="card" aria-label="DentalMatch.ch coming soon">
            <div class="brand" aria-label="DentalMatch brand">
                <!-- Simple tooth SVG icon -->
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path
                        d="M7.5 21c1.2 0 1.8-1 2.3-2 .6-1.1 1.2-2.3 2.2-2.3s1.6 1.2 2.2 2.3c.6 1 1.1 2 2.3 2 1.5 0 2.3-1.3 2.6-2.8.4-2.1.7-4.3.8-6.4.1-2.8-1-4.8-2.7-6-1.6-1.1-3.7-1.5-5.2-.6-1.5-.9-3.6-.5-5.2.6-1.7 1.2-2.8 3.2-2.7 6 .1 2.1.4 4.3.8 6.4C5.2 19.7 6 21 7.5 21Z"
                        stroke="currentColor"
                        stroke-width="1.25"
                    />
                </svg>
                <span class="name">DentalMatch</span>
                <span class="tld">.ch</span>
            </div>

            <h1>We&rsquo;re getting everything ready.</h1>
            <p class="lead">
                A smarter way to connect dental professionals and clinics is on
                the way. <strong>DentalMatch.ch</strong> is launching soon.
            </p>
        </section>

        <footer>
            &copy; <span>{{ year }}</span> DentalMatch.ch
        </footer>
    </main>
</template>

<style>
:root {
    --bg: #0f172a; /* slate-900 */
    --fg: #e2e8f0; /* slate-200 */
    --muted: #94a3b8; /* slate-400 */
    --accent: #22d3ee; /* cyan-400 */
    --card: rgba(255, 255, 255, 0.06);
    --ring: rgba(34, 211, 238, 0.45);
}

@media (prefers-color-scheme: light) {
    :root {
        --bg: #f8fafc; /* slate-50 */
        --fg: #0f172a; /* slate-900 */
        --muted: #334155; /* slate-700 */
        --card: rgba(15, 23, 42, 0.04);
        --ring: rgba(34, 211, 238, 0.35);
    }
}

* {
    box-sizing: border-box;
}
html,
body {
    height: 100%;
}
body {
    margin: 0;
    display: grid;
    place-items: center;
    min-height: 100%;
    color: var(--fg);
    background:
        radial-gradient(
            1200px 800px at 10% 10%,
            rgba(34, 211, 238, 0.12),
            transparent 60%
        ),
        radial-gradient(
            900px 700px at 90% 80%,
            rgba(59, 130, 246, 0.1),
            transparent 60%
        ),
        var(--bg);
    font-family:
        ui-sans-serif,
        system-ui,
        -apple-system,
        Segoe UI,
        Roboto,
        Helvetica,
        Arial,
        'Apple Color Emoji',
        'Segoe UI Emoji';
    line-height: 1.5;
}

.container {
    width: min(92vw, 780px);
    padding: 28px;
}

.card {
    position: relative;
    overflow: hidden;
    border-radius: 1.25rem;
    padding: clamp(28px, 6vw, 56px);
    background: var(--card);
    backdrop-filter: blur(8px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.brand {
    display: inline-flex;
    align-items: center;
    gap: 0.65rem;
    font-weight: 800;
    letter-spacing: 0.2px;
    font-size: clamp(1.25rem, 2.6vw, 1.75rem);
}
.brand svg {
    width: 28px;
    height: 28px;
    filter: drop-shadow(0 1px 0 rgba(0, 0, 0, 0.2));
}
.brand .name {
    background: linear-gradient(90deg, var(--fg), var(--accent));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

h1 {
    margin: 0.25rem 0 0;
    font-size: clamp(1.8rem, 4.2vw, 2.6rem);
}
p.lead {
    margin: 0.5rem 0 1.25rem;
    color: var(--muted);
    font-size: clamp(1rem, 2.2vw, 1.125rem);
}

.actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 1rem;
}
.btn {
    appearance: none;
    border: 1px solid rgba(255, 255, 255, 0.14);
    color: var(--fg);
    background: transparent;
    padding: 0.7rem 1rem;
    border-radius: 0.9rem;
    cursor: pointer;
    font-weight: 600;
    transition:
        transform 0.06s ease,
        box-shadow 0.2s ease,
        border-color 0.2s ease,
        background-color 0.2s ease;
}
.btn.primary {
    border-color: transparent;
    background: linear-gradient(90deg, var(--accent), #60a5fa);
    color: #0b1220;
}
.btn:focus {
    outline: none;
    box-shadow: 0 0 0 6px var(--ring);
}
.btn:hover {
    transform: translateY(-1px);
}

.meta {
    margin-top: 1.25rem;
    font-size: 0.95rem;
    color: var(--muted);
}
.meta a {
    color: var(--fg);
    text-underline-offset: 3px;
}

footer {
    margin-top: 2rem;
    text-align: center;
    font-size: 0.9rem;
    color: var(--muted);
}
</style>
