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
    </style>
</head>
<body class="bg-[#020617] text-slate-50 flex h-screen overflow-hidden"
      x-data="{
        sidebarCollapsed: false,
        activeTab: 'Active Companies',
        currentPage: 1,
        perPage: 5,
        companies: [],

        async init() {
            try {
                const response = await fetch('/api/companies');
                this.companies = await response.json();
            } catch (error) {
                console.error('Veri yüklenemedi:', error);
            }
        },

        get totalPages() {
            return Math.ceil(this.companies.length / this.perPage) || 1;
        },

        get paginatedCompanies() {
            let start = (this.currentPage - 1) * this.perPage;
            let end = start + this.perPage;
            return this.companies.slice(start, end);
        }
      }">

    @include('sidebar') <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-20 border-b border-slate-800/40 bg-slate-950/20 backdrop-blur-md flex items-center justify-between px-8">
             <div class="text-sm font-bold text-slate-500 uppercase tracking-widest">Company Management</div>
             </header>

        <div class="flex-1 p-8 overflow-y-auto space-y-8">
            <div class="flex items-center justify-between">
                <h1 class="text-4xl font-extrabold tracking-tight">Companies</h1>
                <button class="h-12 px-8 rounded-xl font-bold flex items-center gap-2 bg-blue-600 hover:bg-blue-500 transition-all">
                    Add Company
                </button>
            </div>

            <div class="bg-slate-900/20 border border-slate-800/40 backdrop-blur-xl rounded-[2rem] overflow-hidden shadow-xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800/40 bg-slate-950/40">
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-500">ID</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-500">Company Name</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-500">Status</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/20">
                        <template x-for="company in paginatedCompanies" :key="company.id">
                            <tr class="hover:bg-slate-800/20 transition-colors group">
                                <td class="px-8 py-5 text-sm font-medium text-slate-500" x-text="company.id"></td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center text-[10px] font-bold" x-text="company.title.substring(0,2).toUpperCase()"></div>
                                        <span class="text-sm font-bold" x-text="company.title"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-500">Active</span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <button class="text-blue-500 p-2">Edit</button>
                                    <button class="text-red-500 p-2">Delete</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <div class="px-8 py-5 border-t border-slate-800/40 flex items-center justify-between">
                    <div class="text-xs text-slate-500">
                        Total: <span x-text="companies.length"></span> Companies
                    </div>
                    <div class="flex gap-2">
                        <button @click="currentPage--" :disabled="currentPage === 1" class="disabled:opacity-20">Prev</button>
                        <button @click="currentPage++" :disabled="currentPage === totalPages" class="disabled:opacity-20">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
