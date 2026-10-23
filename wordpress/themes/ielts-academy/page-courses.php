<?php
/**
 * Template Name: فهرست دوره‌های IELTS
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$allowed_skills = [
    'listening',
    'reading',
    'writing',
    'speaking',
    
];

$skill = isset($_GET['skill'])
    ? sanitize_key(wp_unslash($_GET['skill']))
    : '';

if (!in_array($skill, $allowed_skills, true)) {
    $skill = '';
}

$args = [
    'post_type'      => 'ielts_course',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
];

if ($skill !== '') {
    $args['tax_query'] = [
        [
            'taxonomy' => 'ielts_course_category',
            'field'    => 'slug',
            'terms'    => $skill,
        ],
    ];
}

$courses = new WP_Query($args);

$skill_names = [
    'listening' => 'Listening — شنیداری',
    'reading'   => 'Reading — خواندن',
    'writing'   => 'Writing — نوشتاری',
    'speaking'  => 'Speaking — گفتاری',
    
];
?>

<main dir="rtl" class="min-h-screen bg-slate-50 py-16">
    <div class="mx-auto max-w-7xl px-6">

        <header class="mb-10 text-center">
            <h1 class="text-3xl font-extrabold text-slate-900 md:text-4xl">
                <?php
                echo esc_html(
                    $skill !== ''
                        ? ($skill_names[$skill] ?? 'دوره‌های IELTS')
                        : 'دوره‌های IELTS'
                );
                ?>
            </h1>

            <p class="mt-4 leading-8 text-slate-600">
                دوره مناسب خودت را انتخاب کن و مسیر یادگیری آیلتس را شروع کن.
            </p>
        </header>

        <nav class="mb-10 flex flex-wrap justify-center gap-3" aria-label="دسته‌بندی دوره‌ها">
            <a
                href="<?php echo esc_url(get_permalink()); ?>"
                class="rounded-xl bg-white px-4 py-2 font-bold text-slate-700 shadow-sm hover:bg-blue-50">
                همه دوره‌ها
            </a>

            <?php foreach ($skill_names as $slug => $label) : ?>
                <a
                    href="<?php echo esc_url(add_query_arg('skill', $slug, get_permalink())); ?>"
                    class="rounded-xl bg-white px-4 py-2 font-bold text-slate-700 shadow-sm hover:bg-blue-50">
                    <?php echo esc_html($label); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <?php if ($courses->have_posts()) : ?>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <?php while ($courses->have_posts()) : $courses->the_post(); ?>

                    <?php
                    $course_id = get_the_ID();

                    $product_id = absint(
                        get_post_meta(
                            $course_id,
                            '_ielts_course_product_id',
                            true
                        )
                    );

                    $product = function_exists('wc_get_product') && $product_id
                        ? wc_get_product($product_id)
                        : false;
                    ?>

                    <article class="flex flex-col rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

                        <h2 class="text-xl font-extrabold text-slate-900">
                            <?php the_title(); ?>
                        </h2>

                        <div class="mt-4 leading-8 text-slate-600">
                            <?php
                            if (has_excerpt()) {
                                the_excerpt();
                            } else {
                                echo esc_html(
                                    wp_trim_words(
                                        wp_strip_all_tags(get_the_content()),
                                        30
                                    )
                                );
                            }
                            ?>
                        </div>

                        <div class="mt-auto pt-6">

                            <?php if ($product && $product->is_visible()) : ?>

                                <div class="mb-4 text-lg font-extrabold text-blue-800">
                                    <?php echo wp_kses_post($product->get_price_html()); ?>
                                </div>

                                <a
                                    href="<?php echo esc_url(get_permalink($product_id)); ?>"
                                    class="inline-flex rounded-xl bg-blue-700 px-5 py-3 font-bold text-white transition hover:bg-blue-800">
                                    مشاهده محصول و خرید
                                </a>

                            <?php else : ?>

                                <p class="text-sm font-bold text-amber-700">
                                    اطلاعات خرید این دوره هنوز تکمیل نشده است.
                                </p>

                            <?php endif; ?>

                        </div>
                    </article>

                <?php endwhile; ?>

            </div>

        <?php else : ?>

            <div class="rounded-2xl bg-white p-8 text-center shadow-sm">
                <p class="text-lg font-bold text-slate-700">
                    در این دسته هنوز دوره‌ای منتشر نشده است.
                </p>
            </div>

        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div>
</main>

<?php get_footer(); ?>
