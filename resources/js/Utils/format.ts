import dayjs from 'dayjs';

export function formatCurrency(value: number, currency = 'USD') {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency }).format(value);
}

export function formatDate(value: string | null | undefined) {
    return value ? dayjs(value).format('MMM D, YYYY') : '—';
}
