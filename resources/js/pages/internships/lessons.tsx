import { Head } from '@inertiajs/react';
import {
    BookOpen,
    ChevronDown,
    ChevronRight,
    ClipboardList,
    Users,
} from 'lucide-react';
import { useEffect, useState } from 'react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import { Skeleton } from '@/components/ui/skeleton';
import { useApi } from '@/hooks/use-api';
import * as pageRoutes from '@/routes/page';
import type { Attendance, Lesson, Task, TaskSubmission } from '@/types';

function attendanceVariant(
    s: string,
): 'default' | 'secondary' | 'destructive' | 'outline' {
    if (s === 'present') return 'default';
    if (s === 'absent') return 'destructive';
    if (s === 'late') return 'secondary';
    return 'outline';
}

function attendanceLabel(s: string) {
    if (s === 'present') return 'Mevcut';
    if (s === 'absent') return 'Yok';
    if (s === 'late') return 'Geç';
    return s;
}

// ── Task detail row ─────────────────────────────────────────────────────────
function TaskRow({ task }: { task: Task }) {
    const api = useApi();
    const [open, setOpen] = useState(false);
    const [submissions, setSubmissions] = useState<TaskSubmission[]>([]);
    const [loadingSubmissions, setLoadingSubmissions] = useState(false);
    const [scoreInput, setScoreInput] = useState<Record<number, string>>({});
    const [saving, setSaving] = useState<Record<number, boolean>>({});

    async function loadSubmissions() {
        if (submissions.length > 0) return;
        setLoadingSubmissions(true);
        const data = await api.get<TaskSubmission[]>(`/task-submissions?task_id=${task.id}`);
        if (data) setSubmissions(data);
        setLoadingSubmissions(false);
    }

    async function saveScore(sub: TaskSubmission) {
        const score = parseInt(scoreInput[sub.id] ?? String(sub.score ?? ''), 10);
        if (isNaN(score)) return;
        setSaving((p) => ({ ...p, [sub.id]: true }));
        const updated = await api.patch<TaskSubmission>(`/task-submissions/${sub.id}`, {
            score,
        });
        if (updated) {
            setSubmissions((prev) =>
                prev.map((s) => (s.id === sub.id ? updated : s)),
            );
        }
        setSaving((p) => ({ ...p, [sub.id]: false }));
    }

    return (
        <Collapsible
            open={open}
            onOpenChange={(val) => {
                setOpen(val);
                if (val) loadSubmissions();
            }}
        >
            <CollapsibleTrigger className="flex w-full items-center justify-between rounded-lg px-4 py-3 text-sm hover:bg-accent/50 transition-colors">
                <div className="flex items-center gap-2">
                    <ClipboardList className="h-4 w-4 shrink-0 text-muted-foreground" />
                    <span className="font-medium">{task.title}</span>
                    {task.due_date && (
                        <span className="text-xs text-muted-foreground">
                            Son: {task.due_date}
                        </span>
                    )}
                </div>
                {open ? (
                    <ChevronDown className="h-4 w-4 text-muted-foreground" />
                ) : (
                    <ChevronRight className="h-4 w-4 text-muted-foreground" />
                )}
            </CollapsibleTrigger>

            <CollapsibleContent className="px-4 pb-3">
                {task.description && (
                    <p className="mb-3 text-xs text-muted-foreground">{task.description}</p>
                )}

                {loadingSubmissions && <Skeleton className="h-12 w-full rounded-lg" />}

                {!loadingSubmissions && submissions.length === 0 && (
                    <p className="text-xs italic text-muted-foreground">
                        Henüz gönderim yok.
                    </p>
                )}

                {submissions.map((sub) => (
                    <div
                        key={sub.id}
                        className="mt-2 flex items-center justify-between rounded-lg border border-border bg-accent/20 px-3 py-2"
                    >
                        <div className="flex flex-col gap-0.5">
                            <span className="text-xs font-medium">
                                Gönderim #{sub.id}
                            </span>
                            {sub.content && (
                                <span className="line-clamp-1 text-xs text-muted-foreground">
                                    {sub.content}
                                </span>
                            )}
                        </div>
                        <div className="flex items-center gap-2">
                            <input
                                type="number"
                                min={0}
                                max={100}
                                className="w-16 rounded-md border border-border bg-background px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-primary"
                                placeholder={sub.score != null ? String(sub.score) : 'Puan'}
                                value={scoreInput[sub.id] ?? (sub.score != null ? String(sub.score) : '')}
                                onChange={(e) =>
                                    setScoreInput((p) => ({ ...p, [sub.id]: e.target.value }))
                                }
                            />
                            <Button
                                size="sm"
                                variant="outline"
                                disabled={saving[sub.id]}
                                onClick={() => saveScore(sub)}
                            >
                                {saving[sub.id] ? '…' : 'Kaydet'}
                            </Button>
                        </div>
                    </div>
                ))}
            </CollapsibleContent>
        </Collapsible>
    );
}

