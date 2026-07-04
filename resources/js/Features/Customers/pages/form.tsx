import { Head, Link, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/input-error';
import type { CompanyOption } from '../types/customer';

export default function CustomerForm({ companies }: { companies: CompanyOption[] }) {
    const { data, setData, post, processing, errors } = useForm({
        company_id: '',
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        status: 'lead',
        source: '',
        notes: '',
    });

    return (
        <>
            <Head title="New customer" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card>
                    <CardHeader>
                        <CardTitle>New customer</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form className="grid gap-4 md:grid-cols-2" onSubmit={(event) => { event.preventDefault(); post('/customers'); }}>
                            <div className="grid gap-2">
                                <Label htmlFor="first_name">First name</Label>
                                <Input id="first_name" value={data.first_name} onChange={(event) => setData('first_name', event.target.value)} />
                                <InputError message={errors.first_name} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="last_name">Last name</Label>
                                <Input id="last_name" value={data.last_name} onChange={(event) => setData('last_name', event.target.value)} />
                                <InputError message={errors.last_name} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="email">Email</Label>
                                <Input id="email" type="email" value={data.email} onChange={(event) => setData('email', event.target.value)} />
                                <InputError message={errors.email} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="phone">Phone</Label>
                                <Input id="phone" value={data.phone} onChange={(event) => setData('phone', event.target.value)} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="company_id">Company</Label>
                                <select id="company_id" className="rounded-md border bg-background px-3 py-2" value={data.company_id} onChange={(event) => setData('company_id', event.target.value)}>
                                    <option value="">No company</option>
                                    {companies.map((company) => <option key={company.id} value={company.id}>{company.name}</option>)}
                                </select>
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="status">Status</Label>
                                <Input id="status" value={data.status} onChange={(event) => setData('status', event.target.value)} />
                            </div>
                            <div className="md:col-span-2 flex gap-2">
                                <Button type="submit" disabled={processing}>Create customer</Button>
                                <Button asChild variant="secondary"><Link href="/customers">Cancel</Link></Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
