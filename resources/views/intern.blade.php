<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intern Panel - Task Orbit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-navy-deep { background-color: #020617; }
        .glass { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(51, 65, 85, 0.4); }
        .sidebar-item-active { background: rgba(37, 99, 235, 0.1); color: #3b82f6; border-left: 3px solid #3b82f6; }
    </style>
</head>
<body class="bg-[#020617] text-slate-50 flex h-screen overflow-hidden"
      x-data="{
        sidebarOpen: true,
        activeTab: 'tasks',
        uploadStatus: 'idle',
        progress: 65
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
            <p x-show="sidebarOpen" class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-500 mb-4">Menu</p>

            <button @click="activeTab = 'tasks'" :class="activeTab === 'tasks' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold">My Duties</span>
                <div x-show="!sidebarOpen" class="absolute left-full ml-4 px-3 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">Görevlerim</div>
            </button>

            <button @click="activeTab = 'curriculum'" :class="activeTab === 'curriculum' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold">Company Curriculum</span>
                <div x-show="!sidebarOpen" class="absolute left-full ml-4 px-3 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">Şirket Müfredatı</div>
            </button>

            <button @click="activeTab = 'repository'" :class="activeTab === 'repository' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group relative">
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold">Repository</span>
                <div x-show="!sidebarOpen" class="absolute left-full ml-4 px-3 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">Repository</div>
            </button>

            <button @click="activeTab = 'mentor'" :class="activeTab === 'mentor' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold">Mentor Comments</span>
                <div x-show="!sidebarOpen" class="absolute left-full ml-4 px-3 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">Mentor Yorumları</div>
            </button>

            <button @click="activeTab = 'docs'" :class="activeTab === 'docs' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold">Documentation</span>
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
                <div class="text-sm font-medium text-slate-500 uppercase tracking-widest">Intern Panel</div>
            </div>
            <div class="flex items-center gap-6">
                <button class="relative h-10 w-10 flex items-center justify-center rounded-xl hover:bg-slate-800/40 transition-all text-slate-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    <span class="absolute top-2 right-2 h-2 w-2 bg-blue-600 rounded-full border-2 border-[#020617]"></span>
                </button>
                <div class="h-8 w-[1px] bg-slate-800/40"></div>
                <div class="relative group cursor-pointer flex items-center gap-3 pl-4 pr-2 py-1.5 rounded-2xl hover:bg-slate-800/30 transition-all border border-transparent hover:border-slate-800/40 max-w-[240px]">
                    <div class="text-right hidden sm:block min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-100 group-hover:text-blue-400 transition-colors truncate">Ccc</p>
                        <p class="text-[10px] font-bold text-blue-500/80 uppercase tracking-widest truncate">Intern @ TechNova</p>
                    </div>
                    <div class="relative flex-shrink-0">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-600 flex items-center justify-center font-bold text-sm shadow-lg shadow-blue-500/30 border border-white/10 text-white">C</div>
                        <div class="absolute -bottom-1 -right-1 h-3.5 w-3.5 bg-emerald-500 rounded-full border-2 border-[#020617] shadow-sm"></div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 space-y-8">
            <div>
                <h1 class="text-4xl font-extrabold tracking-tight">Welcome, Ccc</h1>
                <p class="text-slate-500 font-medium mt-2">You can track your orbital missions from here.</p>
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

                        <h3 class="text-2xl font-bold mb-2">Completed Tasks</h3>
                        <p class="text-slate-500">Out of a total of 20 tasks, 13 were successfully completed.</p>
                        <div class="mt-8">
                            <span class="px-4 py-1.5 rounded-full bg-emerald-500/10 text-emerald-500 text-xs font-bold uppercase tracking-widest">You Are Doing Great</span>
                        </div>
                    </div>

                    <!-- Card 2: Teslim Tarihi Yaklaşanlar (Sadece Bugün) -->
                    <div class="glass p-10 rounded-[2.5rem] flex flex-col group hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-4 mb-10">
                            <div class="h-14 w-14 rounded-2xl bg-red-500/10 text-red-500 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="text-left">
                                <h3 class="text-2xl font-bold">Delivery Today</h3>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Tasks that End on the Last Day</p>
                            </div>
                        </div>

                        <div class="space-y-5 flex-1">
                            <div class="flex items-center justify-between p-5 rounded-2xl bg-red-500/5 border border-red-500/20 group-hover:border-red-500/40 transition-colors">
                                <div class="text-left">
                                    <p class="text-base font-bold text-white">API Integration</p>
                                    <p class="text-xs font-bold uppercase tracking-widest text-red-500">Today at 11:59 PM</p>
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
                             Upcoming Missions
                        </h3>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">2 Tasks Awaiting</span>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex items-center justify-between p-5 rounded-2xl bg-slate-900/40 border border-slate-800/60 hover:border-blue-500/30 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400 group-hover:text-blue-400 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </div>
                                <div>
                                    <p class="font-bold">MVC structure</p>
                                    <p class="text-xs text-slate-500">To Be Delivered Tomorrow</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 rounded-lg bg-slate-800 text-slate-400 text-[10px] font-bold uppercase tracking-wider">Pending</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-5 rounded-2xl bg-slate-900/40 border border-slate-800/60 hover:border-blue-500/30 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400 group-hover:text-blue-400 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>
                                </div>
                                <div>
                                    <p class="font-bold">Inventory Tracking Application Using C#</p>
                                    <p class="text-xs text-slate-500">Delivery on April 18th.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 rounded-lg bg-slate-800 text-slate-400 text-[10px] font-bold uppercase tracking-wider">Pending</span>
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
                            <h3 class="text-3xl font-bold mb-3">Upload Task</h3>
                            <p class="text-slate-400 max-w-md mx-auto mb-10 text-lg">To submit your work to your mentor, drop or select the appropriate file here.</p>
                            <button @click="document.getElementById('fileInput').click()"
                                    class="px-16 py-4 bg-blue-600 text-white rounded-2xl font-bold text-xl shadow-xl shadow-blue-600/20 hover:bg-blue-500 transition-all active:scale-95">
                               Select File
                            </button>
                        </div>
                    </template>

                    <template x-if="uploadStatus === 'uploading'">
                        <div class="space-y-8 py-10">
                            <div class="w-24 h-24 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto"></div>
                            <p class="text-3xl font-bold text-blue-500">Sending...</p>
                        </div>
                    </template>

                    <template x-if="uploadStatus === 'success'">
                        <div class="space-y-8 py-10">
                            <div class="h-28 w-28 rounded-3xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                            </div>
                            <h3 class="text-4xl font-bold text-emerald-500">Successful!</h3>
                            <p class="text-xl text-slate-400">The task has been forwarded to the mentor.</p>
                            <button @click="uploadStatus = 'idle'" class="text-base font-bold text-slate-500 hover:text-white underline mt-8">Submit New Task</button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- TAB: MENTOR YORUMLARI -->
            <div x-show="activeTab === 'mentor'" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="glass p-8 rounded-[2rem]">
                    <h3 class="font-bold mb-8 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>
                        Mentor Feedback
                    </h3>
                    <div class="space-y-6">
                        <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-800/60 hover:border-purple-500/30 transition-all">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-purple-600 flex items-center justify-center text-xs font-bold">AK</div>
                                    <div>
                                        <p class="text-sm font-bold">Ali Kaya</p>
                                        <p class="text-[10px] text-slate-500">Software Developer • 2 hours ago</p>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-slate-300 leading-relaxed">Your assignment has a 404-Not Found error. Please correct the error and resubmit..</p>
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
                        <h4 class="font-bold mb-1 text-white">Beginner's Guide</h4>
                        <p class="text-xs text-slate-500">Company culture and initial steps.</p>
                    </div>
                    <div class="glass p-6 rounded-3xl hover:border-emerald-500/30 transition-all cursor-pointer group">
                        <div class="h-12 w-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        </div>
                        <h4 class="font-bold mb-1 text-white">Code Standards</h4>
                        <p class="text-xs text-slate-500">Spelling rules and best practices.</p>
                    </div>
                    <div class="glass p-6 rounded-3xl hover:border-amber-500/30 transition-all cursor-pointer group">
                        <div class="h-12 w-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <h4 class="font-bold mb-1 text-white">Terminal Commands</h4>
                        <p class="text-xs text-slate-500">Frequently used project commands.</p>
                    </div>
                </div>

                <div class="glass p-8 rounded-[2.5rem]">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-bold text-white">Popular Articles</h3>
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
                                    <h5 class="font-bold text-sm text-white">Git Workflow and Branch Structure</h5>
                                    <p class="text-xs text-slate-500">Git standards we apply in our projects.</p>
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
                                    <h5 class="font-bold text-sm text-white">Security Protocols</h5>
                                    <p class="text-xs text-slate-500">Data security and API key usage.</p>
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
                                    <h5 class="font-bold text-sm text-white">Deployment Processes</h5>
                                    <p class="text-xs text-slate-500">Transition to Staging and Production environments.</p>
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
                <h2 class="text-3xl font-bold mb-4">Repository is not yet empty.</h2>
                <p class="text-slate-500 max-w-md">You don't have any project or repository links yet.</p>
            </div>

            <!-- TAB: MÜFREDAT -->
            <div x-show="activeTab === 'curriculum'" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="glass p-8 rounded-[2rem]">
                    <h3 class="font-bold mb-8 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                       Company Curriculum
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-800/60">
                            <p class="text-xs font-bold text-emerald-500 uppercase tracking-widest mb-2">Week 1</p>
                            <h4 class="font-bold mb-2">Basic Integrations</h4>
                            <p class="text-sm text-slate-400">Learning the API structure and basic database schemas.</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-800/60 opacity-50">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Week 2</p>
                            <h4 class="font-bold mb-2">Advanced Testing Processes</h4>
                            <p class="text-sm text-slate-400">Introduction to unit testing and integration testing processes.</p>
                        </div>
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
