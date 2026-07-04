import { Head } from '@inertiajs/react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { dashboard } from '@/routes';
import { formatCurrency } from '@/Utils/format';

type DashboardMetrics = {
    customers: number;
    companies: number;
    products: number;
    open_tasks: number;
    invoice_total: number;
    payments_total: number;
    overdue_invoices: number;
};

export default function Dashboard({ metrics }: { metrics: DashboardMetrics }) {
    const cards = [
        ['Customers', metrics.customers],
        ['Companies', metrics.companies],
        ['Active products', metrics.products],
        ['Open tasks', metrics.open_tasks],
        ['Invoice pipeline', formatCurrency(metrics.invoice_total)],
        ['Payments received', formatCurrency(metrics.payments_total)],
        ['Overdue invoices', metrics.overdue_invoices],
    ];

    return (
        <>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div>
                    <h1 className="text-2xl font-semibold tracking-tight">CRM Dashboard</h1>
                    <p className="text-sm text-muted-foreground">Operational overview cached in Redis for fast loading.</p>
                </div>
                <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    {cards.map(([label, value]) => (
                        <Card key={label}>
                            <CardHeader className="pb-2">
                                <CardTitle className="text-sm font-medium text-muted-foreground">{label}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold">{value}</div>
                            </CardContent>
                        </Card>
                    ))}
                </div>
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [{ title: 'Dashboard', href: dashboard() }],
};