// ── Lesson card ─────────────────────────────────────────────────────────────
function LessonCard({
    lesson,
    internshipId,
}: {
    lesson: Lesson;
    internshipId: number;
}) {
    const api = useApi();
    const [open, setOpen] = useState(false);
    const [tasks, setTasks] = useState<Task[]>(lesson.tasks ?? []);
    const [attendances, setAttendances] = useState<Attendance[]>(lesson.attendances ?? []);
    const [loadingDetails, setLoadingDetails] = useState(false);
    const [sendingAttendance, setSendingAttendance] = useState(false);
    const [attendanceSent, setAttendanceSent] = useState(false);

    async function loadDetails() {
        if (tasks.length > 0 || attendances.length > 0) return;
        setLoadingDetails(true);
        const [t, a] = await Promise.all([
            api.get<Task[]>(`/tasks?lesson_id=${lesson.id}`),
            api.get<Attendance[]>(`/lessons/${lesson.id}/attendances`),
        ]);
        if (t) setTasks(t);
        if (a) setAttendances(a);
        setLoadingDetails(false);
    }

    async function sendAttendanceCheck() {
        setSendingAttendance(true);
        const result = await api.post(
            `/lessons/${lesson.id}/send-attendance-check`,
            {},
        );
        setSendingAttendance(false);
        if (result !== null) setAttendanceSent(true);
    }

    return (
        <Collapsible
            open={open}
            onOpenChange={(val) => {
                setOpen(val);
                if (val) loadDetails();
            }}
            className="glass-card overflow-hidden rounded-xl"
        >
            {/* Lesson header */}
            <CollapsibleTrigger className="flex w-full items-center justify-between p-5 hover:bg-accent/30 transition-colors">
                <div className="flex min-w-0 flex-col items-start gap-1 text-left">
                    <div className="flex items-center gap-2">
                        <BookOpen className="h-4 w-4 shrink-0 text-primary" />
                        <span className="font-semibold">{lesson.title}</span>
                    </div>
                    <span className="text-xs text-muted-foreground">{lesson.date}</span>
                </div>
                <div className="flex shrink-0 items-center gap-3">
                    <Button
                        size="sm"
                        variant="outline"
                        disabled={sendingAttendance || attendanceSent}
                        onClick={(e) => {
                            e.stopPropagation();
                            sendAttendanceCheck();
                        }}
                    >
                        {attendanceSent
                            ? 'Gönderildi ✓'
                            : sendingAttendance
                              ? 'Gönderiliyor…'
                              : 'Yoklama Gönder'}
                    </Button>
                    {open ? (
                        <ChevronDown className="h-4 w-4 text-muted-foreground" />
                    ) : (
                        <ChevronRight className="h-4 w-4 text-muted-foreground" />
                    )}
                </div>
            </CollapsibleTrigger>

            <CollapsibleContent>
                <div className="border-t border-border p-5">
                    {loadingDetails ? (
                        <div className="flex flex-col gap-2">
                            <Skeleton className="h-10 w-full rounded-lg" />
                            <Skeleton className="h-10 w-full rounded-lg" />
                        </div>
                    ) : (
                        <>
                            {/* Tasks section */}
                            <div className="mb-4">
                                <h3 className="mb-2 flex items-center gap-1.5 text-sm font-semibold">
                                    <ClipboardList className="h-4 w-4 text-muted-foreground" />
                                    Ödevler
                                </h3>
                                {tasks.length === 0 ? (
                                    <p className="text-xs italic text-muted-foreground">
                                        Bu derse ait ödev bulunmuyor.
                                    </p>
                                ) : (
                                    <div className="flex flex-col">
                                        {tasks.map((task) => (
                                            <TaskRow key={task.id} task={task} />
                                        ))}
                                    </div>
                                )}
                            </div>

                            {/* Attendance section */}
                            <div>
                                <h3 className="mb-2 flex items-center gap-1.5 text-sm font-semibold">
                                    <Users className="h-4 w-4 text-muted-foreground" />
                                    Yoklama
                                </h3>
                                {attendances.length === 0 ? (
                                    <p className="text-xs italic text-muted-foreground">
                                        Yoklama kaydı bulunmuyor.
                                    </p>
                                ) : (
                                    <div className="flex flex-wrap gap-2">
                                        {attendances.map((att) => (
                                            <Badge
                                                key={att.id}
                                                variant={attendanceVariant(att.status)}
                                            >
                                                {attendanceLabel(att.status)}
                                            </Badge>
                                        ))}
                                    </div>
                                )}
                            </div>
                        </>
                    )}
                </div>
            </CollapsibleContent>
        </Collapsible>
    );
}

