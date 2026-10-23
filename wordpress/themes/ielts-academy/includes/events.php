<?php

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Register IELTSCAMP Events post type.
 */
function ielts_academy_register_event_type() {

    $labels = [
        'name'                  => 'رویدادها',
        'singular_name'         => 'رویداد',
        'menu_name'             => 'رویدادها',
        'name_admin_bar'        => 'رویداد',
        'add_new'               => 'افزودن رویداد',
        'add_new_item'          => 'افزودن رویداد جدید',
        'edit_item'             => 'ویرایش رویداد',
        'new_item'              => 'رویداد جدید',
        'view_item'             => 'مشاهده رویداد',
        'search_items'          => 'جستجوی رویدادها',
        'not_found'             => 'رویدادی پیدا نشد.',
        'not_found_in_trash'    => 'رویدادی در زباله‌دان پیدا نشد.',
        'all_items'             => 'همه رویدادها',
        'featured_image'        => 'تصویر رویداد',
        'set_featured_image'    => 'انتخاب تصویر رویداد',
        'remove_featured_image' => 'حذف تصویر رویداد',
    ];

    register_post_type('ielts_event', [
        'labels'              => $labels,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => true,
        'publicly_queryable'  => true,
        'exclude_from_search' => false,
        'has_archive'         => true,
        'rewrite'             => [
            'slug'       => 'events',
            'with_front' => false,
        ],
        'menu_position'       => 6,
        'menu_icon'           => 'dashicons-calendar-alt',
        'supports'            => [
            'title',
            'editor',
            'excerpt',
            'thumbnail',
            'revisions',
        ],
    ]);
     add_rewrite_rule(
        '^events/?$',
        'index.php?post_type=ielts_event',
        'top'
    );
}


add_action('init', 'ielts_academy_register_event_type');

/**
 * Add event details meta box.
 */
function ielts_academy_add_event_details_metabox() {
    add_meta_box(
        'ielts_event_details',
        'اطلاعات رویداد',
        'ielts_academy_render_event_details_metabox',
        'ielts_event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'ielts_academy_add_event_details_metabox');


/**
 * Render event details fields.
 */
function ielts_academy_render_event_details_metabox($post) {

    wp_nonce_field(
        'ielts_academy_save_event_details',
        'ielts_academy_event_nonce'
    );

    $date = get_post_meta($post->ID, '_ielts_event_date', true);
    $time = get_post_meta($post->ID, '_ielts_event_time', true);
    $venue = get_post_meta($post->ID, '_ielts_event_venue', true);
    $registration_url = get_post_meta(
        $post->ID,
        '_ielts_event_registration_url',
        true
    );
    ?>

    <p>
        <label for="ielts_event_date">
            <strong>تاریخ برگزاری (شمسی)</strong>
        </label>
        <br>
        <input
            type="text"
            id="ielts_event_date"
            name="ielts_event_date"
            value="<?php echo esc_attr($date); ?>"
            placeholder="۱۴۰۵/۰۸/۰۱"
            class="regular-text"
        >
        <br>
        <span class="description">
            تاریخ را به صورت سال/ماه/روز وارد کنید.
        </span>
    </p>

    <p>
        <label for="ielts_event_time">
            <strong>ساعت برگزاری</strong>
        </label>
        <br>
        <input
            type="time"
            id="ielts_event_time"
            name="ielts_event_time"
            value="<?php echo esc_attr($time); ?>"
        >
    </p>

    <p>
        <label for="ielts_event_venue">
            <strong>محل برگزاری</strong>
        </label>
        <br>
        <input
            type="text"
            id="ielts_event_venue"
            name="ielts_event_venue"
            value="<?php echo esc_attr($venue); ?>"
            class="regular-text"
            placeholder="آدرس محل برگزاری"
        >
    </p>

    <p>
        <label for="ielts_event_registration_url">
            <strong>لینک ثبت‌نام</strong>
        </label>
        <br>
        <input
            type="url"
            id="ielts_event_registration_url"
            name="ielts_event_registration_url"
            value="<?php echo esc_attr($registration_url); ?>"
            class="regular-text"
            placeholder="https://example.com/register"
        >
        <br>
        <span class="description">
            در صورت نداشتن فرم ثبت‌نام، این فیلد را خالی بگذارید.
        </span>
    </p>

    <?php
}


/**
 * Save event details securely.
 */
function ielts_academy_save_event_details($post_id) {

    if (
        ! isset($_POST['ielts_academy_event_nonce']) ||
        ! wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['ielts_academy_event_nonce'])
            ),
            'ielts_academy_save_event_details'
        )
    ) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (
        ! current_user_can('edit_post', $post_id) ||
        get_post_type($post_id) !== 'ielts_event'
    ) {
        return;
    }

    $fields = [
        'ielts_event_date' => '_ielts_event_date',
        'ielts_event_time' => '_ielts_event_time',
        'ielts_event_venue' => '_ielts_event_venue',
    ];

    foreach ($fields as $input_name => $meta_key) {
        if (! isset($_POST[$input_name])) {
            continue;
        }

        $value = sanitize_text_field(
            wp_unslash($_POST[$input_name])
        );

        update_post_meta($post_id, $meta_key, $value);
    }

    if (isset($_POST['ielts_event_registration_url'])) {
        $url = esc_url_raw(
            wp_unslash($_POST['ielts_event_registration_url'])
        );

        update_post_meta(
            $post_id,
            '_ielts_event_registration_url',
            $url
        );
    }
}
add_action('save_post_ielts_event', 'ielts_academy_save_event_details');
