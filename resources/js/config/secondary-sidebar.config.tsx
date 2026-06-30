import {
    CalendarCheckIcon,
    FolderGit2,
    Settings,
    Users,
    FileText,
    Pen,
    GavelIcon,
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
                title: 'Basic Informations',
                href: '/admin/events/:id/information',
                icon: FileText,
            },
            {
                title: 'Edit Event',
                href: '/admin/events/:id/edit',
                icon: Pen,
            },
            {
                title: 'Committees',
                href: '/admin/events/:id/committees',
                icon: Users,
            },
            {
                title: 'Judges',
                href: '/admin/events/:id/judges',
                icon: GavelIcon,
            },
        ],
    },
    committee_event: {
        title: 'Event Management',
        items: [
            {
                title: 'Basic Informations',
                href: '/committee/events/:id/Information',
                icon: FileText,
            },
            {
                title: 'Edit Event',
                href: '/committee/events/:id/edit',
                icon: Pen,
            },
            {
                title: '',
                href: '/committee/events/:id/details',
                icon: FolderGit2,
            },
            {
                title: 'Committees',
                href: '/committee/events/:id/committees',
                icon: Users,
            },
            {
                title: 'Judges',
                href: '/committee/events/:id/judges',
                icon: GavelIcon,
            },
        ],
    },
};
