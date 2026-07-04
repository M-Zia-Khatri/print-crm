import { Head, Link, router } from '@inertiajs/react';
import { Search, UserPlus } from 'lucide-react';
import { FormEvent, useState } from 'react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { formatDate } from '@/Utils/format';
import type { Customer, Paginated } from '../types/customer';

type Props = {
    customers: Paginated<Customer>;
    filters: { search: string };
};

export default function CustomersIndex({ customers, filters }: Props) {
    const [search, setSearch] = useState(filters.search ?? '');

    function submit(event: FormEvent) {
        event.preventDefault();
        router.get('/customers', { search }, { preserveState: true, replace: true });
    }

    return (
        <>
            <Head title="Customers" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex flex-col justify-between gap-3 md:flex-row md:items-center">
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">Customers</h1>
                        <p className="text-sm text-muted-foreground">Manage leads, prospects, and active customer relationships.</p>
                    </div>
                    <Button asChild>
                        <Link href="/customers/create"><UserPlus className="mr-2 size-4" /> New customer</Link>
                    </Button>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Customer directory</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={submit} className="mb-4 flex gap-2">
                            <Input value={search} onChange={(event) => setSearch(event.target.value)} placeholder="Search customers" />
                            <Button type="submit" variant="secondary"><Search className="mr-2 size-4" /> Search</Button>
                        </form>
                        <div className="overflow-hidden rounded-md border">
                            <table className="w-full text-sm">
                                <thead className="bg-muted/50 text-left">
                                    <tr>
                                        <th className="p-3">Name</th>
                                        <th className="p-3">Company</th>
                                        <th className="p-3">Email</th>
                                        <th className="p-3">Status</th>
                                        <th className="p-3">Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {customers.data.map((customer) => (
                                        <tr key={customer.id} className="border-t">
                                            <td className="p-3 font-medium">{customer.first_name} {customer.last_name}</td>
                                            <td className="p-3">{customer.company?.name ?? '—'}</td>
                                            <td className="p-3">{customer.email ?? '—'}</td>
                                            <td className="p-3 capitalize">{customer.status}</td>
                                            <td className="p-3">{formatDate(customer.created_at)}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
