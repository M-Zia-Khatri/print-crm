import { Link } from '@inertiajs/react';
import { Activity, Building2, CreditCard, FileText, LayoutGrid, Package, Receipt, Settings, ShieldCheck, StickyNote, Users } from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    { title: 'Dashboard', href: dashboard(), icon: LayoutGrid },
    { title: 'Customers', href: '/customers', icon: Users },
    { title: 'Companies', href: '/companies', icon: Building2 },
    { title: 'Products', href: '/products', icon: Package },
    { title: 'Quotes', href: '/quotes', icon: FileText },
    { title: 'Invoices', href: '/invoices', icon: Receipt },
    { title: 'Payments', href: '/payments', icon: CreditCard },
    { title: 'Tasks', href: '/tasks', icon: ShieldCheck },
    { title: 'Notes', href: '/notes', icon: StickyNote },
    { title: 'Activity Logs', href: '/activity-logs', icon: Activity },
    { title: 'Settings', href: '/settings', icon: Settings },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
