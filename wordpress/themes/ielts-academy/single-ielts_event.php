<?php

if (! defined('ABSPATH')) {
    exit;
}

get_header();

?>

<main class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8" dir="rtl">

    <?php if (have_posts()) : ?>

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

            $registration_url = get_post_meta(
                $event_id,
                '_ielts_event_registration_url',
                true
            );
            ?>

            <article class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">

                <header class="bg-slate-900 px-6 py-10 text-white sm:px-10 sm:py-14">

                    <p class="mb-4 text-sm font-semibold uppercase tracking-widest text-sky-300">
                        IELTSCAMP · EVENTS
                    </p>

                    <h1 class="text-3xl font-extrabold leading-tight sm:text-5xl">
                        <?php the_title(); ?>
                    </h1>

                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-200 sm:text-lg">
                        در رویدادهای IELTSCAMP شرکت کنید،
                        مهارت‌های زبان انگلیسی خود را تقویت کنید
                        و با اعتمادبه‌نفس بیشتری ارتباط برقرار کنید.
                    </p>

                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="aspect-video overflow-hidden bg-slate-100">
                        <?php
                        the_post_thumbnail(
                            'large',
                            [
                                'class' => 'h-full w-full object-cover',
                            ]
                        );
                        ?>
                    </div>
                <?php endif; ?>

                <div class="grid gap-8 p-6 sm:p-10 lg:grid-cols-[1fr_280px]">

                    <div class="min-w-0">

                        <div class="mb-8">
                            <h2 class="mb-4 text-xl font-bold text-slate-900">
                                درباره رویداد
                            </h2>

                            <div class="space-y-4 leading-8 text-slate-700">
                                <?php the_content(); ?>
                            </div>
                        </div>

                        <a
                            href="<?php echo esc_url(get_post_type_archive_link('ielts_event')); ?>"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 px-5 py-3 font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            <span aria-hidden="true">→</span>
                            بازگشت به همه رویدادها
                        </a>

                    </div>

                    <aside class="h-fit rounded-2xl bg-blue-300">

                        <h2 class="mb-5 text-lg font-bold text-slate-900">
                            اطلاعات رویداد
                        </h2>

                        <dl class="space-y-5">

                            <div>
                                <dt class="mb-1 text-sm text-slate-500">
                                    تاریخ برگزاری
                                </dt>
                                <dd class="font-semibold text-slate-900">
                                    <?php
                                    echo $event_date
                                        ? esc_html($event_date)
                                        : 'به‌زودی اعلام می‌شود';
                                    ?>
                                </dd>
                            </div>

                            <div>
                                <dt class="mb-1 text-sm text-slate-500">
                                    ساعت
                                </dt>
                                <dd class="font-semibold text-slate-900">
                                    <?php
                                    echo $event_time
                                        ? esc_html($event_time)
                                        : 'به‌زودی اعلام می‌شود';
                                    ?>
                                </dd>
                            </div>

                            <div>
                                <dt class="mb-1 text-sm text-slate-500">
                                    محل برگزاری
                                </dt>
                                <dd class="font-semibold text-slate-900">
                                    <?php
                                    echo $event_venue
                                        ? esc_html($event_venue)
                                        : 'به‌زودی اعلام می‌شود';
                                    ?>
                                </dd>
                            </div>

                            <div>
                                <dt class="mb-1 text-sm text-slate-500">
                                    نوع رویداد
                                </dt>
                                <dd class="font-semibold text-slate-900">
                                    حضوری
                                </dd>
                            </div>

                        </dl>

                        <div class="mt-7">

                            <!-- <?php if ($registration_url) : ?>

                                <a
                                    href="<?php echo esc_url($registration_url); ?>"
                                    class="block rounded-xl bg-sky-600 px-5 py-3 text-center  font-bold  text-white transition hover:bg-sky-700"
                                >
                                    ثبت‌نام در رویداد
                                </a>

                            <?php else : ?> -->
                             <?php ieltscamp_render_event_registration_form($event_id); ?>

                                <!-- <p class="rounded-xl bg-white p-4 text-center text-sm leading-7 text-slate-600 ring-1 ring-slate-200">
                                    اطلاعات ثبت‌نام به‌زودی اعلام می‌شود.
                                </p> -->

                            <?php endif; ?>

                        </div>

                    </aside>

                </div>

            </article>

        <?php endwhile; ?>

    <?php else : ?>

        <div class="rounded-2xl border border-gray-200 p-8 text-center">
            <h1 class="text-2xl font-bold">
                رویداد موردنظر پیدا نشد.
            </h1>

            <a
                href="<?php echo esc_url(home_url('/events/')); ?>"
                class="mt-5 inline-block font-semibold text-sky-700"
            >
                مشاهده همه رویدادها
            </a>
        </div>

    <?php endif; ?>

</main>

<?php get_footer(); ?>
