import { Head, Link } from '@inertiajs/react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

export default function ModulePage({ module, slug }: { module: string; slug: string }) {
    return (
        <>
            <Head title={module} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card>
                    <CardHeader>
                        <CardTitle>{module}</CardTitle>
                        <CardDescription>
                            Production scaffold for the {module.toLowerCase()} module. Domain schema, routes, navigation, and module boundaries are in place for incremental feature work.
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="text-sm text-muted-foreground">
                        <p>Module slug: <span className="font-mono">{slug}</span></p>
                        <Link className="mt-4 inline-flex text-primary underline" href="/dashboard">Back to dashboard</Link>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
