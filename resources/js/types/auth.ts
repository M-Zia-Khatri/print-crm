export type UserRole = 'super_admin' | 'admin' | 'viewer';

export type LoginPayload = {
    username: string;
    password: string;
    role: UserRole;
};

export type User = {
    id: number;
    name: string;
    email: string;
    username: string | null;
    role: UserRole;
    avatar?: string;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};

export type AuthContextType = {
    user: User | null;
    isAuthenticated: boolean;
    login: (payload: LoginPayload) => void;
    logout: () => void;
};

export type Passkey = {
    id: number;
    name: string;
    authenticator: string | null;
    created_at_diff: string;
    last_used_at_diff: string | null;
};

export type TwoFactorSetupData = {
    svg: string;
    url: string;
};

export type TwoFactorSecretKey = {
    secretKey: string;
};
