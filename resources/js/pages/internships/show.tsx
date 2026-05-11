import { Head, router } from '@inertiajs/react';
import { Calendar, GraduationCap, Users } from 'lucide-react';
import { useEffect, useState } from 'react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Skeleton } from '@/components/ui/skeleton';
import { useApi } from '@/hooks/use-api';
import * as pageRoutes from '@/routes/page';
import * as pageInternshipRoutes from '@/routes/page/internships';
import type { InternRegister, Internship } from '@/types';

type Tab = 'info' | 'registers';

function statusVariant(s: string): 'default' | 'secondary' | 'destructive' | 'outline' {
    if (s === 'active') return 'default';
    if (s === 'inactive') return 'secondary';
    return 'outline';
}

function registerStatusVariant(
    s: string,
): 'default' | 'secondary' | 'destructive' | 'outline' {
    if (s === 'accepted') return 'default';
    if (s === 'rejected') return 'destructive';
    return 'secondary';
}

function registerStatusLabel(s: string) {
    if (s === 'accepted') return 'Kabul Edildi';
    if (s === 'rejected') return 'Reddedildi';
    return 'Beklemede';
}

export default function InternshipsShow({ internshipId }: { internshipId: number }) {
    const api = useApi();
    const [internship, setInternship] = useState<Internship | null>(null);
    const [registers, setRegisters] = useState<InternRegister[]>([]);
    const [loading, setLoading] = useState(true);
    const [tab, setTab] = useState<Tab>('info');
    const [applying, setApplying] = useState(false);
    const [applyDialogOpen, setApplyDialogOpen] = useState(false);
    const [applyError, setApplyError] = useState<string | null>(null);
    const [applySuccess, setApplySuccess] = useState(false);

    useEffect(() => {
        setLoading(true);
        Promise.all([
            api.get<Internship>(`/internships/${internshipId}`),
            api.get<InternRegister[]>(`/internships/${internshipId}/intern-registers`),
        ])
            .then(([intern, regs]) => {
                if (intern) setInternship(intern);
                if (regs) setRegisters(regs);
            })
            .finally(() => setLoading(false));
    }, [internshipId]);

    async function handleApply() {
        setApplying(true);
        setApplyError(null);
        const result = await api.post('/intern-registers', { internship_id: internshipId });
        setApplying(false);
        if (result) {
            setApplySuccess(true);
            setApplyDialogOpen(false);
        } else {
            setApplyError('Başvuru gönderilemedi. Lütfen tekrar deneyin.');
        }
    }

    const tabs: { label: string; value: Tab }[] = [
        { label: 'Genel Bilgi', value: 'info' },
        { label: 'Stajyerler', value: 'registers' },
    ];

    return (
        <>
            <Head title={internship?.title ?? 'Staj Detayı'} />

            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4">
                {/* Header skeleton */}
                {loading && (
                    <>
                        <div className="flex items-start justify-between">
                            <div className="flex flex-col gap-2">
                                <Skeleton className="h-7 w-64" />
                                <Skeleton className="h-4 w-40" />
                            </div>
                            <Skeleton className="h-9 w-32 rounded-lg" />
                        </div>
                        <Skeleton className="h-40 w-full rounded-xl" />
                    </>
                )}

                {/* Header */}
                {!loading && internship && (
                    <>
                        <div className="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <div className="flex flex-wrap items-center gap-2">
                                    <h1 className="text-2xl font-semibold tracking-tight">
                                        {internship.title}
                                    </h1>
                                    <Badge variant={statusVariant(internship.status)}>
                                        {internship.status === 'active' ? 'Aktif' : 'Pasif'}
                                    </Badge>
                                </div>
                                {internship.company && (
                                    <p className="mt-1 text-sm text-muted-foreground">
                                        {internship.company.title}
                                    </p>
                                )}
                            </div>

                            <div className="flex gap-2">
                                {/* Lessons button */}
                                <Button
                                    variant="outline"
                                    onClick={() =>
                                        router.visit(
                                            pageInternshipRoutes.lessons(internshipId).url,
                                        )
                                    }
                                >
                                    <Calendar className="mr-2 h-4 w-4" />
                                    Dersler
                                </Button>

                                {/* Apply button */}
                                {!applySuccess && (
                                    <Button onClick={() => setApplyDialogOpen(true)}>
                                        <GraduationCap className="mr-2 h-4 w-4" />
                                        Staja Başvur
                                    </Button>
                                )}
                                {applySuccess && (
                                    <Badge variant="default" className="px-3 py-1.5 text-sm">
                                        Başvuru Gönderildi ✓
                                    </Badge>
                                )}
                            </div>
                        </div>

                        {/* Tabs */}
                        <div className="flex gap-2 border-b border-border pb-1">
                            {tabs.map((t) => (
                                <button
                                    key={t.value}
                                    type="button"
                                    onClick={() => setTab(t.value)}
                                    className={[
                                        'px-4 py-2 text-sm font-medium transition-colors',
                                        tab === t.value
                                            ? 'border-b-2 border-primary text-primary'
                                            : 'text-muted-foreground hover:text-foreground',
                                    ].join(' ')}
                                >
                                    {t.label}
                                </button>
                            ))}
                        </div>

                        {/* Info tab */}
                        {tab === 'info' && (
                            <div className="glass-card rounded-xl p-6">
                                <h2 className="mb-3 font-semibold">Açıklama</h2>
                                {internship.description ? (
                                    <p className="whitespace-pre-line text-sm leading-relaxed text-muted-foreground">
                                        {internship.description}
                                    </p>
                                ) : (
                                    <p className="text-sm italic text-muted-foreground">
                                        Açıklama girilmemiş.
                                    </p>
                                )}
                            </div>
                        )}

                        {/* Registers tab */}
                        {tab === 'registers' && (
                            <div className="flex flex-col gap-3">
                                {registers.length === 0 ? (
                                    <div className="glass-card flex flex-col items-center justify-center rounded-xl py-12 text-center">
                                        <Users className="h-8 w-8 text-muted-foreground/50" />
                                        <p className="mt-2 text-sm text-muted-foreground">
                                            Henüz başvuru bulunmuyor.
                                        </p>
                                    </div>
                                ) : (
                                    registers.map((reg) => (
                                        <div
                                            key={reg.id}
                                            className="glass-card flex items-center justify-between rounded-xl px-5 py-4"
                                        >
                                            <span className="text-sm">
                                                Başvuru #{reg.id}
                                            </span>
                                            <Badge
                                                variant={registerStatusVariant(reg.status)}
                                            >
                                                {registerStatusLabel(reg.status)}
                                            </Badge>
                                        </div>
                                    ))
                                )}
                            </div>
                        )}
                    </>
                )}
            </div>

            {/* Apply dialog */}
            <Dialog open={applyDialogOpen} onOpenChange={setApplyDialogOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Staja Başvur</DialogTitle>
                        <DialogDescription>
                            {internship?.title} staj programına başvurmak istediğinizi onaylıyor
                            musunuz?
                        </DialogDescription>
                    </DialogHeader>
                    {applyError && (
                        <p className="text-sm text-rose-500">{applyError}</p>
                    )}
                    <DialogFooter>
                        <Button
                            variant="outline"
                            onClick={() => setApplyDialogOpen(false)}
                            disabled={applying}
                        >
                            İptal
                        </Button>
                        <Button onClick={handleApply} disabled={applying}>
                            {applying ? 'Gönderiliyor…' : 'Başvur'}
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </>
    );
}

InternshipsShow.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Staj Programları', href: pageRoutes.internships() },
        { title: 'Detay', href: '#' },
    ],
};
