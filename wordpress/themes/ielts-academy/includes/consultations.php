<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ثبت نوع محتوای جلسات مشاوره
 */
add_action('init', 'ielts_academy_register_consultation_type');

function ielts_academy_register_consultation_type() {

    register_post_type('ielts_consultation', [
        'labels' => [
            'name'          => 'جلسات مشاوره',
            'singular_name' => 'جلسه مشاوره',
            'add_new'       => 'افزودن جلسه',
            'add_new_item'  => 'افزودن جلسه مشاوره',
            'edit_item'     => 'ویرایش جلسه',
            'all_items'     => 'همه جلسات',
            'menu_name'     => 'جلسات مشاوره',
        ],

        'public'       => false,
        'show_ui'      => true,
        'show_in_rest' => true,

        'supports' => [
            'title',
            'editor',
        ],

        'menu_icon' => 'dashicons-calendar-alt',
    ]);
}


/**
 * فیلدهای مدیریت جلسه
 */
add_action(
    'add_meta_boxes',
    'ielts_academy_add_consultation_metabox'
);

function ielts_academy_add_consultation_metabox() {

    add_meta_box(
        'ielts_consultation_details',
        'اطلاعات جلسه مشاوره',
        'ielts_academy_render_consultation_metabox',
        'ielts_consultation',
        'side',
        'high'
    );
}


function ielts_academy_render_consultation_metabox($post) {

    wp_nonce_field(
        'ielts_consultation_save',
        'ielts_consultation_nonce'
    );

    $student_id = (int) get_post_meta(
        $post->ID,
        '_ielts_consultation_user_id',
        true
    );

    $date = get_post_meta(
        $post->ID,
        '_ielts_consultation_datetime',
        true
    );

    $status = get_post_meta(
        $post->ID,
        '_ielts_consultation_status',
        true
    );

    if (!$status) {
        $status = 'pending';
    }

    $students = get_users([
        'orderby' => 'display_name',
        'order'   => 'ASC',
    ]);

    $statuses = [
        'pending'   => 'در انتظار تأیید',
        'confirmed' => 'تأیید شده',
        'completed' => 'انجام شده',
        'cancelled' => 'لغو شده',
    ];
    ?>

    <p>
        <label for="ielts_consultation_user_id">
            <strong>دانشجو</strong>
        </label>
    </p>

    <select
        name="ielts_consultation_user_id"
        id="ielts_consultation_user_id"
        required
        style="width:100%;max-width:450px"
    >
        <option value="">انتخاب دانشجو</option>

        <?php foreach ($students as $student) : ?>
            <option
                value="<?php echo esc_attr($student->ID); ?>"
                <?php selected($student_id, $student->ID); ?>
            >
                <?php
                echo esc_html(
                    $student->display_name . ' — ' . $student->user_email
                );
                ?>
            </option>
        <?php endforeach; ?>
    </select>

    <p style="margin-top:20px">
        <label for="ielts_consultation_datetime">
            <strong>تاریخ و ساعت جلسه</strong>
        </label>
    </p>

    <input
        type="datetime-local"
        id="ielts_consultation_datetime"
        name="ielts_consultation_datetime"
        value="<?php echo esc_attr($date); ?>"
        style="width:100%;max-width:450px"
    >

    <p style="margin-top:20px">
        <label for="ielts_consultation_status">
            <strong>وضعیت جلسه</strong>
        </label>
    </p>

    <select
        name="ielts_consultation_status"
        id="ielts_consultation_status"
        style="width:100%;max-width:450px"
    >
        <?php foreach ($statuses as $key => $label) : ?>
            <option
                value="<?php echo esc_attr($key); ?>"
                <?php selected($status, $key); ?>
            >
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <p class="description" style="margin-top:15px">
        توضیحات جلسه را می‌توانید در قسمت ویرایشگر اصلی همین صفحه وارد کنید.
    </p>

    <?php
}


/**
 * ذخیره اطلاعات جلسه
 */
add_action(
    'save_post_ielts_consultation',
    'ielts_academy_save_consultation',
    10,
    3
);

function ielts_academy_save_consultation($post_id, $post, $update) {

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (
        wp_is_post_revision($post_id) ||
        wp_is_post_autosave($post_id)
    ) {
        return;
    }

    if (
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    if (
        !isset($_POST['ielts_consultation_nonce']) ||
        !wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['ielts_consultation_nonce'])
            ),
            'ielts_consultation_save'
        )
    ) {
        return;
    }

    $student_id = isset($_POST['ielts_consultation_user_id'])
        ? absint($_POST['ielts_consultation_user_id'])
        : 0;

    $student = $student_id ? get_userdata($student_id) : false;

    if (!$student) {
        delete_post_meta(
            $post_id,
            '_ielts_consultation_user_id'
        );
        return;
    }

    update_post_meta(
        $post_id,
        '_ielts_consultation_user_id',
        $student_id
    );

    $date = isset($_POST['ielts_consultation_datetime'])
        ? sanitize_text_field(
            wp_unslash($_POST['ielts_consultation_datetime'])
        )
        : '';

    // پذیرش قالب تاریخ و ساعت datetime-local
    if (
        $date === '' ||
        preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $date)
    ) {
        update_post_meta(
            $post_id,
            '_ielts_consultation_datetime',
            $date
        );
    }

    $allowed_statuses = [
        'pending',
        'confirmed',
        'completed',
        'cancelled',
    ];

    $status = isset($_POST['ielts_consultation_status'])
        ? sanitize_key(
            wp_unslash($_POST['ielts_consultation_status'])
        )
        : 'pending';

    if (!in_array($status, $allowed_statuses, true)) {
        $status = 'pending';
    }

    update_post_meta(
        $post_id,
        '_ielts_consultation_status',
        $status
    );
}