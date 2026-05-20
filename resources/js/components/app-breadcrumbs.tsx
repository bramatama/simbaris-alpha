import React from 'react';
import { Link } from '@inertiajs/react';
import {
    Breadcrumb,
    BreadcrumbEllipsis,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb'; // Sesuaikan jika jalurnya berbeda
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { BreadcrumbItem as BreadcrumbType } from '@/types';

interface AppBreadcrumbsProps {
    breadcrumbs?: BreadcrumbType[];
}

export function AppBreadcrumbs({ breadcrumbs }: AppBreadcrumbsProps) {
    if (!breadcrumbs || breadcrumbs.length === 0) return null;

    // Batas maksimal item yang ditampilkan utuh sebelum dipotong
    const ITEMS_TO_DISPLAY = 3;

    // JIKA PENDEK: Render normal tanpa Ellipsis
    if (breadcrumbs.length <= ITEMS_TO_DISPLAY) {
        return (
            <Breadcrumb>
                <BreadcrumbList>
                    {breadcrumbs.map((item, index) => (
                        <React.Fragment key={index}>
                            <BreadcrumbItem>
                                {index === breadcrumbs.length - 1 ? (
                                    <BreadcrumbPage>
                                        {item.title}
                                    </BreadcrumbPage>
                                ) : (
                                    <BreadcrumbLink asChild>
                                        <Link href={item.href}>
                                            {item.title}
                                        </Link>
                                    </BreadcrumbLink>
                                )}
                            </BreadcrumbItem>
                            {index < breadcrumbs.length - 1 && (
                                <BreadcrumbSeparator />
                            )}
                        </React.Fragment>
                    ))}
                </BreadcrumbList>
            </Breadcrumb>
        );
    }

    // JIKA PANJANG: Potong array
    // Ambil item PERTAMA (biasanya Home/Dashboard)
    const firstItem = breadcrumbs[0];
    // Ambil 2 item TERAKHIR
    const lastTwoItems = breadcrumbs.slice(-2);
    // Masukkan sisa item yang di tengah ke dalam DROPDOWN (Ellipsis)
    const hiddenItems = breadcrumbs.slice(1, -2);

    return (
        <Breadcrumb>
            <BreadcrumbList>
                {/* 1. Render Item Pertama */}
                <BreadcrumbItem>
                    <BreadcrumbLink asChild>
                        <Link href={firstItem.href}>{firstItem.title}</Link>
                    </BreadcrumbLink>
                </BreadcrumbItem>
                <BreadcrumbSeparator />

                {/* 2. Render Ellipsis & Dropdown (Item yang disembunyikan) */}
                <BreadcrumbItem>
                    <DropdownMenu>
                        <DropdownMenuTrigger className="flex items-center gap-1 outline-none focus:outline-none">
                            <BreadcrumbEllipsis className="h-4 w-4" />
                            <span className="sr-only">Toggle menu</span>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="start">
                            {hiddenItems.map((item, index) => (
                                <DropdownMenuItem key={index} asChild>
                                    <Link
                                        href={item.href}
                                        className="cursor-pointer"
                                    >
                                        {item.title}
                                    </Link>
                                </DropdownMenuItem>
                            ))}
                        </DropdownMenuContent>
                    </DropdownMenu>
                </BreadcrumbItem>
                <BreadcrumbSeparator />

                {/* 3. Render 2 Item Terakhir */}
                {lastTwoItems.map((item, index) => (
                    <React.Fragment key={index}>
                        <BreadcrumbItem>
                            {index === lastTwoItems.length - 1 ? (
                                <BreadcrumbPage>{item.title}</BreadcrumbPage>
                            ) : (
                                <BreadcrumbLink asChild>
                                    <Link href={item.href}>{item.title}</Link>
                                </BreadcrumbLink>
                            )}
                        </BreadcrumbItem>
                        {index < lastTwoItems.length - 1 && (
                            <BreadcrumbSeparator />
                        )}
                    </React.Fragment>
                ))}
            </BreadcrumbList>
        </Breadcrumb>
    );
}
