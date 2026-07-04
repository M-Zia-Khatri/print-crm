export type CompanyOption = {
    id: number;
    name: string;
};

export type Customer = {
    id: number;
    first_name: string;
    last_name: string;
    email: string | null;
    phone: string | null;
    status: string;
    source: string | null;
    company?: CompanyOption | null;
    created_at: string;
};

export type Paginated<T> = {
    data: T[];
    links: Array<{ url: string | null; label: string; active: boolean }>;
};
