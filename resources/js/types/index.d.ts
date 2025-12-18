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

export type DomainsType = {
    id: number;
    domain: string;
    id_reg: string;
    id_owner: string;
    status: string;
    ns1: string;
    ns2: string;
    ns3: string;
    ns4: string;
    expiry_date: string;
};

export type OwnerMarketShareType = {
    id: number;
    owner: string;
    domain_count: number;
    percentage: string;
};

export type RegistrarMarketShareType = {
    id: number;
    registrar: string;
    domain_count: number;
    percentage: string;
};

export type NameServerMarketShareType = {
    id: number;
    ns: string;
    count: number;
    percentage: string;
};

export type CalendarHeatmapByDayType = {
    expiry_day: string;
    domain_count: number;
};

export type MonthsArrayType = {
    name: string;
    index: number;
};

export type CalendarHeatmapValue = {
    name: string;
    data: number[];
};

export interface PaginationType<T> {
    data: T[];
    current_page: number;
    last_page: number;
    first_page_url: string;
    prev_page_url: string;
    next_page_url: string;
    last_page_url: string;
}
