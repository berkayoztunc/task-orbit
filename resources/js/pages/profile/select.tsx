import { Head, router, usePage } from '@inertiajs/react';
import { Building2, CheckCircle2, Plus, UserCircle2, X } from 'lucide-react';
import { useEffect, useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Skeleton } from '@/components/ui/skeleton';
import { useApi } from '@/hooks/use-api';
import * as pageRoutes from '@/routes/page';
import type { Auth, Company, Profile, Role } from '@/types';

type UserContext = {
    user: { id: number; name: string; current_profile_id: number };
    active_profile: Profile | null;
    active_internship: unknown | null;
};

function ProfileCardSkeleton() {
    return (
        <div className="glass-card flex flex-col gap-3 rounded-xl p-5">
            <Skeleton className="h-10 w-10 rounded-full" />
            <Skeleton className="h-5 w-3/4" />
            <Skeleton className="h-4 w-1/2" />
        </div>
    );
}

export default function ProfileSelect() {
    const { auth } = usePage<{ auth: Auth }>().props;
    const api = useApi();

    const [context, setContext] = useState<UserContext | null>(null);
    const [loading, setLoading] = useState(true);
    const [selecting, setSelecting] = useState<number | null>(null);

    // New profile form
    const [showForm, setShowForm] = useState(false);
    const [companies, setCompanies] = useState<Company[]>([]);
    const [roles, setRoles] = useState<Role[]>([]);
    const [formCompany, setFormCompany] = useState('');
    const [formRole, setFormRole] = useState('');
    const [creating, setCreating] = useState(false);
    const [formError, setFormError] = useState<string | null>(null);

    useEffect(() => {
        api.get<UserContext>('/users/me')
            .then((data) => {
                if (data) setContext(data);
            })
            .finally(() => setLoading(false));
    }, []);

    function openForm() {
        setShowForm(true);
        setFormError(null);
        if (companies.length === 0) {
            api.get<Company[]>('/companies').then((d) => { if (d) setCompanies(d); });
        }
        if (roles.length === 0) {
            api.get<Role[]>('/roles').then((d) => { if (d) setRoles(d); });
        }
    }

    async function handleSelect(profileId: number) {
        setSelecting(profileId);
        const result = await api.patch(`/users/${auth.user.id}/switch-profile`, {
            profile_id: profileId,
        });
        if (result) {
            router.visit(pageRoutes.internships());
        }
        setSelecting(null);
    }

    async function handleCreate() {
        if (!formCompany || !formRole) {
            setFormError('Şirket ve rol seçimi zorunludur.');
            return;
        }
        setCreating(true);
        setFormError(null);
        const created = await api.post<Profile>('/profiles', {
            user_id: auth.user.id,
            company_id: Number(formCompany),
            role_id: Number(formRole),
        });
        if (created) {
            // Auto-select the new profile
            await api.patch(`/users/${auth.user.id}/switch-profile`, {
                profile_id: created.id,
            });
            router.visit(pageRoutes.internships());
        }
        setCreating(false);
    }

    const profiles = context?.user
        ? (context.user as any).profiles ?? []
        : [];
    const activeProfileId = context?.user?.current_profile_id ?? 0;

    return (
        <>
            <Head title="Profil Seç" />

            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">Profil Seç</h1>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Aktif profilinizi seçin veya yeni bir profil oluşturun.
                        </p>
                    </div>
                    {!showForm && (
                        <Button onClick={openForm} size="sm" className="gap-2">
                            <Plus className="h-4 w-4" />
                            Yeni Profil
                        </Button>
                    )}
                </div>

                {/* New profile form */}
                {showForm && (
                    <div className="glass-card rounded-xl p-5">
                        <div className="mb-4 flex items-center justify-between">
                            <h2 className="font-semibold">Yeni Profil Oluştur</h2>
                            <button
                                type="button"
                                onClick={() => setShowForm(false)}
                                className="text-muted-foreground hover:text-foreground"
                            >
                                <X className="h-4 w-4" />
                            </button>
                        </div>

                        {formError && (
                            <div className="mb-3 rounded-lg border border-rose-500/30 bg-rose-500/10 px-3 py-2 text-sm text-rose-400">
                                {formError}
                            </div>
                        )}

                        <div className="flex flex-col gap-3 sm:flex-row">
                            <select
                                value={formCompany}
                                onChange={(e) => setFormCompany(e.target.value)}
                                className="flex-1 rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            >
                                <option value="">Şirket seçin…</option>
                                {companies.map((c) => (
                                    <option key={c.id} value={c.id}>{c.title}</option>
                                ))}
                            </select>

                            <select
                                value={formRole}
                                onChange={(e) => setFormRole(e.target.value)}
                                className="flex-1 rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                            >
                                <option value="">Rol seçin…</option>
                                {roles.map((r) => (
                                    <option key={r.id} value={r.id}>{r.name}</option>
                                ))}
                            </select>

                            <Button onClick={handleCreate} disabled={creating} className="shrink-0">
                                {creating ? 'Oluşturuluyor…' : 'Oluştur'}
                            </Button>
                        </div>
                    </div>
                )}

                {/* Profile grid */}
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {loading
                        ? Array.from({ length: 3 }).map((_, i) => <ProfileCardSkeleton key={i} />)
                        : profiles.map((profile: Profile) => {
                              const isActive = profile.id === activeProfileId;
                              const isSelecting = selecting === profile.id;
                              return (
                                  <button
                                      key={profile.id}
                                      type="button"
                                      onClick={() => handleSelect(profile.id)}
                                      disabled={isSelecting || selecting !== null}
                                      className={`glass-card group flex flex-col rounded-xl p-5 text-left transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary ${
                                          isActive
                                              ? 'ring-2 ring-primary'
                                              : 'hover:ring-2 hover:ring-primary/50'
                                      }`}
                                  >
                                      <div className="flex items-start justify-between">
                                          <div className="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                                              <UserCircle2 className="h-5 w-5" />
                                          </div>
                                          {isActive && (
                                              <Badge variant="default" className="flex items-center gap-1">
                                                  <CheckCircle2 className="h-3 w-3" />
                                                  Aktif
                                              </Badge>
                                          )}
                                      </div>

                                      <div className="mt-3 flex items-center gap-2 text-sm text-muted-foreground">
                                          <Building2 className="h-4 w-4 shrink-0" />
                                          <span className="truncate font-medium text-foreground">
                                              {profile.company?.title ?? `Şirket #${profile.company_id}`}
                                          </span>
                                      </div>

                                      <p className="mt-1 text-sm text-muted-foreground">
                                          {profile.role?.name ?? `Rol #${profile.role_id}`}
                                      </p>

                                      <p className="mt-3 text-xs font-medium text-primary opacity-0 transition-opacity group-hover:opacity-100">
                                          {isSelecting ? 'Seçiliyor…' : isActive ? 'Zaten aktif' : 'Bu profili seç →'}
                                      </p>
                                  </button>
                              );
                          })}

                    {/* Empty state */}
                    {!loading && profiles.length === 0 && (
                        <div className="glass-card col-span-full flex flex-col items-center justify-center rounded-xl py-16 text-center">
                            <UserCircle2 className="h-10 w-10 text-muted-foreground/50" />
                            <p className="mt-3 text-sm text-muted-foreground">
                                Henüz profil oluşturmadınız.
                            </p>
                            <Button onClick={openForm} size="sm" className="mt-4 gap-2">
                                <Plus className="h-4 w-4" />
                                İlk profilinizi oluşturun
                            </Button>
                        </div>
                    )}
                </div>
            </div>
        </>
    );
}

ProfileSelect.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>;
