import { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { cn } from '@/lib/utils';
import {
    Sidebar,
    SidebarContent,
    SidebarGroup,
    SidebarGroupContent,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarProvider,
    SidebarTrigger,
} from '@/components/ui/sidebar';
import { secondarySidebarConfig } from '@/config/secondary-sidebar.config';
import { useIsMobile } from '@/hooks/use-mobile';

const eventSidebarRoutes = [
    {
        prefix: '/committee/events/',
        key: 'committee_event',
    },
    {
        prefix: '/admin/events/',
        key: 'admin_event',
    },
];

function getActiveSectionConfig(url: string) {
    for (const route of eventSidebarRoutes) {
        if (url.startsWith(route.prefix)) {
            const eventId = url.split('/')[3];

            const config = secondarySidebarConfig[route.key];

            if (!config) return null;

            return {
                ...config,
                items: config.items.map((item) => ({
                    ...item,
                    href: item.href.replace(':id', eventId),
                })),
            };
        }
    }

    return null;
}

export function SecondarySidebar() {
    const { url } = usePage();
    const [isOpen, setIsOpen] = useState(false);
    const isMobile = useIsMobile();

    const section = getActiveSectionConfig(url);

    if (!section) return null;

    return (
        <SidebarProvider
            open={isOpen}
            onOpenChange={setIsOpen}
            cookieName="secondary_sidebar_state"
            className="mr-2 min-h-0 w-auto **:data-[slot=sidebar-gap]:hidden"
        >
            <Sidebar
                collapsible="icon"
                variant="floating"
                isMobileSheet={false}
                className="static! inset-auto! z-auto! flex h-full! transform-none! border-r bg-sidebar"
            >
                <SidebarHeader className="flex h-16 shrink-0 flex-row items-center justify-between border-b border-sidebar-border/50 px-4 group-data-[collapsible=icon]:justify-center group-data-[collapsible=icon]:px-0">
                    <span className="truncate text-sm font-semibold group-data-[collapsible=icon]:hidden">
                        {section.title}
                    </span>
                    <SidebarTrigger
                        className="shrink-0"
                        onClick={() => {
                            if (isMobile) setIsOpen(!isOpen);
                        }}
                    />
                </SidebarHeader>

                <SidebarContent>
                    <SidebarGroup>
                        <SidebarGroupContent>
                            <SidebarMenu>
                                {section.items.map((item) => {
                                    const isActive =
                                        url === item.href ||
                                        url.startsWith(item.href + '/');

                                    return (
                                        <SidebarMenuItem key={item.href}>
                                            <SidebarMenuButton
                                                asChild
                                                isActive={isActive}
                                                tooltip={item.title}
                                            >
                                                <Link href={item.href} prefetch>
                                                    {item.icon && (
                                                        <item.icon className="size-4" />
                                                    )}
                                                    <span>{item.title}</span>
                                                </Link>
                                            </SidebarMenuButton>
                                        </SidebarMenuItem>
                                    );
                                })}
                            </SidebarMenu>
                        </SidebarGroupContent>
                    </SidebarGroup>
                </SidebarContent>
            </Sidebar>
        </SidebarProvider>
    );
}
