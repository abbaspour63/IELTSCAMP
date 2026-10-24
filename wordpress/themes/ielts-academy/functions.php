<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
function ielts_academy_setup() {
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
}

add_action( 'after_setup_theme', 'ielts_academy_setup' );

function ielts_academy_enqueue_assets() {
    wp_enqueue_style(
        'ielts-academy-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version' )
    );

    $css_file = get_template_directory() . '/assets/css/output.css';

    if ( file_exists( $css_file ) ) {
        wp_enqueue_style(
            'ielts-academy-tailwind',
            get_template_directory_uri() . '/assets/css/output.css',
            array( 'ielts-academy-style' ),
            filemtime( $css_file )
        );
    }
}

add_action( 'wp_enqueue_scripts', 'ielts_academy_enqueue_assets' );

/**
 * Convert English digits to Persian digits
 * on the public-facing website.
 */
function ielts_academy_persian_digits_script() {
    ?>
    <script>
        (function () {
            'use strict';

            const digits = {
                '0': '۰',
                '1': '۱',
                '2': '۲',
                '3': '۳',
                '4': '۴',
                '5': '۵',
                '6': '۶',
                '7': '۷',
                '8': '۸',
                '9': '۹'
            };

            function convertDigits(text) {
                return text.replace(/[0-9]/g, function (digit) {
                    return digits[digit];
                });
            }

            function convertTextNodes(root) {
                const excludedTags = [
                    'SCRIPT', 'STYLE', 'NOSCRIPT',
                    'TEXTAREA', 'INPUT', 'CODE',
                    'PRE', 'SVG'
                ];

                if (root.nodeType === Node.TEXT_NODE) {
                    const parent = root.parentElement;

                    if (
                        parent &&
                        !excludedTags.includes(parent.tagName)
                    ) {
                        root.nodeValue = convertDigits(root.nodeValue);
                    }

                    return;
                }

                if (
                    root.nodeType !== Node.ELEMENT_NODE &&
                    root.nodeType !== Node.DOCUMENT_NODE
                ) {
                    return;
                }

                if (
                    root.nodeType === Node.ELEMENT_NODE &&
                    excludedTags.includes(root.tagName)
                ) {
                    return;
                }

                const walker = document.createTreeWalker(
                    root,
                    NodeFilter.SHOW_TEXT
                );

                let node;

                while ((node = walker.nextNode())) {
                    const parent = node.parentElement;

                    if (
                        parent &&
                        !excludedTags.includes(parent.tagName)
                    ) {
                        node.nodeValue = convertDigits(node.nodeValue);
                    }
                }
            }

            function startConversion() {
                convertTextNodes(document.body);

                const observer = new MutationObserver(function (mutations) {
                    mutations.forEach(function (mutation) {
                        mutation.addedNodes.forEach(function (node) {
                            convertTextNodes(node);
                        });
                    });
                });

                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }

            if (document.body) {
                startConversion();
            } else {
                document.addEventListener(
                    'DOMContentLoaded',
                    startConversion,
                    {once: true}
                );

            }
        })();
    </script>
    <?php
}
add_action(
    'wp_footer',
    'ielts_academy_persian_digits_script',
    100
);
require_once get_template_directory() . '/includes/auth.php';
require_once get_template_directory() . '/includes/courses.php';
require_once get_template_directory() . '/includes/consultations.php';
require_once get_template_directory() . '/includes/course-sales.php';
require_once get_template_directory().'/includes/events.php';
/**
 * Load IELTSCAMP login JavaScript.
 */
