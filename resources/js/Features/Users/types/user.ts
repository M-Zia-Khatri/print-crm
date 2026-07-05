export type UserRole = 'admin' | 'viewer';

export type User = {
    id: number;
    username: string;
    role: UserRole;
};

export type CreateUserPayload = {
    username: string;
    password: string;
    role: UserRole;
};
