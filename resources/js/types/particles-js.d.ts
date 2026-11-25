export {};

declare global {
    interface ParticlesJsInstance {
        fn: {
            vendors: {
                destroypJS: () => void;
            };
        };
    }

    interface Window {
        particlesJS?: {
            (elementId: string, config: Record<string, unknown>): void;
            load?: (...parameters: unknown[]) => void;
        };
        pJSDom?: ParticlesJsInstance[] | null;
    }
}
