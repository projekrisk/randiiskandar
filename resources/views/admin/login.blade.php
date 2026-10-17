<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Workspace - Randi Iskandar</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-base-light text-zinc-900 dark:bg-base-dark dark:text-zinc-100 min-h-full flex items-center justify-center p-6 font-sans">
    
    <div class="w-full max-w-sm">
        
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="font-sans italic font-bold text-2xl tracking-wide text-zinc-900 dark:text-white inline-block mb-2">
                Randi Iskandar.
            </a>
            <p class="text-xs font-bold uppercase tracking-widest text-zinc-500">Workspace Masuk</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-8 rounded-sm border border-zinc-200 dark:border-zinc-800 shadow-sm">
            
            @if($errors->any())
                <div class="p-3.5 mb-6 rounded-xs bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        required 
                        autofocus
                        value="{{ old('email', 'randi@example.com') }}"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1">Kata Sandi</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        value="password"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors"
                    >
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-zinc-600 dark:text-zinc-400">
                        <input type="checkbox" name="remember" class="rounded-xs text-zinc-900 focus:ring-0">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity mt-4">
                    Masuk ke Dasbor
                </button>
            </form>

        </div>

        <p class="text-center text-xs text-zinc-400 mt-6">
            Default kredensial demo: <code>randi@example.com</code> / <code>password</code>
        </p>

    </div>

</body>
</html>
