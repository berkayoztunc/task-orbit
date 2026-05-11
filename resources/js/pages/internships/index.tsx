import { Head, router, usePage } from '@inertiajs/react';
import { GraduationCap, Users } from 'lucide-react';
import { useEffect, useState } from 'react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Skeleton } from '@/components/ui/skeleton';
import { useApi } from '@/hooks/use-api';
import * as pageRoutes from '@/routes/page';
import * as pageInternshipRoutes from '@/routes/page/internships';
import type { InternRegister, Internship, Profile } from '@/types';

type StatusFilter = 'all' | 'active' | 'inactive';

function CardSkeleton() {
    return (
        <div className="glass-card flex flex-col gap-3 rounded-xl p-5">
            <div className="flex items-start justify-between">
                <Skeleton className="h-5 w-2/3" />
                <Skeleton className="h-5 w-16 rounded-full" />
            </div>
            <Skeleton className="h-4 w-full" />
            <Skeleton className="h-4 w-1/2" />
            <div className="mt-2 flex items-center justify-between">
                <Skeleton className="h-4 w-1/3" />
                <Skeleton className="h-8 w-24 rounded-lg" />
            </div>
        </div>
    );
}

function statusLabel(s: string) {
    if (s === 'active') return 'Aktif';
    if (s === 'inactive') return 'Pasif';
    return s;
}

function statusVariant(s: string): 'default' | 'secondary' | 'destructive' | 'outline' {
    if (s === 'active') return 'default';
    if (s === 'inactive') return 'secondary';
    return 'outline';
}

function applyBadge(register: InternRegister | undefined) {
    if (!register) return null;
    if (register.status === 1 || (register.status as unknown) === true) {
        return <Badge variant="default">Onaylandı</Badge>;
    }
    return <Badge variant="secondary">Beklemede</Badge>;
}

