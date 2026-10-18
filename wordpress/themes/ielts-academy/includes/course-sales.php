<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * افزودن فیلد انتخاب محصول ووکامرس به صفحه ویرایش دوره
 */
add_action('add_meta_boxes_ielts_course', 'ielts_academy_add_course_product_metabox');

function ielts_academy_add_course_product_metabox() {
    if (!class_exists('WooCommerce')) {
        return;
    }

    add_meta_box(
        'ielts_course_product',
        'محصول فروش دوره در ووکامرس',
        'ielts_academy_render_course_product_metabox',
        'ielts_course',
        'side',
        'default'
    );
}

function ielts_academy_render_course_product_metabox($post) {
    $selected_product_id = absint(
        get_post_meta($post->ID, '_ielts_course_product_id', true)
    );

    wp_nonce_field(
        'ielts_academy_save_course_product',
        'ielts_academy_course_product_nonce'
    );

    $products = wc_get_products([
        'status'  => 'publish',
        'limit'   => -1,
        'orderby' => 'name',
        'order'   => 'ASC',
        'return'  => 'objects',
    ]);
    ?>

    <p>
        محصولی را انتخاب کنید که با خرید آن، دانشجو در این دوره ثبت‌نام شود.
    </p>

    <select
        name="ielts_course_product_id"
        style="width:100%;"
    >
        <option value="0">— انتخاب محصول —</option>

        <?php foreach ($products as $product) : ?>
            <option
                value="<?php echo esc_attr($product->get_id()); ?>"
                <?php selected($selected_product_id, $product->get_id()); ?>
            >
                <?php
                echo esc_html(
                    $product->get_name() . ' (شناسه: ' . $product->get_id() . ')'
                );
                ?>
            </option>
        <?php endforeach; ?>
    </select>

    <?php
}

/**
 * ذخیره محصول مرتبط با دوره
 */
add_action('save_post_ielts_course', 'ielts_academy_save_course_product');

function ielts_academy_save_course_product($post_id) {
    if (
        !isset($_POST['ielts_academy_course_product_nonce']) ||
        !wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['ielts_academy_course_product_nonce'])
            ),
            'ielts_academy_save_course_product'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (!class_exists('WooCommerce')) {
        return;
    }

    $product_id = isset($_POST['ielts_course_product_id'])
        ? absint(wp_unslash($_POST['ielts_course_product_id']))
        : 0;

    if (
        $product_id &&
        get_post_type($product_id) !== 'product'
    ) {
        return;
    }

    if ($product_id) {
        update_post_meta(
            $post_id,
            '_ielts_course_product_id',
            $product_id
        );
    } else {
        delete_post_meta(
            $post_id,
            '_ielts_course_product_id'
        );
    }
}

/**
 * ثبت‌نام خودکار دانشجو پس از پرداخت موفق سفارش
 */
add_action(
    'woocommerce_order_status_processing',
    'ielts_academy_enroll_courses_from_order'
);

add_action(
    'woocommerce_order_status_completed',
    'ielts_academy_enroll_courses_from_order'
);

function ielts_academy_enroll_courses_from_order($order_id) {
    if (!function_exists('wc_get_order')) {
        return;
    }

    $order = wc_get_order($order_id);

    if (!$order || !$order->is_paid()) {
        return;
    }

    $user_id = absint($order->get_customer_id());

    // سفارش مهمان به‌صورت خودکار به حسابی اختصاص داده نمی‌شود.
    if (!$user_id || !get_user_by('id', $user_id)) {
        return;
    }

    $course_ids = [];

    foreach ($order->get_items('line_item') as $item) {
        $product_id = absint($item->get_product_id());

        if (!$product_id) {
            continue;
        }

        $matching_courses = get_posts([
            'post_type'      => 'ielts_course',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_query'     => [
                [
                    'key'     => '_ielts_course_product_id',
                    'value'   => $product_id,
                    'compare' => '=',
                    'type'    => 'NUMERIC',
                ],
            ],
        ]);

        foreach ($matching_courses as $course_id) {
            $course_ids[] = absint($course_id);
        }
    }

    if (empty($course_ids)) {
        return;
    }

    $enrolled_ids = get_user_meta(
        $user_id,
        'ielts_enrolled_courses',
        true
    );

    if (!is_array($enrolled_ids)) {
        $enrolled_ids = [];
    }

    // دوره‌های قبلی دانشجو و اختصاص‌های دستی مدیر حفظ می‌شوند.
    $enrolled_ids = array_map('absint', $enrolled_ids);
    $enrolled_ids = array_values(
        array_unique(
            array_merge($enrolled_ids, $course_ids)
        )
    );

    update_user_meta(
        $user_id,
        'ielts_enrolled_courses',
        $enrolled_ids
    );
}
