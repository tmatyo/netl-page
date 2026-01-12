import { PageProps } from '@inertiajs/core';
import { usePage } from '@inertiajs/vue3';

interface CustomPageProps extends PageProps {
    translations?: Record<string, string>;
}
export function t(key: string): string {
    const page = usePage<CustomPageProps>();
    const translations = page.props.translations ?? {};
    return translations[key] ?? key;
}