export default function InternshipsIndex() {
    const { activeProfile } = usePage<{ activeProfile?: Profile }>().props;
    const api = useApi();
    const [internships, setInternships] = useState<Internship[]>([]);
    const [registers, setRegisters] = useState<InternRegister[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [filter, setFilter] = useState<StatusFilter>('all');
    const [applying, setApplying] = useState<number | null>(null);

    useEffect(() => {
        setLoading(true);

        // Prefer company_id from active profile, fall back to URL query param
        const params = new URLSearchParams(window.location.search);
        const companyIdFromUrl = params.get('company_id');
        const companyId = activeProfile?.company_id ?? (companyIdFromUrl ? Number(companyIdFromUrl) : null);
        const qs = companyId ? `?company_id=${companyId}` : '';

        Promise.all([
            api.get<Internship[]>(`/internships${qs}`),
            api.get<InternRegister[]>('/intern-registers'),
        ]).then(([internshipsData, registersData]) => {
            if (internshipsData) setInternships(internshipsData);
            else setError('Staj programları yüklenemedi.');
            if (registersData) setRegisters(registersData);
        }).finally(() => setLoading(false));
    }, [activeProfile?.id]);

    async function handleApply(internshipId: number) {
        if (!activeProfile) return;
        setApplying(internshipId);
        const result = await api.post<InternRegister>('/intern-registers', {
            profile_id: activeProfile.id,
            internship_id: internshipId,
        });
        if (result) {
            setRegisters((prev) => [...prev, result]);
        }
        setApplying(null);
    }

    const visible =
        filter === 'all' ? internships : internships.filter((i) => i.status === filter);

    const tabs: { label: string; value: StatusFilter }[] = [
        { label: 'Tümü', value: 'all' },
        { label: 'Aktif', value: 'active' },
        { label: 'Pasif', value: 'inactive' },
    ];

    function getRegister(internshipId: number) {
        return registers.find((r) => r.internship_id === internshipId);
    }

    return (
        <>
            <Head title="Staj Programları" />

            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">Staj Programları</h1>
                        <p className="mt-1 text-sm text-muted-foreground">
                            {activeProfile?.company
                                ? `${(activeProfile.company as any).title} şirketinin staj programları`
                                : 'Mevcut staj programlarını inceleyin ve başvurun.'}
                        </p>
                    </div>
                </div>

                {/* Filter tabs */}
                <div className="flex gap-2">
                    {tabs.map((tab) => (
                        <button
                            key={tab.value}
                            type="button"
                            onClick={() => setFilter(tab.value)}
                            className={[
                                'rounded-lg px-4 py-2 text-sm font-medium transition-colors',
                                filter === tab.value
                                    ? 'bg-primary text-primary-foreground'
                                    : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground',
                            ].join(' ')}
                        >
                            {tab.label}
                        </button>
                    ))}
                </div>

                {/* Error */}
                {error && (
                    <div className="rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-400">
                        {error}
                    </div>
                )}

                {!activeProfile && !loading && (
                    <div className="rounded-xl border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-400">
                        Başvuru yapabilmek için önce{' '}
                        <button
                            type="button"
                            className="underline"
                            onClick={() => router.visit('/profile/select')}
                        >
                            bir profil seçin
                        </button>
                        .
                    </div>
                )}

                {/* Card list */}
                <div className="flex flex-col gap-3">
                    {loading
                        ? Array.from({ length: 4 }).map((_, i) => <CardSkeleton key={i} />)
                        : visible.map((internship) => {
                              const register = getRegister(internship.id);
                              const isApplying = applying === internship.id;
                              const alreadyApplied = !!register;

                              return (
                                  <div
                                      key={internship.id}
                                      className="glass-card flex flex-col gap-3 rounded-xl p-5 sm:flex-row sm:items-center sm:justify-between"
                                  >
                                      {/* Left section */}
                                      <div className="flex min-w-0 flex-col gap-1">
                                          <div className="flex flex-wrap items-center gap-2">
                                              <h2 className="font-semibold">{internship.title}</h2>
                                              <Badge variant={statusVariant(internship.status)}>
                                                  {statusLabel(internship.status)}
                                              </Badge>
                                              {applyBadge(register)}
                                          </div>
                                          {internship.company && (
                                              <p className="text-sm text-muted-foreground">
                                                  {internship.company.title}
                                              </p>
                                          )}
                                          {internship.intern_registers_count !== undefined && (
                                              <div className="flex items-center gap-1 text-xs text-muted-foreground">
                                                  <Users className="h-3 w-3" />
                                                  {internship.intern_registers_count} stajyer
                                              </div>
                                          )}
                                      </div>

                                      {/* Actions */}
                                      <div className="flex shrink-0 gap-2">
                                          <Button
                                              variant="outline"
                                              size="sm"
                                              onClick={() =>
                                                  router.visit(pageInternshipRoutes.show(internship.id).url)
                                              }
                                          >
                                              İncele
                                          </Button>
                                          {activeProfile && !alreadyApplied && (
                                              <Button
                                                  size="sm"
                                                  disabled={isApplying}
                                                  onClick={() => handleApply(internship.id)}
                                              >
                                                  {isApplying ? 'Başvuruluyor…' : 'Başvur'}
                                              </Button>
                                          )}
                                      </div>
                                  </div>
                              );
                          })}

                    {/* Empty state */}
                    {!loading && !error && visible.length === 0 && (
                        <div className="glass-card flex flex-col items-center justify-center rounded-xl py-16 text-center">
                            <GraduationCap className="h-10 w-10 text-muted-foreground/50" />
                            <p className="mt-3 text-sm text-muted-foreground">
                                {filter === 'all'
                                    ? 'Henüz kayıtlı staj programı bulunmuyor.'
                                    : `Bu durumda staj programı bulunamadı.`}
                            </p>
                        </div>
                    )}
                </div>
            </div>
        </>
    );
}

InternshipsIndex.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Şirketler', href: pageRoutes.companies() },
        { title: 'Staj Programları', href: pageRoutes.internships() },
    ],
};

