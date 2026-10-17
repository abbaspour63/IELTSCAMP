
<?php
/**
 * Front Page - IELTS Academy
 */

get_header();
?>

<main dir="rtl" class="bg-slate-50 text-slate-800">



<!-- Hero: IELTS Academy -->


<?php get_template_part('template-parts/hero');  ?>
<?php get_template_part( 'template-parts/course-cards' ); ?>



    <!-- Latest blog posts -->

<section class="py-16 md:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4">

        <div class="flex items-center justify-between mb-10">
            <div>
                <p class="text-blue-600 font-semibold mb-2">
                    مجله آموزشی
                </p>
                <h2 class="text-3xl font-['Vazirmatn']  md:text-4xl font-bold text-slate-900">
                بلاگ
                </h2>
            </div>

            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>"
               class="hidden sm:inline-flex text-blue-700 font-bold hover:text-blue-900">
                همه مقالات
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <?php
            $articles = new WP_Query([
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'post_status'    => 'publish',
            ]);

            if ($articles->have_posts()) :
                while ($articles->have_posts()) :
                    $articles->the_post();
            ?>

                <article class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-lg transition-shadow">

                    <a href="<?php the_permalink(); ?>"
                       class="block overflow-hidden bg-slate-100">

                        <?php if (has_post_thumbnail()) : ?>

                            <?php the_post_thumbnail('large', [
                                'class' => 'w-full h-56 object-cover',
                                'loading' => 'lazy',
                            ]); ?>

                        <?php else : ?>

                            <div class="w-full h-56 flex items-center justify-center text-slate-400">
                                تصویر مقاله ثبت نشده است
                            </div>

                        <?php endif; ?>

                    </a>

                    <div class="p-6">

                        <!-- <p class="text-sm text-slate-500 mb-3">
                            <?php echo esc_html(get_the_date()); ?>
                        </p> -->

                        <h3 class="text-xl font-bold text-slate-900 leading-8 mb-3">
                            <a href="<?php the_permalink(); ?>"
                               class="hover:text-blue-700">
                                <?php the_title(); ?>
                            </a>
                        </h3>

                        <p class="text-slate-600 leading-7 mb-6">
                            <?php echo esc_html(
                                wp_trim_words(
                                    get_the_excerpt(),
                                    22,
                                    '...'
                                )
                            ); ?>
                        </p>

                        <a href="<?php the_permalink(); ?>"
                           class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 rounded-xl transition-colors">
                            ادامه مطلب
                            <span aria-hidden="true">←</span>
                        </a>

                    </div>
                </article>

            <?php
                endwhile;
            else :
            ?>

                <p class="text-slate-600">
                    هنوز مقاله‌ای منتشر نشده است.
                </p>

            <?php
            endif;
            wp_reset_postdata();
            ?>

        </div>
    </div>
</section>


    <!-- Call to action -->
    <section class="bg-blue-950 px-6 py-16 text-center text-white">
        <div class="mx-auto max-w-3xl">
            <h2 class="text-3xl font-extrabold">
                مسیر یادگیری خودت را شروع کن
            </h2>

            <p class="mt-4 leading-8 text-blue-100">
                برای دسترسی به امکانات آموزشی، وارد حساب کاربری خود شو.
            </p>

            <a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>"
               class="mt-8 inline-block rounded-xl bg-cyan-400 px-7 py-3 font-bold text-blue-950 transition hover:bg-cyan-300">
                ورود به آکادمی
            </a>
        </div>
    </section>

</main>

<?php get_footer(); ?>
