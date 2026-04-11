<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Girişi - Task Orbit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-[#020617] text-white flex items-center justify-center p-6 overflow-hidden relative">
    <!-- Background Glows -->
    <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-blue-600/10 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-indigo-600/5 rounded-full blur-[150px]"></div>

    <div class="w-full max-w-md bg-slate-900/40 border border-slate-800/40 backdrop-blur-2xl rounded-[2.5rem] shadow-2xl overflow-hidden relative z-10">
        <div class="p-10 space-y-8">
            <div class="text-center space-y-2">
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-500/10 text-blue-500 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </div>
                <h2 class="text-3xl font-bold tracking-tight">Yönetici Girişi</h2>
                <p class="text-slate-400 text-sm font-medium">Task Orbit yönetim paneline erişin.</p>
            </div>

            <form action="/admin-action-panel" class="space-y-4">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-slate-500 ml-1">E-posta</label>
                    <input type="email" placeholder="admin@taskorbit.com" class="w-full h-12 bg-slate-950/50 border border-slate-800/40 rounded-xl px-4 focus:outline-none focus:ring-1 focus:ring-blue-500/50 text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-slate-500 ml-1">Şifre</label>
                    <input type="password" placeholder="••••••••" class="w-full h-12 bg-slate-950/50 border border-slate-800/40 rounded-xl px-4 focus:outline-none focus:ring-1 focus:ring-blue-500/50 text-sm">
                </div>
                <button type="submit" class="w-full h-14 rounded-xl bg-blue-600 text-white font-bold text-sm shadow-xl shadow-blue-500/20 hover:bg-blue-500 transition-all mt-4">
                    Giriş Yap
                </button>
            </form>
        </div>

        <div class="bg-red-500/5 p-6 border-t border-slate-800/20 flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            <span class="text-[10px] font-black uppercase tracking-widest text-red-500/70">Sadece Yetkili Personel</span>
        </div>
    </div>
</body>
</html>
