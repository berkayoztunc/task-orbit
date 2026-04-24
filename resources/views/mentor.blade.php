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
        body { font-family: 'Inter', sans-serif; background-color: #020617; color: #f8fafc; overflow: hidden; }
        .bg-navy-deep { background-color: #020617; }
        .glass-card { background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(24px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .emerald-glow { box-shadow: 0 0 50px rgba(16, 185, 129, 0.1); }
        .sidebar-item-active { background: rgba(16, 185, 129, 0.1); color: #10b981; border-color: rgba(16, 185, 129, 0.2); }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #10b981; }
    </style>
</head>
<body x-data="{
    sidebarOpen: true,
    currentTab: 'dashboard',
    showAssignModal: false,
    showFeedbackModal: false,
    selectedIntern: null,
    searchQuery: '',

    // Mock Data
    interns: [
        { id: 1, name: 'Ahmet Yılmaz', role: 'Frontend Intern', progress: 65, avatar: 'AY', color: 'blue', email: 'ahmet@technova.com', joinDate: '01.03.2024' },
        { id: 2, name: 'Ayşe Kaya', role: 'Backend Intern', progress: 40, avatar: 'AK', color: 'emerald', email: 'ayse@technova.com', joinDate: '15.03.2024' },
        { id: 3, name: 'Mehmet Demir', role: 'UI/UX Intern', progress: 85, avatar: 'MD', color: 'amber', email: 'mehmet@technova.com', joinDate: '10.02.2024' },
        { id: 4, name: 'Selin Arı', role: 'Cyber Security Intern', progress: 20, avatar: 'SA', color: 'purple', email: 'selin@technova.com', joinDate: '05.04.2024' }
    ],

    lessons: [
        { id: 1, title: 'Web Temelleri & Mimari', topic: 'Frontend', status: 'Aktif', students: 12, week: 1 },
        { id: 2, title: 'Javascript ES6+ & TypeScript', topic: 'Development', status: 'Hazırlanıyor', students: 8, week: 2 },
        { id: 3, title: 'React & State Management', topic: 'Frontend', status: 'Planlandı', students: 10, week: 3 },
        { id: 4, title: 'Node.js & Express API', topic: 'Backend', status: 'Tamamlandı', students: 15, week: 4 }
    ],

    tasks: [
        { id: 1, title: 'API Integration', intern: 'Ahmet Yılmaz', deadline: '2024-04-25', priority: 'High', status: 'In Progress' },
        { id: 2, title: 'Database Schema Design', intern: 'Ayşe Kaya', deadline: '2024-04-26', priority: 'Medium', status: 'Pending Review' },
        { id: 3, title: 'Landing Page Icons', intern: 'Mehmet Demir', deadline: '2024-04-24', priority: 'Low', status: 'Completed' }
    ],

    resources: [
        { name: 'Onboarding Guide.pdf', size: '2.4 MB', date: '12.04.2024' },
        { name: 'Technical Standards.docx', size: '1.1 MB', date: '15.04.2024' },
        { name: 'Training Material V1.zip', size: '45 MB', date: '20.04.2024' }
    ],

    notifications: [],
    notify(msg) {
        const id = Date.now();
        this.notifications.push({ id, message: msg });
        setTimeout(() => {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }, 3000);
    },

    newTask: {
        title: '',
        technicalDetails: '',
        deadline: '',
        priority: 'Medium',
        internId: ''
    },

    assignTask() {
        if(this.newTask.title && this.newTask.internId) {
            this.notify('Görev başarıyla atandı: ' + this.newTask.title);
            this.currentTab = 'dashboard';
            this.newTask = { title: '', technicalDetails: '', deadline: '', priority: 'Medium', internId: '' };
        }
    }
}">

    <div class="flex h-screen w-full relative overflow-hidden">
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

            <nav class="flex-1 px-6 space-y-8 mt-12 overflow-y-auto">
                <div class="space-y-3">
                    <p x-show="sidebarOpen" class="px-4 text-[10px] font-black uppercase tracking-[0.4em] text-slate-500 mb-6">Yönetim Merkezi</p>

                    <button @click="currentTab = 'dashboard'" :class="currentTab === 'dashboard' ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white'" class="w-full h-14 flex items-center gap-4 px-5 rounded-[1.2rem] border border-transparent transition-all group">
                        <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span x-show="sidebarOpen" class="font-bold text-sm">Genel Bakış</span>
                    </button>

                    <button @click="currentTab = 'interns'" :class="currentTab === 'interns' ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white'" class="w-full h-14 flex items-center gap-4 px-5 rounded-[1.2rem] border border-transparent transition-all group">
                        <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <span x-show="sidebarOpen" class="font-bold text-sm">Stajyer Listesi</span>
                    </button>

                    <button @click="currentTab = 'lessons'" :class="currentTab === 'lessons' ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white'" class="w-full h-14 flex items-center gap-4 px-5 rounded-[1.2rem] border border-transparent transition-all group">
                        <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        <span x-show="sidebarOpen" class="font-bold text-sm">Ders İçerikleri</span>
                    </button>

                    <button @click="currentTab = 'evaluation'" :class="currentTab === 'evaluation' ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white'" class="w-full h-14 flex items-center gap-4 px-5 rounded-[1.2rem] border border-transparent transition-all group">
                        <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                        <span x-show="sidebarOpen" class="font-bold text-sm">Değerlendirme</span>
                    </button>

                    <button @click="currentTab = 'assign'" :class="currentTab === 'assign' ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white'" class="w-full h-14 flex items-center gap-4 px-5 rounded-[1.2rem] border border-transparent transition-all group">
                        <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span x-show="sidebarOpen" class="font-bold text-sm">Görev Ata</span>
                    </button>

                    <button @click="currentTab = 'resources'" :class="currentTab === 'resources' ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white'" class="w-full h-14 flex items-center gap-4 px-5 rounded-[1.2rem] border border-transparent transition-all group">
                        <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span x-show="sidebarOpen" class="font-bold text-sm">Kaynaklar</span>
                    </button>

                    <div class="pt-8 border-t border-white/5">
                        <a href="/dashboard" class="w-full h-14 flex items-center gap-4 px-5 rounded-[1.2rem] border border-transparent text-slate-400 hover:bg-white/5 hover:text-white transition-all group">
                            <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                            <span x-show="sidebarOpen" class="font-bold text-sm">Dashboard'a Dön</span>
                        </a>
                    </div>
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
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
            <!-- Header -->
            <header class="h-24 px-12 border-b border-white/5 bg-slate-950/20 backdrop-blur-xl flex items-center justify-between shrink-0 z-20">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="h-12 w-12 flex items-center justify-center rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all text-slate-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7" /></svg>
                    </button>
                    <h2 class="text-xl font-black text-white tracking-tight uppercase px-4 border-l border-white/10" x-text="
                        currentTab === 'dashboard' ? 'Genel Bakış' :
                        currentTab === 'assign' ? 'Görev Yönetimi' :
                        currentTab === 'interns' ? 'Stajyer Merkezi' :
                        currentTab === 'lessons' ? 'Müfredat & Dersler' :
                        currentTab === 'evaluation' ? 'Performans Değerlendirme' :
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
                        <h1 class="text-6xl font-black text-white tracking-tighter">Hoşgeldiniz Mentor.</h1>
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
                             <h2 class="text-4xl font-black text-white uppercase tracking-tighter">Stajyer   Merkezi</h2>
                             <p class="text-lg font-medium text-slate-500">Ekibindeki öğrencilerin detaylı profilleri ve iletişim bilgileri.</p>
                         </div>
                         <div class="flex items-center gap-6">
                            <div class="relative group">
                                <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" x-model="searchQuery" placeholder="Stajyer ara..." class="h-14 w-80 bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 text-xs font-bold focus:outline-none focus:border-emerald-500 transition-all">
                            </div>
                            <button class="h-14 px-8 bg-emerald-600 text-white rounded-2xl font-black text-sm shadow-xl shadow-emerald-600/20 hover:scale-105 transition-all uppercase tracking-tighter">Excel Dökümü Al</button>
                         </div>
                     </div>

                     <div class="glass-card rounded-[3rem] overflow-hidden border-white/5 shadow-3xl">
                         <table class="w-full text-left border-collapse">
                             <thead>
                                 <tr class="bg-white/[0.02] border-b border-white/10 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">
                                     <th class="p-8">Öğrenci</th>
                                     <th class="p-8">Rol & Departman</th>
                                     <th class="p-8">Eğitim İlerlemesi</th>
                                     <th class="p-8">Performans Skoru</th>
                                     <th class="p-8 text-right">İşlem</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 <template x-for="intern in interns.filter(i => i.name.toLowerCase().includes(searchQuery.toLowerCase()))">
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
                                                    <span x-text="`${intern.progress > 50 ? 'İyi' : 'Gelişmeli'}`"></span>
                                                </div>
                                                <div class="h-2 w-48 bg-slate-900 rounded-full overflow-hidden border border-white/5 shadow-inner">
                                                    <div :class="`bg-${intern.color}-500`" class="h-full rounded-full transition-all duration-1000 shadow-[0_0_15px_rgba(16,185,129,0.2)]" :style="`width: ${intern.progress}%`"></div>
                                                </div>
                                             </div>
                                         </td>
                                         <td class="p-8">
                                             <div class="flex items-center gap-1">
                                                 <template x-for="star in [1,2,3,4,5]">
                                                     <svg xmlns="http://www.w3.org/2000/svg" :class="star <= Math.round(intern.progress/20) ? 'text-amber-500' : 'text-slate-800'" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                         <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                     </svg>
                                                 </template>
                                             </div>
                                         </td>
                                         <td class="p-8 text-right">
                                             <button @click="selectedIntern = intern.name; currentTab = 'evaluation'" class="px-6 py-2.5 rounded-xl bg-white/5 border border-white/10 text-[10px] font-black text-white hover:bg-emerald-600 hover:border-emerald-500 transition-all uppercase tracking-widest shadow-xl">Dosyayı Aç</button>
                                         </td>
                                     </tr>
                                 </template>
                             </tbody>
                         </table>
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
                                         <span class="px-4 py-1.5 rounded-xl bg-blue-600/10 text-blue-500 text-[10px] font-black uppercase tracking-[0.3em]" x-text="`HAFTA ${lesson.week}`"></span>
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
                                     <button class="h-12 px-6 rounded-2xl bg-white/5 border border-white/10 text-xs font-black text-white hover:bg-blue-600 hover:border-blue-500 transition-all tracking-widest uppercase">Müfredatı Düzenle</button>
                                 </div>
                             </div>
                         </template>

                         <!-- Add Lesson Card -->
                         <div class="border-4 border-dashed border-white/5 rounded-[3.5rem] flex flex-col items-center justify-center text-center p-12 space-y-6 hover:border-blue-500/20 hover:bg-blue-600/[0.02] transition-all cursor-pointer group">
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
                                        <template x-for="intern in interns">
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
                        <div class="pt-10 flex items-center gap-6 w-full max-w-4xl mx-auto">
  <button @click="newTask = { title: '', technicalDetails: '', deadline: '', priority: 'Medium', internId: '' }"
          class="flex-1 h-20 rounded-2xl border border-white/10 text-slate-400 font-bold tracking-widest text-xs hover:bg-rose-500/10 hover:text-rose-500 hover:border-rose-500/30 transition-all uppercase">
    Temizle
  </button>

  <button @click="assignTask()"
          class="flex-[3] h-20 bg-emerald-500 text-white rounded-2xl font-black text-2xl shadow-[0_20px_50px_rgba(16,185,129,0.4)] hover:scale-[1.01] active:scale-95 transition-all uppercase tracking-tight">
    Görevi Sisteme Gönder
  </button>
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
            </div>

            <!-- Footer Global -->
            <footer class="h-16 flex items-center justify-center px-12 border-t border-white/5 shrink-0 bg-slate-950/20 backdrop-blur-xl">
                <p class="text-[10px] font-black text-slate-700 uppercase tracking-[0.5em]">© 2026 TASK ORBIT MENTORING PORTAL • EMPOWERING TALENT</p>
            </footer>
        </main>
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
                  <button @click="showFeedbackModal = false" class="flex-1 h-18 rounded-2xl border border-white/5 text-slate-500 font-black tracking-widest text-xs hover:bg-slate-800 transition-all uppercase">Vazgeç</button>
                  <button @click="notify('Geri bildirim başarıyla iletildi.'); showFeedbackModal = false" class="flex-[2] h-18 bg-emerald-600 text-white rounded-2xl font-black text-lg shadow-xl shadow-emerald-600/30 transition-all active:scale-95 uppercase tracking-tighter">Değerlendirmeyi Kaydet</button>
             </div>
         </div>
    </div>

    <!-- NOTIFICATIONS -->
    <div class="fixed bottom-8 right-8 z-[200] space-y-4">
        <template x-for="n in notifications" :key="n.id">
            <div x-transition class="bg-emerald-600 text-white px-8 py-4 rounded-2xl font-black shadow-2xl flex items-center gap-4 border border-white/10 backdrop-blur-xl">
                <div class="h-2 w-2 rounded-full bg-white animate-pulse"></div>
                <span x-text="n.message" class="text-sm"></span>
            </div>
        </template>
    </div>

    <!-- Background Glows -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-[-20%] left-[-20%] w-[60%] h-[60%] bg-emerald-600/5 rounded-full blur-[150px]"></div>
        <div class="absolute bottom-[-20%] right-[-20%] w-[60%] h-[60%] bg-blue-600/5 rounded-full blur-[150px]"></div>
    </div>
</body>
</html>
