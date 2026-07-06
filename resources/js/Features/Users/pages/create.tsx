import type { FormEvent } from 'react';
import { Head, useForm } from '@inertiajs/react';
import InputError from '@/components/input-error';
import PasswordInput from '@/components/password-input';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { userSchema } from '@/Features/Users/schemas/user-schema';
import type { CreateUserPayload } from '@/Features/Users/types/user';
import { dashboard } from '@/routes';

export default function CreateUser() {
    const { data, setData, post, transform, processing, errors, setError, clearErrors, reset } = useForm<CreateUserPayload>({
        username: '',
        password: '',
        role: 'viewer',
    });

    function submit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        clearErrors();

        const payload = {
            ...data,
            username: data.username.trim().toLowerCase(),
        };
        const result = userSchema.safeParse(payload);

        if (!result.success) {
            for (const issue of result.error.issues) {
                const field = issue.path[0] as keyof CreateUserPayload;
                setError(field, issue.message);
            }

            return;
        }

        transform(() => result.data);
        post('/users', {
            onSuccess: () => reset(),
        });
    }

    return (
        <>
            <Head title="Add User" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div>
                    <h1 className="text-2xl font-semibold tracking-tight">Add User</h1>
                    <p className="text-sm text-muted-foreground">Create an admin or viewer account for CRM access.</p>
                </div>

                <form onSubmit={submit} className="max-w-xl space-y-6">
                    <div className="grid gap-2">
                        <Label htmlFor="username">Username</Label>
                        <Input id="username" value={data.username} onChange={(event) => setData('username', event.target.value)} required autoFocus autoComplete="username" placeholder="new_user" />
                        <InputError message={errors.username} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="password">Password</Label>
                        <PasswordInput id="password" value={data.password} onChange={(event) => setData('password', event.target.value)} required autoComplete="new-password" placeholder="At least 8 characters" />
                        <InputError message={errors.password} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="role">Role</Label>
                        <select id="role" value={data.role} onChange={(event) => setData('role', event.target.value as CreateUserPayload['role'])} required className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-xs outline-none transition-[color,box-shadow] focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 md:text-sm">
                            <option value="admin">Admin</option>
                            <option value="viewer">Viewer</option>
                        </select>
                        <InputError message={errors.role} />
                    </div>

                    <Button type="submit" disabled={processing}>
                        {processing && <Spinner />}
                        Create user
                    </Button>
                </form>
            </div>
        </>
    );
}

CreateUser.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: dashboard() },
        { title: 'Add User', href: '/users/create' },
    ],
};
