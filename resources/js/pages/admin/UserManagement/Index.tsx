import { Head, Link, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader, DialogClose } from '@/components/ui/dialog';
import { Badge } from '@/components/ui/badge';
import { Trash2, Edit2 } from 'lucide-react';
import type { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';


interface User {
    user_id: number;
    public_id: string;
    name: string;
    email: string;
    role: 'admin' | 'official_team' | 'judge' | 'commitee';
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
            commitee: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-100',
            official_team: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100',
        };
        return colors[role] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-100';
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
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight">User Management</h1>
                        <p className="text-muted-foreground mt-1">
                            Manage system users and their roles
                        </p>
                    </div>
                </div>

                {/* Users Table */}
                <div className="rounded-lg border bg-card overflow-hidden">
                    <div className="overflow-x-auto">
                        <div className="w-full border-collapse">
                            {/* Table Header */}
                            <div className="grid grid-cols-6 gap-4 border-b bg-muted/50 p-4 font-medium sticky top-0">
                                <div>Name</div>
                                <div>Email</div>
                                <div>Contact</div>
                                <div>Created</div>
                                <div className="text-right">Actions</div>
                            </div>

                            {/* Table Body */}
                            {users && users.data && users.data.length > 0 ? (
                                users.data.map((user: User) => (
                                    <div
                                        key={user.user_id}
                                        className="grid grid-cols-6 gap-4 border-b p-4 items-center hover:bg-muted/30 transition-colors"
                                    >
                                        <div className="font-medium">{user.name}</div>
                                        <div className="text-sm text-muted-foreground">{user.email}</div>
                                        <div className="text-sm">{user.contact_info || '-'}</div>
                                        <div className="text-sm text-muted-foreground">
                                            {formatDate(user.created_at)}
                                        </div>
                                        <div className="flex justify-end gap-2">
                                            <Link href={`/admin/users/${user.user_id}`}>
                                                <Button
                                                    variant="outline"
                                                    size="sm"
                                                    className="gap-2"
                                                >
                                                    <Edit2 className="h-4 w-4" />
                                                    Edit
                                                </Button>
                                            </Link>
                                            <Button
                                                variant="destructive"
                                                size="sm"
                                                className="gap-2"
                                                onClick={() => setDeleteUserId(user.user_id)}
                                            >
                                                <Trash2 className="h-4 w-4" />
                                                Delete
                                            </Button>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div className="p-8 text-center text-muted-foreground col-span-full">
                                    No users found
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Pagination */}
                {users && users.meta && users.meta.last_page > 1 && (
                    <div className="flex items-center justify-center gap-2">
                        {users.links && users.links.map((link: PaginationLink, index: number) => (
                            <Link key={index} href={link.url || '#'}>
                                <Button
                                    variant={link.active ? 'default' : 'outline'}
                                    disabled={!link.url}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            </Link>
                        ))}
                    </div>
                )}
            </div>

            {/* Delete Dialog */}
            <Dialog open={deleteUserId !== null} onOpenChange={() => setDeleteUserId(null)}>
                <DialogContent>
                    <DialogHeader>
                        <div>
                            <h2 className="text-lg font-semibold">Delete User</h2>
                            <p className="text-sm text-muted-foreground mt-2">
                                Are you sure you want to delete this user? This action cannot be undone.
                            </p>
                        </div>
                    </DialogHeader>
                    <div className="flex gap-3 justify-end mt-6">
                        <DialogClose asChild>
                            <Button variant="outline">Cancel</Button>
                        </DialogClose>
                        <Button
                            variant="destructive"
                            onClick={handleDelete}
                            disabled={isDeleting}
                        >
                            {isDeleting ? 'Deleting...' : 'Delete'}
                        </Button>
                    </div>
                </DialogContent>
            </Dialog>
        </AppLayout>
    );
}
