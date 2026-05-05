<!-- resources/views/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Task Orbit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; background-color: #020617; color: #f8fafc; overflow: hidden; }
        .bg-navy-deep { background-color: #020617; }
        .glass { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(51, 65, 85, 0.4); }
        .glass-card { background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(24px); border: 1px solid rgba(255, 255, 255, 0.05); }

        /* Netflix Tarzı Profil Kartı Efektleri */
        .profile-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .profile-card:hover { transform: scale(1.02); border-color: rgba(37, 99, 235, 0.6); }
        .profile-card:hover .avatar-box { border-color: #2563eb; box-shadow: 0 0 20px rgba(37, 99, 235, 0.3); }

        .sidebar-item-active { background: #2563eb; color: white; box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4); }
        .layout-transition { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body x-data="{
    sidebarOpen: true,
    currentTab: 'dashboard',
    selectedCompany: null,
    viewingCompanyDetails: null,
    viewingInternship: null,
    showApplicationForm: false,
    form: { name: '', email: '', message: '' },
    companies: [
        { id: 'technova', name: 'TechNova', tag: 'TN', color: 'blue', desc: 'Yapay zeka ve bulut bilişim çözümleri üzerine uzmanlaşmış teknoloji öncüsü.', detail: 'TechNova, 2015 yılında kurulmuş olup, dünya çapında 500+ kurumsal müşteriye hizmet vermektedir. Stajyerler burada gerçek dünya projelerinde yer alarak deneyim kazanırlar.' },
        { id: 'global-soft', name: 'Global Soft', tag: 'GS', color: 'emerald', desc: 'Finansal teknolojiler ve güvenli ödeme sistemleri geliştiricisi.', detail: 'Global Soft, bankacılık sektöründe devrim yaratan yazılımlar üretir. Mentorluk programımız ile genç yetenekleri sektöre kazandırıyoruz.' },
        { id: 'orbit-lab', name: 'Orbit Lab', tag: 'OL', color: 'amber', desc: 'Uzay teknolojileri ve veri analitiği laboratuvarı.', detail: 'Orbit Lab, uydu verilerini işleyerek tarım ve şehircilik alanında kritik analizler sunar. İnovatif fikirlerin hayat bulduğu bir merkezdir.' },
        { id: 'cyber-core', name: 'Cyber Core', tag: 'CC', color: 'purple', desc: 'Siber güvenlik ve ağ altyapı koruma sistemleri.', detail: 'Cyber Core, kritik altyapıları siber saldırılara karşı koruyan ileri düzey savunma sistemleri geliştirir.' }
    ]
}">

    <div class="flex h-screen w-full relative overflow-hidden">
        <!-- SIDEBAR -->
        <aside
            :class="sidebarOpen ? 'w-72' : 'w-20'"
            class="layout-transition glass border-r border-slate-800/40 bg-slate-950/40 backdrop-blur-xl flex flex-col relative z-30 shadow-2xl shrink-0 group/sidebar"
        >
            <div class="h-20 flex items-center px-6 justify-between shrink-0">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0" class="text-xl font-extrabold tracking-tighter whitespace-nowrap uppercase">TASK ORBIT</span>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-slate-800/40 text-slate-500 hover:text-white transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" :class="sidebarOpen ? '' : 'rotate-180'" class="h-4 w-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
                </button>
            </div>

            <nav class="flex-1 px-4 space-y-6 mt-8 overflow-y-auto">
                <div>
                    <p x-show="sidebarOpen" class="px-4 text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-4">Platform</p>
                    <div class="relative group/item">
                        <button @click="currentTab = 'dashboard'" :class="currentTab === 'dashboard' ? 'bg-blue-600/10 text-blue-500 border-blue-600/20 shadow-lg shadow-blue-600/5' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white border-transparent'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                            <span x-show="sidebarOpen" class="font-bold text-sm whitespace-nowrap">Dashboard</span>
                        </button>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="relative group/item">
                        <button @click="currentTab = 'repository'" :class="currentTab === 'repository' ? 'bg-blue-600/10 text-blue-500 border-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white border-transparent'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Repository</span>
                        </button>
                    </div>
                    <div class="relative group/item">
                        <button @click="currentTab = 'docs'" :class="currentTab === 'docs' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white border-transparent'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Documentation</span>
                        </button>
                    </div>
                </div>
            </nav>

            <div class="p-4 border-t border-slate-800/40">
                <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-900/40 border border-slate-800/60 overflow-hidden">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center font-bold text-xs text-white shrink-0">A</div>
                    <div x-show="sidebarOpen" class="min-w-0">
                        <p class="text-sm font-bold truncate">AAA</p>
                        <p class="text-[10px] text-[#2563eb] font-black uppercase tracking-widest whitespace-nowrap">github-user</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden relative z-10">
            <!-- Topbar -->
            <header class="h-20 border-b border-slate-800/40 bg-slate-950/20 backdrop-blur-md flex items-center px-8 shrink-0">
                <div class="flex items-center gap-3 text-slate-400">
                    <template x-if="currentTab === 'dashboard'">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" /></svg>
                            <span class="text-sm font-black uppercase tracking-widest">Dashboard</span>
                        </div>
                    </template>
                    <template x-if="currentTab === 'repository'">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            <span class="text-sm font-black uppercase tracking-widest">Repository</span>
                        </div>
                    </template>
                    <template x-if="currentTab === 'docs'">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <span class="text-sm font-black uppercase tracking-widest">Documentation</span>
                        </div>
                    </template>
                </div>
            </header>

            <!-- CONTENT AREA -->
            <div class="flex-1 overflow-y-auto p-12">
                <!-- PROFIL SEÇİM ALANI -->
                <div x-show="currentTab === 'dashboard'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="flex flex-col items-center justify-center min-h-full">
                    <div class="text-center mb-16">
                        <h2 class="text-5xl font-black tracking-tighter mb-4">Select Profile</h2>
                        <p class="text-slate-500 font-medium text-lg">Select the Task Orbit profile you wish to continue with.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 w-full max-w-6xl">
                        <a href="/user-panel" class="profile-card glass p-10 rounded-[2.5rem] flex flex-col items-center text-center group active:scale-95 shadow-2xl">
                            <div class="avatar-box h-28 w-28 mb-8 rounded-[2rem] border-2 border-slate-700 bg-slate-900 flex items-center justify-center transition-all duration-300 overflow-hidden">
                                <img src="https://github.com/identicons/jason.png" alt="Zerda" class="h-full w-full opacity-70 group-hover:opacity-100 transition-opacity object-cover">
                            </div>
                            <h3 class="text-2xl font-bold text-white group-hover:text-blue-500 transition-colors">AAA (Main)</h3>
                            <p class="text-[10px] text-slate-500 mt-2 font-black uppercase tracking-widest">Sistem Yöneticisi</p>
                        </a>

                        <a href="/intern" class="profile-card glass p-10 rounded-[2.5rem] flex flex-col items-center text-center group active:scale-95 shadow-2xl">
                            <div class="avatar-box h-28 w-28 mb-8 rounded-[2rem] border-2 border-slate-700 bg-blue-600/10 flex items-center justify-center transition-all duration-300">
                                <span class="text-4xl font-black text-blue-500">TN</span>
                            </div>
                            <h3 class="text-2xl font-bold text-white group-hover:text-blue-500 transition-colors">TechNova</h3>
                            <p class="text-[10px] text-blue-500/80 mt-2 font-black uppercase tracking-widest">Intern (Stajyer)</p>
                        </a>

                        <a href="/mentor" class="profile-card glass p-10 rounded-[2.5rem] flex flex-col items-center text-center group active:scale-95 shadow-2xl">
                            <div class="avatar-box h-28 w-28 mb-8 rounded-[2rem] border-2 border-slate-700 bg-emerald-600/10 flex items-center justify-center transition-all duration-300">
                                <span class="text-4xl font-black text-emerald-500">GS</span>
                            </div>
                            <h3 class="text-2xl font-bold text-white group-hover:text-emerald-500 transition-colors">Global Soft</h3>
                            <p class="text-[10px] text-emerald-500/80 mt-2 font-black uppercase tracking-widest">Mentor (Rehber)</p>
                        </a>

                        <a href="/intern" class="profile-card glass p-10 rounded-[2.5rem] flex flex-col items-center text-center group active:scale-95 shadow-2xl">
                            <div class="avatar-box h-28 w-28 mb-8 rounded-[2rem] border-2 border-slate-700 bg-amber-600/10 flex items-center justify-center transition-all duration-300">
                                <span class="text-4xl font-black text-amber-500">OL</span>
                            </div>
                            <h3 class="text-2xl font-bold text-white group-hover:text-amber-500 transition-colors">Orbit Lab</h3>
                            <p class="text-[10px] text-amber-500/80 mt-2 font-black uppercase tracking-widest">Intern (Stajyer)</p>
                        </a>
                    </div>
                </div>

                <!-- DOKÜMANTASYON ALANI -->
                <div x-show="currentTab === 'docs'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="max-w-6xl mx-auto w-full space-y-12">
                    <div class="space-y-4">
                        <h2 class="text-6xl font-black tracking-tighter">Şirket Dokümantasyonu</h2>
                        <p class="text-xl text-slate-500 max-w-2xl font-medium">Task Orbit bünyesindeki şirketleri inceleyin ve staj imkanları hakkında bilgi edinin.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-32">
                        <template x-for="company in companies" :key="company.id">
                            <div @click="selectedCompany = company" class="glass-card p-12 rounded-[3.5rem] group hover:border-blue-500/40 transition-all cursor-pointer relative overflow-hidden shadow-xl">
                                <div :class="`absolute top-0 right-0 w-32 h-32 bg-${company.color}-600/5 rounded-full -mr-16 -mt-16 blur-3xl group-hover:bg-${company.color}-600/10 transition-all`"></div>

                                <div class="flex items-center gap-6 mb-8">
                                    <div :class="`h-16 w-16 rounded-2xl bg-${company.color}-600/10 text-${company.color}-500 flex items-center justify-center group-hover:scale-110 transition-transform`" class="h-16 w-16 rounded-2xl flex items-center justify-center">
                                        <span class="text-3xl font-black" x-text="company.tag"></span>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-white group-hover:text-blue-400 transition-colors" x-text="company.name"></h3>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-600" x-text="company.id"></p>
                                    </div>
                                </div>

                                <p class="text-slate-400 mb-8 text-lg leading-relaxed line-clamp-2" x-text="company.desc"></p>

                                <div class="flex items-center justify-between border-t border-white/5 pt-8">
                                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest group-hover:text-blue-500 transition-colors">İncelemek için tıkla</span>
                                    <div class="h-12 w-12 rounded-full border border-slate-800 flex items-center justify-center group-hover:bg-blue-600 group-hover:border-blue-600 transition-all shadow-xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600 transition-colors group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- REPOSITORY ALANI -->
                <div x-show="currentTab === 'repository'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="flex flex-col items-center justify-center min-h-full text-center">
                    <div class="h-32 w-32 rounded-[2.5rem] glass-card flex items-center justify-center mb-8 border border-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z" /></svg>
                    </div>
                    <h2 class="text-3xl font-black mb-4 uppercase tracking-tighter">Repository Henüz Boş</h2>
                    <p class="text-slate-600 max-w-md font-medium text-lg">Henüz herhangi bir proje veya repository bağlantınız bulunmuyor.</p>
                </div>
            </div>
        </main>
    </div>

    @include('internshipapplication')
</body>
</html>
