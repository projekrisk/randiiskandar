@php
    $isEnabled = \App\Models\Setting::get('ad_in_article_enabled', '1') !== '0';
    $customCode = \App\Models\Setting::get('ad_in_article_code');
@endphp

@if($isEnabled)
    @if(!empty(trim($customCode ?? '')))
        <div class="my-10 text-center not-prose adsense-in-article-container">
            {!! $customCode !!}
        </div>
    @else
        <div class="my-10 p-4 sm:p-6 rounded-sm border border-dashed border-zinc-300 dark:border-zinc-800 bg-zinc-50/70 dark:bg-zinc-900/40 relative text-center not-prose adsense-in-article-container">
            <div class="flex items-center justify-between mb-2 font-sans text-xs">
                <span class="text-xs md:text-sm font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400">
                    IKLAN • GOOGLE ADSENSE
                </span>
                <span class="text-xs md:text-sm uppercase font-semibold text-zinc-400 dark:text-zinc-500">
                    SPONSOR EDITORIAL
                </span>
            </div>

            <div class="w-full min-h-[120px] sm:min-h-[180px] flex flex-col items-center justify-center p-4">
                <div class="adsense-fallback flex flex-col items-center justify-center py-4 text-zinc-400 dark:text-zinc-500 font-sans">
                    <i class="ph ph-google-logo text-2xl mb-1 text-zinc-400"></i>
                    <span class="text-xs md:text-sm font-semibold uppercase tracking-wider">Slot Iklan In-Article Google AdSense</span>
                    <span class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">Format Fluid Responsif Otomatis (Dapat dikonfigurasi di Pengaturan)</span>
                </div>
            </div>
        </div>
    @endif
@endif
