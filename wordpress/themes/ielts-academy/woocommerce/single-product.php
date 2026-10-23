<?php
defined('ABSPATH') || exit;

get_header('shop');
?>

<main class="min-h-screen bg-slate-50 py-10 sm:py-14" dir="rtl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <?php
        while (have_posts()) :
            the_post();
            global $product;

            if (!$product || !is_a($product, 'WC_Product')) {
                $product = wc_get_product(get_the_ID());
            }

            if (!$product) {
                continue;
            }
        ?>

            <article <?php post_class('overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm'); ?>>

                <div class="grid gap-8 p-5 sm:p-8 lg:grid-cols-2 lg:gap-12 lg:p-12">

                    <div class="flex flex-col gap-5">
                        <span class="inline-flex w-fit rounded-full bg-indigo-50 px-4 py-2 text-sm font-bold text-indigo-700">
                            دوره آموزشی IELTS
                        </span>

                        <h1 class="text-3xl font-black leading-relaxed text-slate-900 sm:text-4xl">
                            <?php the_title(); ?>
                        </h1>

                        <?php if ($product->get_short_description()) : ?>
                            <div class="text-base leading-8 text-slate-600">
                                <?php echo wp_kses_post(wpautop($product->get_short_description())); ?>
                            </div>
                        <?php endif; ?>

                        <div class="rounded-2xl bg-slate-50 p-5">
                            <p class="mb-2 text-sm text-slate-500">قیمت دوره</p>
                            <div class="text-2xl font-black text-indigo-700">
                                <?php echo wp_kses_post($product->get_price_html()); ?>
                            </div>
                        </div>

                        <?php if ($product->is_purchasable() && $product->is_in_stock()) : ?>
                            <div class="product-purchase">
                                <?php woocommerce_template_single_add_to_cart(); ?>
                            </div>
                        <?php else : ?>
                            <p class="rounded-xl bg-amber-50 p-4 text-amber-800">
                                این محصول در حال حاضر قابل خرید نیست.
                            </p>
                        <?php endif; ?>

                        <p class="text-sm leading-7 text-slate-500">
                            پس از تکمیل خرید، وضعیت سفارش در حساب کاربری شما قابل پیگیری است.
                        </p>
                    </div>

                    <div class="overflow-hidden rounded-2xl bg-slate-100">
                        <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail(
                                'woocommerce_single',
                                [
                                    'class' => 'h-full min-h-72 w-full object-cover',
                                    'loading' => 'eager',
                                ]
                            );
                        } else {
                            echo wc_placeholder_img(
                                'woocommerce_single',
                                ['class' => 'h-full w-full object-contain']
                            );
                        }
                        ?>
                    </div>

                </div>

                <div class="border-t border-slate-200 p-5 sm:p-8 lg:p-12">
                    <h2 class="mb-5 text-2xl font-black text-slate-900">
                        توضیحات دوره
                    </h2>

                    <div class="prose prose-slate max-w-none leading-9">
                        <?php the_content(); ?>
                    </div>
                </div>

            </article>

        <?php endwhile; ?>

    </div>
</main>

<?php
get_footer('shop');
?>
