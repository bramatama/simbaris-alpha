import {
    CalendarCheckIcon,
    FolderGit2,
    Settings,
    Users,
    FileText,
} from 'lucide-react';
import type { LucideIcon } from 'lucide-react';

export type SecondarySidebarItem = {
    title: string;
    href: string;
    icon?: LucideIcon;
};

export type SecondarySidebarSection = {
    title: string;
    items: SecondarySidebarItem[];
};

export const secondarySidebarConfig: Record<string, SecondarySidebarSection> = {
    admin_event: {
        title: 'Event Management',
        items: [
            {
                title: 'Basic Details',
                href: '/admin/events/:id/edit',
                icon: FileText,
            },
            {
                title: 'Committees',
                href: '/admin/events/:id/committees',
                icon: Users,
            },
        ],
    },
    committee_event: {
        title: 'Event Management',
        items: [
            {
                title: 'Basic Details',
                href: '/committee/events/:id/edit',
                icon: FileText,
            },
            {
                title: 'Committees',
                href: '/committee/events/:id/committees',
                icon: Users,
            },
        ],
    },
};
