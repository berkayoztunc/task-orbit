import { Link, router, Head } from '@inertiajs/react';
import { Building2, ChevronRight, Users } from 'lucide-react';
import { useEffect, useState } from 'react';
import { Badge } from '@/components/ui/badge';
import { Skeleton } from '@/components/ui/skeleton';
import { useApi } from '@/hooks/use-api';
import * as pageRoutes from '@/routes/page';
import * as pageInternshipRoutes from '@/routes/page/internships';
import type { Company } from '@/types';

function CompanyCardSkeleton() {
    return (
        <div className="glass-card rounded-xl p-6">
            <div className="flex items-start justify-between">
                <Skeleton className="h-10 w-10 rounded-lg" />
                <Skeleton className="h-5 w-16 rounded-full" />
            </div>
            <Skeleton className="mt-4 h-5 w-3/4" />
            <Skeleton className="mt-2 h-4 w-full" />
            <Skeleton className="mt-1 h-4 w-2/3" />
            <Skeleton className="mt-4 h-4 w-1/3" />
        </div>
    );
}

export default function CompaniesIndex() {
    const api = useApi();
    const [companies, setCompanies] = useState<Company[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        setLoading(true);
        api.get<Company[]>('/companies')
            .then((data) => {
                if (data) setCompanies(data);
                else setError('Şirketler yüklenemedi.');
            })
            .finally(() => setLoading(false));
    }, []);

    return (
        <>
            <Head title="Şirketler" />

            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">Şirketler</h1>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Staj yapılabilecek şirketleri görüntüleyin.
                        </p>
                    </div>
                </div>

                {/* Error */}
                {error && (
                    <div className="rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-400">
                        {error}
                    </div>
                )}

                {/* Grid */}
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {loading
                        ? Array.from({ length: 6 }).map((_, i) => <CompanyCardSkeleton key={i} />)
                        : companies.map((company) => (
                              <button
                                  key={company.id}
                                  type="button"
                                  onClick={() =>
                                      router.visit(
                                          pageRoutes.internships.url({ company_id: company.id }),
                                      )
                                  }
                                  className="glass-card group flex flex-col rounded-xl p-6 text-left transition-all hover:ring-2 hover:ring-primary/50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                              >
                                  {/* Icon + badge row */}
                                  <div className="flex items-start justify-between">
                                      <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                          <Building2 className="h-5 w-5" />
                                      </div>
                                      {company.internships_count !== undefined && (
                                          <Badge variant="secondary" className="flex items-center gap-1">
                                              <Users className="h-3 w-3" />
                                              {company.internships_count} staj
                                          </Badge>
                                      )}
                                  </div>

                                  {/* Company info */}
                                  <h2 className="mt-4 font-semibold leading-snug">{company.title}</h2>
                                  {company.description && (
                                      <p className="mt-1 line-clamp-2 text-sm text-muted-foreground">
                                          {company.description}
                                      </p>
                                  )}

                                  {/* CTA */}
                                  <div className="mt-4 flex items-center gap-1 text-xs font-medium text-primary opacity-0 transition-opacity group-hover:opacity-100">
                                      Stajları görüntüle
                                      <ChevronRight className="h-3 w-3" />
                                  </div>
                              </button>
                          ))}

                    {/* Empty state */}
                    {!loading && !error && companies.length === 0 && (
                        <div className="glass-card col-span-full flex flex-col items-center justify-center rounded-xl py-16 text-center">
                            <Building2 className="h-10 w-10 text-muted-foreground/50" />
                            <p className="mt-3 text-sm text-muted-foreground">
                                Henüz kayıtlı şirket bulunmuyor.
                            </p>
                        </div>
                    )}
                </div>
            </div>
        </>
    );
}

CompaniesIndex.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Şirketler', href: pageRoutes.companies() },
    ],
};
