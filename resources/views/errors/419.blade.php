<x-layout 
    title="419 - Sesi Kedaluwarsa - Randi Iskandar Workspace"
    description="Sesi browser Anda telah kedaluwarsa karena tidak ada aktivitas dalam waktu tertentu."
>
    <main class="w-full max-w-xl mx-auto px-5 sm:px-8 flex-grow flex flex-col items-center justify-center text-center py-36">
        
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-zinc-100 dark:bg-zinc-900 text-zinc-900 dark:text-white mb-6 border border-zinc-200 dark:border-zinc-800">
            <i class="ph ph-clock-counter-clockwise text-4xl"></i>
        </div>
        
        <span class="text-xs font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400 mb-3 font-sans">
            Kesalahan 419
        </span>
        
        <h1 class="font-sans font-bold text-3xl sm:text-5xl text-zinc-900 dark:text-white mb-4">
            Sesi Telah Kedaluwarsa
        </h1>
        
        <p class="text-zinc-600 dark:text-zinc-400 text-sm sm:text-base mb-10 leading-relaxed font-sans max-w-md">
            Halaman ini telah terlalu lama dibiarkan tidak aktif sehingga token keamanan kedaluwarsa. Silakan muat ulang halaman untuk memperbarui sesi Anda.
        </p>

        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            <button onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-sans font-bold text-xs rounded-sm hover:opacity-90 transition-opacity">
                <i class="ph ph-arrow-clockwise text-base"></i> Muat Ulang Halaman
            </button>
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-transparent border border-zinc-300 dark:border-zinc-700 text-zinc-900 dark:text-white font-sans font-bold text-xs rounded-sm hover:border-zinc-900 dark:hover:border-white transition-colors">
                <i class="ph ph-house text-base"></i> Kembali ke Beranda
            </a>
        </div>

    </main>
</x-layout>
