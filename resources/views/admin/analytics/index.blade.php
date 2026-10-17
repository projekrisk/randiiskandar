<x-admin-layout title="Laporan & Analitik Performa - Randi Iskandar Workspace">
    <div class="w-full font-sans pb-16">
        
        
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-8 gap-4 border-b border-zinc-200 dark:border-zinc-800 pb-6 print:border-b-2 print:border-zinc-900">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 rounded-xs">
                        Laporan Editorial
                    </span>
                    <span class="text-xs text-zinc-400">•</span>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400 font-medium">
                        Periode: {{ $selectedMonth ? $indonesianMonths[$selectedMonth] . ' ' : '' }}{{ $selectedYear }}
                    </span>
                </div>
                <h1 class="font-bold text-2xl sm:text-3xl text-zinc-900 dark:text-white">
                    Analitik Tayangan, Komentar & Artikel
                </h1>
                <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                    Pantau metrik pembaca, tingkat apresiasi, keterlibatan komentar, dan efektivitas publikasi tiap bulan dan tahun.
                </p>
            </div>

            
            <div class="flex items-center gap-2.5 print:hidden shrink-0">
                <a 
                    href="{{ route('admin.analytics.export', ['year' => $selectedYear]) }}" 
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xs border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-bold text-xs transition-colors shadow-2xs"
                    title="Unduh Data dalam format CSV"
                >
                    <i class="ph ph-download-simple text-base"></i>
                    <span>Ekspor CSV</span>
                </a>

                <button 
                    type="button" 
                    onclick="window.print()" 
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xs bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs hover:opacity-90 transition-opacity shadow-xs cursor-pointer"
                    title="Cetak Laporan Resmi atau Simpan sebagai PDF"
                >
                    <i class="ph ph-printer text-base"></i>
                    <span>Cetak Laporan</span>
                </button>
            </div>
        </div>

        
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs p-5 mb-8 shadow-xs print:hidden">
            <form action="{{ route('admin.analytics.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 flex-grow">
                    
                    <div>
                        <label for="year" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
                            Tahun
                        </label>
                        <select 
                            name="year" 
                            id="year" 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white font-medium focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                        >
                            @foreach($availableYears as $yearOption)
                                <option value="{{ $yearOption }}" {{ $selectedYear == $yearOption ? 'selected' : '' }}>
                                    Tahun {{ $yearOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    
                    <div>
                        <label for="month" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
                            Bulan
                        </label>
                        <select 
                            name="month" 
                            id="month" 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white font-medium focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                        >
                            <option value="">Semua Bulan (12 Bulan)</option>
                            @foreach($indonesianMonths as $mNum => $mName)
                                <option value="{{ $mNum }}" {{ $selectedMonth == $mNum ? 'selected' : '' }}>
                                    {{ $mName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    
                    <div>
                        <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 mb-1.5">
                            Kategori Artikel
                        </label>
                        <select 
                            name="category_id" 
                            id="category_id" 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xs px-3 py-2 text-xs text-zinc-900 dark:text-white font-medium focus:outline-none focus:border-zinc-900 dark:focus:border-white transition-colors cursor-pointer"
                        >
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                
                <div class="flex items-center gap-2 shrink-0">
                    <button 
                        type="submit" 
                        class="px-5 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs rounded-xs hover:opacity-90 transition-opacity cursor-pointer flex items-center gap-1.5 shadow-2xs"
                    >
                        <i class="ph ph-funnel text-sm"></i> Terapkan
                    </button>
                    @if($selectedMonth || $selectedCategoryId || $selectedYear != now()->year)
                        <a 
                            href="{{ route('admin.analytics.index') }}" 
                            class="px-3.5 py-2 text-xs font-semibold text-zinc-500 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-xs transition-colors"
                            title="Reset Filter"
                        >
                            Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
            
            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-xs shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400">Total Tayangan</span>
                    <div class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white flex items-center justify-center text-base">
                        <i class="ph ph-eye"></i>
                    </div>
                </div>
                <h3 class="font-bold text-2xl text-zinc-900 dark:text-white tracking-tight">
                    {{ number_format($totalViews) }}
                </h3>
                <p class="text-[11px] text-zinc-500 mt-1">
                    {{ $selectedMonth ? 'Bulan ' . $indonesianMonths[$selectedMonth] : 'Tahun ' . $selectedYear }}
                </p>
            </div>

            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-xs shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400">Total Komentar</span>
                    <div class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white flex items-center justify-center text-base">
                        <i class="ph ph-chat-circle-dots"></i>
                    </div>
                </div>
                <h3 class="font-bold text-2xl text-zinc-900 dark:text-white tracking-tight">
                    {{ number_format($totalComments) }}
                </h3>
                <p class="text-[11px] text-zinc-500 mt-1">
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ $totalApprovedComments }}</span> disetujui
                </p>
            </div>

            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-xs shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400">Apresiasi (Claps)</span>
                    <div class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white flex items-center justify-center text-base">
                        <i class="ph ph-hands-clapping"></i>
                    </div>
                </div>
                <h3 class="font-bold text-2xl text-zinc-900 dark:text-white tracking-tight">
                    {{ number_format($totalClaps) }}
                </h3>
                <p class="text-[11px] text-zinc-500 mt-1">Tepuk tangan pembaca</p>
            </div>

            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-xs shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400">Artikel Terbit</span>
                    <div class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white flex items-center justify-center text-base">
                        <i class="ph ph-article"></i>
                    </div>
                </div>
                <h3 class="font-bold text-2xl text-zinc-900 dark:text-white tracking-tight">
                    {{ number_format($totalPosts) }}
                </h3>
                <p class="text-[11px] text-zinc-500 mt-1">Tulisan publikasi</p>
            </div>

            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-xs shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400">Rerata Tayangan</span>
                    <div class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white flex items-center justify-center text-base">
                        <i class="ph ph-chart-bar"></i>
                    </div>
                </div>
                <h3 class="font-bold text-2xl text-zinc-900 dark:text-white tracking-tight">
                    {{ number_format($avgViewsPerPost) }}
                </h3>
                <p class="text-[11px] text-zinc-500 mt-1">Per artikel terbit</p>
            </div>

            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-5 rounded-xs shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400">Rasio Interaksi</span>
                    <div class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white flex items-center justify-center text-base">
                        <i class="ph ph-sparkle"></i>
                    </div>
                </div>
                <h3 class="font-bold text-2xl text-zinc-900 dark:text-white tracking-tight">
                    {{ $engagementRate }}%
                </h3>
                <p class="text-[11px] text-zinc-500 mt-1">Komentar + Apresiasi</p>
            </div>

        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8 print:hidden">
            
            
            <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6 rounded-xs shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
                    <div>
                        <h2 class="font-bold text-base sm:text-lg text-zinc-900 dark:text-white">
                            Grafik Tren Tayangan & Komentar Tiap Bulan
                        </h2>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                            Perbandingan volume pembaca (Tayangan) dan respon pembaca (Komentar) sepanjang Tahun {{ $selectedYear }}.
                        </p>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-semibold">
                        <span class="flex items-center gap-1.5 text-zinc-900 dark:text-white">
                            <span class="w-3 h-3 rounded-full bg-zinc-900 dark:bg-white inline-block"></span> Tayangan
                        </span>
                        <span class="flex items-center gap-1.5 text-zinc-500 dark:text-zinc-400">
                            <span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span> Komentar
                        </span>
                    </div>
                </div>

                <div class="w-full h-72 sm:h-80">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>

            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6 rounded-xs shadow-xs flex flex-col">
                <div class="mb-4">
                    <h2 class="font-bold text-base sm:text-lg text-zinc-900 dark:text-white">
                        Pangsa Kategori
                    </h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                        Proporsi tayangan artikel berdasarkan topik kategori.
                    </p>
                </div>

                <div class="flex-grow flex items-center justify-center relative min-h-[200px]">
                    <canvas id="categoryDistributionChart"></canvas>
                </div>

                <div class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800 flex flex-wrap gap-2 text-xs">
                    @foreach($categoryBreakdown as $cat)
                        <div class="flex items-center gap-1.5 text-[11px] text-zinc-600 dark:text-zinc-400">
                            <span class="w-2.5 h-2.5 rounded-full inline-block" style="background-color: {{ $cat['color'] }}"></span>
                            <span>{{ $cat['name'] }} ({{ $cat['views_count'] }})</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs overflow-hidden shadow-xs mb-8">
            <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-base sm:text-lg text-zinc-900 dark:text-white">
                        Rekapitulasi Bulanan - Tahun {{ $selectedYear }}
                    </h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                        Rincian terperinci artikel terbit, jumlah tayangan, komentar, dan apresiasi setiap bulan.
                    </p>
                </div>
                <span class="text-xs font-bold text-zinc-400 print:text-zinc-800">
                    12 Bulan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm whitespace-nowrap">
                    <thead class="bg-zinc-50 dark:bg-zinc-950/50 border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-5 py-3.5 w-12 text-center">No</th>
                            <th class="px-5 py-3.5">Bulan</th>
                            <th class="px-5 py-3.5 text-center">Artikel Terbit</th>
                            <th class="px-5 py-3.5 text-right">Total Tayangan</th>
                            <th class="px-5 py-3.5 text-center">Total Komentar</th>
                            <th class="px-5 py-3.5 text-center">Disetujui</th>
                            <th class="px-5 py-3.5 text-right">Apresiasi (Claps)</th>
                            <th class="px-5 py-3.5 text-right">Rerata Tayangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/60 text-zinc-800 dark:text-zinc-200">
                        @foreach($monthlyBreakdown as $mNum => $row)
                            <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30 transition-colors {{ $selectedMonth == $mNum ? 'bg-zinc-100/60 dark:bg-zinc-800/50 font-bold' : '' }}">
                                <td class="px-5 py-3 text-center text-zinc-400">{{ $mNum }}</td>
                                <td class="px-5 py-3 font-semibold text-zinc-900 dark:text-white">
                                    {{ $row['month_name'] }}
                                    @if($selectedMonth == $mNum)
                                        <span class="ml-1 text-[10px] bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 px-1.5 py-0.2 rounded-xs">Aktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($row['posts'] > 0)
                                        <span class="font-bold text-zinc-900 dark:text-white">{{ $row['posts'] }}</span>
                                    @else
                                        <span class="text-zinc-400">0</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right font-mono font-medium">
                                    {{ number_format($row['views']) }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($row['comments'] > 0)
                                        <span class="font-bold text-zinc-900 dark:text-white">{{ $row['comments'] }}</span>
                                    @else
                                        <span class="text-zinc-400">0</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($row['approved_comments'] > 0)
                                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">{{ $row['approved_comments'] }}</span>
                                    @else
                                        <span class="text-zinc-400">0</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right font-mono text-zinc-600 dark:text-zinc-400">
                                    {{ number_format($row['claps']) }}
                                </td>
                                <td class="px-5 py-3 text-right font-mono text-zinc-500">
                                    {{ number_format($row['avg_views']) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-zinc-50 dark:bg-zinc-950 font-bold text-xs sm:text-sm text-zinc-900 dark:text-white border-t-2 border-zinc-300 dark:border-zinc-700">
                        <tr>
                            <td class="px-5 py-4 text-center">#</td>
                            <td class="px-5 py-4 uppercase tracking-wider text-xs">Total Akumulasi 12 Bulan</td>
                            <td class="px-5 py-4 text-center">{{ array_sum(array_column($monthlyBreakdown, 'posts')) }}</td>
                            <td class="px-5 py-4 text-right font-mono text-base text-zinc-900 dark:text-white">
                                {{ number_format(array_sum(array_column($monthlyBreakdown, 'views'))) }}
                            </td>
                            <td class="px-5 py-4 text-center">{{ array_sum(array_column($monthlyBreakdown, 'comments')) }}</td>
                            <td class="px-5 py-4 text-center text-emerald-600 dark:text-emerald-400">
                                {{ array_sum(array_column($monthlyBreakdown, 'approved_comments')) }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono">{{ number_format(array_sum(array_column($monthlyBreakdown, 'claps'))) }}</td>
                            <td class="px-5 py-4 text-right font-mono text-zinc-500">
                                @php
                                    $totP = array_sum(array_column($monthlyBreakdown, 'posts'));
                                    $totV = array_sum(array_column($monthlyBreakdown, 'views'));
                                @endphp
                                {{ $totP > 0 ? number_format(round($totV / $totP)) : 0 }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            
            <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs overflow-hidden shadow-xs">
                <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-base sm:text-lg text-zinc-900 dark:text-white">
                            Peringkat Artikel Berdasarkan Tayangan
                        </h2>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                            Tulisan yang paling banyak dibaca pada periode yang dipilih.
                        </p>
                    </div>
                    <span class="text-xs font-semibold text-zinc-400">Top {{ $topPosts->count() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm whitespace-nowrap">
                        <thead class="bg-zinc-50 dark:bg-zinc-950/50 border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-bold uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="px-4 py-3 text-center w-10">#</th>
                                <th class="px-4 py-3">Judul Artikel</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3 text-right">Tayangan</th>
                                <th class="px-4 py-3 text-center">Komentar</th>
                                <th class="px-4 py-3 text-right">Apresiasi</th>
                                <th class="px-4 py-3 text-right print:hidden">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/60 text-zinc-800 dark:text-zinc-200">
                            @forelse($topPosts as $index => $post)
                                <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30 transition-colors">
                                    <td class="px-4 py-3.5 text-center font-bold text-xs {{ $index < 3 ? 'text-amber-500' : 'text-zinc-400' }}">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-4 py-3.5 font-bold text-zinc-900 dark:text-white truncate max-w-xs">
                                        <a href="{{ route('post.show', $post->slug) }}" target="_blank" class="hover:underline" title="{{ $post->title }}">
                                            {{ $post->title }}
                                        </a>
                                        <div class="text-[11px] font-normal text-zinc-400 mt-0.5">
                                            {{ $post->published_at ? $post->published_at->translatedFormat('d M Y') : 'Draf' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if($post->category)
                                            <span class="text-xs bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 px-2 py-0.5 rounded-xs font-medium">
                                                {{ $post->category->name }}
                                            </span>
                                        @else
                                            <span class="text-xs text-zinc-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-right font-mono font-bold text-zinc-900 dark:text-white">
                                        {{ number_format($post->views_count) }}
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-semibold text-zinc-600 dark:text-zinc-400">
                                        {{ $post->comments_count }}
                                    </td>
                                    <td class="px-4 py-3.5 text-right font-mono text-zinc-500">
                                        {{ number_format($post->claps_count) }}
                                    </td>
                                    <td class="px-4 py-3.5 text-right print:hidden">
                                        <a 
                                            href="{{ route('admin.posts.edit', $post->id) }}" 
                                            class="text-xs font-semibold text-zinc-500 hover:text-zinc-900 dark:hover:text-white hover:underline"
                                        >
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-zinc-400 italic">
                                        Belum ada data artikel untuk filter yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xs overflow-hidden shadow-xs flex flex-col">
                <div class="p-5 border-b border-zinc-200 dark:border-zinc-800">
                    <h2 class="font-bold text-base sm:text-lg text-zinc-900 dark:text-white">
                        Kinerja Kategori
                    </h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                        Agregat tayangan dan komentar per kategori.
                    </p>
                </div>

                <div class="divide-y divide-zinc-100 dark:divide-zinc-800/60 flex-grow">
                    @forelse($categoryBreakdown as $cat)
                        <div class="p-4 flex items-center justify-between gap-3 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/20 transition-colors">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $cat['color'] }}"></span>
                                <div class="truncate">
                                    <span class="font-bold text-xs sm:text-sm text-zinc-900 dark:text-white block truncate">
                                        {{ $cat['name'] }}
                                    </span>
                                    <span class="text-[11px] text-zinc-400">
                                        {{ $cat['posts_count'] }} artikel diterbitkan
                                    </span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-mono font-bold text-xs sm:text-sm text-zinc-900 dark:text-white block">
                                    {{ number_format($cat['views_count']) }} view
                                </span>
                                <span class="text-[11px] text-zinc-500">
                                    {{ $cat['comments_count'] }} komentar
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="p-6 text-center text-xs text-zinc-400 italic">Belum ada kategori terdata.</p>
                    @endforelse
                </div>

                <div class="p-4 bg-zinc-50 dark:bg-zinc-950/60 border-t border-zinc-200 dark:border-zinc-800 text-center">
                    <a href="{{ route('admin.categories.index') }}" class="text-xs font-bold text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white inline-flex items-center gap-1">
                        <span>Kelola Kategori</span>
                        <i class="ph ph-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#a1a1aa' : '#71717a';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)';

        const trendCtx = document.getElementById('monthlyTrendChart')?.getContext('2d');
        if (trendCtx) {
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        {
                            label: 'Tayangan (Views)',
                            data: @json($chartViews),
                            borderColor: isDark ? '#ffffff' : '#18181b',
                            backgroundColor: isDark ? 'rgba(255, 255, 255, 0.06)' : 'rgba(24, 24, 27, 0.05)',
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: isDark ? '#ffffff' : '#18181b',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'yViews'
                        },
                        {
                            label: 'Komentar',
                            data: @json($chartComments),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            borderDash: [4, 4],
                            fill: false,
                            tension: 0.3,
                            pointBackgroundColor: '#3b82f6',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'yComments'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#27272a' : '#18181b',
                            titleColor: '#ffffff',
                            bodyColor: '#e4e4e7',
                            padding: 10,
                            borderRadius: 4,
                            boxPadding: 4
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: gridColor },
                            ticks: { color: textColor, font: { size: 11 } }
                        },
                        yViews: {
                            type: 'linear',
                            position: 'left',
                            grid: { color: gridColor },
                            ticks: { color: textColor, font: { size: 11 } },
                            title: {
                                display: true,
                                text: 'Tayangan',
                                color: textColor,
                                font: { size: 10, weight: 'bold' }
                            }
                        },
                        yComments: {
                            type: 'linear',
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: { color: '#3b82f6', font: { size: 11 } },
                            title: {
                                display: true,
                                text: 'Komentar',
                                color: '#3b82f6',
                                font: { size: 10, weight: 'bold' }
                            }
                        }
                    }
                }
            });
        }

        const catCtx = document.getElementById('categoryDistributionChart')?.getContext('2d');
        if (catCtx) {
            new Chart(catCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($categoryChartLabels),
                    datasets: [{
                        data: @json($categoryChartViews),
                        backgroundColor: ['#18181b', '#3f3f46', '#71717a', '#a1a1aa', '#d4d4d8', '#0ea5e9', '#6366f1', '#ec4899'],
                        borderWidth: 2,
                        borderColor: isDark ? '#18181b' : '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#27272a' : '#18181b',
                            titleColor: '#ffffff',
                            bodyColor: '#e4e4e7',
                            padding: 10,
                            borderRadius: 4
                        }
                    },
                    cutout: '68%'
                }
            });
        }
    });
    </script>
</x-admin-layout>
