<!-- DİKKAT: Bu kod lesson-monitor.blade.php içerisine konulacak -->

<div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
    <div class="flex items-center gap-4">
        <!-- Geri Butonu (Grup detaylarına geri döner) -->
        <button @click="currentTab = 'lesson-detail'" class="h-12 w-12 flex items-center justify-center rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all text-slate-400">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <div>
            <h2 class="text-4xl font-black text-white uppercase tracking-tighter">Canlı  Ders Takibi</h2>
            <div class="flex items-center gap-3 mt-1">
                <p class="text-sky-400 text-sm font-bold uppercase tracking-widest" x-text="'Ders: ' + selectedLesson?.title"></p>
                <div class="w-1.5 h-1.5 rounded-full bg-slate-600"></div>
                <p class="text-emerald-400 text-sm font-bold uppercase tracking-widest relative">
                    <span class="absolute inset-0 bg-emerald-400/20 blur-md rounded-full shadow-[0_0_15px_rgba(16,185,129,0.8)] animate-pulse"></span>
                    <span class="relative">Durum: Bildirim Gönderildi ⚡</span>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- SOL/ORTA PANEL: Canlı Katılımcı Tablosu -->
    <div class="lg:col-span-2 glass-panel rounded-[3rem] p-10 border-white/5 shadow-3xl">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-2xl font-black text-white tracking-tighter uppercase border-l-4 border-emerald-500 pl-4 h-8 flex items-center">Katılım   Listesi</h3>
            <div class="flex items-center gap-3 bg-emerald-500/10 px-4 py-2 rounded-xl border border-emerald-500/20 shadow-inner">
                <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse border border-emerald-400"></div>
                <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Katılım Durumları</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <!-- Alpine.js loop ile öğrencileri listele -->
            <template x-for="intern in filteredInterns" :key="intern.id">
                <div class="bg-slate-900/40 border border-white/5 p-5 rounded-2xl flex items-center justify-between hover:bg-slate-800/40 transition-colors">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center font-black text-xl text-white shadow-inner"
                             :class="`bg-${intern.color}-500/20 text-${intern.color}-400`" x-text="intern.avatar"></div>
                        <div>
                            <p class="font-black text-white text-lg leading-tight" x-text="intern.name"></p>
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest mt-1 font-bold" x-text="intern.role"></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-8">
                        <div class="text-right hidden sm:block">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Dönüş Saati</p>
                            <p class="text-sm font-bold text-white mt-1" x-text="intern.telegramTime"></p>
                        </div>

                        <!-- NEON Durum Rozetleri -->
                        <div x-show="intern.telegramStatus === 'confirm'" class="px-5 py-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                            <span class="text-xs font-black uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> EVET
                            </span>
                        </div>
                        <div x-show="intern.telegramStatus === 'reject'" class="px-5 py-2.5 rounded-xl bg-rose-500/10 border border-rose-500/50 text-rose-400 shadow-[0_0_15px_rgba(244,63,94,0.3)]">
                            <span class="text-xs font-black uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg> HAYIR
                            </span>
                        </div>
                        <div x-show="intern.telegramStatus === 'waiting'" class="px-5 py-2.5 rounded-xl bg-slate-800/50 border border-white/10 text-slate-400">
                            <span class="text-xs font-black uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> BEKLİYOR
                            </span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- SAĞ PANEL: Aksiyonlar & İstatistikler -->
    <div class="lg:col-span-1 space-y-8">
        <div class="glass-panel rounded-[3rem] p-10 border-sky-500/20 shadow-[0_0_50px_rgba(14,165,233,0.1)] relative overflow-hidden flex flex-col items-center">

            <div class="w-full space-y-6">
                <!-- Canlı İstatistikler -->
                <div class="w-full bg-slate-950/50 rounded-3xl p-8 border border-white/5 relative z-10 space-y-5">
                    <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-white/5 pb-4 mb-2">Canlı Katılım İstatistiği</h4>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 text-xs font-bold uppercase tracking-widest">Kayıtlı Öğrenci</span>
                        <span class="text-white font-black text-xl" x-text="filteredInterns.length"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-emerald-400 text-xs font-bold uppercase tracking-widest">Katılacaklar</span>
                        <div class="relative">
                             <div class="absolute inset-0 bg-emerald-500 blur-lg opacity-40"></div>
                             <span class="text-emerald-400 font-black text-xl relative" x-text="filteredInterns.filter(i => i.telegramStatus === 'confirm').length"></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-rose-400 text-xs font-bold uppercase tracking-widest">Katılmayacaklar</span>
                        <span class="text-rose-400 font-black text-xl" x-text="filteredInterns.filter(i => i.telegramStatus === 'reject').length"></span>
                    </div>
                </div>

                <!-- Aksiyon Butonları -->
                <div class="space-y-4 pt-4 border-t border-white/5 relative z-10 w-full">
                    <!-- Neon Yeşil Onay -->
                    <button class="w-full h-16 bg-emerald-500/10 border border-emerald-500 text-emerald-400 hover:bg-emerald-500 hover:text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:shadow-[0_0_30px_rgba(16,185,129,0.6)] transition-all flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Dersi Onayla
                    </button>

                    <!-- Neon Kırmızı İptal -->
                    <button class="w-full h-16 bg-rose-500/10 border border-rose-500 text-rose-400 hover:bg-rose-600 hover:text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-[0_0_20px_rgba(244,63,94,0.3)] hover:shadow-[0_0_30px_rgba(244,63,94,0.6)] transition-all flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Dersi İptal Et
                    </button>

                    <div class="flex items-center gap-4 py-4 w-full">
                        <div class="flex-1 h-px bg-white/5"></div>
                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">VE YA</span>
                        <div class="flex-1 h-px bg-white/5"></div>
                    </div>

                    <!-- Telegram Aç -->
                    <button onclick="window.open('https://web.telegram.org', '_blank')" class="w-full h-16 bg-sky-500 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-[0_0_25px_rgba(14,165,233,0.4)] hover:bg-sky-400 transition-all flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.11.02-1.93 1.23-5.46 3.62-.51.35-.98.52-1.4.51-.46-.01-1.35-.26-2.01-.48-.81-.27-1.44-.42-1.39-.89.03-.24.35-.49.96-.75 3.76-1.63 6.27-2.71 7.52-3.23 3.58-1.48 4.32-1.74 4.81-1.75.11 0 .35.03.5.16.13.1.17.24.18.34z"/></svg>
                        Telegram Grubunu Aç
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>
