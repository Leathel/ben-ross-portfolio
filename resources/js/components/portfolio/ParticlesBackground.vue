<script setup lang="ts">
import { onBeforeUnmount, onMounted } from 'vue';
import particlesScriptUrl from 'particles.js/particles.js?url';

const containerId = 'particles-js';
const reducedMotionQuery = '(prefers-reduced-motion: reduce)';

let particlesScriptPromise: Promise<void> | null = null;

function loadParticlesScript(): Promise<void> {
    if (window.particlesJS) {
        return Promise.resolve();
    }

    if (! particlesScriptPromise) {
        particlesScriptPromise = new Promise<void>((resolve, reject) => {
            const script = document.createElement('script');
            script.src = particlesScriptUrl;
            script.async = true;
            script.onload = () => resolve();
            script.onerror = () => reject(new Error('Unable to load particles.js.'));
            document.head.appendChild(script);
        });
    }

    return particlesScriptPromise;
}

function particleConfiguration(): Record<string, unknown> {
    return {
        particles: {
            number: {
                value: 13,
                density: {
                    enable: true,
                    value_area: 800,
                },
            },
            color: {
                value: '#ffffff',
            },
            shape: {
                type: 'circle',
                stroke: {
                    width: 0,
                    color: '#000000',
                },
                polygon: {
                    nb_sides: 5,
                },
                image: {
                    src: 'img/github.svg',
                    width: 100,
                    height: 100,
                },
            },
            opacity: {
                value: 0.5,
                random: false,
                anim: {
                    enable: false,
                    speed: 1,
                    opacity_min: 0.1,
                    sync: false,
                },
            },
            size: {
                value: 3,
                random: true,
                anim: {
                    enable: false,
                    speed: 40,
                    size_min: 0.1,
                    sync: false,
                },
            },
            line_linked: {
                enable: true,
                distance: 150,
                color: '#ffffff',
                opacity: 0.4,
                width: 1,
            },
            move: {
                enable: true,
                speed: 0.7999999999999999,
                direction: 'none',
                random: false,
                straight: false,
                out_mode: 'out',
                bounce: false,
                attract: {
                    enable: false,
                    rotateX: 600,
                    rotateY: 1200,
                },
            },
        },
        interactivity: {
            detect_on: 'canvas',
            events: {
                onhover: {
                    enable: false,
                    mode: 'repulse',
                },
                onclick: {
                    enable: true,
                    mode: 'push',
                },
                resize: true,
            },
            modes: {
                grab: {
                    distance: 400,
                    line_linked: {
                        opacity: 1,
                    },
                },
                bubble: {
                    distance: 400,
                    size: 40,
                    duration: 2,
                    opacity: 8,
                    speed: 3,
                },
                repulse: {
                    distance: 200,
                    duration: 0.4,
                },
                push: {
                    particles_nb: 4,
                },
                remove: {
                    particles_nb: 2,
                },
            },
        },
        retina_detect: true,
    };
}

function destroyParticles(): void {
    const instances = window.pJSDom ?? [];

    for (const instance of instances) {
        instance.fn.vendors.destroypJS();
    }

    window.pJSDom = [];
}

async function initializeParticles(): Promise<void> {
    if (window.matchMedia(reducedMotionQuery).matches) {
        destroyParticles();

        return;
    }

    await loadParticlesScript();

    if (! window.particlesJS) {
        return;
    }

    destroyParticles();
    window.particlesJS(containerId, particleConfiguration());
}

function safelyInitializeParticles(): void {
    void initializeParticles().catch(() => undefined);
}

function handleMotionPreferenceChange(): void {
    safelyInitializeParticles();
}

onMounted(() => {
    safelyInitializeParticles();
    window.matchMedia(reducedMotionQuery).addEventListener('change', handleMotionPreferenceChange);
});

onBeforeUnmount(() => {
    window.matchMedia(reducedMotionQuery).removeEventListener('change', handleMotionPreferenceChange);
    destroyParticles();
});
</script>

<template>
    <div :id="containerId" class="absolute inset-0"></div>
</template>
