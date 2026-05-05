<!-- resources/views/mentor.blade.php -->
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Paneli - Task Orbit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; background-color: #020617; color: #f8fafc; overflow-x: hidden; }
        .bg-navy-deep { background-color: #020617; }
        .glass-card { background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(24px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .emerald-glow { box-shadow: 0 0 50px rgba(16, 185, 129, 0.1); }
        .sidebar-item-active { background: rgba(16, 185, 129, 0.1); color: #10b981; border-color: rgba(16, 185, 129, 0.2); }

        /* Monitor Özel CSS'leri */
        .glass-panel { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(24px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .telegram-pulse { animation: pulse-blue 2s infinite; }
        @keyframes pulse-blue {
            0% { box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(14, 165, 233, 0); }
            100% { box-shadow: 0 0 0 0 rgba(14, 165, 233, 0); }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #10b981; }

        @media print {
            @page { size: A4; margin: 0; }
            body { background: white !important; color: black !important; margin: 0 !important; padding: 0 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .print\:hidden { display: none !important; }

            /* Full page certificate control */
            #certificate {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
                margin: 0 !important;
                padding: 15mm !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: center !important;
                z-index: 9999 !important;
                background: white !important;
                box-sizing: border-box !important;
            }

            #certificate-modal { background: white !important; padding: 0 !important; display: block !important; }
            #certificate-modal > div { max-width: none !important; width: 100% !important; padding: 0 !important; }

            /* Hide UI elements */
            header, aside, footer, main, .notifications, .fixed:not(#certificate-modal) { display: none !important; }
        }
    </style>
</head>
<body x-data="{
    sidebarOpen: true,
    currentTab: 'dashboard',
    lessonsOpen: false,
    selectedLesson: null,
    showAssignModal: false,
    showFeedbackModal: false,
    selectedIntern: null,
    searchQuery: '',
    showCertificateModal: false,
    certificateData: {
        technical: 5,
        softSkills: 5,
        attendance: 5,
        participation: 5,
        tasks: 5,
        discipline: 5,
        finalNote: ''
    },

    openCertificateModal(intern) {
        this.selectedIntern = intern;
        this.certificateData = {
            technical: Math.floor(intern.progress / 20) || 1,
            softSkills: 5,
            attendance: 5,
            participation: 5,
            tasks: Math.floor(intern.progress / 20) || 1,
            discipline: 5,
            finalNote: `${intern.name} staj süreci boyunca ${intern.role} olarak başarılı bir performans sergilemiştir.`
        };
        this.showCertificateModal = true;
    },

    lessons: JSON.parse(localStorage.getItem('taskorbit_lessons')) || [
        {
            id: 1,
            title: 'Yazılım Geliştirme',
            topic: 'Backend',
            status: 'Aktif',
            students: 12,
            weeks: [
                { id: 1, title: 'Hafta 1: API Tasarımı', content: 'RESTful mimari, endpoint yönetimi ve dokümantasyon.' },
                { id: 2, title: 'Hafta 2: Veritabanı Yönetimi', content: 'İlişkisel veritabanları, Query optimizasyonu.' },
                { id: 3, title: 'Hafta 3: Unit Testing', content: 'Jest ve PHPUnit ile temel test yazımı.' }
            ]
        },
        {
            id: 2,
            title: 'UI/UX Tasarım',
            topic: 'Frontend',
            status: 'Aktif',
            students: 8,
            weeks: [
                { id: 1, title: 'Hafta 1: Figma Kullanımı', content: 'Auto-layout, varyantlar ve tasarım sistemleri.' },
                { id: 2, title: 'Hafta 2: Renk ve Tipografi', content: 'Görsel hiyerarşi ve okunabilirlik standartları.' },
                { id: 3, title: 'Hafta 3: Kullanıcı Akışları', content: 'Analiz ve prototipleme süreçleri.' }
            ]
        }
    ],

    saveLessons() {
        localStorage.setItem('taskorbit_lessons', JSON.stringify(this.lessons));
    },

    // Stajyerler artık ders ID'sine sahip
    interns: [
        { id: 1, lessonId: 1, name: 'Ahmet Yılmaz', role: 'Frontend Intern', progress: 65, avatar: 'AY', color: 'blue', email: 'ahmet@technova.com', telegramStatus: 'confirm', telegramTime: '14:02' },
        { id: 2, lessonId: 1, name: 'Ayşe Kaya', role: 'Backend Intern', progress: 40, avatar: 'AK', color: 'emerald', email: 'ayse@technova.com', telegramStatus: 'confirm', telegramTime: '14:05' },
        { id: 3, lessonId: 1, name: 'Mehmet Demir', role: 'UI/UX Intern', progress: 85, avatar: 'MD', color: 'amber', email: 'mehmet@technova.com', telegramStatus: 'reject', telegramTime: '14:10' },
        { id: 4, lessonId: 1, name: 'Selin Arı', role: 'Cyber Security Intern', progress: 20, avatar: 'SA', color: 'purple', email: 'selin@technova.com', telegramStatus: 'waiting', telegramTime: '-' },
        { id: 5, lessonId: 2, name: 'Can Öz', role: 'Cloud Intern', progress: 50, avatar: 'CÖ', color: 'rose', email: 'can@technova.com', telegramStatus: 'confirm', telegramTime: '14:12' },
        { id: 6, lessonId: 2, name: 'Elif Ak', role: 'DevOps Intern', progress: 30, avatar: 'EA', color: 'indigo', email: 'elif@technova.com', telegramStatus: 'waiting', telegramTime: '-' }
    ],

    lastUpdate: 'Az önce',

    selectLesson(lesson) {
        this.selectedLesson = lesson;
        this.currentTab = 'lesson-detail';
    },

    showWeekModal: false,
    weekModalMode: 'add',
    currentWeek: { id: null, title: '', content: '' },

    openAddWeekModal() {
        if(this.selectedLesson) {
            const nextWeekNum = this.selectedLesson.weeks.length + 1;
            this.currentWeek = { id: null, title: `Hafta ${nextWeekNum}: `, content: '' };
            this.weekModalMode = 'add';
            this.showWeekModal = true;
        }
    },

    openEditWeekModal(week) {
        this.currentWeek = { ...week };
        this.weekModalMode = 'edit';
        this.showWeekModal = true;
    },

    saveWeek() {
        if (!this.selectedLesson || !this.currentWeek.title) return;

        if (this.weekModalMode === 'add') {
            this.currentWeek.id = Date.now();
            this.selectedLesson.weeks.push({ ...this.currentWeek });
            this.notify('Yeni hafta müfredata eklendi.');
        } else {
            const index = this.selectedLesson.weeks.findIndex(w => w.id === this.currentWeek.id);
            if (index !== -1) {
                this.selectedLesson.weeks[index] = { ...this.currentWeek };
                this.notify('Hafta detayları güncellendi.');
            }
        }
        this.saveLessons();
        this.showWeekModal = false;
    },

    deleteWeek() {
        if (!this.selectedLesson) return;

        if (this.weekModalMode === 'edit') {
            this.selectedLesson.weeks = this.selectedLesson.weeks.filter(w => w.id !== this.currentWeek.id);
            this.notify('Hafta başarıyla silindi.');
            this.saveLessons();
        }
        this.showWeekModal = false;
    },

    get filteredInterns() {
        if (!this.selectedLesson) return this.interns;
        return this.interns.filter(i => i.lessonId === this.selectedLesson.id);
    },

    notifications: [],
    notify(msg) {
        const id = Date.now();
        this.notifications.push({ id, message: msg });
        setTimeout(() => {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }, 3000);
    },

    tasks: [
        { id: 1, lessonId: 1, title: 'API Integration', intern: 'Ahmet Yılmaz', deadline: '2024-04-25', priority: 'High', status: 'In Progress' },
        { id: 2, lessonId: 1, title: 'Database Schema Design', intern: 'Ayşe Kaya', deadline: '2024-04-26', priority: 'Medium', status: 'Pending Review' },
        { id: 3, lessonId: 2, title: 'Landing Page Icons', intern: 'Mehmet Demir', deadline: '2024-04-24', priority: 'Low', status: 'Completed' }
    ],

    resources: [
        { name: 'Onboarding Guide.pdf', size: '2.4 MB', date: '12.04.2024' },
        { name: 'Technical Standards.docx', size: '1.1 MB', date: '15.04.2024' }
    ],

    newTask: {
        title: '',
        technicalDetails: '',
        deadline: '',
        priority: 'Medium',
        internId: ''
    },

    newLesson: { title: '', topic: '' },

    newInternship: { title: '', company: '', startDate: '', endDate: '', quota: '', description: '' },

    communityComments: [
        { id: 1, author: 'Ahmet Yılmaz', parentLesson: 'Yazılım Geliştirme', lesson: 'API Tasarımı', comment: 'Gerçekten çok açıklayıcı bir dersti. RESTful mimariyi sonunda tam olarak anladım.', date: '3 saat önce', avatar: 'AY', color: 'blue' },
        { id: 2, author: 'Ayşe Kaya', parentLesson: 'Yazılım Geliştirme', lesson: 'Unit Testing', comment: 'PHPUnit kullanımıyla ilgili örnekler çok faydalıydı. Test yazmanın önemi daha netleşti.', date: '5 saat önce', avatar: 'AK', color: 'indigo' },
        { id: 3, author: 'Mehmet Demir', parentLesson: 'UI/UX Tasarım', lesson: 'Figma Kullanımı', comment: 'Varyantlar ve component yapısı inanılmaz kolaylık sağlıyor. Tasarım süreci hızlandı.', date: 'Dün', avatar: 'MD', color: 'emerald' },
        { id: 4, author: 'Selin Arı', parentLesson: 'Yazılım Geliştirme', lesson: 'API Tasarımı', comment: 'Özellikle Header yönetimi kısmındaki pratik bilgiler için teşekkürler.', date: 'Dün', avatar: 'SA', color: 'rose' },
        ...JSON.parse(localStorage.getItem('taskorbit_community_comments') || '[]')
    ],

    addInternship() {
        if(this.newInternship.title && this.newInternship.company) {
            this.notify('Yeni staj programı oluşturuldu: ' + this.newInternship.title);
            this.currentTab = 'dashboard';
            this.newInternship = { title: '', company: '', startDate: '', endDate: '', quota: '', description: '' };
        }
    },

    addLesson() {
        if(this.newLesson.title && this.newLesson.topic) {
            this.lessons.push({
                id: Date.now(),
                title: this.newLesson.title,
                topic: this.newLesson.topic,
                status: 'Aktif',
                students: 0,
                weeks: [
                    { id: Date.now() + 1, title: 'Hafta 1: Giriş', content: 'Temel kavramlar ve ortama giriş.' },
                    { id: Date.now() + 2, title: 'Hafta 2: Temeller', content: 'Temel yapılar ve söz dizimi.' },
                    { id: Date.now() + 3, title: 'Hafta 3: İleri Seviye Öğeler', content: 'Gelişmiş teknikler ve mimari.' },
                    { id: Date.now() + 4, title: 'Hafta 4: Proje Geliştirme', content: 'Öğrenilenlerle küçük çaplı a proje.' }
                ]
            });
            this.saveLessons();
            this.notify('Yeni ders eklendi: ' + this.newLesson.title);
            this.currentTab = 'dashboard';
            this.newLesson = { title: '', topic: '' };
        }
    },

    deleteLesson() {
        if(this.selectedLesson) {
            this.lessons = this.lessons.filter(l => l.id !== this.selectedLesson.id);
            this.saveLessons();
            this.notify('Ders başarıyla silindi.');
            this.selectedLesson = null;
            this.currentTab = 'dashboard';
        }
    },

    assignTask() {
        if(this.newTask.title && this.newTask.internId) {
            this.notify('Görev başarıyla atandı: ' + this.newTask.title);
            this.currentTab = 'dashboard';
            this.newTask = { title: '', technicalDetails: '', deadline: '', priority: 'Medium', internId: '' };
        }
    }
}" class="shadow-inner">

    <div class="flex h-screen w-full relative overflow-hidden print:hidden">
        <!-- SIDEBAR -->
        <aside :class="sidebarOpen ? 'w-80' : 'w-24'" class="glass-card border-r border-white/5 flex flex-col relative z-30 transition-all duration-300 shrink-0">
            <div class="h-24 flex items-center px-8 justify-between shrink-0 border-b border-white/5">
                <div class="flex items-center gap-4 overflow-hidden">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <span x-show="sidebarOpen" x-transition class="text-xl font-black tracking-tighter uppercase whitespace-nowrap text-white">MENTOR PANEL</span>
                </div>
            </div>

            <nav class="flex-1 px-6 space-y-4 mt-8 overflow-y-auto">
                <div class="space-y-2">
                    <p x-show="sidebarOpen" class="px-4 text-[10px] font-black uppercase tracking-[0.4em] text-slate-500 mb-4">Gezinti</p>

                    <!-- GENEL BAKIŞ -->
                    <button @click="currentTab = 'dashboard'; selectedLesson = null" :class="currentTab === 'dashboard' && !selectedLesson ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white'" class="w-full h-12 flex items-center gap-4 px-5 rounded-xl border border-transparent transition-all group">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span x-show="sidebarOpen" class="font-bold text-sm">Genel Bakış</span>
                    </button>

                    <!-- DERSLER DROPDOWN -->
                    <div class="space-y-1">
                        <button @click="lessonsOpen = !lessonsOpen" :class="selectedLesson ? 'text-emerald-500' : 'text-slate-400 hover:bg-white/5 hover:text-white'" class="w-full h-12 flex items-center justify-between px-5 rounded-xl transition-all group">
                            <div class="flex items-center gap-4">
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                <span x-show="sidebarOpen" class="font-bold text-sm">Dersler</span>
                            </div>
                            <svg x-show="sidebarOpen" :class="lessonsOpen ? 'rotate-180' : ''" class="h-4 w-4 transition-transform font-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                        </button>

                        <div x-show="lessonsOpen && sidebarOpen" x-transition class="pl-12 space-y-1 border-l-2 border-white/5 ml-7">
                            <template x-for="lesson in lessons" :key="lesson.id">
                                <div class="space-y-1">
                                    <button @click="selectLesson(lesson)" :class="selectedLesson?.id === lesson.id ? 'text-emerald-500 bg-emerald-500/5' : 'text-slate-500 hover:text-white'" class="w-full text-left py-2 px-3 rounded-lg text-xs font-black transition-all" x-text="lesson.title"></button>

                                    <!-- Lesson specific sub-menu -->
                                    <div x-show="selectedLesson?.id === lesson.id" x-transition class="pl-4 py-2 space-y-2 border-l border-white/10 ml-2">
                                        <button @click="currentTab = 'interns'" :class="currentTab === 'interns' ? 'text-emerald-400' : 'text-slate-600 hover:text-slate-400'" class="block text-[10px] font-black uppercase tracking-wider transition-colors w-full text-left">• Stajyerler</button>
                                        <button @click="currentTab = 'lesson-detail'" :class="currentTab === 'lesson-detail' ? 'text-emerald-400' : 'text-slate-600 hover:text-slate-400'" class="block text-[10px] font-black uppercase tracking-wider transition-colors w-full text-left">• Müfredat</button>
                                        <button @click="currentTab = 'assign'" :class="currentTab === 'assign' ? 'text-emerald-400' : 'text-slate-600 hover:text-slate-400'" class="block text-[10px] font-black uppercase tracking-wider transition-colors w-full text-left">• Görev Ata</button>
                                        <button @click="currentTab = 'community'" :class="currentTab === 'community' ? 'text-emerald-400' : 'text-slate-600 hover:text-slate-400'" class="block text-[10px] font-black uppercase tracking-wider transition-colors w-full text-left">• Topluluk Yorumları</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- STAJ OLUŞTUR -->
                    <button @click="currentTab = 'create-internship'; selectedLesson = null" :class="currentTab === 'create-internship' ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white'" class="w-full h-12 flex items-center gap-4 px-5 rounded-xl border border-transparent transition-all group">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        <span x-show="sidebarOpen" class="font-bold text-sm">Staj Oluştur</span>
                    </button>

                    <!-- DERS EKLE -->
                    <button @click="currentTab = 'add-lesson'; selectedLesson = null" :class="currentTab === 'add-lesson' ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white'" class="w-full h-12 flex items-center gap-4 px-5 rounded-xl border border-transparent transition-all group">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span x-show="sidebarOpen" class="font-bold text-sm">Ders Ekle</span>
                    </button>

                    <!-- STAJYER LİSTESİ (GLOBAL) -->
                    <button @click="currentTab = 'interns'; selectedLesson = null" :class="currentTab === 'interns' && !selectedLesson ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white'" class="w-full h-12 flex items-center gap-4 px-5 rounded-xl border border-transparent transition-all group">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <span x-show="sidebarOpen" class="font-bold text-sm">Stajyer Listesi</span>
                    </button>

                    <!-- PERFORMANS -->
                    <button @click="currentTab = 'evaluation'; selectedLesson = null" :class="currentTab === 'evaluation' && !selectedLesson ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white'" class="w-full h-12 flex items-center gap-4 px-5 rounded-xl border border-transparent transition-all group">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                        <span x-show="sidebarOpen" class="font-bold text-sm">Performans</span>
                    </button>

                    <!-- DERS KATILIM (Sadeca Seçiliyse) -->
                    <template x-if="selectedLesson">
                        <div class="pt-4 border-t border-white/5 space-y-1">
                            <button @click="currentTab = 'monitoring'" :class="currentTab === 'monitoring' ? 'bg-sky-500/10 text-sky-400' : 'text-sky-400 hover:bg-sky-500/5'" class="w-full h-12 flex items-center gap-4 px-5 rounded-xl transition-all group">
                                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.11.02-1.93 1.23-5.46 3.62-.51.35-.98.52-1.4.51-.46-.01-1.35-.26-2.01-.48-.81-.27-1.44-.42-1.39-.89.03-.24.35-.49.96-.75 3.76-1.63 6.27-2.71 7.52-3.23 3.58-1.48 4.32-1.74 4.81-1.75.11 0 .35.03.5.16.13.1.17.24.18.34z"/></svg>
                                <span x-show="sidebarOpen" class="font-bold text-sm leading-tight">Canlı Ders İzle (Telegram)</span>
                            </button>
                        </div>
                    </template>
                </div>
            </nav>

            <div class="p-6 border-t border-white/5">
                <div class="p-4 rounded-3xl bg-emerald-600/5 border border-emerald-600/10 flex items-center gap-4">
                    <div class="h-10 w-10 rounded-xl bg-emerald-600 flex items-center justify-center font-black text-xs text-white">M</div>
                    <div x-show="sidebarOpen" class="overflow-hidden">
                        <p class="text-sm font-black text-white truncate">Mentor İsmi</p>
                        <p class="text-[10px] text-emerald-500/60 font-black uppercase tracking-widest whitespace-nowrap">Global Soft Team</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto relative custom-scrollbar">
            <!-- Header -->
            <header class="h-24 px-12 border-b border-white/5 bg-slate-950/20 backdrop-blur-xl flex items-center justify-between shrink-0 z-20">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="h-12 w-12 flex items-center justify-center rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all text-slate-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7" /></svg>
                    </button>
                    <h2 class="text-xl font-black text-white tracking-tight uppercase px-4 border-l border-white/10" x-text="
                        currentTab === 'dashboard' ? 'Genel Bakış' :
                        currentTab === 'assign' ? 'Görev Yönetimi' :
                        currentTab === 'add-lesson' ? 'Ders Ekle' :
                        currentTab === 'create-internship' ? 'Staj Programı Oluştur' :
                        currentTab === 'community' ? 'Topluluk Yorumları' :
                        currentTab === 'interns' ? 'Stajyer Merkezi' :
                        currentTab === 'lesson-detail' ? 'Ders Detayı' :
                        currentTab === 'evaluation' ? 'Performans Değerlendirme' :
                        currentTab === 'monitoring' ? 'Canlı Takip' :
                        'Eğitim Materyalleri'"></h2>
                </div>

                <div class="flex items-center gap-6">
                    <div class="hidden lg:flex items-center gap-3 px-6 py-3 rounded-2xl bg-white/5 border border-white/5">
                        <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Sistem Aktif</span>
                    </div>
                    <button class="h-12 w-12 rounded-2xl bg-white/5 border border-white/5 flex items-center justify-center text-slate-400 hover:text-white transition-all relative">
                         <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                         <span class="absolute top-3 right-3 h-2 w-2 bg-emerald-500 rounded-full"></span>
                    </button>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto min-h-0 p-12 space-y-12 bg-navy-deep scroll-smooth">
                <!-- 1. DASHBOARD VIEW -->
                <div x-show="currentTab === 'dashboard'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-12">
                    <div class="space-y-4">
                        <h1 class="text-6xl font-black text-white tracking-tighter">İyi Mesailer, Mentor.</h1>
                        <p class="text-xl text-slate-500 font-medium max-w-2xl">Ekibinin gelişimini ve atanmış görevlerin durumunu buradan anlık olarak izleyebilirsin.</p>
                    </div>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="glass-card p-8 rounded-[2.5rem] flex flex-col justify-between h-48 border-white/5 group hover:border-emerald-500/30 transition-all">
                             <div class="flex justify-between items-start">
                                 <div class="h-14 w-14 rounded-2xl bg-emerald-600/10 text-emerald-500 flex items-center justify-center"><svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg></div>
                                 <span class="text-[10px] font-black text-emerald-500/50 uppercase tracking-widest">+2 Bu Hafta</span>
                             </div>
                             <div>
                                 <p class="text-4xl font-black text-white">12</p>
                                 <p class="text-xs font-black text-slate-500 uppercase tracking-widest mt-1">Aktif Stajyer</p>
                             </div>
                        </div>
                        <div class="glass-card p-8 rounded-[2.5rem] flex flex-col justify-between h-48 border-white/5 group hover:border-blue-500/30 transition-all">
                             <div class="flex justify-between items-start">
                                 <div class="h-14 w-14 rounded-2xl bg-blue-600/10 text-blue-500 flex items-center justify-center"><svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                 <span class="text-[10px] font-black text-blue-500/50 uppercase tracking-widest">80% Başarı</span>
                             </div>
                             <div>
                                 <p class="text-4xl font-black text-white">45</p>
                                 <p class="text-xs font-black text-slate-500 uppercase tracking-widest mt-1">Tamamlanan Görev</p>
                             </div>
                        </div>
                        <div class="glass-card p-8 rounded-[2.5rem] flex flex-col justify-between h-48 border-white/5 group hover:border-amber-500/30 transition-all">
                             <div class="flex justify-between items-start">
                                 <div class="h-14 w-14 rounded-2xl bg-amber-600/10 text-amber-500 flex items-center justify-center"><svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                 <span class="text-[10px] font-black text-amber-500/50 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">Acil Bekliyor</span>
                             </div>
                             <div>
                                 <p class="text-4xl font-black text-white">7</p>
                                 <p class="text-xs font-black text-slate-500 uppercase tracking-widest mt-1">Geri Bildirim Bekleyen</p>
                             </div>
                        </div>
                        <div class="glass-card p-8 rounded-[2.5rem] flex flex-col justify-between h-48 border-white/5 group hover:border-purple-500/30 transition-all">
                             <div class="flex justify-between items-start">
                                 <div class="h-14 w-14 rounded-2xl bg-purple-600/10 text-purple-500 flex items-center justify-center"><svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg></div>
                             </div>
                             <div>
                                 <p class="text-4xl font-black text-white">128</p>
                                 <p class="text-xs font-black text-slate-500 uppercase tracking-widest mt-1">Paylaşılan Kaynak</p>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- COMMUNITY COMMENTS VIEW -->
                <div x-show="currentTab === 'community'" x-transition class="space-y-12 pb-32">
                    <div class="glass-card rounded-[3.5rem] p-12 shadow-2xl relative overflow-hidden group">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl transition-all group-hover:bg-emerald-600/10"></div>
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                            <div>
                                <h3 class="text-3xl font-black text-white tracking-tighter uppercase" x-text="selectedLesson ? selectedLesson.title + ' Yorumları' : 'Topluluk Geri Bildirimleri'"></h3>
                                <p class="text-slate-500 font-medium mt-2" x-text="selectedLesson ? 'Bu derse özel stajyer yorumları.' : 'Dersleri tamamlayan stajyerlerin paylaştığı deneyim ve yorumlar.'"></p>
                            </div>
                            <div class="flex items-center gap-3 bg-slate-900/40 p-2 rounded-2xl border border-white/5">
                                <button class="px-6 py-2 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-emerald-600/20 transition-all">Tümü</button>
                                <button class="px-6 py-2 text-slate-500 hover:text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">Popüler</button>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <template x-for="comment in communityComments.filter(c => !selectedLesson || c.parentLesson === selectedLesson.title)" :key="comment.id">
                                <div class="p-8 rounded-[3rem] bg-slate-950/40 border border-slate-800/40 hover:border-emerald-500/30 hover:bg-slate-950/60 transition-all duration-300 group">
                                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
                                        <div class="flex items-start gap-6">
                                            <div :class="`bg-${comment.color}-600/20 text-${comment.color}-400`" class="h-16 w-16 rounded-2xl flex items-center justify-center font-black text-2xl shrink-0 shadow-inner" x-text="comment.avatar"></div>
                                            <div class="space-y-3">
                                                <div class="flex items-center gap-4">
                                                    <h5 class="text-xl font-black text-white" x-text="comment.author"></h5>
                                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-700"></span>
                                                    <span class="text-sm font-bold text-slate-500" x-text="comment.date"></span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20" x-text="comment.lesson"></span>
                                                </div>
                                                <p class="text-slate-400 text-lg leading-relaxed pt-2" x-text="comment.comment"></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4 self-end sm:self-auto">
                                            <button class="flex items-center gap-3 px-5 py-3 rounded-2xl bg-slate-900 border border-white/5 text-slate-500 hover:text-emerald-500 hover:border-emerald-500/30 transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.708c.94 0 1.667.83 1.45 1.744l-1.137 4.893a2 2 0 01-1.95 2.503L15.35 19H9m4-9l-1-4M9 10v9" /></svg>
                                                <span class="text-sm font-black tracking-widest">12</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- 2. EVALUATION VIEW -->
                <div x-show="currentTab === 'evaluation'" x-transition class="space-y-12">
                     <div class="glass-card rounded-[3.5rem] p-12 shadow-2xl relative overflow-hidden group">
                          <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl transition-all group-hover:bg-emerald-600/10"></div>
                          <div class="flex items-center justify-between mb-12">
                              <div class="flex items-center gap-6">
                                  <div class="h-16 w-16 rounded-[1.5rem] bg-emerald-600/10 text-emerald-500 flex items-center justify-center shadow-inner"><svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                  <div>
                                      <h3 class="text-3xl font-black text-white uppercase tracking-tighter">Değerlendirme Kuyruğu</h3>
                                      <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] mt-1">Son Gelen Teslimatlar</p>
                                  </div>
                              </div>
                          </div>

                          <div class="space-y-6">
                              <template x-for="task in tasks.filter(t => t.status === 'Pending Review')">
                                  <div class="flex items-center justify-between p-8 rounded-[3rem] bg-slate-950/40 border border-white/5 hover:border-emerald-500/40 transition-all cursor-pointer group/item shadow-xl transform hover:-translate-y-1">
                                      <div class="flex items-center gap-8">
                                          <div class="h-14 w-14 rounded-full bg-slate-800 border border-white/5 flex items-center justify-center font-black text-emerald-500" x-text="task.intern.split(' ').map(n => n[0]).join('')"></div>
                                          <div>
                                               <p class="text-xl font-black text-white" x-text="task.title"></p>
                                               <p class="text-xs font-black text-slate-600 uppercase tracking-widest mt-1" x-text="`Gönderen: ${task.intern}`"></p>
                                          </div>
                                      </div>
                                      <div class="flex items-center gap-12">
                                           <div class="text-right hidden sm:block">
                                                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Teslim Tarihi</p>
                                                <p class="text-sm font-bold text-white mt-1" x-text="task.deadline"></p>
                                           </div>
                                           <button @click="selectedIntern = task.intern; showFeedbackModal = true" class="px-8 py-4 bg-emerald-600 text-white rounded-2xl font-black text-sm shadow-xl shadow-emerald-600/20 hover:scale-105 active:scale-95 transition-all">İNCELE & PUANLA</button>
                                      </div>
                                  </div>
                              </template>
                          </div>
                     </div>
                </div>

                <!-- 3. INTERNS LIST VIEW (Detailed Table) -->
                <div x-show="currentTab === 'interns'" x-transition class="space-y-12">
                     <div class="flex items-center justify-between">
                         <div class="space-y-1">
                             <h2 class="text-4xl font-black text-white uppercase tracking-tighter" x-text="selectedLesson ? selectedLesson.title + ' Stajyerleri' : 'Stajyer Merkezi'"></h2>
                             <p class="text-lg font-medium text-slate-500">Ekibindeki öğrencilerin detaylı profilleri ve iletişim bilgileri.</p>
                         </div>
                         <div class="flex items-center gap-6">
                            <div class="relative group">
                                <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" x-model="searchQuery" placeholder="Stajyer ara..." class="h-14 w-80 bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 text-xs font-bold focus:outline-none focus:border-emerald-500 transition-all">
                            </div>
                         </div>
                     </div>

                     <div class="glass-card rounded-[3rem] overflow-hidden border-white/5 shadow-3xl">
                         <table class="w-full text-left border-collapse">
                             <thead>
                                 <tr class="bg-white/[0.02] border-b border-white/10 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">
                                     <th class="p-8">Öğrenci</th>
                                     <th class="p-8">Rol & Departman</th>
                                     <th class="p-8">Eğitim İlerlemesi</th>
                                     <th class="p-8 text-right">İşlem</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 <template x-for="intern in filteredInterns.filter(i => i.name.toLowerCase().includes(searchQuery.toLowerCase()))">
                                     <tr class="border-b border-white/5 hover:bg-white/[0.04] transition-colors group">
                                         <td class="p-8">
                                             <div class="flex items-center gap-5">
                                                 <div :class="`bg-${intern.color}-600/20 text-${intern.color}-500 shadow-inner`" class="h-14 w-14 rounded-2xl flex items-center justify-center font-black text-lg" x-text="intern.avatar"></div>
                                                 <div>
                                                     <p class="text-base font-black text-white leading-none" x-text="intern.name"></p>
                                                     <p class="text-[11px] text-emerald-500/60 mt-1.5 font-bold uppercase tracking-widest font-mono" x-text="intern.email"></p>
                                                 </div>
                                             </div>
                                         </td>
                                         <td class="p-8">
                                             <span class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-[10px] font-black text-slate-300 uppercase tracking-widest" x-text="intern.role"></span>
                                         </td>
                                         <td class="p-8">
                                             <div class="space-y-3">
                                                <div class="flex justify-between items-center text-[10px] font-black text-slate-500 uppercase">
                                                    <span x-text="`%${intern.progress}`"></span>
                                                </div>
                                                <div class="h-2 w-48 bg-slate-900 rounded-full overflow-hidden border border-white/5 shadow-inner">
                                                    <div :class="`bg-${intern.color}-500`" class="h-full rounded-full transition-all duration-1000 shadow-[0_0_15px_rgba(16,185,129,0.2)]" :style="`width: ${intern.progress}%`"></div>
                                                </div>
                                             </div>
                                         </td>
                                         <td class="p-8 text-right">
                                             <div class="flex items-center justify-end gap-3">
                                                 <button @click="openCertificateModal(intern)" class="px-6 py-2.5 rounded-xl bg-emerald-600/10 border border-emerald-600/20 text-[10px] font-black text-emerald-500 hover:bg-emerald-600 hover:text-white transition-all uppercase tracking-widest">Sertifika</button>
                                             </div>
                                         </td>
                                     </tr>
                                 </template>
                             </tbody>
                         </table>
                     </div>
                </div>

                <!-- 7. LESSON DETAIL VIEW (Müfredat) -->
                <div x-show="currentTab === 'lesson-detail'" x-transition class="space-y-12">
                     <div class="flex items-center justify-between">
                         <div>
                            <h2 class="text-4xl font-black text-white uppercase tracking-tighter" x-text="selectedLesson?.title"></h2>
                            <p class="text-lg font-medium text-slate-500" x-text="selectedLesson?.topic + ' Müfredat Akışı ve Hafta Detayları'"></p>
                         </div>
                         <div class="flex items-center gap-4">
                             <button @click="deleteLesson()" class="h-14 px-8 bg-rose-600/10 text-rose-500 border border-rose-500/20 rounded-2xl font-black text-sm hover:bg-rose-600 hover:text-white transition-all uppercase tracking-widest">DERSİ SİL</button>
                             <button @click="openAddWeekModal()" class="h-14 px-8 bg-blue-600 text-white rounded-2xl font-black text-sm shadow-xl hover:scale-105 transition-all uppercase tracking-widest">YENİ HAFTA EKLE</button>
                         </div>
                     </div>

                     <div class="space-y-6">
                        <template x-for="(week, index) in selectedLesson?.weeks" :key="week.id">
                            <div class="glass-card p-8 rounded-[2.5rem] border-white/5 group hover:border-blue-500/30 transition-all">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-xl bg-blue-600/10 text-blue-500 flex items-center justify-center font-black" x-text="index + 1"></div>
                                        <h3 class="text-2xl font-black text-white" x-text="week.title"></h3>
                                    </div>
                                    <button @click="openEditWeekModal(week)" class="px-6 py-2 rounded-xl bg-white/5 border border-white/10 text-[10px] font-black text-slate-400 hover:text-white hover:border-blue-500 transition-all uppercase tracking-widest">DÜZENLE</button>
                                </div>
                                <p class="text-slate-500 font-medium pl-16" x-text="week.content"></p>
                            </div>
                        </template>
                     </div>
                </div>

                <!-- 4. LESSONS VIEW -->
                <div x-show="currentTab === 'lessons'" x-transition class="space-y-12">
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                         <template x-for="lesson in lessons">
                             <div class="glass-card p-10 rounded-[3.5rem] group hover:border-blue-500/30 transition-all cursor-pointer relative overflow-hidden flex flex-col justify-between min-h-[320px]">
                                 <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-600/5 rounded-full blur-2xl"></div>

                                 <div class="space-y-6">
                                     <div class="flex items-center justify-between">
                                         <span class="px-4 py-1.5 rounded-xl bg-blue-600/10 text-blue-500 text-[10px] font-black uppercase tracking-[0.3em]" x-text="`${lesson.weeks.length} HAFTALIK`"></span>
                                         <span :class="lesson.status === 'Aktif' ? 'text-emerald-500' : 'text-slate-600'" class="text-[10px] font-black uppercase tracking-widest" x-text="lesson.status"></span>
                                     </div>
                                     <h3 class="text-3xl font-black text-white leading-tight group-hover:text-blue-400 transition-colors" x-text="lesson.title"></h3>
                                     <p class="text-sm font-medium text-slate-500" x-text="`${lesson.topic} Temelleri ve İleri Seviye Uygulamalar.`"></p>
                                 </div>

                                 <div class="pt-8 border-t border-white/5 flex items-center justify-between">
                                     <div class="flex items-center gap-3">
                                         <div class="flex -space-x-2">
                                             <div class="h-8 w-8 rounded-full border-2 border-slate-900 bg-slate-800"></div>
                                             <div class="h-8 w-8 rounded-full border-2 border-slate-900 bg-slate-700"></div>
                                             <div class="h-8 w-8 rounded-full border-2 border-slate-900 bg-slate-600 flex items-center justify-center text-[8px] font-black text-white" x-text="`+${lesson.students}`"></div>
                                         </div>
                                         <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Katılımcı</span>
                                     </div>
                                     <div class="flex gap-2">
                                         <button @click="selectLesson(lesson); currentTab = 'monitoring'" class="h-12 px-6 flex items-center rounded-2xl bg-emerald-600 text-xs font-black text-white hover:bg-emerald-500 transition-all tracking-widest uppercase">TAKİP ET</button>
                                         <button class="h-12 px-6 rounded-2xl bg-white/5 border border-white/10 text-xs font-black text-white hover:bg-blue-600 hover:border-blue-500 transition-all tracking-widest uppercase">DÜZENLE</button>
                                     </div>
                                 </div>
                             </div>
                         </template>

                         <!-- Add Lesson Card -->
                         <div @click="currentTab = 'add-lesson'; selectedLesson = null" class="border-4 border-dashed border-white/5 rounded-[3.5rem] flex flex-col items-center justify-center text-center p-12 space-y-6 hover:border-blue-500/20 hover:bg-blue-600/[0.02] transition-all cursor-pointer group">
                             <div class="h-20 w-20 rounded-full border-4 border-dashed border-white/10 flex items-center justify-center text-slate-700 group-hover:text-blue-500 group-hover:border-blue-500/40 transition-all">
                                 <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                             </div>
                             <div>
                                 <h4 class="text-2xl font-black text-white uppercase tracking-tighter">Yeni Ders Ekle</h4>
                                 <p class="text-sm font-medium text-slate-600 mt-2">Müfredata yeni bir konu veya eğitim içeriği dahil edin.</p>
                             </div>
                         </div>
                     </div>
                </div>

                <!-- ADD LESSON VIEW -->
                <div x-show="currentTab === 'add-lesson'" x-transition class="max-w-4xl mx-auto space-y-12 pb-32">
                    <div class="text-center space-y-4 mb-20 relative">
                         <div class="absolute inset-0 bg-blue-500/10 blur-[100px] w-64 h-64 mx-auto rounded-full -z-10"></div>
                         <div class="h-20 w-20 rounded-[2rem] bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center mx-auto shadow-2xl shadow-blue-500/50 hover:scale-110 transition-transform duration-500"><svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg></div>
                         <h1 class="text-5xl font-black text-white uppercase tracking-tighter">Yeni Ders Ekle</h1>
                         <p class="text-lg text-slate-400 font-medium max-w-lg mx-auto">Müfredata yeni bir konu veya eğitim içeriği dahil edin. İlk 4 haftalık temel müfredat otomatik olarak oluşturulacaktır.</p>
                    </div>

                    <div class="glass-card p-12 rounded-[4rem] space-y-10 border-blue-500/20 shadow-3xl relative overflow-hidden">
                         <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent opacity-50"></div>
                         <div class="space-y-4">
                              <label class="text-xs font-black text-blue-400 uppercase tracking-widest ml-4 flex items-center gap-2">
                                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                  Ders Başlığı
                              </label>
                              <input type="text" x-model="newLesson.title" placeholder="Örn: İleri Seviye JavaScript ve Mimari" class="w-full h-16 bg-[#020617]/50 border border-slate-700/50 rounded-2xl px-6 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-black text-white placeholder:text-slate-600 shadow-inner">
                         </div>
                         <div class="space-y-4">
                              <label class="text-xs font-black text-blue-400 uppercase tracking-widest ml-4 flex items-center gap-2">
                                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                  Kategori / Konu
                              </label>
                              <input type="text" x-model="newLesson.topic" placeholder="Örn: Frontend Development" class="w-full h-16 bg-[#020617]/50 border border-slate-700/50 rounded-2xl px-6 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-black text-white placeholder:text-slate-600 shadow-inner">
                         </div>

                         <div class="pt-10 flex flex-col sm:flex-row items-center gap-6 border-t border-white/5">
                              <button @click="newLesson = { title: '', topic: '' }" class="w-full sm:w-auto flex-1 h-16 rounded-2xl border border-rose-500/20 text-rose-500 font-black tracking-widest text-xs hover:bg-rose-500/10 hover:border-rose-500/40 transition-all uppercase">Formu Temizle</button>
                              <button @click="addLesson()" class="w-full sm:w-auto flex-[3] h-16 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl font-black text-lg shadow-[0_15px_30px_rgba(37,99,235,0.3)] hover:scale-[1.02] active:scale-95 transition-all uppercase tracking-tighter flex items-center justify-center gap-3">
                                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                  Müfredatı Oluştur ve Başlat
                              </button>
                         </div>
                    </div>
                </div>

                <!-- CREATE INTERNSHIP VIEW -->
                <div x-show="currentTab === 'create-internship'" x-transition class="max-w-4xl mx-auto space-y-12 pb-32">
                    <div class="text-center space-y-4 mb-20 relative">
                         <div class="absolute inset-0 bg-emerald-500/10 blur-[100px] w-64 h-64 mx-auto rounded-full -z-10"></div>
                         <div class="h-20 w-20 rounded-[2rem] bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center mx-auto shadow-2xl shadow-emerald-500/50 hover:scale-110 transition-transform duration-500"><svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg></div>
                         <h1 class="text-5xl font-black text-white uppercase tracking-tighter">Staj Programı Oluştur</h1>
                         <p class="text-lg text-slate-400 font-medium max-w-lg mx-auto">Yeni bir staj dönemi veya özel staj programı başlatın. Tüm detayları buradan yönetebilirsiniz.</p>
                    </div>

                    <div class="glass-card p-12 rounded-[4rem] space-y-10 border-emerald-500/20 shadow-3xl relative overflow-hidden">
                         <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-emerald-500 to-transparent opacity-50"></div>

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                             <div class="space-y-4">
                                  <label class="text-xs font-black text-emerald-400 uppercase tracking-widest ml-4">Staj Programı Adı</label>
                                  <input type="text" x-model="newInternship.title" placeholder="Örn: 2024 Yazılım Stajı - Güz Dönemi" class="w-full h-16 bg-[#020617]/50 border border-slate-700/50 rounded-2xl px-6 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-black text-white placeholder:text-slate-600 shadow-inner">
                             </div>
                             <div class="space-y-4">
                                  <label class="text-xs font-black text-emerald-400 uppercase tracking-widest ml-4">Şirket / Departman</label>
                                  <input type="text" x-model="newInternship.company" placeholder="Örn: R&D Department" class="w-full h-16 bg-[#020617]/50 border border-slate-700/50 rounded-2xl px-6 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-black text-white placeholder:text-slate-600 shadow-inner">
                             </div>
                             <div class="space-y-4">
                                  <label class="text-xs font-black text-emerald-400 uppercase tracking-widest ml-4">Başlangıç Tarihi</label>
                                  <input type="date" x-model="newInternship.startDate" class="w-full h-16 bg-[#020617]/50 border border-slate-700/50 rounded-2xl px-6 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-black text-white">
                             </div>
                             <div class="space-y-4">
                                  <label class="text-xs font-black text-emerald-400 uppercase tracking-widest ml-4">Bitiş Tarihi</label>
                                  <input type="date" x-model="newInternship.endDate" class="w-full h-16 bg-[#020617]/50 border border-slate-700/50 rounded-2xl px-6 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-black text-white">
                             </div>
                         </div>

                         <div class="space-y-4">
                              <label class="text-xs font-black text-emerald-400 uppercase tracking-widest ml-4">Kontenjan (Öğrenci Sayısı)</label>
                              <input type="number" x-model="newInternship.quota" placeholder="Örn: 10" class="w-full h-16 bg-[#020617]/50 border border-slate-700/50 rounded-2xl px-6 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-black text-white placeholder:text-slate-600 shadow-inner">
                         </div>

                         <div class="space-y-4">
                              <label class="text-xs font-black text-emerald-400 uppercase tracking-widest ml-4">Program Detayları & Açıklama</label>
                              <textarea x-model="newInternship.description" placeholder="Adaylara program hakkında bilgi verin..." class="w-full min-h-[150px] bg-[#020617]/50 border border-slate-700/50 rounded-[2rem] p-8 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-bold text-white placeholder:text-slate-600 leading-relaxed shadow-inner"></textarea>
                         </div>

                         <div class="pt-10 flex flex-col sm:flex-row items-center gap-6 border-t border-white/5">
                              <button @click="newInternship = { title: '', company: '', startDate: '', endDate: '', quota: '', description: '' }" class="w-full sm:w-auto flex-1 h-16 rounded-2xl border border-rose-500/20 text-rose-500 font-black tracking-widest text-xs hover:bg-rose-500/10 hover:border-rose-500/40 transition-all uppercase">İptal Et</button>
                              <button @click="addInternship()" class="w-full sm:w-auto flex-[3] h-16 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl font-black text-lg shadow-[0_15px_30px_rgba(16,185,129,0.3)] hover:scale-[1.02] active:scale-95 transition-all uppercase tracking-tighter flex items-center justify-center gap-3">
                                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                  Staj Programını Yayınla
                              </button>
                         </div>
                    </div>
                </div>

                <!-- 5. ASSIGN TASK VIEW -->
                <div x-show="currentTab === 'assign'" x-transition class="max-w-4xl mx-auto space-y-12 pb-32">
                    <div class="text-center space-y-4 mb-20">
                         <div class="h-20 w-20 rounded-[2rem] bg-emerald-600/10 text-emerald-500 flex items-center justify-center mx-auto shadow-inner"><svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                         <h1 class="text-5xl font-black text-white uppercase tracking-tighter">Yeni Görev Atama</h1>
                         <p class="text-lg text-slate-500 font-medium">Stajyerine hedef belirle, beklentilerini ve süreyi paylaş.</p>
                    </div>

                    <div class="glass-card p-12 rounded-[4rem] space-y-10 border-white/10 shadow-3xl">
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                              <div class="space-y-3">
                                   <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Görev Başlığı</label>
                                   <input type="text" x-model="newTask.title" placeholder="Örn: API Endpoint Refactor" class="w-full h-16 bg-slate-950/50 border border-white/5 rounded-2xl px-6 outline-none focus:border-emerald-500 transition-all font-black text-white placeholder:text-slate-700">
                              </div>
                              <div class="space-y-3">
                                   <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Stajyer Seçimi</label>
                                   <select x-model="newTask.internId" class="w-full h-16 bg-slate-950/50 border border-white/5 rounded-2xl px-6 outline-none focus:border-emerald-500 transition-all font-black text-white appearance-none cursor-pointer">
                                        <option value="" disabled>Stajyer Seçin</option>
                                        <option value="all">Tüm Stajyerler</option>
                                    <template x-for="intern in interns.filter(i => !selectedLesson || i.lessonId === selectedLesson.id)">
                                         <option :value="intern.id" x-text="intern.name"></option>
                                    </template>
                                   </select>
                              </div>
                              <div class="space-y-3">
                                   <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Son Teslim Tarihi</label>
                                   <input type="date" x-model="newTask.deadline" class="w-full h-16 bg-slate-950/50 border border-white/5 rounded-2xl px-6 outline-none focus:border-emerald-500 transition-all font-black text-white flex-row-reverse text-right">
                              </div>
                              <div class="space-y-3">
                                   <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Öncelik Seviyesi</label>
                                   <div class="grid grid-cols-3 gap-3 h-16">
                                        <template x-for="p in ['Low', 'Medium', 'High']">
                                             <button @click="newTask.priority = p" :class="newTask.priority === p ? 'bg-emerald-600 text-white border-emerald-500 shadow-lg shadow-emerald-600/20' : 'bg-slate-900 text-slate-600 border-white/5 hover:bg-slate-800'" class="h-full rounded-2xl border font-black text-[10px] uppercase tracking-widest transition-all" x-text="p"></button>
                                        </template>
                                   </div>
                              </div>
                         </div>
                         <div class="space-y-3">
                              <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Teknik Detaylar & Beklentiler</label>
                              <textarea x-model="newTask.technicalDetails" placeholder="Görevin kapsamını, kullanılacak teknolojileri ve başarı kriterlerini detaylandırın..." class="w-full min-h-[200px] bg-slate-950/50 border border-white/5 rounded-[2rem] p-8 outline-none focus:border-emerald-500 transition-all font-bold text-white placeholder:text-slate-700 leading-relaxed"></textarea>
                         </div>
                         <div class="pt-10 flex items-center gap-6">
                              <button @click="newTask = { title: '', technicalDetails: '', deadline: '', priority: 'Medium', internId: '' }" class="flex-1 h-20 rounded-2xl border border-white/5 text-slate-500 font-black tracking-[0.2em] text-xs hover:bg-rose-500/5 hover:text-rose-500 hover:border-rose-500/20 transition-all uppercase">Temizle</button>
                              <button @click="assignTask()" class="flex-[4] h-20 bg-emerald-600 text-white rounded-2xl font-black text-xl shadow-[0_15px_40px_rgba(16,185,129,0.3)] hover:scale-[1.02] active:scale-95 transition-all uppercase tracking-tighter">Görevi Sisteme Gönder</button>
                         </div>
                    </div>
                </div>

                <!-- 6. RESOURCES VIEW -->
                <div x-show="currentTab === 'resources'" x-transition class="space-y-12">
                     <div class="glass-card rounded-[4rem] p-12 shadow-3xl bg-gradient-to-br from-emerald-600/5 to-transparent border-emerald-500/10">
                          <div class="flex flex-col md:flex-row items-center justify-between gap-10">
                               <div class="space-y-4 text-center md:text-left">
                                    <h3 class="text-4xl font-black text-white uppercase tracking-tighter">Eğitim Kütüphanesi</h3>
                                    <p class="text-xl text-slate-500 font-medium font-medium">Stajyerlerine yol gösterecek dökümanları ve materyalleri buradan yükleyip yönetebilirsin.</p>
                               </div>
                               <button class="px-12 py-6 bg-emerald-600 text-white rounded-[2rem] font-black text-xl shadow-[0_15px_40px_rgba(16,185,129,0.3)] hover:scale-105 transition-all active:scale-95 whitespace-nowrap">YENİ DOSYA YÜKLE</button>
                          </div>
                     </div>

                     <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                          <template x-for="item in resources">
                               <div class="glass-card p-8 rounded-[3rem] group hover:border-white/20 transition-all cursor-pointer flex items-center justify-between shadow-xl">
                                    <div class="flex items-center gap-6">
                                         <div class="h-16 w-16 rounded-2xl bg-white/5 border border-white/5 flex items-center justify-center text-slate-500 group-hover:text-white transition-colors shadow-2xl">
                                              <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                         </div>
                                         <div>
                                              <p class="text-xl font-black text-white" x-text="item.name"></p>
                                              <p class="text-xs font-black text-slate-600 uppercase tracking-widest mt-1" x-text="`${item.date} • ${item.size}`"></p>
                                         </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                         <button class="h-12 w-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-600 hover:text-white transition-all"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                         <button class="h-12 px-6 rounded-2xl bg-white/5 border border-white/10 text-xs font-black text-white hover:bg-emerald-600 hover:border-emerald-500 transition-all tracking-[0.2em]">İNDİR</button>
                                    </div>
                               </div>
                          </template>
                     </div>
                </div>

                <!-- 8. CANLI TAKİP / TELEGRAM GÖRÜNÜMÜ -->
                <div x-show="currentTab === 'monitoring'" x-transition class="space-y-12 pb-32">
                    @include('lesson-monitor')
                </div>
            </div>

            <!-- Footer Global -->
            <footer class="h-16 flex items-center justify-center px-12 border-t border-white/5 shrink-0 bg-slate-950/20 backdrop-blur-xl">
                <p class="text-[10px] font-black text-slate-700 uppercase tracking-[0.5em]">© 2026 TASK ORBIT MENTORING PORTAL • EMPOWERING TALENT</p>
            </footer>
        </main>
    </div>

    <!-- CERTIFICATE MODAL -->
    <div id="certificate-modal" x-show="showCertificateModal" x-cloak class="fixed inset-0 z-[150] flex items-center justify-center p-8 bg-slate-950/95 backdrop-blur-3xl print:p-0 print:bg-white">
         <div class="w-full max-w-4xl flex flex-col gap-8 print:gap-0">
             <!-- Modal Header (Hidden on print) -->
             <div class="flex items-center justify-between text-white print:hidden">
                 <h2 class="text-3xl font-black tracking-tighter uppercase">Staj Sonu Değerlendirme Raporu</h2>
                 <button @click="showCertificateModal = false" class="h-12 w-12 rounded-2xl bg-white/5 flex items-center justify-center hover:bg-rose-500/20 hover:text-rose-500 transition-all">
                     <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                 </button>
             </div>

             <div class="flex flex-col lg:flex-row gap-8 print:block">
                 <!-- Controls (Hidden on print) -->
                 <div class="w-full lg:w-80 space-y-4 print:hidden overflow-y-auto max-h-[70vh] custom-scrollbar pr-2">
                     <div class="glass-card p-6 rounded-[2rem] space-y-4 border-white/5">
                         <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Performans Puanlaması</p>
                         <div class="space-y-3">
                             <template x-for="skill in ['technical', 'softSkills', 'attendance', 'participation', 'tasks', 'discipline']">
                                 <div class="space-y-1.5">
                                     <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider" x-text="{
                                         technical: 'Teknik Bilgi',
                                         softSkills: 'Sosyal Beceri',
                                         attendance: 'Devamlılık',
                                         participation: 'Derse Katılım',
                                         tasks: 'Görev Başarısı',
                                         discipline: 'Disiplin'
                                     }[skill]"></label>
                                     <div class="flex gap-1">
                                         <template x-for="i in 5">
                                             <button @click="certificateData[skill] = i" :class="certificateData[skill] >= i ? 'bg-emerald-600 text-white' : 'bg-slate-800 text-slate-600'" class="h-6 flex-1 rounded-md text-[9px] font-black transition-all" x-text="i"></button>
                                         </template>
                                     </div>
                                 </div>
                             </template>
                         </div>
                         <div class="space-y-1.5 pt-2">
                             <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Final Notu / Yorum</label>
                             <textarea x-model="certificateData.finalNote" class="w-full h-20 bg-slate-900 border border-white/5 rounded-xl p-3 text-[10px] text-white focus:border-emerald-500 outline-none resize-none"></textarea>
                         </div>
                         <button @click="window.print()" class="w-full h-14 bg-emerald-600 text-white rounded-xl font-black text-xs shadow-xl shadow-emerald-600/20 hover:scale-[1.02] active:scale-95 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                             <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                             YAZDIR / PDF İNDİR
                         </button>
                     </div>
                 </div>

                 <!-- Certificate Preview -->
                 <div id="certificate" class="flex-1 bg-white rounded-[3rem] shadow-2xl p-16 text-slate-950 relative overflow-hidden print:shadow-none print:rounded-none print:p-8 print:w-[210mm] print:h-[297mm] mx-auto">
                     <!-- Decorative border -->
                     <div class="absolute inset-4 border-4 border-emerald-600/20 rounded-[2.5rem] pointer-events-none"></div>
                     <div class="absolute inset-8 border border-emerald-600/10 rounded-[2rem] pointer-events-none"></div>

                     <!-- Logo & Title -->
                     <div class="text-center space-y-8 relative z-10">
                         <div class="flex justify-center">
                             <div class="h-20 w-20 rounded-2xl bg-emerald-600 text-white flex items-center justify-center">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                             </div>
                         </div>
                         <div class="space-y-2">
                             <h4 class="text-xs font-black text-emerald-600 uppercase tracking-[0.5em]">TASK ORBIT ACADEMY</h4>
                             <h1 class="text-5xl font-black tracking-tighter uppercase">STAJ BAŞARI BELGESİ</h1>
                         </div>
                     </div>

                     <!-- Content -->
                     <div class="mt-16 text-center space-y-10 relative z-10">
                         <p class="text-xl font-medium text-slate-500 italic pb-4 border-b border-slate-100 max-w-sm mx-auto">Bu belge, üstün başarı ve gayretleri sebebiyle;</p>

                         <div class="space-y-4">
                             <div class="space-y-2">
                                <h2 class="text-7xl font-black tracking-tighter uppercase text-emerald-600" x-text="selectedIntern?.name"></h2>
                                <p class="text-2xl font-bold uppercase tracking-widest text-slate-400" x-text="selectedIntern?.role"></p>
                             </div>
                             <p class="text-sm font-black text-slate-900 uppercase tracking-[0.4em] pt-2">almaya hak kazanmıştır</p>
                         </div>

                         <p class="text-lg leading-relaxed text-slate-600 max-w-2xl mx-auto px-8" x-text="certificateData.finalNote"></p>

                         <!-- Stats Grid -->
                         <div class="grid grid-cols-2 gap-x-12 gap-y-6 py-10 border-y border-slate-100 mx-12">
                             <template x-for="skill in Object.keys(certificateData).filter(k => k !== 'finalNote')">
                                 <div class="flex flex-col items-center">
                                     <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2" x-text="{
                                         technical: 'Teknik Beceri',
                                         softSkills: 'Sosyal Uyum',
                                         attendance: 'Devamlılık',
                                         participation: 'Derse Katılım',
                                         tasks: 'Görev Başarısı',
                                         discipline: 'Disiplin'
                                     }[skill]"></p>
                                     <div class="flex justify-center gap-1">
                                         <template x-for="i in 5">
                                             <div :class="certificateData[skill] >= i ? 'bg-emerald-600' : 'bg-slate-100'" class="h-1.5 w-5 rounded-full transition-colors duration-500"></div>
                                         </template>
                                     </div>
                                 </div>
                             </template>
                         </div>

                         <!-- Footer info -->
                         <div class="flex justify-between items-end pt-12">
                             <div class="text-left space-y-1">
                                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Dönem</p>
                                 <p class="font-bold text-slate-900">2024 Bahar Dönemi</p>
                             </div>
                             <div class="text-center space-y-4">
                                 <div class="h-1 bg-slate-900 w-48 mx-auto"></div>
                                 <div class="space-y-1">
                                     <p class="font-black text-slate-900 uppercase">Mentor Onayı</p>
                                     <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Senior Solution Architect</p>
                                 </div>
                             </div>
                             <div class="text-right space-y-1">
                                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tarih</p>
                                 <p class="font-bold text-slate-900" x-text="new Date().toLocaleDateString('tr-TR')"></p>
                             </div>
                         </div>
                     </div>

                     <!-- Background patterns -->
                     <div class="absolute -bottom-20 -right-20 h-64 w-64 bg-emerald-600/5 rounded-full blur-3xl"></div>
                     <div class="absolute -top-20 -left-20 h-64 w-64 bg-emerald-600/5 rounded-full blur-3xl"></div>
                 </div>
             </div>
         </div>
    </div>

    <!-- FEEDBACK MODAL -->
    <div x-show="showFeedbackModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-8 bg-slate-950/90 backdrop-blur-2xl">
         <div class="glass-card w-full max-w-2xl rounded-[4rem] p-16 space-y-12 shadow-[0_0_100px_rgba(16,185,129,0.15)] transform transition-all border border-emerald-500/20" @click.away="showFeedbackModal = false">
             <div class="text-center space-y-3">
                 <h2 class="text-4xl font-black text-white tracking-tighter uppercase">İş Değerlendirme</h2>
                 <p class="text-slate-500 font-medium" x-text="`${selectedIntern} tarafından gönderilen çalışmayı puanlayın.`"></p>
             </div>

             <div class="space-y-4">
                  <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest ml-4">Başarı Puanı</p>
                  <div class="grid grid-cols-5 gap-4">
                       <template x-for="i in [1,2,3,4,5]">
                            <button class="h-16 rounded-2xl border border-white/5 bg-slate-900 font-black text-xl text-white hover:border-emerald-500 hover:text-emerald-500 transition-all flex items-center justify-center gap-2">
                                <span x-text="i"></span><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            </button>
                       </template>
                  </div>
             </div>

             <div class="space-y-4">
                  <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Mentor Geri Bildirimi</p>
                  <textarea placeholder="Gelişim alanları, eksikler ve takdirlerinizi buraya yazın..." class="w-full min-h-[150px] bg-slate-900 border border-white/5 rounded-[2rem] p-8 outline-none focus:border-emerald-500 transition-all font-bold text-white placeholder:text-slate-700"></textarea>
             </div>

             <div class="flex items-center gap-6 pt-6">
                  <button @click="showFeedbackModal = false" class="flex-1 h-20 rounded-2xl border border-white/5 text-slate-500 font-black tracking-widest text-xs hover:bg-slate-800 transition-all uppercase">Vazgeç</button>
                  <button @click="notify('Geri bildirim başarıyla iletildi.'); showFeedbackModal = false" class="flex-[2] h-20 bg-emerald-600 text-white rounded-2xl font-black text-lg shadow-xl shadow-emerald-600/30 transition-all active:scale-95 uppercase tracking-tighter">Değerlendirmeyi Kaydet</button>
             </div>
         </div>
    </div>

    <!-- WEEK MODAL -->
    <div x-show="showWeekModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-8 bg-slate-950/90 backdrop-blur-2xl">
         <div class="glass-card w-full max-w-2xl rounded-[4rem] p-16 space-y-8 shadow-[0_0_100px_rgba(59,130,246,0.15)] transform transition-all border border-blue-500/20" @click.away="showWeekModal = false">
             <div class="text-center space-y-3">
                 <h2 class="text-4xl font-black text-white tracking-tighter uppercase" x-text="weekModalMode === 'add' ? 'Yeni Hafta Ekle' : 'Haftayı Düzenle'"></h2>
                 <p class="text-slate-500 font-medium" x-text="weekModalMode === 'add' ? 'Müfredata yeni bir hafta ekliyorsunuz.' : 'Hafta bilgilerini güncelliyorsunuz.'"></p>
             </div>

             <div class="space-y-4">
                  <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Hafta Adı ve Konu</p>
                  <input type="text" x-model="currentWeek.title" placeholder="Örn: Hafta 1: Veritabanı Mimarisi" class="w-full h-16 bg-slate-900 border border-white/5 rounded-2xl px-6 outline-none focus:border-blue-500 transition-all font-bold text-white placeholder:text-slate-700">
             </div>

             <div class="space-y-4">
                  <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Açıklama ve İçerik</p>
                  <textarea x-model="currentWeek.content" placeholder="Hafta içeriği ve hedefleri..." class="w-full min-h-[150px] bg-slate-900 border border-white/5 rounded-[2rem] p-8 outline-none focus:border-blue-500 transition-all font-bold text-white placeholder:text-slate-700"></textarea>
             </div>

             <div class="flex items-center gap-6 pt-6">
                  <template x-if="weekModalMode === 'edit'">
                      <button @click="deleteWeek()" class="flex-1 h-20 rounded-2xl border border-rose-500/20 text-rose-500 font-black tracking-widest text-xs hover:bg-rose-500/10 transition-all uppercase">Sil</button>
                  </template>
                  <button @click="showWeekModal = false" class="flex-1 h-20 rounded-2xl border border-white/5 text-slate-500 font-black tracking-widest text-xs hover:bg-slate-800 transition-all uppercase">İptal</button>
                  <button @click="saveWeek()" class="flex-[2] h-20 bg-blue-600 text-white rounded-2xl font-black text-lg shadow-xl shadow-blue-600/30 transition-all active:scale-95 uppercase tracking-tighter">Kaydet</button>
             </div>
         </div>
    </div>

    <!-- NOTIFICATIONS -->
    <div class="fixed bottom-8 right-8 z-[200] space-y-4 print:hidden">
        <template x-for="n in notifications" :key="n.id">
            <div x-transition class="bg-emerald-600 text-white px-8 py-4 rounded-2xl font-black shadow-2xl flex items-center gap-4 border border-white/10 backdrop-blur-xl">
                <div class="h-2 w-2 rounded-full bg-white animate-pulse"></div>
                <span x-text="n.message" class="text-sm"></span>
            </div>
        </template>
    </div>

    <!-- Background Glows -->
    <div class="fixed inset-0 pointer-events-none z-0 print:hidden">
        <div class="absolute top-[-20%] left-[-20%] w-[60%] h-[60%] bg-emerald-600/5 rounded-full blur-[150px]"></div>
        <div class="absolute bottom-[-20%] right-[-20%] w-[60%] h-[60%] bg-blue-600/5 rounded-full blur-[150px]"></div>
    </div>
</body>
</html>
