import { Head, useForm, router, Link } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/layouts/secondary-app-layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger, DialogFooter } from "@/components/ui/dialog";
import InputError from '@/components/input-error';
import { Spinner } from '@/components/ui/spinner';
import { Badge } from '@/components/ui/badge';
import { ConfirmationDialog } from '@/components/confirmation-dialog';
import { ArrowLeft, Plus, UserX, Mail } from 'lucide-react';
import type { BreadcrumbItem } from '@/types';

export default function CommitteeIndex({ event }: { event: any }) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Events', href: '/admin/events' },
        { title: event.event_name, href: `/admin/events/${event.public_id}/edit` },
        { title: 'Manage Committees', href: '#' },
    ];

    const [isAddOpen, setIsAddOpen] = useState(false);
    const [deleteId, setDeleteId] = useState<number | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    const { data, setData, post, processing, errors, reset, clearErrors } = useForm({
        name: '',
        email: '',
        department: '',
        position: '',
    });

    const submitAdd = (e: React.FormEvent) => {
        e.preventDefault();
        post(`/admin/events/${event.public_id}/committees`, {
            preserveScroll: true,
            onSuccess: () => {
                setIsAddOpen(false);
                reset();
            },
        });
    };

    const confirmDelete = () => {
        if (!deleteId) return;
        setIsDeleting(true);
        router.delete(`/admin/events/${event.public_id}/committees/${deleteId}`, {
            preserveScroll: true,
            onSuccess: () => setDeleteId(null),
            onFinish: () => setIsDeleting(false),
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Committees - ${event.event_name}`} />

            <div className="mx-auto w-full max-w-6xl p-4 md:p-6 lg:p-8">
                <div className="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <Link href={`/admin/events/${event.public_id}/edit`} className="inline-flex items-center text-sm font-medium text-muted-foreground hover:text-primary mb-2">
                            <ArrowLeft className="mr-1 h-4 w-4" /> Back to Event
                        </Link>
                        <h1 className="text-3xl font-bold tracking-tight">Committee Roster</h1>
                        <p className="mt-1 text-muted-foreground">Manage the team organizing <strong>{event.event_name}</strong>.</p>
                    </div>

                    <Dialog open={isAddOpen} onOpenChange={(open) => { setIsAddOpen(open); if (!open) { reset(); clearErrors(); } }}>
                        <DialogTrigger asChild>
                            <Button className="gap-2">
                                <Plus className="h-4 w-4" /> Assign New Member
                            </Button>
                        </DialogTrigger>
                        <DialogContent>
                            <form onSubmit={submitAdd}>
                                <DialogHeader>
                                    <DialogTitle>Add Committee Member</DialogTitle>
                                </DialogHeader>
                                <div className="space-y-4 py-4">
                                    <div className="grid gap-2">
                                        <Label htmlFor="name">Full Name</Label>
                                        <Input id="name" value={data.name} onChange={e => setData('name', e.target.value)} required />
                                        <InputError message={errors.name} />
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="email">Email Address</Label>
                                        <Input id="email" type="email" value={data.email} onChange={e => setData('email', e.target.value)} required />
                                        <InputError message={errors.email} />
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="department">Department</Label>
                                        <Input id="department" placeholder="e.g. Divisi Acara" value={data.department} onChange={e => setData('department', e.target.value)} required />
                                        <InputError message={errors.department} />
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="position">Position / Role</Label>
                                        <Input id="position" placeholder="e.g. Ketua Pelaksana" value={data.position} onChange={e => setData('position', e.target.value)} required />
                                        <InputError message={errors.position} />
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button type="button" variant="outline" onClick={() => setIsAddOpen(false)}>Cancel</Button>
                                    <Button type="submit" disabled={processing}>
                                        {processing && <Spinner className="mr-2 h-4 w-4" />} Save & Create Account
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Assigned Personnel ({event.event_committees?.length || 0})</CardTitle>
                        <CardDescription>All members listed below have access to the committee dashboard for this event.</CardDescription>
                    </CardHeader>
                    <CardContent className="p-0">
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm text-left">
                                <thead className="bg-muted/50 text-muted-foreground">
                                    <tr>
                                        <th className="px-6 py-4 font-medium">Name & Contact</th>
                                        <th className="px-6 py-4 font-medium">Department</th>
                                        <th className="px-6 py-4 font-medium">Position</th>
                                        <th className="px-6 py-4 font-medium text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y">
                                    {event.event_committees?.map((ec: any) => (
                                        <tr key={ec.event_commitee_id} className="hover:bg-muted/20">
                                            <td className="px-6 py-4">
                                                <div className="font-semibold text-base">{ec.committee?.user?.name}</div>
                                                <div className="flex items-center text-muted-foreground mt-1 gap-1.5">
                                                    <Mail className="h-3 w-3" /> {ec.committee?.user?.email}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4">{ec.committee?.department}</td>
                                            <td className="px-6 py-4">
                                                <Badge variant="secondary" className="uppercase text-[10px] tracking-wider">{ec.position}</Badge>
                                            </td>
                                            <td className="px-6 py-4 text-right">
                                                <Button 
                                                    variant="destructive" 
                                                    size="sm" 
                                                    className="text-red-600 hover:text-red-700 hover:bg-red-50"
                                                    onClick={() => setDeleteId(ec.event_commitee_id)}
                                                >
                                                    <UserX className="h-4 w-4 mr-1.5" /> Remove
                                                </Button>
                                            </td>
                                        </tr>
                                    ))}
                                    {event.event_committees?.length === 0 && (
                                        <tr>
                                            <td colSpan={4} className="px-6 py-12 text-center text-muted-foreground">
                                                No committee members assigned yet.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>

                {/* Dialog Konfirmasi Hapus Panitia */}
                <ConfirmationDialog
                    open={deleteId !== null}
                    onOpenChange={(open) => !open && setDeleteId(null)}
                    title="Remove Committee Member?"
                    description="This will revoke their access to manage this event. Their actual account will not be deleted."
                    onConfirm={confirmDelete}
                    isProcessing={isDeleting}
                    confirmText="Yes, Remove"
                />

            </div>
        </AppLayout>
    );
}