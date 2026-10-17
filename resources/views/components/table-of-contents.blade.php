@props(['toc' => []])

@if(!empty($toc))
<div id="toc-container" class="fixed bottom-24 right-6 sm:bottom-28 sm:right-8 z-30 transition-all duration-300">
    <button 
        id="toc-toggle-btn"
        type="button"
        class="flex items-center gap-2 px-3.5 py-2.5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 font-sans text-xs font-bold"
        aria-label="Daftar Isi"
    >
        <i class="ph ph-list-numbers text-lg text-zinc-600 dark:text-zinc-400"></i>
        <span class="hidden sm:inline">Daftar Isi</span>
    </button>

    <div 
        id="toc-menu" 
        class="hidden absolute bottom-12 right-0 w-[calc(100vw-3rem)] max-w-xs sm:w-80 max-h-[55vh] sm:max-h-96 overflow-y-auto bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg shadow-2xl p-5 mb-2 font-sans transition-all duration-200 z-50"
    >
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-zinc-100 dark:border-zinc-800">
            <span class="text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Daftar Isi Artikel</span>
            <button id="close-toc-btn" class="text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200">
                <i class="ph ph-x text-base"></i>
            </button>
        </div>
        <nav class="flex flex-col gap-2.5 text-sm">
            @foreach($toc as $item)
                <a 
                    href="#{{ $item['id'] }}" 
                    class="toc-item text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors leading-snug {{ $item['level'] === 3 ? 'pl-4 text-xs' : 'font-medium' }}"
                >
                    {{ $item['text'] }}
                </a>
            @endforeach
        </nav>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tocToggleBtn = document.getElementById('toc-toggle-btn');
        const tocMenu = document.getElementById('toc-menu');
        const closeTocBtn = document.getElementById('close-toc-btn');
        const tocItems = document.querySelectorAll('.toc-item');

        if (tocToggleBtn && tocMenu) {
            tocToggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                tocMenu.classList.toggle('hidden');
            });

            closeTocBtn?.addEventListener('click', (e) => {
                e.stopPropagation();
                tocMenu.classList.add('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!tocMenu.contains(e.target) && !tocToggleBtn.contains(e.target)) {
                    tocMenu.classList.add('hidden');
                }
            });

            tocItems.forEach(item => {
                item.addEventListener('click', () => {
                    tocMenu.classList.add('hidden');
                });
            });
        }
    });
</script>
@endif
