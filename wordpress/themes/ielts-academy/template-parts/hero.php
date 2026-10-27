<?php
/**
 * IELTSCAMP - Hero Section
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<section
    id="ielts-hero"
    dir="rtl"
    class="relative isolate overflow-hidden bg-gradient-to-br from-[#F8F7EC] via-white to-[#D9D28B]/50 py-16 sm:py-20 lg:py-24"
>
    <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-6 lg:grid-cols-2 lg:gap-16">

        <div class="relative z-10 text-right">
            <span class="inline-flex items-center gap-2 rounded-full border border-[#B2BD7E] bg-white/80 px-4 py-2 text-sm font-extrabold text-[#46533C] shadow-sm">
                <span class="h-2 w-2 rounded-full bg-[#6A795D]"></span>
                مسیر حرفه‌ای آمادگی آیلتس
            </span>

            <h1 class="mt-6 text-4xl font-extrabold leading-tight text-[#293326] sm:text-5xl lg:text-6xl">
                مسیر رسیدن به
                <span class="text-[#6A795D]">نمره هدف IELTS</span>
                از اینجا شروع می‌شود
            </h1>

            <p class="mt-6 max-w-xl text-base leading-8 text-[#59634F] sm:text-lg">
                با دوره‌های هدفمند، تمرین چهار مهارت اصلی و مشاوره تخصصی،
                برای رسیدن به نمره موردنظرت برنامه‌ریزی کن.
            </p>

            <div class="mt-8 flex flex-wrap gap-4">
                <a
                    href="<?php echo esc_url( home_url( '/courses/' ) ); ?>"
                    class="inline-flex items-center justify-center rounded-xl bg-[#6A795D] px-6 py-3 font-bold text-white transition hover:bg-[#46533C]"
                >
                    مشاهده دوره‌ها
                </a>

                <a
                    href="<?php echo esc_url( home_url( '/consultation/' ) ); ?>"
                    class="inline-flex items-center justify-center rounded-xl border border-[#B2BD7E] bg-white px-6 py-3 font-bold text-[#46533C] transition hover:bg-[#F8F7EC]"
                >
                    رزرو مشاوره
                </a>
            </div>

            <div class="mt-8 flex flex-wrap gap-6 text-sm font-medium text-[#46533C]">
                <span>✓ تمرکز بر چهار مهارت</span>
                <span>✓ مسیر یادگیری هدفمند</span>
            </div>
        </div>

        <div class="relative mx-auto w-full max-w-xl">
            <div
                id="ielts-hero-3d"
                class="relative h-[320px] w-full overflow-hidden rounded-3xl border border-[#B2BD7E]/60 bg-gradient-to-br from-[#F8F7EC] via-white to-[#D9D28B]/50 shadow-xl sm:h-[400px] lg:h-[460px]"
                role="img"
                aria-label="صحنه سه‌بعدی آکادمی آیلتس"
            ></div>

            <!-- <div class="relative mx-auto -mt-6 flex w-[90%] items-center justify-between rounded-2xl border border-[#B2BD7E]/60 bg-white/90 p-5 shadow-lg">
                <div>
                    <p class="text-sm font-bold text-slate-900">Your IELTS Journey</p>
                    <p class="mt-1 text-xs text-slate-500">Learn · Practice · Achieve</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#6A795D] text-xl font-extrabold text-white">
                    7+
                </div>
            </div>
        </div> -->

    </div>
</section>