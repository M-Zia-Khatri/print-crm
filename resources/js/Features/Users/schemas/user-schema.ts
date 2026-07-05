import { z } from 'zod';

export const userSchema = z.object({
    username: z.string().min(1).max(255).regex(/^[a-z0-9_-]+$/),
    password: z.string().min(8),
    role: z.enum(['admin', 'viewer']),
});

export type UserSchema = z.infer<typeof userSchema>;
