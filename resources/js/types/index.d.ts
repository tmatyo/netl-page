export interface Auth {
    user: User;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type NumberOfDomainType = {
    date_created: string;
    domain_count: number;
};

export type CrawlingDurationType = {
    date_created: string;
    crawling_duration_seconds: number;
};

export type DownloadSpeedType = {
    date_created: string;
    download_speed_bytes_per_second: number;
};