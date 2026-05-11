// ── Domain model types matching the Laravel API response shapes ──────────────

export type Role = {
    id: number;
    name: string;
};

export type Profile = {
    id: number;
    user_id: number;
    company_id: number;
    role_id: number;
    role?: Role;
    company?: Company;
};

export type Company = {
    id: number;
    title: string;
    description?: string | null;
    created_at: string;
    updated_at: string;
    internships_count?: number;
    profiles?: Profile[];
};

export type Internship = {
    id: number;
    company_id: number;
    title: string;
    description?: string | null;
    status: 'active' | 'inactive' | string;
    created_at: string;
    updated_at: string;
    company?: Company;
    intern_registers_count?: number;
};

export type InternRegister = {
    id: number;
    internship_id: number;
    user_id: number;
    status: 'pending' | 'accepted' | 'rejected' | string;
    created_at: string;
    updated_at: string;
    internship?: Internship;
};

export type Lesson = {
    id: number;
    internship_id: number;
    title: string;
    description?: string | null;
    date: string;
    created_at: string;
    updated_at: string;
    internship?: Internship;
    tasks?: Task[];
    attendances?: Attendance[];
};

export type Task = {
    id: number;
    lesson_id: number;
    title: string;
    description?: string | null;
    due_date?: string | null;
    lesson?: Lesson;
    task_submissions?: TaskSubmission[];
};

export type TaskSubmission = {
    id: number;
    task_id: number;
    user_id: number;
    content?: string | null;
    score?: number | null;
    submitted_at?: string | null;
    created_at: string;
    updated_at: string;
};

export type Attendance = {
    id: number;
    lesson_id: number;
    user_id: number;
    status: 'present' | 'absent' | 'late' | string;
    created_at: string;
    updated_at: string;
};

/** Standard REST API response envelope */
export type ApiResponse<T> = {
    status: 'success' | 'error';
    message: string;
    data: T;
};
