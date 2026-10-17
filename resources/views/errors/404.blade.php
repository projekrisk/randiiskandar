<x-layout 
    title="404 - Halaman Tidak Ditemukan - Randi Iskandar Workspace"
    description="Halaman yang Anda tuju mungkin sudah kedaluwarsa, dipindahkan, atau alamat URL yang Anda masukkan salah."
>
    <main class="w-full max-w-xl mx-auto px-5 sm:px-8 flex-grow flex flex-col items-center justify-center text-center py-36">
        
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-zinc-100 dark:bg-zinc-900 text-zinc-900 dark:text-white mb-6 border border-zinc-200 dark:border-zinc-800">
            <i class="ph ph-compass text-4xl"></i>
        </div>
        
        <span class="text-xs font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400 mb-3 font-sans">
            Kesalahan 404
        </span>
        
        <h1 class="font-sans font-bold text-3xl sm:text-5xl text-zinc-900 dark:text-white mb-4">
            Halaman Tidak Ditemukan
        </h1>
        
        <p class="text-zinc-600 dark:text-zinc-400 text-sm sm:text-base mb-10 leading-relaxed font-sans max-w-md">
            Tautan yang Anda tuju mungkin sudah kedaluwarsa, dipindahkan, atau alamat URL yang Anda masukkan salah.
        </p>

        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-sans font-bold text-xs rounded-sm hover:opacity-90 transition-opacity">
                <i class="ph ph-house text-base"></i> Kembali ke Beranda
            </a>
            <button onclick="document.getElementById('search-trigger').click()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-transparent border border-zinc-300 dark:border-zinc-700 text-zinc-900 dark:text-white font-sans font-bold text-xs rounded-sm hover:border-zinc-900 dark:hover:border-white transition-colors">
                <i class="ph ph-magnifying-glass text-base"></i> Cari Artikel
            </button>
        </div>

    </main>
</x-layout>
