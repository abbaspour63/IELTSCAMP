
<?php
/**
 * Category Archive - IELTS Academy
 */

get_header();

$category_title = single_cat_title('', false);
$category_description = category_description();
?>

<main dir="rtl" class="min-h-screen bg-slate-950 px-4 py-16 text-slate-100 sm:px-6">

    <div class="mx-auto max-w-7xl">

        <!-- Category Header -->
        <header class="mb-12 text-center">

            <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-sky-400">
                IELTS ACADEMY BLOG
            </p>

            <h1 class="text-3xl font-extrabold sm:text-5xl">
                <?php echo esc_html($category_title); ?>
            </h1>

            <?php if ( ! empty($category_description) ) : ?>
                <div class="mx-auto mt-5 max-w-2xl leading-8 text-slate-400">
                    <?php echo wp_kses_post($category_description); ?>
                </div>
            <?php else : ?>
                <p class="mx-auto mt-5 max-w-2xl leading-8 text-slate-400">
                    مقالات آموزشی و نکات کاربردی این بخش را بخوانید
                    و مهارت‌های خود را برای آزمون آیلتس تقویت کنید.
                </p>
            <?php endif; ?>

        </header>

        <!-- Articles -->
        <?php if ( have_posts() ) : ?>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <?php while ( have_posts() ) : the_post(); ?>

                    <article <?php post_class('overflow-hidden rounded-2xl border border-slate-800 bg-slate-900 transition duration-300 hover:-translate-y-1 hover:border-sky-500'); ?>>

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
                               class="inline-flex items-center gap-2 font-semibold text-sky-400 transition hover:text-sky-300">
                                ادامه مطلب
                                <span aria-hidden="true">←</span>
                            </a>

                        </div>

                    </article>

                <?php endwhile; ?>

            </div>

            <!-- Pagination -->
            <nav class="mt-12 flex justify-center"
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

            <section class="rounded-2xl border border-slate-800 bg-slate-900 p-10 text-center">

                <h2 class="text-2xl font-bold">
                    هنوز مقاله‌ای در این دسته منتشر نشده است
                </h2>

                <p class="mt-4 leading-8 text-slate-400">
                    به‌زودی مطالب آموزشی این بخش منتشر خواهند شد.
                </p>

                <a href="<?php echo esc_url(home_url('/blog/')); ?>"
                   class="mt-6 inline-block rounded-lg bg-sky-500 px-5 py-3 font-semibold text-slate-950 transition hover:bg-sky-400">
                    بازگشت به وبلاگ
                </a>

            </section>

        <?php endif; ?>

    </div>

</main>

<?php get_footer(); ?>