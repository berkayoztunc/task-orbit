<aside :class="sidebarCollapsed ? 'w-20' : 'w-72'"
       class="border-r border-slate-800/40 bg-slate-950/40 backdrop-blur-xl flex flex-col transition-all duration-300 relative z-20">

    <div class="p-6 flex items-center gap-3" :class="sidebarCollapsed ? 'justify-center' : ''">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
        <span x-show="!sidebarCollapsed" class="text-xl font-bold tracking-tight whitespace-nowrap">TASK ORBIT</span>
    </div>

    <div class="px-4 mb-4 flex justify-end">
        <button @click="sidebarCollapsed = !sidebarCollapsed" class="p-2 rounded-lg hover:bg-slate-800/40 text-slate-500 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" :class="sidebarCollapsed ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="9" y1="3" x2="9" y2="21"></line>
            </svg>
        </button>
    </div>

    <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
        <p x-show="!sidebarCollapsed" class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-500 mb-4">Ana Menü</p>

        <a href="/admin-action-panel"
           :title="sidebarCollapsed ? 'Dashboard' : ''"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold {{ request()->is('admin-action-panel') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white' }} transition-all"
           :class="sidebarCollapsed ? 'justify-center' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                <path d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>
            <span x-show="!sidebarCollapsed">Dashboard</span>
        </a>

        <a href="/companies"
           :title="sidebarCollapsed ? 'Companies' : ''"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold {{ request()->is('companies') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white' }} transition-all"
           :class="sidebarCollapsed ? 'justify-center' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span x-show="!sidebarCollapsed">Companies</span>
        </a>

        <a href="#"
           :title="sidebarCollapsed ? 'Settings' : ''"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-400 hover:bg-slate-800/40 hover:text-white transition-all"
           :class="sidebarCollapsed ? 'justify-center' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span x-show="!sidebarCollapsed">Settings</span>
        </a>
    </nav>

    <div class="p-6 border-t border-slate-800/40">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit"
                    :title="sidebarCollapsed ? 'Log Out' : ''"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-red-400 hover:bg-red-500/10 transition-all"
                    :class="sidebarCollapsed ? 'justify-center' : ''">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span x-show="!sidebarCollapsed">Log Out</span>
            </button>
        </form>
    </div>
</aside>