// ── Page ─────────────────────────────────────────────────────────────────────
export default function InternshipsLessons({ internshipId }: { internshipId: number }) {
    const api = useApi();
    const [lessons, setLessons] = useState<Lesson[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        setLoading(true);
        api.get<Lesson[]>(`/internships/${internshipId}/lessons`)
            .then((data) => {
                if (data) setLessons(data);
                else setError('Dersler yüklenemedi.');
            })
            .finally(() => setLoading(false));
    }, [internshipId]);

    return (
        <>
            <Head title="Dersler" />

            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4">
                <div>
                    <h1 className="text-2xl font-semibold tracking-tight">Dersler</h1>
                    <p className="mt-1 text-sm text-muted-foreground">
                        Staj programına ait ders planı ve ödevler.
                    </p>
                </div>

                {error && (
                    <div className="rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-400">
                        {error}
                    </div>
                )}

                <div className="flex flex-col gap-3">
                    {loading
                        ? Array.from({ length: 3 }).map((_, i) => (
                              <div key={i} className="glass-card rounded-xl p-5">
                                  <Skeleton className="h-5 w-1/2" />
                                  <Skeleton className="mt-2 h-3 w-1/4" />
                              </div>
                          ))
                        : lessons.map((lesson) => (
                              <LessonCard
                                  key={lesson.id}
                                  lesson={lesson}
                                  internshipId={internshipId}
                              />
                          ))}

                    {!loading && !error && lessons.length === 0 && (
                        <div className="glass-card flex flex-col items-center justify-center rounded-xl py-16 text-center">
                            <BookOpen className="h-10 w-10 text-muted-foreground/50" />
                            <p className="mt-3 text-sm text-muted-foreground">
                                Bu staj programına ait ders bulunmuyor.
                            </p>
                        </div>
                    )}
                </div>
            </div>
        </>
    );
}

InternshipsLessons.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Staj Programları', href: pageRoutes.internships() },
        { title: 'Detay', href: '#' },
        { title: 'Dersler', href: '#' },
    ],
};