function ielts_academy_enqueue_login_script() {
    if ( ! is_page( 'login' ) ) {
        return;
    }

    $file_path = get_template_directory() . '/assets/js/login.js';

    wp_enqueue_script(
        'ielts-academy-login',
        get_template_directory_uri() . '/assets/js/login.js',
        array(),
        file_exists( $file_path ) ? filemtime( $file_path ) : '1.0.0',
        true
    );

    wp_localize_script(
        'ielts-academy-login',
        'IELTSLogin',
        array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'ielts_login_nonce' ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'ielts_academy_enqueue_login_script' );

/**
 * Load the 3D Hero animation only on the homepage.
 */
function ielts_academy_enqueue_hero_3d() {
    if ( ! is_front_page() ) {
        return;
    }

    $file_path = get_template_directory() . '/assets/js/hero-3d.js';

    if ( ! file_exists( $file_path ) ) {
        return;
    }

    wp_enqueue_script(
        'ielts-academy-hero-3d',
        get_template_directory_uri() . '/assets/js/hero-3d.js',
        array(),
        filemtime( $file_path ),
        true
    );
}


add_action( 'wp_enqueue_scripts', 'ielts_academy_enqueue_hero_3d' );
add_filter( 'woocommerce_apply_user_tracking', '__return_false' );
add_filter( 'woocommerce_apply_tracking', '__return_false' );


/**
 * تبدیل تاریخ میلادی به تاریخ شمسی برای وبلاگ IELTSCAMP
 */
function ielts_academy_jalali_date( $timestamp = null ) {
    if ( ! $timestamp ) {
        $timestamp = get_post_timestamp();
    }

    if ( ! $timestamp ) {
        return '';
    }

    $gy = (int) wp_date( 'Y', $timestamp );
    $gm = (int) wp_date( 'n', $timestamp );
    $gd = (int) wp_date( 'j', $timestamp );

    $g_days_in_month = array(
        31, 28, 31, 30, 31, 30,
        31, 31, 30, 31, 30, 31
    );

    $j_days_in_month = array(
        31, 31, 31, 31, 31, 31,
        30, 30, 30, 30, 30, 29
    );

    $gy2 = $gy - 1600;
    $gm2 = $gm - 1;
    $gd2 = $gd - 1;

    $g_day_no = 365 * $gy2
        + (int) floor( ( $gy2 + 3 ) / 4 )
        - (int) floor( ( $gy2 + 99 ) / 100 )
        + (int) floor( ( $gy2 + 399 ) / 400 );

    for ( $i = 0; $i < $gm2; $i++ ) {
        $g_day_no += $g_days_in_month[ $i ];
    }

    if (
        $gm2 > 1 &&
        (
            ( $gy % 4 === 0 && $gy % 100 !== 0 )
            || $gy % 400 === 0
        )
    ) {
        $g_day_no++;
    }

    $g_day_no += $gd2;
    $j_day_no = $g_day_no - 79;

    $j_np = (int) floor( $j_day_no / 12053 );
    $j_day_no %= 12053;

    $jy = 979 + 33 * $j_np + 4 * (int) floor( $j_day_no / 1461 );
    $j_day_no %= 1461;

    if ( $j_day_no >= 366 ) {
        $jy += (int) floor( ( $j_day_no - 1 ) / 365 );
        $j_day_no = ( $j_day_no - 1 ) % 365;
    }

    for ( $i = 0; $i < 11 && $j_day_no >= $j_days_in_month[ $i ]; $i++ ) {
        $j_day_no -= $j_days_in_month[ $i ];
    }

    $jm = $i + 1;
    $jd = $j_day_no + 1;

    $months = array(
        1 => 'فروردین',
        2 => 'اردیبهشت',
        3 => 'خرداد',
        4 => 'تیر',
        5 => 'مرداد',
        6 => 'شهریور',
        7 => 'مهر',
        8 => 'آبان',
        9 => 'آذر',
        10 => 'دی',
        11 => 'بهمن',
        12 => 'اسفند',
    );

    return sprintf( '%d %s %d', $jd, $months[ $jm ], $jy );
}
/**
 * پشتیبانی قالب از WooCommerce
 */
function ielts_academy_woocommerce_support() {
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'ielts_academy_woocommerce_support');
add_filter( 'wp_mail_from', function ( $email ) {
    return get_option( 'admin_email' );
} );

add_filter( 'wp_mail_from_name', function ( $name ) {
    return 'IELTSCAMP';
} );

add_action( 'wp_mail_failed', function ( $error ) {
    error_log(
        '[IELTS CAMP mail error] ' . $error->get_error_message()
    );
} );