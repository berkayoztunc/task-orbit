<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Companies - Task Orbit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#020617] text-slate-50 flex h-screen overflow-hidden"
      x-data="{
        sidebarCollapsed: false,
        activeTab: 'Active Companies',
        currentPage: 1,
        perPage: 5,
        // Örnek veri seti (Normalde backend'den gelir)
        companies: [
            { id: '01', name: 'TechNova Solutions', short: 'TN', sector: 'Software', status: 'Active' },
            { id: '02', name: 'Global Logistics', short: 'GL', sector: 'Transport', status: 'Active' },
            { id: '03', name: 'Quantum Cyber', short: 'QC', sector: 'Security', status: 'Active' },
            { id: '04', name: 'Solaris Energy', short: 'SE', sector: 'Energy', status: 'Active' },
            { id: '05', name: 'Apex Design', short: 'AD', sector: 'Design', status: 'Active' },
            { id: '06', name: 'Blue Horizon', short: 'BH', sector: 'Tourism', status: 'Active' },
            { id: '07', name: 'Nexus Core', short: 'NC', sector: 'Tech', status: 'Active' }
        ],
        get totalPages() { return Math.ceil(this.companies.length / this.perPage); },
        get paginatedCompanies() {
            let start = (this.currentPage - 1) * this.perPage;
            let end = start + this.perPage;
            return this.companies.slice(start, end);
        }
      }">

    <aside :class="sidebarCollapsed ? 'w-20' : 'w-72'" class="border-r border-slate-800/40 bg-slate-950/40 backdrop-blur-xl flex flex-col transition-all duration-300 relative z-20">
        <div class="p-6 flex items-center gap-3" :class="sidebarCollapsed ? 'justify-center' : ''">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
            <span x-show="!sidebarCollapsed" class="text-xl font-bold tracking-tight whitespace-nowrap">TASK ORBIT</span>
        </div>

        <div class="px-4 mb-4 flex justify-end">
            <button @click="sidebarCollapsed = !sidebarCollapsed" class="p-2 rounded-lg hover:bg-slate-800/40 text-slate-500 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" :class="sidebarCollapsed ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line></svg>
            </button>
        </div>

        <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
            <p x-show="!sidebarCollapsed" class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-500 mb-4">Ana Menü</p>
            <a href="/admin-action-panel" :title="sidebarCollapsed ? 'Dashboard' : ''" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-400 hover:bg-slate-800/40 hover:text-white transition-all" :class="sidebarCollapsed ? 'justify-center' : ''">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                <span x-show="!sidebarCollapsed">Dashboard</span>
            </a>
            <a href="/companies" :title="sidebarCollapsed ? 'Companies' : ''" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold bg-blue-600 text-white shadow-lg shadow-blue-600/20" :class="sidebarCollapsed ? 'justify-center' : ''">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                <span x-show="!sidebarCollapsed">Companies</span>
            </a>
            <a href="#" :title="sidebarCollapsed ? 'Settings' : ''" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-400 hover:bg-slate-800/40 hover:text-white transition-all" :class="sidebarCollapsed ? 'justify-center' : ''">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                <span x-show="!sidebarCollapsed">Settings</span>
            </a>
        </nav>

        <div class="p-6 border-t border-slate-800/40">
            <a href="/admin" :title="sidebarCollapsed ? 'Log Out' : ''" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-red-400 hover:bg-red-500/10 transition-all" :class="sidebarCollapsed ? 'justify-center' : ''">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                <span x-show="!sidebarCollapsed">Log Out</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-20 border-b border-slate-800/40 bg-slate-950/20 backdrop-blur-md flex items-center justify-between px-8">
            <div class="text-sm font-bold text-slate-500 uppercase tracking-widest">Company Management</div>
            <div class="flex items-center gap-6">
                <button class="relative h-10 w-10 flex items-center justify-center rounded-xl hover:bg-slate-800/40 transition-all text-slate-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    <span class="absolute top-2 right-2 h-2 w-2 bg-blue-600 rounded-full border-2 border-[#020617]"></span>
                </button>
                <div class="h-8 w-[1px] bg-slate-800/40"></div>
                <div class="relative group cursor-pointer flex items-center gap-3 pl-4 pr-2 py-1.5 rounded-2xl hover:bg-slate-800/30 transition-all border border-transparent hover:border-slate-800/40 max-w-[240px]">
                    <div class="text-right hidden sm:block min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-100 group-hover:text-blue-400 transition-colors truncate">AAA BBBB (Admin)</p>
                        <p class="text-[10px] font-bold text-blue-500/80 uppercase tracking-widest truncate">Süper Yönetici</p>
                    </div>
                    <div class="relative flex-shrink-0">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-600 flex items-center justify-center font-bold text-sm shadow-lg shadow-blue-500/30 border border-white/10 text-white">AB</div>
                        <div class="absolute -bottom-1 -right-1 h-3.5 w-3.5 bg-emerald-500 rounded-full border-2 border-[#020617] shadow-sm"></div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500 group-hover:text-slate-300 transition-colors flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>
        </header>

        <div class="flex-1 p-8 overflow-y-auto space-y-8">
            <div class="flex items-center justify-between">
                <h1 class="text-4xl font-extrabold tracking-tight">Companies</h1>
                <button class="h-12 px-8 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-blue-600/20 bg-blue-600 hover:bg-blue-500 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4" /></svg>
                    Add Company
                </button>
            </div>

            <div class="flex items-center gap-4 border-b border-slate-800/40 pb-1">
                <template x-for="tab in ['Active Companies', 'Pending Approval', 'Archived']">
                    <button @click="activeTab = tab; currentPage = 1" :class="activeTab === tab ? 'text-blue-500' : 'text-slate-500 hover:text-slate-300'" class="px-4 py-3 text-sm font-bold transition-all relative">
                        <span x-text="tab"></span>
                        <div x-show="activeTab === tab" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-500"></div>
                    </button>
                </template>
            </div>

            <div class="flex items-center justify-between">
                <div class="text-lg font-bold" x-text="activeTab"></div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 bg-slate-900/40 border border-slate-800/40 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-blue-500/40 text-slate-200 w-64">
                    </div>
                    <button class="flex items-center gap-2 px-4 py-2 bg-slate-900/40 border border-slate-800/40 rounded-xl text-sm font-bold text-slate-400 hover:bg-slate-800/40 hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                        Filter
                    </button>
                </div>
            </div>

            <div class="bg-slate-900/20 border border-slate-800/40 backdrop-blur-xl rounded-[2rem] overflow-hidden shadow-xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800/40 bg-slate-950/40">
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">No</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Company Name</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Sector</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Status</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/20">
                        <template x-for="company in paginatedCompanies" :key="company.id">
                            <tr class="hover:bg-slate-800/20 transition-colors group">
                                <td class="px-8 py-5 text-sm font-medium text-slate-500" x-text="company.id"></td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center text-[10px] font-bold border border-blue-600/20" x-text="company.short"></div>
                                        <span class="text-sm font-bold" x-text="company.name"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-400" x-text="company.sector"></td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-500" x-text="company.status"></span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button class="p-2 rounded-lg hover:bg-blue-500/10 text-blue-500 transition-all" title="Update">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </button>
                                        <button class="p-2 rounded-lg hover:bg-red-500/10 text-red-500 transition-all" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <div class="px-8 py-5 border-t border-slate-800/40 bg-slate-950/40 flex items-center justify-between">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                        Showing <span class="text-slate-300" x-text="((currentPage - 1) * perPage) + 1"></span> to
                        <span class="text-slate-300" x-text="Math.min(currentPage * perPage, companies.length)"></span> of
                        <span class="text-slate-300" x-text="companies.length"></span> entries
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            @click="currentPage > 1 ? currentPage-- : null"
                            :disabled="currentPage === 1"
                            class="p-2 rounded-lg border border-slate-800/40 hover:bg-slate-800/40 text-slate-400 disabled:opacity-30 disabled:cursor-not-allowed transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                        </button>

                        <template x-for="page in totalPages" :key="page">
                            <button
                                @click="currentPage = page"
                                :class="currentPage === page ? 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-600/20' : 'border-slate-800/40 text-slate-400 hover:bg-slate-800/40'"
                                class="h-8 w-8 rounded-lg border text-xs font-bold transition-all"
                                x-text="page">
                            </button>
                        </template>

                        <button
                            @click="currentPage < totalPages ? currentPage++ : null"
                            :disabled="currentPage === totalPages"
                            class="p-2 rounded-lg border border-slate-800/40 hover:bg-slate-800/40 text-slate-400 disabled:opacity-30 disabled:cursor-not-allowed transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
