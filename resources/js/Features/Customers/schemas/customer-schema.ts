import { z } from 'zod';

export const customerSchema = z.object({
    company_id: z.coerce.number().optional().nullable(),
    first_name: z.string().min(1, 'First name is required').max(255),
    last_name: z.string().min(1, 'Last name is required').max(255),
    email: z.string().email().optional().or(z.literal('')),
    phone: z.string().max(50).optional(),
    status: z.string().min(1).max(50),
    source: z.string().max(100).optional(),
    notes: z.string().optional(),
});

export type CustomerFormData = z.infer<typeof customerSchema>;
