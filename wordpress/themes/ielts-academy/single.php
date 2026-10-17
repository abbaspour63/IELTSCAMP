
<?php
/**
 * Single Blog Post 
 */

get_header();
?>

<main dir="rtl" class="min-h-screen bg-slate-950 px-4 py-12 text-slate-100 sm:px-6">
    <div class="mx-auto max-w-4xl">

        <?php if ( have_posts() ) : ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <article <?php post_class('overflow-hidden rounded-2xl border border-slate-800 bg-slate-900'); ?>>

                    <header class="p-6 sm:p-10">
                        <a href="<?php echo esc_url(home_url('/blog/')); ?>"
                           class="mb-8 inline-flex items-center gap-2 text-sm text-sky-400 transition hover:text-sky-300">
                            <span aria-hidden="true">→</span>
                            بازگشت به مجله آموزشی
                        </a>

                        <p class="mb-4 text-sm text-sky-400">
                            <?php echo esc_html( ielts_academy_jalali_date( get_post_timestamp() ) ); ?>
                        </p>

                        <h1 class="text-3xl font-extrabold leading-relaxed sm:text-4xl">
                            <?php the_title(); ?>
                        </h1>

                        <div class="mt-5 text-sm text-slate-400">
                            نویسنده:
                            <?php echo esc_html(get_the_author()); ?>
                        </div>
                    </header>

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="px-6 sm:px-10">
                            <?php
                            the_post_thumbnail(
                                'large',
                                array(
                                    'class' => 'max-h-[500px] w-full rounded-xl object-cover',
                                    'loading' => 'eager',
                                )
                            );
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="px-6 py-8 sm:px-10 sm:py-10">
                        <div class="prose prose-invert max-w-none leading-9 text-slate-300">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <footer class="border-t border-slate-800 px-6 py-6 sm:px-10">
                        <div class="flex flex-wrap items-center justify-between gap-4">

                            <div class="text-sm text-slate-400">
                                دسته‌بندی:
                                <?php the_category('، '); ?>
                            </div>

                            <a href="<?php echo esc_url(home_url('/blog/')); ?>"
                               class="rounded-lg bg-sky-500 px-5 py-3 font-semibold text-slate-950 transition hover:bg-sky-400">
                                بازگشت به مقالات
                            </a>

                        </div>
                    </footer>

                </article>

            <?php endwhile; ?>

        <?php else : ?>

            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-10 text-center">
                <h1 class="text-2xl font-bold">مقاله‌ای پیدا نشد</h1>
                <p class="mt-4 text-slate-400">
                    این مقاله ممکن است حذف شده باشد یا در دسترس نباشد.
                </p>
                <a href="<?php echo esc_url(home_url('/blog/')); ?>"
                   class="mt-6 inline-block text-sky-400 hover:text-sky-300">
                    بازگشت به وبلاگ
                </a>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>