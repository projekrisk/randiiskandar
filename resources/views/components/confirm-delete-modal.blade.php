<div 
    id="universal-delete-modal" 
    class="fixed inset-0 bg-zinc-950/75 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-200"
    role="dialog"
    aria-modal="true"
    aria-labelledby="confirm-delete-title"
>
    <div 
        id="universal-delete-card" 
        class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs max-w-md w-full p-6 space-y-5 font-sans shadow-2xl transform scale-95 transition-transform duration-200"
    >
        <div class="flex items-start gap-4">
            <div class="w-11 h-11 rounded-full bg-red-100 dark:bg-red-950/60 text-red-600 dark:text-red-400 flex items-center justify-center text-2xl shrink-0">
                <i class="ph ph-warning-octagon"></i>
            </div>
            <div class="flex-grow min-w-0">
                <h3 id="confirm-delete-title" class="font-bold text-base text-zinc-900 dark:text-white leading-tight">
                    Konfirmasi Hapus
                </h3>
                <p id="confirm-delete-message" class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed">
                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <button 
                type="button" 
                id="btn-close-delete-modal" 
                class="text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors text-lg p-1 -mt-1 -mr-1"
                aria-label="Tutup"
            >
                <i class="ph ph-x"></i>
            </button>
        </div>

        <div id="confirm-delete-item-box" class="p-3 bg-zinc-50 dark:bg-zinc-800/80 border border-zinc-200 dark:border-zinc-700/80 rounded-xs">
            <span class="text-[10px] uppercase font-bold tracking-widest text-zinc-400 block mb-0.5">Item Terpilih:</span>
            <p id="confirm-delete-item-name" class="text-xs font-semibold text-zinc-900 dark:text-zinc-100 truncate"></p>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <button 
                type="button" 
                id="btn-cancel-delete-modal" 
                class="px-4 py-2 text-xs font-bold text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors cursor-pointer"
            >
                Batal
            </button>
            <form id="universal-delete-form" method="POST" action="" class="inline m-0">
                @csrf
                @method('DELETE')
                <button 
                    type="submit" 
                    id="btn-execute-delete" 
                    class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xs transition-colors flex items-center gap-1.5 cursor-pointer shadow-xs"
                >
                    <i class="ph ph-trash"></i> Ya, Hapus Sekarang
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('universal-delete-modal');
    const card = document.getElementById('universal-delete-card');
    const form = document.getElementById('universal-delete-form');
    const titleEl = document.getElementById('confirm-delete-title');
    const msgEl = document.getElementById('confirm-delete-message');
    const itemBox = document.getElementById('confirm-delete-item-box');
    const itemNameEl = document.getElementById('confirm-delete-item-name');
    const btnClose = document.getElementById('btn-close-delete-modal');
    const btnCancel = document.getElementById('btn-cancel-delete-modal');

    function openDeleteModal(actionUrl, title, itemName, message) {
        form.action = actionUrl;
        titleEl.textContent = title || 'Konfirmasi Hapus';
        msgEl.textContent = message || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini bersifat permanen.';
        
        if (itemName) {
            itemNameEl.textContent = itemName;
            itemBox.classList.remove('hidden');
        } else {
            itemBox.classList.add('hidden');
        }

        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        });
    }

    function closeDeleteModal() {
        modal.classList.add('opacity-0');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            form.action = '';
        }, 200);
    }

    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('.btn-delete-trigger');
        if (trigger) {
            e.preventDefault();
            const action = trigger.dataset.action;
            const title = trigger.dataset.title;
            const item = trigger.dataset.item;
            const message = trigger.dataset.message;
            if (action) {
                openDeleteModal(action, title, item, message);
            }
        }
    });

    btnClose.addEventListener('click', closeDeleteModal);
    btnCancel.addEventListener('click', closeDeleteModal);

    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            closeDeleteModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    });
});
</script>
