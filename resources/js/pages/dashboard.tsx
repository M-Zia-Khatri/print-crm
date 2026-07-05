import { Head, usePage } from '@inertiajs/react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { Auth } from '@/types';
import { dashboard } from '@/routes';

type PageProps = {
    auth: Auth;
    flash?: { success?: string };
};

export default function Dashboard() {
    const { auth, flash } = usePage<PageProps>().props;
    const username = auth.user.username ?? auth.user.name;

    return (
        <>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div>
                    <h1 className="text-2xl font-semibold tracking-tight">CRM Dashboard</h1>
                    <p className="text-sm text-muted-foreground">Welcome, {username}</p>
                    <p className="text-sm text-muted-foreground">Your role: {auth.user.role}</p>
                </div>
                {flash?.success && <div className="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{flash.success}</div>}
                <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <Card>
                        <CardHeader className="pb-2"><CardTitle className="text-sm font-medium text-muted-foreground">Status</CardTitle></CardHeader>
                        <CardContent><div className="text-2xl font-bold">Ready</div></CardContent>
                    </Card>
                </div>
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [{ title: 'Dashboard', href: dashboard() }],
};
