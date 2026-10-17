<?php


if (! defined('ABSPATH')) {
    exit;
}

$skills = [
    [
        'name'        => 'Listening',
        'fa_name'     => 'مهارت شنیداری',
        'description' => 'تقویت درک مکالمات و فایل‌های صوتی انگلیسی',
        'number'      => '01',
        'color'       => 'bg-sky-100',
        'accent'      => 'text-sky-700',
        'image' => 'listening.webp',
        'href'        => home_url('/courses/'),
    ],
    [
        'name'        => 'Reading',
        'fa_name'     => 'مهارت خواندن',
        'description' => 'افزایش سرعت درک مطلب و پاسخ‌گویی به سؤالات',
        'number'      => '02',
        'color'       => 'bg-amber-100',
        'accent'      => 'text-amber-700',
        'image' => 'reading.webp',
        'href'        => home_url('/courses/'),
    ],
    [
        'name'        => 'Writing',
        'fa_name'     => 'مهارت نوشتاری',
        'description' => 'تمرین نوشتن پاسخ‌های استاندارد برای Task 1 و Task 2',
        'number'      => '03',
        'color'       => 'bg-indigo-100',
        'accent'      => 'text-indigo-700',
        'image' => 'writing .webp',
        'href'        => home_url('/courses/'),
    ],
    [
        'name'        => 'Speaking',
        'fa_name'     => 'مهارت گفتاری',
        'description' => 'تمرین مکالمه و آمادگی برای مصاحبه آیلتس',
        'number'      => '04',
        'color'       => 'bg-rose-100',
        'accent'      => 'text-rose-700',
        'image' => 'speaking.webp',
        'href'        => home_url('/courses/'),
    ],
];
?>

<section
    id="ielts-skills"
    dir="rtl"
    class="relative overflow-hidden bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-6">

        <div class="mx-auto mb-12 max-w-2xl text-center">
            <span class="inline-flex rounded-full border border-blue-100 bg-white px-4 py-2 text-sm font-bold text-blue-800 shadow-sm">
                مسیر یادگیری IELTS
            </span>

            <h2 class="mt-5 text-3xl font-extrabold leading-relaxed text-slate-950 md:text-4xl">
                چهار مهارت، یک هدف
            </h2>

            <p class="mt-4 leading-8 text-slate-600">
                مهارت‌های اصلی آیلتس را هدفمند تمرین کن و قدم‌به‌قدم به نمره دلخواهت نزدیک شو.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

            <?php foreach ($skills as $skill) : ?>
                <article
                    class="group relative flex min-h-72 flex-col overflow-hidden rounded-3xl border border-white bg-white p-6 shadow-[0_12px_35px_rgba(30,64,175,0.08)] transition duration-300 ease-out hover:-translate-y-2 hover:shadow-[0_22px_45px_rgba(30,64,175,0.16)] motion-reduce:transform-none motion-reduce:transition-none">
                    <div class="absolute -left-8 -top-8 h-28 w-28 rounded-full <?php echo esc_attr($skill['color']); ?> opacity-70 transition duration-500 group-hover:scale-125 motion-reduce:transform-none" aria-hidden="true"></div>

                    <div class="relative flex items-center justify-between">
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl <?php echo esc_attr($skill['color']); ?> <?php echo esc_attr($skill['accent']); ?> text-xl font-extrabold shadow-sm transition duration-300 group-hover:rotate-3 group-hover:scale-105 motion-reduce:transform-none">
                            <?php echo esc_html($skill['number']); ?>
                        </span>

                        <span class="text-xs font-bold tracking-widest text-slate-400">
                            IELTS
                        </span>
                    </div>


                    <div class="relative mt-7">
                        <h3 class="text-xl font-extrabold text-slate-900">
                            <?php echo esc_html($skill['name']); ?>

                        </h3>
                        <div class="relative mt-5 flex h-36 items-center justify-center">
                            <img
                                src="<?php echo esc_url(
                                            get_theme_file_uri('assets/images/' . $skill['image'])
                                        ); ?>"
                                alt="<?php echo esc_attr($skill['fa_name']); ?>"
                                class="h-full w-full object-contain transition-transform duration-500 group-hover:scale-110"
                                loading="lazy"
                                decoding="async">
                        </div>
                        <p class="mt-2 text-sm font-bold <?php echo esc_attr($skill['accent']); ?>">
                            <?php echo esc_html($skill['fa_name']); ?>
                        </p>

                        <p class="mt-4 min-h-14 text-sm leading-7 text-slate-600">
                            <?php echo esc_html($skill['description']); ?>
                        </p>
                    </div>

                    <a
                        href="<?php echo esc_url($skill['href']); ?>"
                        class="relative mt-6 inline-flex w-fit items-center gap-2 rounded-xl px-3 py-2 text-sm font-bold text-blue-800 transition-colors hover:bg-blue-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                        مشاهده دوره‌ها
                        <span aria-hidden="true">←</span>
                    </a>
                </article>
            <?php endforeach; ?>

        </div>
    </div>
</section>