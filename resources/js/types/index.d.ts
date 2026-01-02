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

export type MetricType = {
    value: number;
    date: string;
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
    total: number;
}

export type DomainListPropsType = {
    domains: PaginationType<DomainsType>;
    error: string | null;
};

export type OwnerMarketSharePropsType = {
    ownersMarketShare: PaginationType<OwnerMarketShareType>;
    error: string | null;
};

export type RegistrarMarketSharePropsType = {
    registrarsMarketShare: PaginationType<RegistrarMarketShareType>;
    error: string | null;
};

export type NameServerMarketSharePropsType = {
    nameserverMarketShare: PaginationType<NameServerMarketShareType>;
    error: string | null;
};

export type CrawlInfo = {
    id: number;
    domain_count: number;
    crawling_duration: number;
    download_duration: number;
    avg_speed_in_bytes_per_sec: number;
    file_size: number;
    time_generated: string;
    table_name: string;
};

export type DomainStatisticsType = {
    avg_domain_name_length: number;
    created_at: string;
    data_extraction_duration_in_seconds: number;
    data_length_in_bytes: number;
    id: number;
    longest_domain_name: string;
    longest_domain_name_length: number;
    updated_at: string;
    crawling_average_duration_seconds: number;
    average_download_speed_bytes_per_second: number;
};
