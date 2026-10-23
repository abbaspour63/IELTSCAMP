<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ثبت نوع محتوای دوره‌های IELTS
 */
add_action('init', 'ielts_academy_register_course_type');

function ielts_academy_register_course_type()
{

    register_post_type('ielts_course', [
        'labels' => [
            'name'          => 'دوره‌های IELTS',
            'singular_name' => 'دوره IELTS',
            'add_new'       => 'افزودن دوره',
            'add_new_item'  => 'افزودن دوره جدید',
            'edit_item'     => 'ویرایش دوره',
            'all_items'     => 'همه دوره‌ها',
            'menu_name'     => 'دوره‌های IELTS',
        ],

        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'exclude_from_search' => false,
        'has_archive'         => 'false',
        'rewrite'             => [
            'slug'       => 'courses',
            'with_front' => false,
        ],

        'supports' => [
            'title',
            'editor',
            'excerpt',
        ],

        'menu_icon' => 'dashicons-welcome-learn-more',
    ]);
}

/**
 * دسته‌بندی دوره‌های IELTS
 */
add_action('init', 'ielts_academy_register_course_category');

function ielts_academy_register_course_category()
{
    
        
register_taxonomy('ielts_course_category', ['ielts_course'], [
    'labels' => [
        'name'              => 'دسته‌بندی دوره‌ها',
        'singular_name'     => 'دسته دوره',
        'search_items'      => 'جستجوی دسته‌ها',
        'all_items'         => 'همه دسته‌ها',
        'edit_item'         => 'ویرایش دسته',
        'add_new_item'      => 'افزودن دسته جدید',
        'menu_name'         => 'دسته‌بندی دوره‌ها',
    ],

    'hierarchical'       => true,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_admin_column'  => true,
    'show_in_rest'       => true,

    'rewrite' => [
        'slug'       => 'course-category',
        'with_front' => false,
    ],
]);


}
/**
 * نمایش فهرست دوره‌ها در صفحه ویرایش کاربر وردپرس
 */
add_action(
    'show_user_profile',
    'ielts_academy_render_user_courses'
);

add_action(
    'edit_user_profile',
    'ielts_academy_render_user_courses'
);

function ielts_academy_render_user_courses($user)
{

    // اختصاص دوره‌ها فقط توسط مدیر سایت
    if (!current_user_can('manage_options')) {
        return;
    }

    $assigned_ids = get_user_meta(
        $user->ID,
        'ielts_enrolled_courses',
        true
    );

    if (!is_array($assigned_ids)) {
        $assigned_ids = [];
    }

    $assigned_ids = array_map('absint', $assigned_ids);

    $courses = get_posts([
        'post_type'      => 'ielts_course',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ]);

    wp_nonce_field(
        'ielts_academy_save_user_courses',
        'ielts_academy_user_courses_nonce'
    );
?>

    <h2>دوره‌های IELTS این کاربر</h2>

    <table class="form-table">
        <tr>
            <th>دوره‌های اختصاص‌یافته</th>

            <td>
                <?php if (empty($courses)) : ?>

                    <p>
                        هنوز دوره‌ای ایجاد نشده است.
                        ابتدا از منوی دوره‌های IELTS یک دوره منتشر کنید.
                    </p>

                <?php else : ?>

                    <?php foreach ($courses as $course) : ?>
                        <label style="display:block; margin-bottom:10px;">

                            <input
                                type="checkbox"
                                name="ielts_enrolled_courses[]"
                                value="<?php echo esc_attr($course->ID); ?>"
                                <?php
                                checked(
                                    in_array(
                                        (int) $course->ID,
                                        $assigned_ids,
                                        true
                                    )
                                );
                                ?>>

                            <?php echo esc_html(get_the_title($course)); ?>

                        </label>
                    <?php endforeach; ?>

                    <p class="description">
                        دوره‌هایی را که این دانشجو در آن‌ها ثبت‌نام کرده
                        است انتخاب کنید.
                    </p>

                <?php endif; ?>
            </td>
        </tr>
    </table>

<?php
}


/**
 * ذخیره دوره‌های اختصاص‌یافته به کاربر
 */
add_action(
    'personal_options_update',
    'ielts_academy_save_user_courses'
);

add_action(
    'edit_user_profile_update',
    'ielts_academy_save_user_courses'
);

function ielts_academy_save_user_courses($user_id)
{

    if (!current_user_can('manage_options')) {
        return;
    }

    if (!current_user_can('edit_user', $user_id)) {
        return;
    }

    if (
        !isset($_POST['ielts_academy_user_courses_nonce']) ||
        !wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['ielts_academy_user_courses_nonce'])
            ),
            'ielts_academy_save_user_courses'
        )
    ) {
        return;
    }

    $selected_ids = [];

    if (
        isset($_POST['ielts_enrolled_courses']) &&
        is_array($_POST['ielts_enrolled_courses'])
    ) {
        $selected_ids = array_unique(
            array_filter(
                array_map(
                    'absint',
                    wp_unslash($_POST['ielts_enrolled_courses'])
                )
            )
        );
    }

    // فقط دوره‌های منتشرشده و معتبر ذخیره شوند.
    if (!empty($selected_ids)) {
        $published_courses = get_posts([
            'post_type'      => 'ielts_course',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'post__in'       => $selected_ids,
            'fields'         => 'ids',
        ]);

        $selected_ids = array_map('absint', $published_courses);
    }

    update_user_meta(
        $user_id,
        'ielts_enrolled_courses',
        $selected_ids
    );
}
