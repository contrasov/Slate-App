import { onMounted, ref } from 'vue';

type Appearance = 'light' | 'dark' | 'system';

export function updateTheme(value: Appearance) {
    if (typeof window === 'undefined') {
        return;
    }

    document.documentElement.classList.toggle('dark', value === 'dark');
}

export function initializeTheme() {
    if (typeof window === 'undefined') {
        return;
    }
    updateTheme('light');
}

export function useAppearance() {
    const appearance = ref<Appearance>('light');

    onMounted(() => {
        initializeTheme();
    });

    return {
        appearance,
    };
}
