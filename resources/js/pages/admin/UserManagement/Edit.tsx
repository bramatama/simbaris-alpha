import { Head, Link, useForm, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/main-app-layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { ChevronLeft } from 'lucide-react';
import type { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';

interface User {
    user_id: number;
    public_id: string;
    name: string;
    email: string;
    role: 'admin' | 'official_team' | 'judge' | 'committee';
    contact_info?: string;
}

interface FormData {
    name: string;
    email: string;
    role: 'admin' | 'official_team' | 'judge' | 'committee';
    contact_info: string;
    password: string;
    password_confirmation: string;
    _method: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
    {
        title: 'User Management',
        href: '/admin/users',
    },
    {
        title: 'Edit User',
        href: '#',
    },
];

export default function EditUser() {
    const { props } = usePage<{ user: User }>();
    const { user } = props;

    const { data, setData, post, processing, errors } = useForm<FormData>({
        name: user.name,
        email: user.email,
        role: user.role,
        contact_info: user.contact_info || '',
        password: '',
        password_confirmation: '',
        _method: 'PUT',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(`/admin/users/${user.user_id}`);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit ${user.name}`} />

            <div className="flex flex-1 flex-col gap-4 overflow-auto p-4">
                {/* Header */}
                <div className="flex items-center gap-4">
                    <Link href="/admin/users">
                        <Button variant="outline" size="sm" className="gap-2">
                            <ChevronLeft className="h-4 w-4" />
                            Back
                        </Button>
                    </Link>
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight">
                            Edit User
                        </h1>
                        <p className="mt-1 text-muted-foreground">
                            Update user information
                        </p>
                    </div>
                </div>

                {/* Form */}
                <div className="w-full max-w-2xl">
                    <form
                        onSubmit={handleSubmit}
                        className="space-y-6 rounded-lg border bg-card p-6"
                    >
                        {/* Name Field */}
                        <div className="space-y-2">
                            <Label htmlFor="name">Full Name</Label>
                            <Input
                                id="name"
                                type="text"
                                value={data.name}
                                onChange={(e) =>
                                    setData('name', e.target.value)
                                }
                                placeholder="John Doe"
                                className={
                                    errors.name ? 'border-destructive' : ''
                                }
                            />
                            {errors.name && (
                                <p className="text-sm text-destructive">
                                    {errors.name}
                                </p>
                            )}
                        </div>

                        {/* Email Field */}
                        <div className="space-y-2">
                            <Label htmlFor="email">Email</Label>
                            <Input
                                id="email"
                                type="email"
                                value={data.email}
                                onChange={(e) =>
                                    setData('email', e.target.value)
                                }
                                placeholder="john@example.com"
                                className={
                                    errors.email ? 'border-destructive' : ''
                                }
                            />
                            {errors.email && (
                                <p className="text-sm text-destructive">
                                    {errors.email}
                                </p>
                            )}
                        </div>

                        {/* Role Select */}
                        <div className="space-y-2">
                            <Label htmlFor="role">Role</Label>
                            <Select
                                value={data.role}
                                onValueChange={(value) =>
                                    setData('role', value as any)
                                }
                            >
                                <SelectTrigger
                                    className={
                                        errors.role ? 'border-destructive' : ''
                                    }
                                >
                                    <SelectValue placeholder="Select a role" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="admin">Admin</SelectItem>
                                    <SelectItem value="judge">Judge</SelectItem>
                                    <SelectItem value="committee">
                                        Committee
                                    </SelectItem>
                                    <SelectItem value="official_team">
                                        Official Team
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            {errors.role && (
                                <p className="text-sm text-destructive">
                                    {errors.role}
                                </p>
                            )}
                        </div>

                        {/* Contact Info */}
                        <div className="space-y-2">
                            <Label htmlFor="contact_info">
                                Contact Information
                            </Label>
                            <Input
                                id="contact_info"
                                type="text"
                                value={data.contact_info}
                                onChange={(e) =>
                                    setData('contact_info', e.target.value)
                                }
                                placeholder="+62 812 3456 7890"
                                className={
                                    errors.contact_info
                                        ? 'border-destructive'
                                        : ''
                                }
                            />
                            {errors.contact_info && (
                                <p className="text-sm text-destructive">
                                    {errors.contact_info}
                                </p>
                            )}
                        </div>

                        {/* Divider */}
                        <div className="border-t pt-6">
                            <h3 className="mb-4 text-lg font-semibold">
                                Change Password (Optional)
                            </h3>
                        </div>

                        {/* Password Field */}
                        <div className="space-y-2">
                            <Label htmlFor="password">New Password</Label>
                            <Input
                                id="password"
                                type="password"
                                value={data.password}
                                onChange={(e) =>
                                    setData('password', e.target.value)
                                }
                                placeholder="Leave blank to keep current password"
                                className={
                                    errors.password ? 'border-destructive' : ''
                                }
                            />
                            {errors.password && (
                                <p className="text-sm text-destructive">
                                    {errors.password}
                                </p>
                            )}
                        </div>

                        {/* Password Confirmation */}
                        <div className="space-y-2">
                            <Label htmlFor="password_confirmation">
                                Confirm Password
                            </Label>
                            <Input
                                id="password_confirmation"
                                type="password"
                                value={data.password_confirmation}
                                onChange={(e) =>
                                    setData(
                                        'password_confirmation',
                                        e.target.value,
                                    )
                                }
                                placeholder="Confirm new password"
                                className={
                                    errors.password_confirmation
                                        ? 'border-destructive'
                                        : ''
                                }
                            />
                            {errors.password_confirmation && (
                                <p className="text-sm text-destructive">
                                    {errors.password_confirmation}
                                </p>
                            )}
                        </div>

                        {/* Form Actions */}
                        <div className="flex justify-start gap-3 border-t pt-6">
                            <Button
                                type="submit"
                                disabled={processing}
                                className="gap-2"
                            >
                                {processing ? 'Saving...' : 'Save Changes'}
                            </Button>
                            <Link href="/admin/users">
                                <Button variant="outline">Cancel</Button>
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
