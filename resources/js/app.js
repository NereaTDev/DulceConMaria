import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Spinner global para formularios + evitar envíos múltiples
function ensureGlobalFormSpinner() {
    let overlay = document.getElementById('global-form-spinner-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'global-form-spinner-overlay';
        overlay.setAttribute('aria-hidden', 'true');
        overlay.style.position = 'fixed';
        overlay.style.inset = '0';
        overlay.style.backgroundColor = 'rgba(15, 23, 42, 0.55)'; // slate-900/55
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.zIndex = '9999';
        overlay.style.backdropFilter = 'blur(2px)';
        overlay.style.pointerEvents = 'none';
        overlay.style.opacity = '0';
        overlay.style.transition = 'opacity 150ms ease-out';

        const box = document.createElement('div');
        box.style.backgroundColor = 'white';
        box.style.borderRadius = '9999px';
        box.style.padding = '0.75rem 1.5rem';
        box.style.display = 'flex';
        box.style.alignItems = 'center';
        box.style.gap = '0.75rem';
        box.style.boxShadow = '0 10px 25px rgba(15, 23, 42, 0.25)';

        const spinner = document.createElement('div');
        spinner.style.width = '1.5rem';
        spinner.style.height = '1.5rem';
        spinner.style.borderRadius = '9999px';
        spinner.style.borderWidth = '3px';
        spinner.style.borderStyle = 'solid';
        spinner.style.borderColor = 'rgba(148, 163, 184, 0.6)'; // slate-400
        spinner.style.borderTopColor = '#0f766e'; // teal-700
        spinner.style.animation = 'dc-global-spin 0.7s linear infinite';

        const text = document.createElement('span');
        text.textContent = 'Procesando...';
        text.style.fontSize = '0.875rem';
        text.style.fontWeight = '500';
        text.style.color = '#0f172a'; // slate-900

        box.appendChild(spinner);
        box.appendChild(text);
        overlay.appendChild(box);
        document.body.appendChild(overlay);

        // Inyectar estilos para la animación si no existen
        if (!document.getElementById('dc-global-spinner-style')) {
            const style = document.createElement('style');
            style.id = 'dc-global-spinner-style';
            style.textContent = `@keyframes dc-global-spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }`;
            document.head.appendChild(style);
        }
    }
    return overlay;
}

function showGlobalFormSpinner() {
    const overlay = ensureGlobalFormSpinner();
    overlay.style.pointerEvents = 'auto';
    overlay.style.opacity = '1';
}

// --- YouTube IFrame API: marcar progreso de lecciones al 80% ---
let dcYouTubeApiLoading = false;
let dcYouTubeApiReadyCallbacks = [];

function dcLoadYouTubeApi(callback) {
    if (typeof window.YT !== 'undefined' && typeof window.YT.Player !== 'undefined') {
        callback();
        return;
    }

    dcYouTubeApiReadyCallbacks.push(callback);

    if (dcYouTubeApiLoading) return;
    dcYouTubeApiLoading = true;

    const tag = document.createElement('script');
    tag.src = 'https://www.youtube.com/iframe_api';
    const firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    window.onYouTubeIframeAPIReady = () => {
        dcYouTubeApiReadyCallbacks.forEach((cb) => cb());
        dcYouTubeApiReadyCallbacks = [];
    };
}

function dcExtractYouTubeId(url) {
    if (!url) return null;

    // URLs tipo https://www.youtube.com/embed/VIDEO_ID
    const embedMatch = url.match(/youtube\.com\/embed\/([^?&#]+)/);
    if (embedMatch) return embedMatch[1];

    // URLs tipo https://youtu.be/VIDEO_ID
    const shortMatch = url.match(/youtu\.be\/([^?&#]+)/);
    if (shortMatch) return shortMatch[1];

    // URLs tipo https://www.youtube.com/watch?v=VIDEO_ID
    const watchMatch = url.match(/[?&]v=([^&#]+)/);
    if (watchMatch) return watchMatch[1];

    return null;
}

function dcInitLessonPlayers() {
    const containers = document.querySelectorAll('[data-lesson-id][data-video-url]');
    if (!containers.length) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    containers.forEach((el) => {
        const lessonId = el.getAttribute('data-lesson-id');
        const videoUrl = el.getAttribute('data-video-url');
        const videoId = dcExtractYouTubeId(videoUrl);
        if (!lessonId || !videoId) return;

        let reported = false;

        const markCompleted = () => {
            if (reported || !csrfToken) return;
            reported = true;

            fetch(`/campus/leccion/${lessonId}/progreso`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ completed: true }),
            }).catch(() => {
                // silencioso; no molestamos al usuario si falla
            });
        };

        const player = new window.YT.Player(el.id, {
            videoId,
            playerVars: {
                rel: 0,
                modestbranding: 1,
            },
            events: {
                onReady: (event) => {
                    const duration = event.target.getDuration();
                    if (!duration || duration <= 0) return;

                    const interval = setInterval(() => {
                        if (reported) {
                            clearInterval(interval);
                            return;
                        }
                        const currentTime = event.target.getCurrentTime();
                        if (currentTime && currentTime >= 0.8 * duration) {
                            markCompleted();
                            clearInterval(interval);
                        }
                    }, 5000); // cada 5s es suficiente
                },
            },
        });
    });
}

window.addEventListener('DOMContentLoaded', () => {
    // Inicializar YouTube IFrame API si hay lecciones con vídeo
    const hasLessonVideos = document.querySelector('[data-lesson-id][data-video-url]');
    if (hasLessonVideos) {
        dcLoadYouTubeApi(() => {
            dcInitLessonPlayers();
        });
    }

    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) return;

        // Si el formulario ya se está enviando, bloqueamos el nuevo submit
        if (form.dataset.submitting === 'true') {
            event.preventDefault();
            return;
        }

        form.dataset.submitting = 'true';

        const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');

        submitButtons.forEach((button) => {
            button.disabled = true;

            // Opcional: cambiar el texto a "Enviando..." si es un <button>
            if (button.tagName === 'BUTTON') {
                const btn = button;
                if (!btn.dataset.originalText) {
                    btn.dataset.originalText = btn.innerHTML;
                }
                btn.innerHTML = 'Enviando...';
            }
        });

        // Mostrar overlay/spinner global
        showGlobalFormSpinner();
    });
});
