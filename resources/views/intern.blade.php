<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stajyer Paneli - Task Orbit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-navy-deep { background-color: #020617; }
        .glass { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(51, 65, 85, 0.4); }
        .sidebar-item-active { background: rgba(37, 99, 235, 0.1); color: #3b82f6; border-left: 3px solid #3b82f6; }
        [x-cloak] { display: none !important; }
    </style>
</head>
    <body class="bg-[#020617] text-slate-50 flex h-screen overflow-hidden"
          x-data="{
            sidebarOpen: true,
            activeTab: 'tasks',
            lessonsOpen: false,
            uploadStatus: 'idle',
            progress: 65,
            commentModalOpen: false,
            selectedCurriculumItem: null,
            newComment: '',
            comments: {},
            evaluationView: 'lessons',
            selectedLesson: null,
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
                        { id: 3, title: 'Hafta 3: Unit Testing', content: 'Jest ve PHPUnit ile temel test yazımı.' },
                        { id: 4, title: 'Hafta 4: Code Review', content: 'Kod kalitesi, review süreçleri ve best practices.' }
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
                        { id: 3, title: 'Hafta 3: Kullanıcı Akışları', content: 'Analiz ve prototipleme süreçleri.' },
                        { id: 4, title: 'Hafta 4: Prototipleme', content: 'Yüksek kaliteli prototipler ve kullanıcı testleri.' }
                    ]
                }
            ],
            communityComments: [
                { id: 1, author: 'Ahmet Yılmaz', parentLesson: 'Yazılım Geliştirme', lesson: 'API Tasarımı', comment: 'Gerçekten çok açıklayıcı bir dersti. RESTful mimariyi sonunda tam olarak anladım.', date: '3 saat önce', avatar: 'AY', color: 'blue' },
                { id: 2, author: 'Ayşe Kaya', parentLesson: 'Yazılım Geliştirme', lesson: 'Unit Testing', comment: 'PHPUnit kullanımıyla ilgili örnekler çok faydalıydı. Test yazmanın önemi daha netleşti.', date: '5 saat önce', avatar: 'AK', color: 'indigo' },
                { id: 3, author: 'Mehmet Demir', parentLesson: 'UI/UX Tasarım', lesson: 'Figma Kullanımı', comment: 'Varyantlar ve component yapısı inanılmaz kolaylık sağlıyor. Tasarım süreci hızlandı.', date: 'Dün', avatar: 'MD', color: 'emerald' },
                { id: 4, author: 'Selin Arı', parentLesson: 'Yazılım Geliştirme', lesson: 'API Tasarımı', comment: 'Özellikle Header yönetimi kısmındaki pratik bilgiler için teşekkürler.', date: 'Dün', avatar: 'SA', color: 'rose' },
                ...JSON.parse(localStorage.getItem('taskorbit_community_comments') || '[]')
            ]
          }">

    <!-- Arka Plan Parlamaları -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-600/5 rounded-full blur-[150px]"></div>
    </div>

    <!-- SIDEBAR -->
    <aside :class="sidebarOpen ? 'w-72' : 'w-20'" class="glass border-r border-slate-800/40 transition-all duration-300 flex flex-col relative z-20 shrink-0">
        <div class="h-20 flex items-center px-6 gap-3 shrink-0">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </div>
            <span x-show="sidebarOpen" class="text-xl font-bold tracking-tight whitespace-nowrap">TASK ORBIT</span>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-8 overflow-y-auto">
            <p x-show="sidebarOpen" class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-500 mb-4">Stajyer Menüsü</p>

            <button @click="activeTab = 'tasks'" :class="activeTab === 'tasks' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold">Görevlerim</span>
                <div x-show="!sidebarOpen" class="absolute left-full ml-4 px-3 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">Görevlerim</div>
            </button>

            <button @click="activeTab = 'curriculum'; selectedLesson = null" :class="activeTab === 'curriculum' && !selectedLesson ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold">Şirket Müfredatı</span>
                <div x-show="!sidebarOpen" class="absolute left-full ml-4 px-3 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">Şirket Müfredatı</div>
            </button>

            <button @click="activeTab = 'evaluations'; selectedLesson = null" :class="activeTab === 'evaluations' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold">Ders Değerlendirme</span>
                <div x-show="!sidebarOpen" class="absolute left-full ml-4 px-3 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">Ders Değerlendirme</div>
            </button>

            <!-- DERSLER DROPDOWN (NEW) -->
            <div class="space-y-1">
                <button @click="lessonsOpen = !lessonsOpen" :class="selectedLesson ? 'text-blue-400' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white'" class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all group relative">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        <span x-show="sidebarOpen" class="text-sm font-bold">Dersler</span>
                    </div>
                    <svg x-show="sidebarOpen" :class="lessonsOpen ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                </button>

                <div x-show="lessonsOpen && sidebarOpen" x-transition class="pl-10 space-y-1">
                    <template x-for="lesson in lessons" :key="lesson.title">
                        <div class="space-y-1">
                            <button @click="selectedLesson = lesson; activeTab = 'curriculum'" :class="selectedLesson?.title === lesson.title ? 'text-blue-400 bg-blue-400/5' : 'text-slate-500 hover:text-white'" class="w-full text-left py-2 px-3 rounded-lg text-xs font-bold transition-all" x-text="lesson.title"></button>

                            <div x-show="selectedLesson?.title === lesson.title" x-transition class="pl-4 py-1 space-y-1 border-l border-slate-800 ml-2">
                                <button @click="activeTab = 'curriculum'" :class="activeTab === 'curriculum' ? 'text-blue-400' : 'text-slate-600'" class="block text-[10px] font-bold uppercase tracking-wider w-full text-left">• Müfredat</button>
                                <button @click="activeTab = 'community'" :class="activeTab === 'community' ? 'text-blue-400' : 'text-slate-600'" class="block text-[10px] font-bold uppercase tracking-wider w-full text-left">• Topluluk Yorumları</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <button @click="activeTab = 'repository'" :class="activeTab === 'repository' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group relative">
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold">Repository</span>
                <div x-show="!sidebarOpen" class="absolute left-full ml-4 px-3 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">Repository</div>
            </button>

            <button @click="activeTab = 'mentor'" :class="activeTab === 'mentor' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold">Mentor Yorumları</span>
                <div x-show="!sidebarOpen" class="absolute left-full ml-4 px-3 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">Mentor Yorumları</div>
            </button>

            <button @click="activeTab = 'docs'" :class="activeTab === 'docs' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold">Dokümantasyon</span>
                <div x-show="!sidebarOpen" class="absolute left-full ml-4 px-3 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">Dokümantasyon</div>
            </button>
        </nav>

        <div class="p-6 border-t border-slate-800/40 shrink-0">
            <a href="/logout" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-red-400 hover:bg-red-500/10 transition-all" :class="!sidebarOpen ? 'justify-center' : ''">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                <span x-show="sidebarOpen">Log Out</span>
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col min-w-0 overflow-hidden relative z-10">
        <header class="h-20 border-b border-slate-800/40 bg-slate-950/20 backdrop-blur-md flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl hover:bg-slate-800/40 text-slate-400 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="text-sm font-medium text-slate-500 uppercase tracking-widest">Stajyer Paneli</div>
            </div>
            <div class="flex items-center gap-6">
                <button class="relative h-10 w-10 flex items-center justify-center rounded-xl hover:bg-slate-800/40 transition-all text-slate-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    <span class="absolute top-2 right-2 h-2 w-2 bg-blue-600 rounded-full border-2 border-[#020617]"></span>
                </button>
                <div class="h-8 w-[1px] bg-slate-800/40"></div>
                <div class="relative group cursor-pointer flex items-center gap-3 pl-4 pr-2 py-1.5 rounded-2xl hover:bg-slate-800/30 transition-all border border-transparent hover:border-slate-800/40 max-w-[240px]">
                    <div class="text-right hidden sm:block min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-100 group-hover:text-blue-400 transition-colors truncate">Zerda</p>
                        <p class="text-[10px] font-bold text-blue-500/80 uppercase tracking-widest truncate">Stajyer @ TechNova</p>
                    </div>
                    <div class="relative flex-shrink-0">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-600 flex items-center justify-center font-bold text-sm shadow-lg shadow-blue-500/30 border border-white/10 text-white">Z</div>
                        <div class="absolute -bottom-1 -right-1 h-3.5 w-3.5 bg-emerald-500 rounded-full border-2 border-[#020617] shadow-sm"></div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 space-y-8">
            <div>
                <h1 class="text-4xl font-extrabold tracking-tight">Hoşgeldiniz, CCC</h1>
                <p class="text-slate-500 font-medium mt-2">Yörüngedeki görevlerinizi buradan takip edebilirsiniz.</p>
            </div>

            <!-- TAB: GÖREVLERİM -->
            <div x-show="activeTab === 'tasks'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <!-- Top Row: Side-by-Side Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Card 1: Tamamlanan Görevler -->
                    <div class="glass p-10 rounded-[2.5rem] flex flex-col items-center text-center relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-32 h-32 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>

                        <div class="relative w-40 h-40 mb-8">
                            <svg class="w-40 h-40">
                                <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="transparent" class="text-slate-800" />
                                <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="transparent"
                                        :stroke-dasharray="2 * Math.PI * 70"
                                        :stroke-dashoffset="(2 * Math.PI * 70) - (progress / 100) * (2 * Math.PI * 70)"
                                        class="text-emerald-500 progress-ring__circle" />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-4xl font-black text-emerald-500" x-text="'%' + progress"></span>
                            </div>
                        </div>

                        <h3 class="text-2xl font-bold mb-2">Tamamlanan Görevler</h3>
                        <p class="text-slate-500">Toplam 20 görevin 13'ü başarıyla tamamlandı.</p>
                        <div class="mt-8">
                            <span class="px-4 py-1.5 rounded-full bg-emerald-500/10 text-emerald-500 text-xs font-bold uppercase tracking-widest">Harika Gidiyorsun</span>
                        </div>
                    </div>

                    <!-- Card 2: Teslim Tarihi Yaklaşanlar (Sadece Bugün) -->
                    <div class="glass p-10 rounded-[2.5rem] flex flex-col group hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-4 mb-10">
                            <div class="h-14 w-14 rounded-2xl bg-red-500/10 text-red-500 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="text-left">
                                <h3 class="text-2xl font-bold">Bugün Teslim</h3>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Son Gün Olan Görevler</p>
                            </div>
                        </div>

                        <div class="space-y-5 flex-1">
                            <div class="flex items-center justify-between p-5 rounded-2xl bg-red-500/5 border border-red-500/20 group-hover:border-red-500/40 transition-colors">
                                <div class="text-left">
                                    <p class="text-base font-bold text-white">API Entegrasyonu</p>
                                    <p class="text-xs font-bold uppercase tracking-widest text-red-500">Bugün 23:59</p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Diğer Görevler Listesi -->
                <div class="glass p-8 rounded-[2.5rem] space-y-6">
                    <div class="flex items-center justify-between px-2">
                        <h3 class="text-xl font-bold flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            Gelecek Görevler
                        </h3>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">2 Görev Bekliyor</span>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex items-center justify-between p-5 rounded-2xl bg-slate-900/40 border border-slate-800/60 hover:border-blue-500/30 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400 group-hover:text-blue-400 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </div>
                                <div>
                                    <p class="font-bold">Unit Test Yazımı</p>
                                    <p class="text-xs text-slate-500">Yarın Teslim Edilecek</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 rounded-lg bg-slate-800 text-slate-400 text-[10px] font-bold uppercase tracking-wider">Hazırlanıyor</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-5 rounded-2xl bg-slate-900/40 border border-slate-800/60 hover:border-blue-500/30 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400 group-hover:text-blue-400 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>
                                </div>
                                <div>
                                    <p class="font-bold">UI Refactor</p>
                                    <p class="text-xs text-slate-500">18 Nisan Teslim Edilecek</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 rounded-lg bg-slate-800 text-slate-400 text-[10px] font-bold uppercase tracking-wider">Beklemede</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Row: Görev Yükle (Geniş Kart) -->
                <div class="glass p-12 rounded-[2.5rem] flex flex-col items-center justify-center text-center group hover:-translate-y-1 transition-all duration-300">
                    <input type="file" id="fileInput" class="hidden" @change="uploadStatus = 'uploading'; setTimeout(() => uploadStatus = 'success', 2000)">

                    <template x-if="uploadStatus === 'idle'">
                        <div class="flex flex-col items-center">
                            <div class="h-24 w-24 rounded-3xl bg-blue-600/10 text-blue-500 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform shadow-lg shadow-blue-600/5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                            </div>
                            <h3 class="text-3xl font-bold mb-3">Görev Yükle</h3>
                            <p class="text-slate-400 max-w-md mx-auto mb-10 text-lg">Yaptığın çalışmayı mentora göndermek için dosyayı buraya bırak veya seç.</p>
                            <button @click="document.getElementById('fileInput').click()"
                                    class="px-16 py-4 bg-blue-600 text-white rounded-2xl font-bold text-xl shadow-xl shadow-blue-600/20 hover:bg-blue-500 transition-all active:scale-95">
                                Dosya Seç
                            </button>
                        </div>
                    </template>

                    <template x-if="uploadStatus === 'uploading'">
                        <div class="space-y-8 py-10">
                            <div class="w-24 h-24 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto"></div>
                            <p class="text-3xl font-bold text-blue-500">Gönderiliyor...</p>
                        </div>
                    </template>

                    <template x-if="uploadStatus === 'success'">
                        <div class="space-y-8 py-10">
                            <div class="h-28 w-28 rounded-3xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                            </div>
                            <h3 class="text-4xl font-bold text-emerald-500">Başarılı!</h3>
                            <p class="text-xl text-slate-400">Görevin mentora iletildi.</p>
                            <button @click="uploadStatus = 'idle'" class="text-base font-bold text-slate-500 hover:text-white underline mt-8">Yeni Görev Gönder</button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- TAB: MENTOR YORUMLARI -->
            <div x-show="activeTab === 'mentor'" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="glass p-8 rounded-[2rem]">
                    <h3 class="font-bold mb-8 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>
                        Mentor Geri Bildirimleri
                    </h3>
                    <div class="space-y-6">
                        <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-800/60 hover:border-purple-500/30 transition-all">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-purple-600 flex items-center justify-center text-xs font-bold">AK</div>
                                    <div>
                                        <p class="text-sm font-bold">Ahmet Kaya</p>
                                        <p class="text-[10px] text-slate-500">Kıdemli Developer • 2 Saat Önce</p>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-slate-300 leading-relaxed">"Son yaptığın UI revizyonunda kod temizliği çok iyiydi. Özellikle Tailwind sınıflarını gruplandırman okunabilirliği artırmış. Responsive tarafta mobil menü geçişlerine biraz daha dikkat edebilirsin, orada ufak bir titreme var."</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB: DOKÜMANTASYON -->
            <div x-show="activeTab === 'docs'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="glass p-6 rounded-3xl hover:border-blue-500/30 transition-all cursor-pointer group">
                        <div class="h-12 w-12 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <h4 class="font-bold mb-1 text-white">Başlangıç Rehberi</h4>
                        <p class="text-xs text-slate-500">Şirket kültürü ve ilk adımlar.</p>
                    </div>
                    <div class="glass p-6 rounded-3xl hover:border-emerald-500/30 transition-all cursor-pointer group">
                        <div class="h-12 w-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        </div>
                        <h4 class="font-bold mb-1 text-white">Kod Standartları</h4>
                        <p class="text-xs text-slate-500">Yazım kuralları ve best practices.</p>
                    </div>
                    <div class="glass p-6 rounded-3xl hover:border-amber-500/30 transition-all cursor-pointer group">
                        <div class="h-12 w-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <h4 class="font-bold mb-1 text-white">Terminal Komutları</h4>
                        <p class="text-xs text-slate-500">Sık kullanılan proje komutları.</p>
                    </div>
                </div>

                <div class="glass p-8 rounded-[2.5rem]">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-bold text-white">Popüler Makaleler</h3>
                        <div class="flex gap-2">
                            <input type="text" placeholder="Dokümanlarda ara..." class="bg-slate-900/50 border border-slate-800/60 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all w-64">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="p-5 rounded-2xl bg-slate-900/40 border border-slate-800/60 hover:bg-slate-800/40 transition-all cursor-pointer flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-lg bg-blue-500/10 text-blue-500 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div>
                                    <h5 class="font-bold text-sm text-white">Git Workflow ve Branch Yapısı</h5>
                                    <p class="text-xs text-slate-500">Projelerimizde uyguladığımız git standartları.</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-900/40 border border-slate-800/60 hover:bg-slate-800/40 transition-all cursor-pointer flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-lg bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                </div>
                                <div>
                                    <h5 class="font-bold text-sm text-white">Güvenlik Protokolleri</h5>
                                    <p class="text-xs text-slate-500">Veri güvenliği ve API anahtarı kullanımı.</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600 group-hover:text-emerald-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-900/40 border border-slate-800/60 hover:bg-slate-800/40 transition-all cursor-pointer flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                                </div>
                                <div>
                                    <h5 class="font-bold text-sm text-white">Deployment Süreçleri</h5>
                                    <p class="text-xs text-slate-500">Staging ve Production ortamlarına çıkış.</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600 group-hover:text-amber-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB: REPOSITORY -->
            <div x-show="activeTab === 'repository'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="flex flex-col items-center justify-center min-h-[400px] text-center">
                <div class="h-24 w-24 rounded-full bg-slate-800 flex items-center justify-center mb-8">
                    <svg class="h-12 w-12 text-slate-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                </div>
                <h2 class="text-3xl font-bold mb-4 text-white">Repository Henüz Boş</h2>
                <p class="text-slate-500 max-w-md">Henüz herhangi bir proje veya repository bağlantınız bulunmuyor.</p>
            </div>

            <!-- TAB: MÜFREDAT -->
            <div x-show="activeTab === 'curriculum'" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="glass p-12 rounded-[3rem]">
                    <!-- Header Section -->
                    <div class="flex items-center justify-between mb-12">
                        <div>
                            <h3 class="font-bold flex items-center gap-3 text-3xl tracking-tight text-white uppercase">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                <span x-text="selectedLesson ? selectedLesson.title + ' Müfredatı' : 'Şirket Müfredatı'"></span>
                            </h3>
                            <p class="text-slate-400 mt-2 text-lg max-w-2xl leading-relaxed" x-text="selectedLesson ? selectedLesson.topic + ' eğitim sürecindeki hafta detaylarını inceliyorsunuz.' : 'TechNova bünyesindeki eğitim süreçlerimizden birini seçerek müfredatı inceleyebilirsiniz.'"></p>
                        </div>

                        <!-- Back Button -->
                        <template x-if="selectedLesson">
                            <button @click="selectedLesson = null" class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 border border-slate-700 text-slate-400 hover:text-white hover:border-emerald-500/50 transition-all group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                <span class="text-xs font-black uppercase tracking-widest">Geri Dön</span>
                            </button>
                        </template>
                    </div>

                    <!-- 1. LESSON CARDS (Visible when NO lesson is selected) -->
                    <template x-if="!selectedLesson">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <template x-for="lesson in lessons" :key="lesson.id">
                                <div @click="selectedLesson = lesson" class="group relative bg-slate-900/40 border border-slate-800/60 rounded-[2.5rem] p-10 hover:bg-slate-900/60 hover:border-emerald-500/50 transition-all duration-500 overflow-hidden cursor-pointer">
                                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-600/5 rounded-full blur-3xl group-hover:bg-emerald-600/10 transition-all duration-700"></div>

                                    <div class="relative z-10 space-y-6">
                                        <div class="flex items-center justify-between">
                                            <div class="h-14 w-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300 shadow-inner">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                            </div>
                                            <span class="px-4 py-1.5 rounded-xl bg-slate-800/50 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em]" x-text="lesson.topic"></span>
                                        </div>

                                        <div>
                                            <h4 class="text-3xl font-black text-white tracking-tighter group-hover:text-emerald-400 transition-colors" x-text="lesson.title"></h4>
                                            <p class="text-slate-500 font-bold uppercase text-[10px] tracking-widest mt-2" x-text="lesson.weeks.length + ' Haftalık Eğitim Programı'"></p>
                                        </div>

                                        <div class="flex items-center justify-between pt-6 border-t border-white/5">
                                            <div class="flex items-center gap-3">
                                                 <div class="flex -space-x-2">
                                                     <div class="h-8 w-8 rounded-full border-2 border-slate-900 bg-slate-800"></div>
                                                     <div class="h-8 w-8 rounded-full border-2 border-slate-900 bg-slate-700"></div>
                                                 </div>
                                                 <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest" x-text="lesson.students + ' Stajyer'"></span>
                                            </div>
                                            <div class="text-emerald-500 font-black text-xs uppercase tracking-widest flex items-center gap-2 group-hover:translate-x-1 transition-transform">
                                                İncele <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <!-- 2. WEEKLY DETAILS (Visible when a lesson IS selected) -->
                    <template x-if="selectedLesson">
                        <div class="space-y-12" x-transition>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <template x-for="week in selectedLesson.weeks" :key="week.id">
                                    <div class="p-8 rounded-[2.5rem] bg-slate-950/40 border border-slate-800/60 hover:bg-slate-950/60 hover:border-emerald-500/30 transition-all group border-l-4" :class="`border-l-${selectedLesson.topic === 'Backend' ? 'blue' : 'indigo'}-500/40` ">
                                        <div class="mb-6 flex items-center justify-between">
                                            <span class="px-4 py-1.5 bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest rounded-xl" x-text="'Hafta ' + week.id"></span>
                                            <div class="h-8 w-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-600">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                            </div>
                                        </div>
                                        <h5 class="text-xl font-bold text-white mb-4 leading-tight" x-text="week.title"></h5>
                                        <p class="text-slate-500 text-sm leading-relaxed" x-text="week.content"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- TAB: TOPLULUK YORUMLARI -->
            <div x-show="activeTab === 'community'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="glass p-10 rounded-[3rem] space-y-10 border-white/5">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h3 class="text-3xl font-black text-white tracking-tighter uppercase" x-text="selectedLesson ? selectedLesson.title + ' Yorumları' : 'Topluluk Beslemesi'"></h3>
                            <p class="text-slate-500 font-medium mt-2" x-text="selectedLesson ? 'Bu derse ait stajyer yorumlarını inceliyorsunuz.' : 'Diğer stajyerlerin ders içerikleri hakkındaki görüşlerini inceleyin.'"></p>
                        </div>
                        <div class="flex items-center gap-3 bg-slate-900/40 p-2 rounded-2xl border border-white/5">
                            <button @click="selectedLesson = null" :class="!selectedLesson ? 'bg-emerald-600 text-white' : 'text-slate-500'" class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg transition-all">Tümü</button>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <template x-for="comment in communityComments.filter(c => !selectedLesson || c.parentLesson === selectedLesson.title)" :key="comment.id">
                            <div class="p-8 rounded-[2.5rem] bg-slate-950/40 border border-slate-800/40 hover:border-emerald-500/30 hover:bg-slate-950/60 transition-all duration-300 group">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
                                    <div class="flex items-start gap-5">
                                        <div :class="`bg-${comment.color}-600/20 text-${comment.color}-400`" class="h-14 w-14 rounded-2xl flex items-center justify-center font-black text-xl shrink-0 shadow-inner" x-text="comment.avatar"></div>
                                        <div class="space-y-2">
                                            <div class="flex items-center gap-3">
                                                <h5 class="text-lg font-black text-white" x-text="comment.author"></h5>
                                                <span class="h-1 w-1 rounded-full bg-slate-700"></span>
                                                <span class="text-xs font-bold text-slate-500" x-text="comment.date"></span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20" x-text="comment.lesson"></span>
                                            </div>
                                            <p class="text-slate-400 text-base leading-relaxed pt-2" x-text="comment.comment"></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 self-end sm:self-auto">
                                        <button class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 border border-white/5 text-slate-500 hover:text-emerald-500 hover:border-emerald-500/30 transition-all group/btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.708c.94 0 1.667.83 1.45 1.744l-1.137 4.893a2 2 0 01-1.95 2.503L15.35 19H9m4-9l-1-4M9 10v9" /></svg>
                                            <span class="text-xs font-bold tracking-widest">12</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- TAB: DEĞERLENDİRME -->
            <div x-show="activeTab === 'evaluations'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="glass p-12 rounded-[3rem]">

                    <!-- STEP 1: LESSON CARDS -->
                    <template x-if="evaluationView === 'lessons'">
                        <div>
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                                <div>
                                    <h3 class="font-bold flex items-center gap-3 text-3xl tracking-tight text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        Ders Değerlendirme
                                    </h3>
                                    <p class="text-slate-400 mt-2 text-lg">Değerlendirmek istediğiniz dersi seçiniz.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <template x-for="lesson in lessons" :key="lesson.title">
                                    <div class="group relative bg-slate-900/40 border border-slate-800/60 rounded-[2.5rem] p-10 hover:bg-slate-900/60 hover:border-indigo-500/50 transition-all duration-500 overflow-hidden cursor-pointer"
                                         @click="selectedLesson = lesson; evaluationView = 'details'">

                                        <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-600/10 rounded-full blur-3xl group-hover:bg-indigo-600/20 transition-all duration-700"></div>

                                        <div class="relative z-10 flex flex-col h-full">
                                            <div class="flex items-center justify-between mb-8">
                                                <div class="flex items-center gap-4">
                                                    <div class="h-14 w-14 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-500 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                                    </div>
                                                    <div>
                                                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-1" x-text="lesson.topic"></span>
                                                        <h4 class="text-2xl font-bold text-white tracking-tighter" x-text="lesson.title"></h4>
                                                    </div>
                                                </div>
                                                <div class="h-10 w-10 rounded-xl bg-slate-800/50 border border-white/5 flex items-center justify-center text-slate-500 group-hover:border-indigo-500/30 group-hover:text-indigo-400 transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                                </div>
                                            </div>

                                            <p class="text-slate-400 text-sm leading-relaxed mb-6 flex-1" x-text="lesson.description"></p>

                                            <div class="flex items-center justify-between pt-6 border-t border-white/5">
                                                <span class="text-xs font-bold text-indigo-400 group-hover:text-indigo-300 transition-colors flex items-center gap-2">
                                                    Müfredatı Gör <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- STEP 2: CURRICULUM ITEMS (Mentor's perspective/Weekly list) -->
                    <template x-if="evaluationView === 'details' && selectedLesson">
                        <div>
                            <div class="flex items-center gap-4 mb-10">
                                <button @click="evaluationView = 'lessons'; selectedLesson = null" class="p-3 rounded-2xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:border-slate-700 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                </button>
                                <div>
                                    <h3 class="text-3xl font-black text-white tracking-tighter" x-text="selectedLesson.title"></h3>
                                    <p class="text-slate-500 font-bold uppercase text-[10px] tracking-[0.2em]" x-text="selectedLesson.topic + ' Müfredatı'"></p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <template x-for="item in selectedLesson.weeks" :key="item.id">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between p-8 rounded-[2rem] bg-slate-900/40 border border-slate-800/60 hover:bg-slate-900/60 transition-all group">
                                        <div class="flex items-center gap-6 mb-6 md:mb-0">
                                            <div class="h-16 w-16 rounded-2xl bg-slate-800 flex items-center justify-center text-slate-500 group-hover:bg-indigo-500/10 group-hover:text-indigo-500 transition-all duration-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            </div>
                                            <div>
                                                <h5 class="text-xl font-bold text-white mb-1" x-text="item.title"></h5>
                                                <p class="text-slate-500 text-sm" x-text="item.content"></p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-4">
                                            <template x-if="comments[item.title]">
                                                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                                                    <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                                    <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Değerlendirildi</span>
                                                </div>
                                            </template>

                                            <button @click="selectedCurriculumItem = item.title; newComment = comments[item.title] || ''; commentModalOpen = true"
                                                    class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-600/20 transition-all active:scale-95">
                                                Değerlendir
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- COMMENT MODAL -->
            <div x-show="commentModalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-8 bg-slate-950/90 backdrop-blur-3xl" x-transition.opacity>
                 <div class="glass w-full max-w-xl rounded-[3rem] p-8 sm:p-12 space-y-8 shadow-2xl relative overflow-hidden" @click.away="commentModalOpen = false" x-transition.scale.95>
                     <div class="absolute -top-32 -left-32 w-64 h-64 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none"></div>

                     <div class="relative z-10 text-center space-y-3">
                         <h2 class="text-3xl font-black text-white tracking-tighter uppercase">Ders Değerlendirmesi</h2>
                         <p class="text-slate-400 font-medium text-sm" x-text="selectedCurriculumItem"></p>
                     </div>

                     <div class="relative z-10 space-y-4">
                          <label class="text-[10px] font-black text-emerald-400 uppercase tracking-widest pl-4">Yorumunuz / Görüşünüz</label>
                          <textarea x-model="newComment" placeholder="Bu haftaki içerik nasıldı? Anlaşılmayan yerler veya önerileriniz..." class="w-full min-h-[160px] bg-slate-900/50 border border-slate-700/50 rounded-[2rem] p-6 outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-medium text-white placeholder:text-slate-600 shadow-inner resize-none"></textarea>
                     </div>

                     <div class="relative z-10 flex flex-col sm:flex-row items-center gap-4 pt-4 border-t border-white/5">
                          <button @click="commentModalOpen = false" class="w-full sm:w-auto flex-1 h-14 rounded-2xl border border-white/5 text-slate-400 font-black tracking-widest text-xs hover:bg-slate-800 transition-all uppercase">İptal</button>
                          <button @click="
                              if(newComment.trim() !== '') {
                                comments[selectedCurriculumItem] = newComment;

                                // Topluluk yorumlarına ekle
                                const commentData = {
                                    id: Date.now(),
                                    author: 'Zerda',
                                    parentLesson: selectedLesson.title,
                                    lesson: selectedCurriculumItem,
                                    comment: newComment,
                                    date: 'Şimdi',
                                    avatar: 'Z',
                                    color: 'blue'
                                };
                                communityComments.unshift(commentData);

                                // localStorage'a kaydet
                                let stored = JSON.parse(localStorage.getItem('taskorbit_community_comments') || '[]');
                                stored.unshift(commentData);
                                localStorage.setItem('taskorbit_community_comments', JSON.stringify(stored));

                                alert('Değerlendirmeniz başarıyla güncellendi/iletildi.');
                              } else {
                                delete comments[selectedCurriculumItem];
                              }
                              newComment = '';
                              commentModalOpen = false;
                          " class="w-full sm:w-auto flex-[2] h-14 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl font-black text-sm shadow-[0_15px_30px_rgba(16,185,129,0.3)] hover:scale-[1.02] active:scale-95 transition-all uppercase tracking-tighter">Değerlendirmeyi Gönder</button>
                     </div>
                 </div>
            </div>

        </div>
    </main>

    <footer class="fixed bottom-6 right-8 z-20">
        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">© 2026 TASK ORBIT</p>
    </footer>
</body>
</html>
