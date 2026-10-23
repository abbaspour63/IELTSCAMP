
<?php
/**
 * Blog page - IELTSCAMPS
 */

get_header();
?>

<main dir="rtl" class="min-h-screen bg-slate-950 px-4 py-16 text-slate-100 sm:px-6">

    <div class="mx-auto max-w-7xl">

        <header class="mb-12 text-center">
            <p class="mb-3 text-sm font-semibold text-sky-400">
                IELTSCAMP BLOG
            </p>

            <h1 class="text-3xl font-extrabold sm:text-5xl">
                مجله آموزشی آیلتس
            </h1>

            <p class="mx-auto mt-5 max-w-2xl leading-8 text-slate-400">
                نکات کاربردی، تکنیک‌های آزمون و منابع یادگیری زبان انگلیسی
                برای رسیدن به نمره دلخواه شما.
            </p>
        </header>

        <?php if ( have_posts() ) : ?>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <?php while ( have_posts() ) : the_post(); ?>

                    <article class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900 transition duration-300 hover:-translate-y-1 hover:border-sky-500">

                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>"
                               class="block overflow-hidden">
                                <?php
                                the_post_thumbnail(
                                    'large',
                                    array(
                                        'class' => 'h-56 w-full object-cover transition duration-500 hover:scale-105',
                                        'loading' => 'lazy',
                                    )
                                );
                                ?>
                            </a>
                        <?php endif; ?>

                        <div class="p-6">

                            <p class="mb-3 text-sm text-sky-400">
                                <?php echo esc_html( ielts_academy_jalali_date( get_post_timestamp() ) ); ?>
                            </p>

                            <h2 class="mb-4 text-xl font-bold leading-8">
                                <a href="<?php the_permalink(); ?>"
                                   class="transition hover:text-sky-400">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <div class="mb-5 leading-7 text-slate-400">
                                <?php the_excerpt(); ?>
                            </div>

                            <a href="<?php the_permalink(); ?>"
                               class="inline-flex items-center gap-2 font-semibold text-sky-400 hover:text-sky-300">
                                ادامه مطلب
                                <span aria-hidden="true">←</span>
                            </a>

                        </div>
                    </article>

                <?php endwhile; ?>

            </div>

            <nav class="mt-12 flex justify-center gap-4"
                 aria-label="صفحه‌بندی مقالات">
                <?php
                the_posts_pagination(
                    array(
                        'prev_text' => 'مقالات جدیدتر',
                        'next_text' => 'مقالات قدیمی‌تر',
                    )
                );
                ?>
            </nav>

        <?php else : ?>

            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-10 text-center">
                <h2 class="text-2xl font-bold">
                    هنوز مقاله‌ای منتشر نشده است
                </h2>

                <p class="mt-4 leading-8 text-slate-400">
                    به‌زودی مطالب آموزشی و نکات آمادگی آزمون آیلتس
                    در این بخش منتشر خواهند شد.
                </p>
            </div>

        <?php endif; ?>

    </div>

</main>

<?php get_footer(); ?>
