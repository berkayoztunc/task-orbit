<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel - Task Orbit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        .bg-navy-deep { background-color: #020617; }
        .glass { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(51, 65, 85, 0.4); }
        .glass-card { background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(24px); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="bg-navy-deep text-slate-50 flex h-screen overflow-hidden" x-data="{
    sidebarOpen: true,
    currentTab: 'companies',
    selectedCompany: null,
    applyModalOpen: false,
    internshipDetailModalOpen: false,
    selectedInternship: null,
    applyForm: { name: '', email: '', phone: '', cv: '', coverLetter: '' },
    get allInternships() {
        return this.companies.flatMap(c => c.internships.map(i => ({...i, companyName: c.name, companyTag: c.tag, companyColor: c.color, compDesc: c.desc})));
    },
    companies: [
        {
            id: 'technova',
            name: 'TechNova Solutions',
            tag: 'TN',
            color: 'blue',
            desc: 'Yapay zeka ve bulut bilişim çözümleri üzerine uzmanlaşmış teknoloji öncüsü.',
            detail: 'TechNova, yapay zeka ve bulut sistemleri üzerine yoğunlaşan global bir teknoloji firmasıdır.',
            employees: [
                { name: 'Ahmet Yılmaz', role: 'Senior AI Engineer', initial: 'A' },
                { name: 'Ceren Demir', role: 'Cloud Architect', initial: 'C' }
            ],
            internships: [
                { id: 1, title: 'Frontend Developer Intern', location: 'Hibrit', duration: '3 Ay', requirements: 'React, Tailwind, AlpineJS', type: 'Popular' },
                { id: 2, title: 'Backend Developer Intern', location: 'Uzaktan', duration: '6 Ay', requirements: 'Node.js, Postgres', type: 'New' }
            ]
        },
        {
            id: 'globalsoft',
            name: 'Global Soft',
            tag: 'GS',
            color: 'emerald',
            desc: 'Kurumsal yazılım ve e-ticaret altyapıları geliştiren köklü firma.',
            detail: 'Global Soft, Fortune 500 şirketlerine özel ERP sistemleri ve e-ticaret altyapıları sunmaktadır.',
            employees: [
                { name: 'Mehmet Kaya', role: 'Product Manager', initial: 'M' },
                { name: 'Elif Ak', role: 'Backend Lead', initial: 'E' }
            ],
            internships: [
                { id: 3, title: 'UI/UX Design Intern', location: 'Ankara • Ofis', duration: 'Yaz Dönemi', requirements: 'Figma, Adobe XD', type: '' }
            ]
        },
        {
            id: 'orbitlab',
            name: 'Orbit Lab',
            tag: 'OL',
            color: 'purple',
            desc: 'Mobil oyunlar ve etkileşimli deneyimler tasarlayan yaratıcı stüdyo.',
            detail: 'Orbit Lab, mobil platformlarda milyonlarca oyuncuya ulaşan hyper-casual oyunlar üretir.',
            employees: [
                { name: 'Selin Arı', role: 'Game Designer', initial: 'S' },
                { name: 'Can Öz', role: 'Unity Developer', initial: 'C' }
            ],
            internships: [
                { id: 4, title: 'Frontend Intern', location: 'Online • Full Time', duration: '6 Ay', requirements: 'React, WebGL, UI', type: 'New' }
            ]
        }
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
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </div>
            <span x-show="sidebarOpen" class="text-xl font-black tracking-tight whitespace-nowrap">TASK ORBIT</span>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-8 overflow-y-auto">
            <button @click="currentTab = 'companies'; selectedCompany = null"
                :class="currentTab === 'companies' && !selectedCompany ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white'"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold">Şirketler</span>
            </button>
            <button @click="currentTab = 'internships'; selectedCompany = null"
                :class="currentTab === 'internships' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/40 hover:text-white'"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span x-show="sidebarOpen" class="text-sm font-bold">İlanlar</span>
            </button>
        </nav>

        <!-- Alt Profil -->
        <div class="p-4 border-t border-slate-800/40">
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-900/40 border border-slate-800/60">
                <div class="h-9 w-9 shrink-0 rounded-xl bg-blue-600 flex items-center justify-center font-bold text-xs text-white">A</div>
                <div x-show="sidebarOpen" class="min-w-0">
                    <p class="text-sm font-bold truncate">AAA</p>
                    <p class="text-[10px] text-slate-500 font-medium">Intern @ TechNova</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col min-w-0 overflow-hidden relative z-10">
        <header class="h-20 border-b border-slate-800/40 bg-slate-950/20 backdrop-blur-md flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl hover:bg-slate-800/40 text-slate-400 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                </button>
                <h2 class="text-lg font-bold" x-text="selectedCompany ? selectedCompany.name : 'Şirketler Ağımız'"></h2>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 space-y-8 scroll-smooth">

            <!-- Şirketler Listesi (Ana Görünüm) -->
            <div x-show="currentTab === 'companies' && !selectedCompany" x-transition>
                <div class="space-y-4 mb-8">
                    <h1 class="text-4xl font-black text-white tracking-tighter">Şirketleri Keşfet</h1>
                    <p class="text-lg text-slate-400 font-medium max-w-2xl">Hayalindeki kariyere giden yolda ilk adım. Şirketleri incele, ilanlara başvur ve ekibe katıl.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <template x-for="c in companies">
                        <div @click="selectedCompany = c; currentTab = 'company-detail'" class="glass-card rounded-[2.5rem] p-8 border border-white/5 hover:border-white/10 transition-all cursor-pointer group flex flex-col justify-between h-full shadow-2xl hover:-translate-y-1">
                            <div class="flex items-start justify-between mb-6">
                                <div :class="`bg-${c.color}-600/10 text-${c.color}-400 shadow-inner`" class="h-20 w-20 rounded-2xl flex items-center justify-center font-black text-3xl group-hover:scale-110 transition-transform" x-text="c.tag"></div>
                                <div class="px-4 py-1.5 rounded-full bg-slate-800 border border-slate-700 text-[10px] font-black text-slate-400 uppercase tracking-widest" x-text="`${c.internships.length} İLAN`"></div>
                            </div>
                            <div class="space-y-2">
                                <h3 class="text-2xl font-black transition-colors" :class="`text-${c.color}-500 group-hover:text-${c.color}-400`" x-text="c.name"></h3>
                                <p class="text-sm font-medium text-slate-500 line-clamp-2" x-text="c.desc"></p>
                            </div>
                            <div class="pt-6 mt-6 border-t border-white/5">
                                <button :class="`hover:bg-${c.color}-600 hover:border-${c.color}-500 text-slate-300 hover:text-white`" class="w-full h-12 rounded-xl bg-white/5 border border-white/10 font-black text-sm uppercase tracking-widest transition-all flex items-center justify-center gap-2 group-hover:bg-slate-800">
                                    İncele
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Tüm İlanlar Listesi -->
            <div x-show="currentTab === 'internships'" x-transition class="space-y-6 max-w-6xl mx-auto">
                <div class="space-y-4 mb-8">
                    <h1 class="text-4xl font-black text-white tracking-tighter">Açık İlanlar</h1>
                    <p class="text-lg text-slate-400 font-medium max-w-2xl">Sana en uygun staj ilanlarını detaylı olarak inceleyip hemen başvurunu yapabilirsin.</p>
                </div>

                <div class="space-y-4">
                    <template x-for="internship in allInternships">
                        <div class="glass-card p-6 md:p-8 rounded-[2.5rem] border border-white/5 hover:border-slate-600 transition-all group">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div class="flex items-center gap-6">
                                    <div :class="`bg-${internship.companyColor}-600/10 text-${internship.companyColor}-400 shadow-inner`" class="h-16 w-16 rounded-2xl flex items-center justify-center font-black text-2xl" x-text="internship.companyTag"></div>
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-3">
                                            <h4 class="text-xl font-black text-white transition-colors" :class="`group-hover:text-${internship.companyColor}-400`" x-text="internship.title"></h4>
                                            <template x-if="internship.type">
                                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest"
                                                      :class="internship.type === 'Popular' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20'"
                                                      x-text="internship.type"></span>
                                            </template>
                                        </div>
                                        <p class="text-xs font-black text-slate-500 uppercase tracking-widest" x-text="`${internship.companyName} • ${internship.location} • ${internship.duration}`"></p>
                                    </div>
                                </div>
                                <button @click="selectedInternship = internship; internshipDetailModalOpen = true" :class="`hover:bg-${internship.companyColor}-600 hover:border-${internship.companyColor}-500`" class="h-14 px-8 rounded-2xl bg-white/5 border border-white/10 text-white font-black text-sm uppercase tracking-widest transition-all whitespace-nowrap">İncele</button>
                            </div>
                        </div>
                    </template>
                    <template x-if="!allInternships.length">
                        <div class="p-8 rounded-[2.5rem] border-2 border-dashed border-white/10 text-center">
                            <p class="text-slate-500 font-bold uppercase tracking-widest">Şu an aktif bir ilan bulunmuyor.</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Şirket Detay (Company Detail) -->
            <div x-show="currentTab === 'company-detail' && selectedCompany" x-transition class="space-y-10 max-w-6xl mx-auto">
                <button @click="selectedCompany = null; currentTab = 'companies'" class="flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-white transition-colors mb-4 uppercase tracking-widest">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                    Şirketlere Dön
                </button>

                <div class="glass-card rounded-[3rem] p-12 relative overflow-hidden flex flex-col md:flex-row items-center gap-10 shadow-3xl border-white/5">
                    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full blur-[100px] opacity-20 pointer-events-none" :class="`bg-${selectedCompany?.color}-500`"></div>

                    <div :class="`bg-${selectedCompany?.color}-600/10 text-${selectedCompany?.color}-400`" class="h-32 w-32 shrink-0 rounded-[2rem] flex items-center justify-center font-black text-5xl shadow-2xl relative z-10" x-text="selectedCompany?.tag"></div>
                    <div class="relative z-10 text-center md:text-left space-y-4">
                        <h1 class="text-5xl font-black tracking-tighter" :class="`text-${selectedCompany?.color}-500`" x-text="selectedCompany?.name"></h1>
                        <p class="text-xl text-slate-400 font-medium leading-relaxed max-w-3xl" x-text="selectedCompany?.detail"></p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Açık İlanlar -->
                    <div class="space-y-6">
                        <h3 class="text-2xl font-black text-white uppercase tracking-tighter flex items-center gap-3">
                            <span class="h-8 w-8 rounded-lg bg-blue-600/20 text-blue-500 flex items-center justify-center">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </span>
                            Açık Pozisyonlar
                        </h3>
                        <div class="space-y-4">
                            <template x-for="internship in selectedCompany?.internships">
                                <div class="glass-card p-6 md:p-8 rounded-[2.5rem] border border-white/5 hover:border-slate-600 transition-all group">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                        <div class="space-y-2">
                                            <div class="flex items-center gap-3">
                                                <h4 class="text-xl font-black text-white transition-colors" :class="`group-hover:text-${selectedCompany?.color}-400`" x-text="internship.title"></h4>
                                                <template x-if="internship.type">
                                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest"
                                                          :class="internship.type === 'Popular' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20'"
                                                          x-text="internship.type"></span>
                                                </template>
                                            </div>
                                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest" x-text="`${internship.location} • ${internship.duration}`"></p>
                                        </div>
                                        <button @click="selectedInternship = internship; applyModalOpen = true" :class="`hover:bg-${selectedCompany?.color}-600 hover:border-${selectedCompany?.color}-500`" class="h-14 px-8 rounded-2xl bg-white/5 border border-white/10 text-white font-black text-sm uppercase tracking-widest transition-all whitespace-nowrap">Başvur</button>
                                    </div>
                                    <div class="mt-6 pt-6 border-t border-white/5">
                                        <p class="text-sm font-bold text-slate-400" x-text="internship.requirements"></p>
                                    </div>
                                </div>
                            </template>
                            <template x-if="!selectedCompany?.internships?.length">
                                <div class="p-8 rounded-[2.5rem] border-2 border-dashed border-white/10 text-center">
                                    <p class="text-slate-500 font-bold uppercase tracking-widest">Şu an aktif bir ilan bulunmuyor.</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Apply Modal -->
            <div x-show="applyModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center px-4" x-transition.opacity>
                <!-- Backdrop -->
                <div @click="applyModalOpen = false" class="absolute inset-0 bg-[#020617]/80 backdrop-blur-sm"></div>

                <!-- Modal Content -->
                <div class="glass-card relative w-full max-w-2xl rounded-[3rem] p-10 border border-white/10 shadow-2xl overflow-hidden transform transition-all" x-transition.scale.95>
                    <!-- Close Button -->
                    <button @click="applyModalOpen = false" class="absolute top-6 right-6 h-12 w-12 flex items-center justify-center rounded-2xl bg-white/5 text-slate-400 hover:text-white hover:bg-rose-500/20 hover:border-rose-500/50 border border-transparent transition-all">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>

                    <div class="space-y-8">
                        <div>
                            <h2 class="text-3xl font-black text-white tracking-tighter" x-text="'Başvuru Formu'"></h2>
                            <p class="text-slate-400 mt-2 font-medium" x-text="selectedInternship ? selectedInternship.title : ''"></p>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Ad Soyad</label>
                                    <input type="text" x-model="applyForm.name" class="w-full h-14 bg-slate-900/50 border border-white/5 rounded-2xl px-6 outline-none focus:border-blue-500 transition-all font-bold text-white placeholder:text-slate-700" placeholder="Adınız">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">E-posta</label>
                                    <input type="email" x-model="applyForm.email" class="w-full h-14 bg-slate-900/50 border border-white/5 rounded-2xl px-6 outline-none focus:border-blue-500 transition-all font-bold text-white placeholder:text-slate-700" placeholder="E-posta">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Telefon Numarası</label>
                                <input type="tel" x-model="applyForm.phone" class="w-full h-14 bg-slate-900/50 border border-white/5 rounded-2xl px-6 outline-none focus:border-blue-500 transition-all font-bold text-white placeholder:text-slate-700" placeholder="+90">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Kısa Kendini Tanıt / Ön Yazı</label>
                                <textarea x-model="applyForm.coverLetter" class="w-full h-32 bg-slate-900/50 border border-white/5 rounded-2xl p-6 outline-none focus:border-blue-500 transition-all font-bold text-white placeholder:text-slate-700 resize-none" placeholder="Neden bu şirkette olmak istiyorsun? Öğrenmek istediklerinden veya yapabildiklerinden bahset."></textarea>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Özgeçmiş URL (LinkedIn / GitHub vb.)</label>
                                <input type="url" x-model="applyForm.cv" class="w-full h-14 bg-slate-900/50 border border-white/5 rounded-2xl px-6 outline-none focus:border-blue-500 transition-all font-bold text-white placeholder:text-slate-700" placeholder="https://">
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4 border-t border-white/5">
                            <button @click="applyModalOpen = false" class="flex-1 h-14 rounded-2xl border border-white/5 text-slate-400 font-black text-xs uppercase tracking-widest hover:bg-slate-800 transition-all">İptal</button>
                            <button @click="
                                alert('Başvurunuz başarıyla iletildi!');
                                applyForm = { name: '', email: '', phone: '', cv: '', coverLetter: '' };
                                applyModalOpen = false;
                            " class="flex-[2] h-14 rounded-2xl bg-blue-600 text-white font-black text-sm uppercase tracking-widest hover:bg-blue-500 transition-all active:scale-95 shadow-xl shadow-blue-500/20">Başvuruyu Gönder</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Internship Details Modal -->
            <div x-show="internshipDetailModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4" x-transition.opacity>
                <!-- Backdrop -->
                <div @click="internshipDetailModalOpen = false" class="absolute inset-0 bg-[#020617]/80 backdrop-blur-sm"></div>

                <!-- Modal Content -->
                <div class="glass-card relative w-full max-w-2xl rounded-[3rem] p-10 border border-white/10 shadow-3xl overflow-hidden transform transition-all" x-transition.scale.95>
                    <div class="absolute -top-32 -right-32 w-80 h-80 rounded-full blur-[80px] opacity-20 pointer-events-none" :class="`bg-${selectedInternship?.companyColor}-500`"></div>

                    <div class="space-y-8 relative z-10">
                        <div class="flex items-start gap-6">
                            <div :class="`bg-${selectedInternship?.companyColor}-600/10 text-${selectedInternship?.companyColor}-400 shadow-inner`" class="h-20 w-20 shrink-0 rounded-2xl flex items-center justify-center font-black text-3xl" x-text="selectedInternship?.companyTag"></div>
                            <div>
                                <h2 class="text-3xl font-black text-white tracking-tighter" x-text="selectedInternship?.title"></h2>
                                <p class="text-lg text-slate-400 font-bold" x-text="selectedInternship?.companyName"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-slate-900/40 border border-white/5 space-y-1">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Çalışma Modeli</p>
                                <p class="text-sm font-bold text-white" x-text="selectedInternship?.location"></p>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-900/40 border border-white/5 space-y-1">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Süre</p>
                                <p class="text-sm font-bold text-white" x-text="selectedInternship?.duration"></p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest border-b border-white/5 pb-2">Gereksinimler / Yetenekler</h3>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="req in (selectedInternship?.requirements || '').split(',').map(s=>s.trim())">
                                    <span class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-xs font-bold text-slate-300" x-text="req"></span>
                                </template>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest border-b border-white/5 pb-2">Şirket Hakkında</h3>
                            <p class="text-sm font-medium text-slate-400 leading-relaxed" x-text="selectedInternship?.compDesc"></p>
                        </div>

                        <div class="flex gap-4 pt-6 border-t border-white/5">
                            <button @click="internshipDetailModalOpen = false" class="flex-1 h-14 rounded-2xl border border-white/5 text-slate-400 font-black text-xs uppercase tracking-widest hover:bg-slate-800 transition-all">Kapat</button>
                            <button @click="internshipDetailModalOpen = false; setTimeout(() => applyModalOpen = true, 100)" class="flex-[2] h-14 rounded-2xl bg-blue-600 text-white font-black text-sm uppercase tracking-widest hover:bg-blue-500 transition-all active:scale-95 shadow-xl shadow-blue-500/20">Hemen Başvur</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
