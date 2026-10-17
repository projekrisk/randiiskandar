<x-admin-layout title="Moderasi Komentar - Randi Iskandar">
    <div class="w-full font-sans pb-16">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="font-bold text-2xl text-zinc-900 dark:text-white">Moderasi Komentar</h1>
                <p class="text-xs text-zinc-500 mt-1">Tinjau, setujui, bersihkan komentar pembaca, atau atur filter kata terlarang.</p>
            </div>
            
            @if($pendingCount > 0)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 rounded-full text-xs font-bold self-start sm:self-auto">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    {{ $pendingCount }} komentar menunggu persetujuan
                </span>
            @endif
        </div>

        <div class="flex items-center gap-2 mb-6 border-b border-zinc-200 dark:border-zinc-800 pb-3 text-xs font-medium overflow-x-auto">
            <a 
                href="{{ route('admin.comments.index', ['tab' => 'comments', 'status' => 'all']) }}" 
                class="px-3.5 py-1.5 rounded-xs transition-colors whitespace-nowrap {{ ($currentTab === 'comments' && $currentStatus === 'all') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                <i class="ph ph-chat-circle-dots mr-1"></i> Semua Komentar
            </a>
            
            <a 
                href="{{ route('admin.comments.index', ['tab' => 'comments', 'status' => 'pending']) }}" 
                class="px-3.5 py-1.5 rounded-xs transition-colors whitespace-nowrap {{ ($currentTab === 'comments' && $currentStatus === 'pending') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                <i class="ph ph-clock mr-1"></i> Menunggu Moderasi @if($pendingCount > 0) ({{ $pendingCount }}) @endif
            </a>
            
            <a 
                href="{{ route('admin.comments.index', ['tab' => 'comments', 'status' => 'approved']) }}" 
                class="px-3.5 py-1.5 rounded-xs transition-colors whitespace-nowrap {{ ($currentTab === 'comments' && $currentStatus === 'approved') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}"
            >
                <i class="ph ph-check-circle mr-1"></i> Disetujui
            </a>

            <a 
                href="{{ route('admin.comments.index', ['tab' => 'filter']) }}" 
                class="px-3.5 py-1.5 rounded-xs transition-colors whitespace-nowrap flex items-center gap-1.5 {{ $currentTab === 'filter' ? 'bg-red-600 text-white font-bold shadow-xs' : 'text-red-600/80 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300' }}"
            >
                <i class="ph ph-shield-warning text-sm"></i> Filter Kata Terlarang
            </a>
        </div>

        @if($currentTab === 'filter')
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs p-6 sm:p-8 space-y-6 shadow-xs w-full">
                <div class="flex items-start gap-4 pb-6 border-b border-zinc-100 dark:border-zinc-800">
                    <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-950/60 text-red-600 dark:text-red-400 flex items-center justify-center text-2xl shrink-0">
                        <i class="ph ph-shield-check"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-zinc-900 dark:text-white">Filter Anti-Spam & Kata Terlarang</h2>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed">
                            Komentar atau nama pengirim yang memuat kata-kata dalam daftar ini akan otomatis diblokir oleh sistem dengan notifikasi penolakan ke pengunjung.
                        </p>
                    </div>
                </div>

                <form action="{{ route('admin.comments.filter.update') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="banned_words" class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                                Daftar Kata Terlarang (Pisahkan dengan koma)
                            </label>
                            <span id="banned-count-badge" class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">
                                0 kata aktif
                            </span>
                        </div>

                        <textarea 
                            name="banned_words" 
                            id="banned_words" 
                            rows="6" 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs p-4 text-xs sm:text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors leading-relaxed font-mono"
                            placeholder="judi, slot, spam, penipuan, gacor, bokep, obat terlarang, pinjol..."
                        >{{ old('banned_words', $bannedWords) }}</textarea>
                        
                        <p class="text-[11px] text-zinc-400 mt-1.5 leading-normal">
                            * Pencocokan kata bersifat tidak sensitif huruf besar/kecil (<em>case-insensitive</em>). Contoh: jika Anda memasukkan <code class="text-zinc-600 dark:text-zinc-300 font-semibold font-mono">slot</code>, maka komentar yang memuat "Slot", "SLOT", atau "main slot" akan langsung dicekal.
                        </p>
                        @error('banned_words') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 block mb-2">
                            Tambahkan Rekomendasi Kata Cepat:
                        </span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach(['judi', 'slot online', 'gacor', 'togel', 'kasino', 'bokep', 'porn', 'obat kuat', 'pinjol', 'transfer dana', 'hacker', 'penipuan', 'promo gratis'] as $sampleWord)
                                <button 
                                    type="button" 
                                    class="btn-add-banned-word px-2.5 py-1 text-xs rounded-full bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 transition-colors cursor-pointer"
                                    data-word="{{ $sampleWord }}"
                                >
                                    + {{ $sampleWord }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                        <a href="{{ route('admin.comments.index') }}" class="text-xs font-bold text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors">
                            Kembali ke Moderasi
                        </a>
                        <button 
                            type="submit" 
                            class="px-6 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity shadow-xs cursor-pointer flex items-center gap-1.5"
                        >
                            <i class="ph ph-floppy-disk text-sm"></i> Simpan Filter Kata Terlarang
                        </button>
                    </div>
                </form>
            </div>

            <script>
            document.addEventListener('DOMContentLoaded', function () {
                const textarea = document.getElementById('banned_words');
                const badge = document.getElementById('banned-count-badge');

                function updateCount() {
                    const list = textarea.value.split(',').map(s => s.trim()).filter(Boolean);
                    badge.textContent = `${list.length} kata aktif`;
                }

                textarea.addEventListener('input', updateCount);
                updateCount();

                document.querySelectorAll('.btn-add-banned-word').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const word = this.dataset.word;
                        const current = textarea.value.split(',').map(s => s.trim()).filter(Boolean);
                        if (!current.includes(word.toLowerCase())) {
                            current.push(word);
                            textarea.value = current.join(', ');
                            updateCount();
                        }
                    });
                });
            });
            </script>
        @else
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs overflow-x-auto shadow-xs">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 text-xs bg-zinc-50/50 dark:bg-zinc-800/40">
                        <tr>
                            <th class="px-4 py-3 font-medium">Pengirim</th>
                            <th class="px-4 py-3 font-medium">Artikel</th>
                            <th class="px-4 py-3 font-medium">Isi Komentar</th>
                            <th class="px-4 py-3 font-medium text-center">Status</th>
                            <th class="px-4 py-3 font-medium">Waktu</th>
                            <th class="px-4 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/50">
                        @forelse($comments as $comment)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/20 transition-colors {{ ! $comment->is_approved ? 'bg-amber-50/40 dark:bg-amber-950/20' : '' }}">
                                <td class="px-4 py-4">
                                    <div class="font-bold text-xs text-zinc-900 dark:text-white flex items-center gap-1.5">
                                        <span>{{ $comment->name }}</span>
                                        @if($comment->user_id)
                                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.2 text-[9px] bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-xs font-bold">
                                                Penulis
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-zinc-400">
                                        {{ $comment->email }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-xs text-zinc-600 dark:text-zinc-400 truncate max-w-xs">
                                    @if($comment->post)
                                        <a href="{{ route('post.show', $comment->post->slug) }}" target="_blank" class="hover:underline font-medium text-zinc-900 dark:text-white">
                                            {{ $comment->post->title }}
                                        </a>
                                    @else
                                        <span class="text-zinc-400">Artikel dihapus</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-xs text-zinc-700 dark:text-zinc-300 whitespace-normal max-w-sm leading-relaxed">
                                    {{ $comment->comment }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($comment->is_approved)
                                        <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 rounded-xs">
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 rounded-xs">
                                            Menunggu
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-[11px] text-zinc-400">
                                    {{ $comment->formatted_date }}
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 text-xs font-bold">
                                        @if(! $comment->is_approved)
                                            <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-emerald-600 text-white rounded-xs hover:bg-emerald-700 transition-colors shadow-xs cursor-pointer">
                                                    Setujui
                                                </button>
                                            </form>
                                        @endif
                                        <button 
                                            type="button" 
                                            class="btn-delete-trigger p-1 text-zinc-400 hover:text-red-600 transition-colors cursor-pointer" 
                                            data-action="{{ route('admin.comments.destroy', $comment) }}"
                                            data-title="Hapus Komentar"
                                            data-item="{{ \Illuminate\Support\Str::limit($comment->name . ': ' . $comment->comment, 60) }}"
                                            data-message="Apakah Anda yakin ingin menghapus komentar ini? Komentar dan balasan di dalamnya akan dihapus secara permanen."
                                            title="Hapus Komentar"
                                        >
                                            <i class="ph ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-zinc-400">Tidak ada komentar dalam kriteria ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($comments->hasPages())
                <div class="mt-6">
                    {{ $comments->links() }}
                </div>
            @endif
        @endif
    </div>
</x-admin-layout>
