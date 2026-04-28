import { Head, Link, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/layouts/main-app-layout';
import { Button } from '@/components/ui/button';
import { ConfirmationDialog } from '@/components/confirmation-dialog';
import { Trash2, Edit2 } from 'lucide-react';
import type { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';

interface User {
    user_id: number;
    public_id: string;
    name: string;
    email: string;
    role: 'admin' | 'official_team' | 'judge' | 'committee';
    contact_info?: string;
    created_at: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface UsersResponse {
    data: User[];
    links: PaginationLink[];
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        path: string;
        per_page: number;
        to: number;
        total: number;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
    {
        title: 'User Management',
        href: '#',
    },
];

export default function UserManagementIndex() {
    const { props } = usePage<{ users?: UsersResponse }>();
    const { users } = props;

    const [deleteUserId, setDeleteUserId] = useState<number | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    const getRoleBadgeColor = (role: string) => {
        const colors: Record<string, string> = {
            admin: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100',
            judge: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100',
            committee:
                'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-100',
            official_team:
                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100',
        };
        return (
            colors[role] ||
            'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-100'
        );
    };

    const handleDelete = () => {
        if (!deleteUserId) return;

        setIsDeleting(true);
        router.delete(`/admin/users/${deleteUserId}`, {
            onFinish: () => {
                setIsDeleting(false);
                setDeleteUserId(null);
            },
        });
    };

    const formatDate = (date: string) => {
        return new Date(date).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="User Management" />

            <div className="flex flex-1 flex-col gap-4 overflow-hidden p-4">
                {/* Header */}
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight sm:text-3xl">
                            User Management
                        </h1>
                        <p className="mt-1 text-muted-foreground">
                            Manage system users and their roles
                        </p>
                    </div>
                </div>

                {/* Users Table */}
                <div className="overflow-hidden rounded-lg border bg-card">
                    <div className="overflow-x-auto">
                        <div className="w-full border-collapse">
                            {/* Table Header */}
                            <div className="sticky top-0 hidden grid-cols-5 gap-4 border-b bg-muted/50 p-4 font-medium md:grid">
                                <div>Name</div>
                                <div>Email</div>
                                <div>Contact</div>
                                <div>Role</div>
                                <div>Actions</div>
                            </div>

                            {/* Table Body */}
                            {users && users.data && users.data.length > 0 ? (
                                users.data.map((user: User) => (
                                    <div
                                        key={user.user_id}
                                        className="grid grid-cols-1 items-center gap-4 border-b p-4 transition-colors hover:bg-muted/30 md:grid-cols-5 md:items-center md:gap-4"
                                    >
                                        <div className="flex justify-between md:block">
                                            <span className="text-sm text-muted-foreground md:hidden">
                                                Name
                                            </span>
                                            <span className="font-medium">
                                                {user.name}
                                            </span>
                                        </div>
                                        <div className="flex justify-between md:block">
                                            <span className="text-sm text-muted-foreground md:hidden">
                                                Email
                                            </span>
                                            <span className="text-sm text-muted-foreground">
                                                {user.email}
                                            </span>
                                        </div>
                                        <div className="flex justify-between md:block">
                                            <span className="text-sm text-muted-foreground md:hidden">
                                                Contact
                                            </span>
                                            <span className="text-sm">
                                                {user.contact_info || '-'}
                                            </span>
                                        </div>
                                        <div className="flex justify-between md:block">
                                            <span className="text-sm text-muted-foreground md:hidden">
                                                Role
                                            </span>
                                            <span className="text-sm text-muted-foreground">
                                                {user.role}
                                            </span>
                                        </div>
                                        <div className="flex flex-col gap-2 md:flex-row">
                                            <Link
                                                href={`/admin/users/${user.user_id}`}
                                            >
                                                <Button
                                                    variant="outline"
                                                    size="sm"
                                                    className="w-full md:w-auto"
                                                >
                                                    <Edit2 className="h-4 w-4" />
                                                    Edit
                                                </Button>
                                            </Link>
                                            <Button
                                                variant="destructive"
                                                size="sm"
                                                className="w-full md:w-auto"
                                                onClick={() =>
                                                    setDeleteUserId(
                                                        user.user_id,
                                                    )
                                                }
                                            >
                                                <Trash2 className="h-4 w-4" />
                                                Delete
                                            </Button>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div className="col-span-full p-8 text-center text-muted-foreground">
                                    No users found
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Pagination */}
                {users && users.meta && users.meta.last_page > 1 && (
                    <div className="flex flex-wrap items-center justify-center gap-2">
                        {users.links &&
                            users.links.map(
                                (link: PaginationLink, index: number) => (
                                    <Link key={index} href={link.url || '#'}>
                                        <Button
                                            variant={
                                                link.active
                                                    ? 'default'
                                                    : 'outline'
                                            }
                                            disabled={!link.url}
                                            dangerouslySetInnerHTML={{
                                                __html: link.label,
                                            }}
                                        />
                                    </Link>
                                ),
                            )}
                    </div>
                )}
            </div>

            <ConfirmationDialog
                open={deleteUserId !== null}
                onOpenChange={(open) => {
                    if (!open) setDeleteUserId(null);
                }}
                title="Delete User"
                description="Are you sure you want to delete this user? This action cannot be undone."
                onConfirm={handleDelete}
                isProcessing={isDeleting}
                confirmText="Delete"
                variant="destructive"
            />
        </AppLayout>
    );
}
