@php
    $isEnabled = \App\Models\Setting::get('ad_bottom_enabled', '1') !== '0';
    $customCode = \App\Models\Setting::get('ad_bottom_code');
@endphp

@if($isEnabled)
    @if(!empty(trim($customCode ?? '')))
        <div class="my-10 text-center not-prose adsense-bottom-container">
            {!! $customCode !!}
        </div>
    @else
        <div class="my-12 p-4 sm:p-6 rounded-sm border border-dashed border-zinc-300 dark:border-zinc-800 bg-zinc-50/70 dark:bg-zinc-900/40 relative text-center not-prose adsense-bottom-container">
            <div class="flex items-center justify-between mb-2 font-sans text-xs">
                <span class="text-xs md:text-sm font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400">
                    IKLAN • GOOGLE ADSENSE
                </span>
                <span class="text-xs md:text-sm uppercase font-semibold text-zinc-400 dark:text-zinc-500">
                    ADVERTISEMENT
                </span>
            </div>

            <div class="w-full min-h-[140px] sm:min-h-[200px] flex flex-col items-center justify-center p-4">
                <div class="adsense-fallback flex flex-col items-center justify-center py-6 text-zinc-400 dark:text-zinc-500 font-sans">
                    <i class="ph ph-google-logo text-3xl mb-2 text-zinc-400"></i>
                    <span class="text-xs md:text-sm font-semibold uppercase tracking-wider">Slot Iklan Bawah Artikel Google AdSense</span>
                    <span class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">Unit Display Responsif (Dapat dikonfigurasi di Pengaturan)</span>
                </div>
            </div>
        </div>
    @endif
@endif
