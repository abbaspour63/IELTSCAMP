<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Validate the phone number for the login flow.
 */
function ielts_academy_normalize_phone( $phone ) {
    $phone = preg_replace( '/[\s\-\(\)]/', '', (string) $phone );

    // Convert Persian and Arabic digits to English digits.
    $phone = strtr( $phone, [
        '۰' => '0', '۱' => '1', '۲' => '2',
        '۳' => '3', '۴' => '4', '۵' => '5',
        '۶' => '6', '۷' => '7', '۸' => '8',
        '۹' => '9',
        '٠' => '0', '١' => '1', '٢' => '2',
        '٣' => '3', '٤' => '4', '٥' => '5',
        '٦' => '6', '٧' => '7', '٨' => '8',
        '٩' => '9',
    ] );

    if ( ! preg_match( '/^09[0-9]{9}$/', $phone ) ) {
        return false;
    }

    return $phone;
}

add_action( 'wp_ajax_nopriv_ielts_request_otp', 'ielts_academy_request_otp' );
add_action( 'wp_ajax_ielts_request_otp', 'ielts_academy_request_otp' );

function ielts_academy_request_otp() {
    check_ajax_referer( 'ielts_login_nonce', 'nonce' );

    $phone = isset( $_POST['phone'] )
        ? ielts_academy_normalize_phone( wp_unslash( $_POST['phone'] ) )
        : false;

    if ( ! $phone ) {
        wp_send_json_error(
            [ 'message' => 'شماره موبایل معتبر نیست. نمونه: 09123456789' ],
            400
        );
    }

    // OTP generation and SMS delivery will be added in the next step.
    wp_send_json_success(
        [ 'message' => 'شماره موبایل معتبر است. سرویس ارسال کد هنوز متصل نشده است.' ]
    );
}


/**
 * Handle email and password login.
 */
add_action( 'template_redirect', 'ielts_academy_handle_email_login' );

function ielts_academy_handle_email_login() {
    if (
        'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ?? '' ) ||
        ! isset( $_POST['ielts_email_login'] )
    ) {
        return;
    }

    if (
        ! isset( $_POST['ielts_email_nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['ielts_email_nonce'] ) ),
            'ielts_email_login'
        )
    ) {
        wp_die( 'درخواست معتبر نیست. صفحه را تازه‌سازی کنید و دوباره تلاش کنید.' );
    }

    $email = isset( $_POST['email'] )
        ? sanitize_email( wp_unslash( $_POST['email'] ) )
        : '';

    $password = isset( $_POST['password'] )
        ? (string) wp_unslash( $_POST['password'] )
        : '';

    if ( ! is_email( $email ) || '' === $password ) {
        ielts_academy_email_login_error();
    }

    $user = get_user_by( 'email', $email );

    if ( ! $user ) {
        ielts_academy_email_login_error();
    }

    $result = wp_signon(
        [
            'user_login'    => $user->user_login,
            'user_password' => $password,
            'remember'      => true,
        ],
        is_ssl()
    );

    if ( is_wp_error( $result ) ) {
        ielts_academy_email_login_error();
    }

    wp_safe_redirect( home_url( '/profile/' ) );
    exit;
}

function ielts_academy_email_login_error() {
    wp_safe_redirect(
        add_query_arg( 'login_error', '1', home_url( '/login/' ) )
    );
    exit;
}
add_action('template_redirect', 'ielts_academy_handle_profile_update');

function ielts_academy_handle_profile_update() {

    // فقط درخواست POST برای صفحه پروفایل پردازش شود.
    if (
        !is_page('profile') ||
        strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' ||
        !isset($_POST['ielts_profile_update'])
    ) {
        return;
    }

    // فقط کاربران واردشده اجازه ویرایش پروفایل دارند.
    if (!is_user_logged_in()) {
        wp_safe_redirect(home_url('/login/'));
        exit;
    }

    // بررسی امنیت فرم
    $nonce = isset($_POST['ielts_profile_nonce'])
        ? sanitize_text_field(wp_unslash($_POST['ielts_profile_nonce']))
        : '';

    if (!wp_verify_nonce($nonce, 'ielts_profile_update')) {
        wp_safe_redirect(
            add_query_arg('profile_error', 'security', home_url('/profile/'))
        );
        exit;
    }

    // دریافت و پاک‌سازی اطلاعات
    $first_name = isset($_POST['first_name'])
        ? sanitize_text_field(wp_unslash($_POST['first_name']))
        : '';

    $last_name = isset($_POST['last_name'])
        ? sanitize_text_field(wp_unslash($_POST['last_name']))
        : '';

    $phone = isset($_POST['phone'])
        ? sanitize_text_field(wp_unslash($_POST['phone']))
        : '';

    // نام و نام خانوادگی الزامی هستند.
    if ($first_name === '' || $last_name === '') {
        wp_safe_redirect(
            add_query_arg('profile_error', 'missing_name', home_url('/profile/'))
        );
        exit;
    }

    // اعتبارسنجی شماره موبایل با تابع موجود در پروژه
    $normalized_phone = '';

    if ($phone !== '') {
        $normalized_phone = ielts_academy_normalize_phone($phone);

        if (!$normalized_phone) {
            wp_safe_redirect(
                add_query_arg('profile_error', 'invalid_phone', home_url('/profile/'))
            );
            exit;
        }
    }

    $user_id = get_current_user_id();

    // به‌روزرسانی اطلاعات استاندارد کاربر وردپرس
    $updated = wp_update_user([
        'ID'           => $user_id,
        'first_name'   => $first_name,
        'last_name'    => $last_name,
        'display_name' => trim($first_name . ' ' . $last_name),
    ]);

    if (is_wp_error($updated)) {
        wp_safe_redirect(
            add_query_arg('profile_error', 'save_failed', home_url('/profile/'))
        );
        exit;
    }

    // ذخیره شماره موبایل به‌عنوان اطلاعات اختصاصی کاربر
    update_user_meta($user_id, 'ielts_phone', $normalized_phone);

    // جلوگیری از ارسال مجدد فرم هنگام بارگذاری مجدد
    wp_safe_redirect(
        add_query_arg('profile_updated', '1', home_url('/profile/'))
    );
    exit;
}