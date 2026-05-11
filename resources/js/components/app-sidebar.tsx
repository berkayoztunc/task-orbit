import { Link, usePage } from '@inertiajs/react';
import { BookOpen, Building2, CalendarDays, ClipboardList, FolderGit2, GraduationCap, LayoutGrid, UserCircle2 } from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavFooter } from '@/components/nav-footer';
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
import * as pageRoutes from '@/routes/page';
import * as pageProfileRoutes from '@/routes/page/profile';
import * as pageInternshipRoutes from '@/routes/page/internships';
import type { Internship, NavItem, Profile } from '@/types';

type SharedPageProps = {
    activeProfile?: Profile & { company?: { id: number; title: string } };
    activeInternship?: Internship;
};

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    const { activeProfile, activeInternship } = usePage<SharedPageProps>().props;

    const mainNavItems: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
        {
            title: 'Profilim',
            href: pageProfileRoutes.select(),
            icon: UserCircle2,
        },
        {
            title: 'Şirketler',
            href: pageRoutes.companies(),
            icon: Building2,
        },
        {
            title: 'Stajlar',
            href: pageRoutes.internships(),
            icon: GraduationCap,
        },
    ];

    if (activeInternship) {
        mainNavItems.push({
            title: 'Staj Programım',
            href: pageInternshipRoutes.show({ internship: activeInternship.id }),
            icon: CalendarDays,
            children: [
                {
                    title: 'Dersler',
                    href: pageInternshipRoutes.lessons({ internship: activeInternship.id }),
                    icon: ClipboardList,
                },
            ],
        });
    }

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
                {activeProfile && (
                    <div className="px-3 py-2 text-xs text-muted-foreground border-t border-sidebar-border">
                        <p className="font-medium truncate">{(activeProfile as any).company?.title ?? 'Şirket'}</p>
                        <p className="truncate opacity-70">{(activeProfile as any).role?.name ?? 'Rol'}</p>
                    </div>
                )}
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
