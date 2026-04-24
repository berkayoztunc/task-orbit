<!-- resources/views/internshipapplication.blade.php -->

<!-- 1. Şirket Kısa Özet Modalı (İncele'ye basınca gelen) -->
<div x-show="selectedCompany"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
     @click.self="selectedCompany = null">

    <div x-show="selectedCompany"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         class="glass w-full max-w-2xl rounded-[3rem] overflow-hidden shadow-2xl relative">

        <div class="h-48 relative overflow-hidden">
            <div :class="`absolute inset-0 bg-${selectedCompany?.color}-600/20`" class="bg-blue-600/20"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div :class="`h-24 w-24 rounded-3xl bg-${selectedCompany?.color}-600/20 border-2 border-${selectedCompany?.color}-500/40 flex items-center justify-center`" class="h-24 w-24 rounded-3xl flex items-center justify-center">
                    <span class="text-4xl font-black" :class="`text-${selectedCompany?.color}-500`" x-text="selectedCompany?.tag"></span>
                </div>
            </div>
            <button @click="selectedCompany = null" class="absolute top-6 right-6 p-2 rounded-full bg-slate-900/50 hover:bg-slate-800 text-slate-400 hover:text-white transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="p-12 space-y-6">
            <div class="text-center">
                <h3 class="text-3xl font-black text-white mb-2" x-text="selectedCompany?.name"></h3>
                <p class="text-slate-400 font-medium" x-text="selectedCompany?.desc"></p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-800/60">
                <p class="text-slate-300 leading-relaxed" x-text="selectedCompany?.detail"></p>
            </div>

            <div class="flex flex-col gap-4 pt-4">
                <button @click="viewingCompanyDetails = selectedCompany; selectedCompany = null" class="w-full py-4 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-center shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center gap-2 group">
                    Ayrıntıları Görüntüle
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </button>
                <button @click="selectedCompany = null" class="w-full py-4 rounded-2xl border border-slate-800 text-slate-500 font-bold hover:bg-slate-800 hover:text-white transition-all">
                    Kapat
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Şirket Detay Sayfası (Tam Ekran Overlay) -->
<div x-show="viewingCompanyDetails"
     x-cloak
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="opacity-0 translate-x-full"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 -translate-x-full"
     class="fixed inset-0 z-[110] bg-[#020617] overflow-y-auto p-8 md:p-12">

    <div class="max-w-6xl mx-auto space-y-12">
        <div class="flex items-center justify-between">
            <button @click="viewingCompanyDetails = null" class="text-slate-400 hover:text-white flex items-center gap-2 font-bold transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                Geri Dön
            </button>
            <div class="flex items-center gap-3">
                <div :class="`h-10 w-10 rounded-xl bg-${viewingCompanyDetails?.color}-600/10 text-${viewingCompanyDetails?.color}-500 flex items-center justify-center font-black`" x-text="viewingCompanyDetails?.tag"></div>
                <span class="text-xl font-bold text-white" x-text="viewingCompanyDetails?.name"></span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="glass p-10 rounded-[3rem] space-y-6">
                    <h1 class="text-4xl font-black text-white">Şirket Hakkında</h1>
                    <p class="text-xl text-slate-400 leading-relaxed" x-text="viewingCompanyDetails?.detail"></p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 pt-8">
                        <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-800/60 text-center transition-all hover:border-blue-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            <p class="text-2xl font-bold text-white">500+</p>
                            <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Çalışan</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-800/60 text-center transition-all hover:border-emerald-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                            <p class="text-2xl font-bold text-white">12</p>
                            <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Ülke</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-800/60 text-center transition-all hover:border-amber-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            <p class="text-2xl font-bold text-white">150+</p>
                            <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Proje</p>
                        </div>
                    </div>
                </div>

                <div class="glass p-10 rounded-[3rem] space-y-6">
                    <h2 class="text-2xl font-bold text-white">Aktif Staj Programları</h2>
                    <div class="space-y-4">
                        <template x-for="job in ['Frontend Development', 'Backend Systems', 'UI/UX Design']" :key="job">
                            <div class="flex items-center justify-between p-6 rounded-2xl bg-slate-900/40 border border-slate-800/60 hover:border-blue-500/40 transition-all group cursor-pointer">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-white group-hover:text-blue-400 transition-colors" x-text="job"></p>
                                        <p class="text-xs text-slate-500">Tam Zamanlı • Uzaktan/Hibrit</p>
                                    </div>
                                </div>
                                <button @click="viewingInternship = { title: job, detail: 'Seçilen staj programı kapsamında modern teknolojilerle gerçek dünya projelerinde yer alacak, sektörün öncü isimlerinden mentorluk desteği alacaksınız.' }" class="text-blue-500 font-bold hover:underline">Başvur</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="glass p-8 rounded-[3rem] space-y-6">
                    <h3 class="text-xl font-bold text-white">İletişim Bilgileri</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <span class="text-sm" x-text="`hr@${viewingCompanyDetails?.id}.com`"></span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                            <span class="text-sm" x-text="`www.${viewingCompanyDetails?.id}.com`"></span>
                        </div>
                    </div>
                    <button class="w-full py-6 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-bold transition-all shadow-lg shadow-blue-600/20">Şirketi Takip Et</button>
                </div>

                <div class="glass p-8 rounded-[3rem] space-y-6">
                    <h3 class="text-xl font-bold text-white">Lokasyon</h3>
                    <div class="h-48 rounded-2xl bg-slate-900/60 border border-slate-800/60 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 opacity-20 bg-[url('https://www.google.com/maps/vt/pb=!1m4!1m3!1i12!2i2345!3i1234!2m3!1e0!2sm!3i420120488!3m8!2sen!3sus!5e1105!12m4!1e68!2m2!1sset!2sRoadmap!4e0!5m1!5f2')] bg-cover"></div>
                        <div class="relative z-10 flex flex-col items-center gap-2">
                            <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center animate-bounce">
                                <div class="h-2 w-2 rounded-full bg-white"></div>
                            </div>
                            <span class="text-xs font-bold text-white bg-slate-900/80 px-3 py-1 rounded-full backdrop-blur-sm">İstanbul, Türkiye</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. Staj Detay Sayfası -->
<div x-show="viewingInternship"
     x-cloak
     x-transition:enter="transition ease-out duration-500 transform"
     x-transition:enter-start="translate-y-full"
     x-transition:enter-end="translate-y-0"
     x-transition:leave="transition ease-in duration-300 transform"
     x-transition:leave-start="translate-y-0"
     x-transition:leave-end="translate-y-full"
     class="fixed inset-0 z-[120] bg-[#020617] overflow-y-auto p-8 md:p-12">
    <div class="max-w-4xl mx-auto space-y-12 pb-24">
        <button @click="viewingInternship = null" class="text-slate-400 hover:text-white flex items-center gap-2 font-bold transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            Geri Dön
        </button>
        <div class="space-y-8">
            <div class="flex items-center gap-4">
                <span class="px-4 py-1.5 rounded-full bg-blue-600/10 text-blue-500 text-[10px] font-black uppercase tracking-widest">Aktif İlan Detayı</span>
            </div>
            <h1 class="text-6xl font-black text-white leading-tight" x-text="viewingInternship?.title"></h1>
            <div class="glass p-10 rounded-[3rem] space-y-8">
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-blue-500 uppercase tracking-widest">Program Açıklaması</h3>
                    <p class="text-xl text-slate-300 leading-relaxed font-medium" x-text="viewingInternship?.detail"></p>
                </div>
                <div class="pt-6 border-t border-white/5 space-y-4">
                    <h3 class="text-xl font-bold text-blue-500 uppercase tracking-widest">Neler Bekliyoruz?</h3>
                    <ul class="space-y-3 text-slate-400">
                         <li class="flex items-center gap-3"><svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Temel algoritma ve programlama mantığı bilgisi</li>
                         <li class="flex items-center gap-3"><svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Öğrenmeye meraklı ve takım çalışmasına yatkın</li>
                         <li class="flex items-center gap-3"><svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Haftada en az 3 gün katılım sağlayabilecek</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Hemen Başvur Butonu -->
        <button @click="showApplicationForm = true" class="w-full py-8 bg-blue-600 hover:bg-blue-500 text-white font-black text-2xl rounded-3xl shadow-2xl shadow-blue-600/30 transition-all active:scale-95 flex items-center justify-center gap-4">
            HEMEN BAŞVUR
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </button>
    </div>
</div>

<!-- 4. Başvuru Formu Modalı -->
<div x-show="showApplicationForm"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[130] flex items-center justify-center p-6 bg-slate-950/90 backdrop-blur-xl">
    <div class="glass w-full max-w-2xl rounded-[3rem] p-12 space-y-10 shadow-2xl" @click.away="showApplicationForm = false">
        <div class="text-center space-y-2">
            <h2 class="text-4xl font-black text-white tracking-tighter">BAŞVURU FORMU</h2>
            <p class="text-slate-500 font-medium">Lütfen bilgilerinizi eksiksiz doldurun.</p>
        </div>
        <form class="space-y-6">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Ad Soyad</label>
                <input type="text" x-model="form.name" placeholder="Adınızı girin" class="w-full h-16 bg-slate-900/50 border border-slate-800 rounded-2xl px-6 outline-none focus:border-blue-500 transition-all font-bold text-white placeholder:text-slate-700">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">E-posta</label>
                <input type="email" x-model="form.email" placeholder="E-posta adresinizi girin" class="w-full h-16 bg-slate-900/50 border border-slate-800 rounded-2xl px-6 outline-none focus:border-blue-500 transition-all font-bold text-white placeholder:text-slate-700">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Kısaca Kendinizden Bahsedin</label>
                <textarea x-model="form.message" placeholder="Mesajınız..." class="w-full min-h-[120px] bg-slate-900/50 border border-slate-800 rounded-2xl p-6 outline-none focus:border-blue-500 transition-all font-bold text-white placeholder:text-slate-700"></textarea>
            </div>
            <div class="flex items-center gap-4 pt-6">
                <button type="button" @click="showApplicationForm = false" class="flex-1 h-16 rounded-2xl border border-slate-800 text-slate-500 font-bold hover:bg-slate-800 hover:text-white transition-all uppercase text-sm tracking-widest">İptal</button>
                <button type="button"
                        @click="alert('Başvurunuz başarıyla alındı! Teşekkür ederiz.'); showApplicationForm = false; viewingInternship = null; form = {name: '', email: '', message: ''}"
                        :disabled="!form.name || !form.email"
                        :class="form.name && form.email ? 'bg-blue-600 hover:bg-blue-500 shadow-blue-500/50 scale-100 opacity-100' : 'bg-blue-900/40 text-blue-500/40 cursor-not-allowed opacity-50 scale-95'"
                        class="flex-[2] h-16 rounded-2xl text-white font-black text-xl shadow-xl transition-all active:scale-95 flex items-center justify-center gap-2 group">
                    GÖNDER
                    <svg class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Arka Plan Parlamaları (Tüm sayfalar için ortak) -->
<div class="fixed inset-0 pointer-events-none z-0">
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/10 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-600/5 rounded-full blur-[150px]"></div>
</div>
