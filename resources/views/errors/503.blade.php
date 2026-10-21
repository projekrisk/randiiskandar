<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Pemeliharaan Sistem - Randi Iskandar Workspace</title>
    <x-favicons />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
</head>
<body class="bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 min-h-screen flex flex-col items-center justify-center p-6 antialiased font-sans">
    <main class="w-full max-w-md mx-auto text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-zinc-100 dark:bg-zinc-900 text-zinc-900 dark:text-white mb-6 border border-zinc-200 dark:border-zinc-800">
            <i class="ph ph-wrench text-4xl"></i>
        </div>
        
        <span class="text-xs font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400 mb-3 block">
            Pemeliharaan Sistem
        </span>
        
        <h1 class="font-bold text-3xl sm:text-4xl text-zinc-900 dark:text-white mb-4">
            Sedang Ditingkatkan
        </h1>
        
        <p class="text-zinc-600 dark:text-zinc-400 text-sm sm:text-base mb-8 leading-relaxed">
            Kami sedang melakukan pemeliharaan rutin dan peningkatan performa server. Layanan akan segera kembali aktif dalam beberapa menit.
        </p>

        <button onclick="window.location.reload()" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-sm hover:opacity-90 transition-opacity">
            <i class="ph ph-arrow-clockwise text-base"></i> Cek Ketersediaan
        </button>
    </main>
</body>
</html>
