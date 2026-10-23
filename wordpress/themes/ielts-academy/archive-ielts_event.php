<?php

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="min-h-screen bg-slate-50 text-slate-900" dir="rtl">

    <section class="bg-gradient-to-l from-slate-950 via-slate-900 to-sky-950 text-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
            <p class="ielts-events-eyebrow">IELTSCAMP · EVENTS</p>

            <h1 class="text-3xl font-extrabold leading-tight sm:text-5xl">رویدادهای آکادمی IELTS</h1>

            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                در رویدادهای ما شرکت کنید، انگلیسی صحبت کنید
                و مهارت‌های ارتباطی خود را تقویت کنید.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">

        <?php if (have_posts()) : ?>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <?php while (have_posts()) : ?>
                    <?php
                    the_post();

                    $event_id = get_the_ID();

                    $event_date = get_post_meta(
                        $event_id,
                        '_ielts_event_date',
                        true
                    );

                    $event_time = get_post_meta(
                        $event_id,
                        '_ielts_event_time',
                        true
                    );

                    $event_venue = get_post_meta(
                        $event_id,
                        '_ielts_event_venue',
                        true
                    );
                    ?>

                    <article <?php post_class('ielts-event-card group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl'); ?>>

                        <a
                            class="group block aspect-[16/9] overflow-hidden bg-slate-100"
                            href="<?php the_permalink(); ?>"
                            aria-label="<?php
                                echo esc_attr(
                                    'مشاهده رویداد ' . get_the_title()
                                );
                            ?>"
                        >
                            <?php if (has_post_thumbnail()) : ?>
                               
                                <?php
                                the_post_thumbnail(
                                    'large',
                                    [
                                        'loading' => 'lazy',
                                        'class' => 'h-full w-full object-cover transition duration-500 group-hover:scale-105',
                                    ]
                                );
                                ?>

                            <?php else : ?>

                                <div class="ielts-event-placeholder">
                                    <span>IELTSCAMP</span>
                                    <strong>English Speaking Club</strong>
                                </div>

                            <?php endif; ?>
                        </a>

                        <div class="p-5 sm:p-7">

                            <?php if ($event_date) : ?>
                                <p class="mb-3 text-sm font-bold text-sky-700">
                                    <span>تاریخ برگزاری:</span>
                                    <?php echo esc_html($event_date); ?>
                                </p>
                            <?php endif; ?>

                            <h2 class="mb-3 text-xl font-extrabold leading-8 text-slate-900 sm:text-2xl">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <div class="mb-6 flex flex-wrap gap-x-4 gap-y-2 text-sm text-slate-500">
                                <?php the_excerpt(); ?>
                            </div>

                            <div class="mb-6 flex flex-wrap gap-x-4 gap-y-2 text-sm text-slate-500">

                                <?php if ($event_time) : ?>
                                    <span>
                                        ساعت:
                                        <?php echo esc_html($event_time); ?>
                                    </span>
                                <?php endif; ?>

                                <?php if ($event_venue) : ?>
                                    <span>
                                        مکان:
                                        <?php echo esc_html($event_venue); ?>
                                    </span>
                                <?php endif; ?>

                            </div>

                            <a
                                class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-5 py-3 font-bold text-white transition hover:bg-sky-700"
                                href="<?php the_permalink(); ?>"
                            >
                                مشاهده جزئیات رویداد
                                <span aria-hidden="true">←</span>
                            </a>

                        </div>

                    </article>

                <?php endwhile; ?>

            </div>

            <div class="ielts-events-pagination">
                <?php
                the_posts_pagination([
                    'mid_size'  => 1,
                    'prev_text' => 'قبلی',
                    'next_text' => 'بعدی',
                ]);
                ?>
            </div>

        <?php else : ?>

            <div class="ielts-events-empty">
                <h2>در حال آماده‌سازی رویدادهای جدید هستیم</h2>

                <p>
                    به‌زودی رویدادهای آموزشی IELTSCAMP
                    در این صفحه منتشر خواهند شد.
                </p>
            </div>

        <?php endif; ?>

    </section>

</main>

<?php get_footer(); ?>
