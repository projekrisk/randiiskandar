<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    /**
     * Nama-nama bulan dalam bahasa Indonesia
     *
     * @var array<int, string>
     */
    protected array $indonesianMonths = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    /**
     * Tampilkan Halaman Laporan & Analitik Tayangan, Komentar, dan Artikel
     */
    public function index(Request $request): View
    {
        $currentYear = (int) Carbon::now()->year;
        $selectedYear = (int) $request->input('year', $currentYear);
        $selectedMonth = $request->filled('month') ? (int) $request->input('month') : null;
        $selectedCategoryId = $request->filled('category_id') ? (int) $request->input('category_id') : null;

        // Dapatkan semua tahun yang tersedia dari data
        $postYears = Post::query()
            ->whereNotNull('published_at')
            ->pluck('published_at')
            ->map(fn ($d) => (int) Carbon::parse($d)->year)
            ->unique()
            ->toArray();

        $viewYears = PostView::query()
            ->pluck('viewed_date')
            ->map(fn ($d) => (int) Carbon::parse($d)->year)
            ->unique()
            ->toArray();

        $availableYears = array_values(array_unique(array_merge([$currentYear], $postYears, $viewYears)));
        rsort($availableYears);

        // Kategori untuk dropdown filter
        $categories = Category::orderBy('name')->get();

        // Rekapitulasi 12 bulan untuk tahun yang dipilih
        $monthlyBreakdown = [];
        $chartLabels = [];
        $chartViews = [];
        $chartComments = [];
        $chartPosts = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthName = $this->indonesianMonths[$m];
            $chartLabels[] = $monthName;

            // Hitung views
            $viewQuery = PostView::query()
                ->whereYear('viewed_date', $selectedYear)
                ->whereMonth('viewed_date', $m);

            if ($selectedCategoryId) {
                $viewQuery->whereHas('post', fn ($q) => $q->where('category_id', $selectedCategoryId));
            }
            $views = (int) $viewQuery->sum('views');

            // Hitung komentar
            $commentQuery = Comment::query()
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m);

            if ($selectedCategoryId) {
                $commentQuery->whereHas('post', fn ($q) => $q->where('category_id', $selectedCategoryId));
            }
            $commentsCount = (int) $commentQuery->count();
            $approvedCommentsCount = (int) (clone $commentQuery)->where('is_approved', true)->count();

            // Hitung artikel diterbitkan
            $postQuery = Post::query()
                ->published()
                ->whereYear('published_at', $selectedYear)
                ->whereMonth('published_at', $m);

            if ($selectedCategoryId) {
                $postQuery->where('category_id', $selectedCategoryId);
            }
            $postsCount = (int) $postQuery->count();
            $clapsCount = (int) $postQuery->sum('claps_count');

            $chartViews[] = $views;
            $chartComments[] = $commentsCount;
            $chartPosts[] = $postsCount;

            $monthlyBreakdown[$m] = [
                'month_num' => $m,
                'month_name' => $monthName,
                'views' => $views,
                'comments' => $commentsCount,
                'approved_comments' => $approvedCommentsCount,
                'posts' => $postsCount,
                'claps' => $clapsCount,
                'avg_views' => $postsCount > 0 ? round($views / $postsCount) : 0,
            ];
        }

        // Metrik Ringkasan (KPI Cards) berdasarkan filter
        if ($selectedMonth) {
            $currentSummary = $monthlyBreakdown[$selectedMonth];
            $totalViews = $currentSummary['views'];
            $totalComments = $currentSummary['comments'];
            $totalApprovedComments = $currentSummary['approved_comments'];
            $totalPosts = $currentSummary['posts'];
            $totalClaps = $currentSummary['claps'];
        } else {
            $totalViews = (int) array_sum(array_column($monthlyBreakdown, 'views'));
            $totalComments = (int) array_sum(array_column($monthlyBreakdown, 'comments'));
            $totalApprovedComments = (int) array_sum(array_column($monthlyBreakdown, 'approved_comments'));
            $totalPosts = (int) array_sum(array_column($monthlyBreakdown, 'posts'));
            $totalClaps = (int) array_sum(array_column($monthlyBreakdown, 'claps'));
        }

        $avgViewsPerPost = $totalPosts > 0 ? (int) round($totalViews / $totalPosts) : 0;
        $engagementRate = $totalViews > 0 ? round((($totalComments + $totalClaps) / $totalViews) * 100, 2) : 0.0;

        // Distribusi Kategori
        $categoryBreakdown = [];
        $categoryChartLabels = [];
        $categoryChartViews = [];
        $categoryColors = ['#18181b', '#3f3f46', '#71717a', '#a1a1aa', '#d4d4d8', '#0ea5e9', '#6366f1', '#ec4899'];

        foreach ($categories as $index => $cat) {
            $catPostsQuery = Post::query()
                ->published()
                ->where('category_id', $cat->id)
                ->whereYear('published_at', $selectedYear);

            if ($selectedMonth) {
                $catPostsQuery->whereMonth('published_at', $selectedMonth);
            }

            $catPosts = (int) $catPostsQuery->count();

            $catViewsQuery = PostView::query()
                ->whereYear('viewed_date', $selectedYear)
                ->whereHas('post', fn ($q) => $q->where('category_id', $cat->id));

            if ($selectedMonth) {
                $catViewsQuery->whereMonth('viewed_date', $selectedMonth);
            }
            $catViews = (int) $catViewsQuery->sum('views');

            $catCommentsQuery = Comment::query()
                ->whereYear('created_at', $selectedYear)
                ->whereHas('post', fn ($q) => $q->where('category_id', $cat->id));

            if ($selectedMonth) {
                $catCommentsQuery->whereMonth('created_at', $selectedMonth);
            }
            $catComments = (int) $catCommentsQuery->count();

            $categoryBreakdown[] = [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'posts_count' => $catPosts,
                'views_count' => $catViews,
                'comments_count' => $catComments,
                'color' => $categoryColors[$index % count($categoryColors)],
            ];

            $categoryChartLabels[] = $cat->name;
            $categoryChartViews[] = $catViews;
        }

        // Top 10 Tulisan Terpopuler
        $topPostsQuery = Post::query()
            ->published()
            ->with(['category'])
            ->withCount('comments');

        if ($selectedCategoryId) {
            $topPostsQuery->where('category_id', $selectedCategoryId);
        }

        if ($selectedMonth) {
            $topPostsQuery->whereYear('published_at', $selectedYear)->whereMonth('published_at', $selectedMonth);
        } else {
            $topPostsQuery->whereYear('published_at', $selectedYear);
        }

        $topPosts = $topPostsQuery->orderByDesc('views_count')->limit(10)->get();

        // Data Top 5 untuk Horizontal Bar Chart
        $topChartLabels = [];
        $topChartViews = [];
        foreach ($topPosts->take(5) as $top) {
            $topChartLabels[] = mb_strimwidth($top->title, 0, 24, '...');
            $topChartViews[] = (int) $top->views_count;
        }

        return view('admin.analytics.index', [
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'selectedCategoryId' => $selectedCategoryId,
            'availableYears' => $availableYears,
            'categories' => $categories,
            'indonesianMonths' => $this->indonesianMonths,
            'monthlyBreakdown' => $monthlyBreakdown,
            'totalViews' => $totalViews,
            'totalComments' => $totalComments,
            'totalApprovedComments' => $totalApprovedComments,
            'totalPosts' => $totalPosts,
            'totalClaps' => $totalClaps,
            'avgViewsPerPost' => $avgViewsPerPost,
            'engagementRate' => $engagementRate,
            'categoryBreakdown' => $categoryBreakdown,
            'topPosts' => $topPosts,
            'chartLabels' => $chartLabels,
            'chartViews' => $chartViews,
            'chartComments' => $chartComments,
            'chartPosts' => $chartPosts,
            'categoryChartLabels' => $categoryChartLabels,
            'categoryChartViews' => $categoryChartViews,
            'topChartLabels' => $topChartLabels,
            'topChartViews' => $topChartViews,
        ]);
    }

    /**
     * Unduh Laporan Rekapitulasi Analitik dalam format CSV
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $selectedYear = (int) $request->input('year', Carbon::now()->year);

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"laporan-analitik-randi-iskandar-{$selectedYear}.csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($selectedYear) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                return;
            }

            // Menulis UTF-8 BOM agar Excel menampilkan aksen bahasa dengan benar
            fwrite($file, "\xEF\xBB\xBF");

            // Header Laporan
            fputcsv($file, ['LAPORAN ANALITIK TAYANGAN, KOMENTAR, DAN ARTIKEL - RANDI ISKANDAR']);
            fputcsv($file, ['Tahun Laporan', (string) $selectedYear]);
            fputcsv($file, ['Tanggal Unduh', Carbon::now()->translatedFormat('d F Y H:i')]);
            fputcsv($file, []);

            // Baris Judul Kolom Bulanan
            fputcsv($file, [
                'No',
                'Bulan',
                'Artikel Diterbitkan',
                'Total Tayangan (Views)',
                'Total Komentar',
                'Komentar Disetujui',
                'Total Apresiasi (Claps)',
                'Rata-rata Tayangan/Artikel',
            ]);

            $totalPosts = 0;
            $totalViews = 0;
            $totalComments = 0;
            $totalApproved = 0;
            $totalClaps = 0;

            for ($m = 1; $m <= 12; $m++) {
                $monthName = $this->indonesianMonths[$m];
                $views = (int) PostView::query()->whereYear('viewed_date', $selectedYear)->whereMonth('viewed_date', $m)->sum('views');
                $comments = (int) Comment::query()->whereYear('created_at', $selectedYear)->whereMonth('created_at', $m)->count();
                $approved = (int) Comment::query()->where('is_approved', true)->whereYear('created_at', $selectedYear)->whereMonth('created_at', $m)->count();
                $posts = (int) Post::published()->whereYear('published_at', $selectedYear)->whereMonth('published_at', $m)->count();
                $claps = (int) Post::published()->whereYear('published_at', $selectedYear)->whereMonth('published_at', $m)->sum('claps_count');

                $avgViews = $posts > 0 ? (int) round($views / $posts) : 0;

                $totalPosts += $posts;
                $totalViews += $views;
                $totalComments += $comments;
                $totalApproved += $approved;
                $totalClaps += $claps;

                fputcsv($file, [
                    $m,
                    $monthName,
                    $posts,
                    $views,
                    $comments,
                    $approved,
                    $claps,
                    $avgViews,
                ]);
            }

            // Baris Total Akumulasi
            fputcsv($file, []);
            fputcsv($file, [
                'TOTAL',
                'Akumulasi 12 Bulan',
                $totalPosts,
                $totalViews,
                $totalComments,
                $totalApproved,
                $totalClaps,
                $totalPosts > 0 ? (int) round($totalViews / $totalPosts) : 0,
            ]);

            fputcsv($file, []);
            fputcsv($file, ['DAFTAR 10 ARTIKEL DENGAN TAYANGAN TERTINGGI']);
            fputcsv($file, ['Peringkat', 'Judul Artikel', 'Kategori', 'Tanggal Terbit', 'Total Tayangan', 'Total Komentar', 'Total Apresiasi']);

            $topPosts = Post::published()
                ->whereYear('published_at', $selectedYear)
                ->with(['category'])
                ->withCount('comments')
                ->orderByDesc('views_count')
                ->limit(10)
                ->get();

            foreach ($topPosts as $idx => $p) {
                fputcsv($file, [
                    $idx + 1,
                    $p->title,
                    $p->category?->name ?? 'Tanpa Kategori',
                    $p->published_at ? $p->published_at->format('Y-m-d') : '-',
                    $p->views_count,
                    $p->comments_count,
                    $p->claps_count,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
