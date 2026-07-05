import { Link, usePage } from '@inertiajs/react';
import { BriefcaseBusiness, CircleDollarSign, LayoutGrid, Settings, UserPlus, Users } from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import type { Auth, NavItem } from '@/types';
import { dashboard } from '@/routes';

type PageProps = { auth: Auth };

export function AppSidebar() {
    const { auth } = usePage<PageProps>().props;
    const mainNavItems: NavItem[] = [
        { title: 'Dashboard', href: dashboard(), icon: LayoutGrid },
        { title: 'Entry', href: '/customers', icon: Users },
        { title: 'Worker', href: '/tasks', icon: BriefcaseBusiness },
        { title: 'Expense', href: '/payments', icon: CircleDollarSign },
        ...(auth.user.role === 'super_admin' ? [{ title: 'Add User', href: '/users/create', icon: UserPlus }] : []),
        { title: 'Settings', href: '/settings', icon: Settings },
    ];

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch><AppLogo /></Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>
            <SidebarContent><NavMain items={mainNavItems} /></SidebarContent>
            <SidebarFooter><NavUser /></SidebarFooter>
        </Sidebar>
    );
}
