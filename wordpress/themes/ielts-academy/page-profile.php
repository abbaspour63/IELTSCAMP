<?php
if (!defined('ABSPATH')) {
    exit;
}

// فقط کاربران واردشده می‌توانند پروفایل را ببینند.
if (!is_user_logged_in()) {
    wp_safe_redirect(home_url('/login/'));
    exit;
}

$current_user = wp_get_current_user();

$first_name = $current_user->first_name;
$last_name  = $current_user->last_name;
$phone      = get_user_meta($current_user->ID, 'ielts_phone', true);

// پیام‌های فرم پروفایل
$profile_error = isset($_GET['profile_error'])
    ? sanitize_key(wp_unslash($_GET['profile_error']))
    : '';

$profile_messages = [
    'security'      => 'اعتبار فرم تأیید نشد. لطفاً دوباره تلاش کنید.',
    'missing_name'  => 'نام و نام خانوادگی را وارد کنید.',
    'invalid_phone' => 'شماره موبایل معتبر نیست.',
    'save_failed'   => 'ذخیره اطلاعات انجام نشد. دوباره تلاش کنید.',
];

// دریافت دوره‌های اختصاص‌یافته به دانشجو
$enrolled_course_ids = get_user_meta(
    $current_user->ID,
    'ielts_enrolled_courses',
    true
);

if (!is_array($enrolled_course_ids)) {
    $enrolled_course_ids = [];
}

$enrolled_course_ids = array_values(
    array_filter(
        array_map('absint', $enrolled_course_ids)
    )
);

$enrolled_courses = [];

if (!empty($enrolled_course_ids)) {
    $enrolled_courses = get_posts([
        'post_type'      => 'ielts_course',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post__in'       => $enrolled_course_ids,
        'orderby'        => 'post__in',
    ]);
}

// دریافت جلسات مشاوره مختص همین دانشجو
$student_consultations = get_posts([
    'post_type'      => 'ielts_consultation',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_key'       => '_ielts_consultation_datetime',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_query'     => [
        [
            'key'     => '_ielts_consultation_user_id',
            'value'   => (string) $current_user->ID,
            'compare' => '=',
        ],
    ],
]);

$consultation_status_labels = [
    'pending'   => 'در انتظار تأیید',
    'confirmed' => 'تأیید شده',
    'completed' => 'انجام شده',
    'cancelled' => 'لغو شده',
];
// دریافت سفارش‌های ووکامرس برای کاربر واردشده
$student_orders = [];

if (function_exists('wc_get_orders')) {
    $student_orders = wc_get_orders([
        'customer_id' => $current_user->ID,
        'limit'       => 10,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'return'      => 'objects',
    ]);
}

// ترجمه وضعیت سفارش‌ها
$order_status_labels = [
    'pending'    => 'در انتظار پرداخت',
    'processing' => 'در حال پردازش',
    'on-hold'    => 'در انتظار تأیید',
    'completed'  => 'تکمیل‌شده',
    'cancelled'  => 'لغوشده',
    'refunded'   => 'مستردشده',
    'failed'     => 'پرداخت ناموفق',
];
// دریافت فایل‌هایی که ووکامرس اجازه دانلودشان را به کاربر داده است
$student_downloads = [];

if (function_exists('wc_get_customer_available_downloads')) {
    $student_downloads = wc_get_customer_available_downloads(
        (int) $current_user->ID
    );
}
get_header();
?>

<main dir="rtl" class="min-h-screen scroll-smooth bg-slate-50 px-4 py-10">

    <div class="mx-auto max-w-6xl">

        <!-- سربرگ پروفایل -->
        <section class="mb-6 rounded-2xl bg-white p-6 shadow-sm">

            <p class="mb-2 text-sm text-slate-500">
                پنل کاربری IELTSCAMP
            </p>

            <h1 class="text-2xl font-bold text-slate-800">
                سلام،
                <?php
                echo esc_html(
                    $current_user->display_name ?: $current_user->user_login
                );
                ?>
            </h1>

            <p class="mt-3 text-slate-600">
                از این بخش می‌توانید اطلاعات حساب، دوره‌ها و جلسات مشاوره خود را مدیریت کنید.
            </p>

        </section>

        <!-- پیام ذخیره موفق -->
        <?php
        if (
            isset($_GET['profile_updated']) &&
            sanitize_text_field(
                wp_unslash($_GET['profile_updated'])
            ) === '1'
        ) :
        ?>

            <div
                class="mb-6 rounded-lg bg-green-50 p-4 text-green-700"
                role="status">
                اطلاعات پروفایل با موفقیت ذخیره شد.
            </div>

        <?php endif; ?>

        <!-- پیام خطا -->
        <?php if (isset($profile_messages[$profile_error])) : ?>

            <div
                class="mb-6 rounded-lg bg-red-50 p-4 text-red-700"
                role="alert">
                <?php echo esc_html($profile_messages[$profile_error]); ?>
            </div>

        <?php endif; ?>


        <!-- چیدمان اصلی: منو سمت راست و محتوا در کنار آن -->
        <div class="grid grid-cols-1 items-start gap-6 md:grid-cols-4">

            <!-- منوی سمت راست -->
            <aside class="rounded-2xl bg-white p-5 shadow-sm md:col-span-1">

                <h2 class="mb-5 text-lg font-bold text-slate-800">
                    منوی پروفایل
                </h2>

                <nav class="space-y-2" aria-label="منوی پروفایل">

                    <a
                        href="#profile-info"
                        class="block rounded-lg bg-slate-50 p-3 text-slate-700 hover:bg-blue-50 hover:text-blue-700">
                        اطلاعات شخصی
                    </a>

                    <a
                        href="#my-courses"
                        class="block rounded-lg bg-slate-50 p-3 text-slate-700 hover:bg-blue-50 hover:text-blue-700">
                        دوره‌های من
                    </a>

                    <a
                        href="#my-consultations"
                        class="block rounded-lg bg-slate-50 p-3 text-slate-700 hover:bg-blue-50 hover:text-blue-700">
                        جلسات مشاوره
                    </a>

                    <a
                        href="#my-orders"
                        class="block rounded-lg bg-slate-50 p-3 text-slate-700 hover:bg-blue-50 hover:text-blue-700">
                        سفارش‌های من
                    </a>
                    <a
                        href="#my-downloads"
                        class="block rounded-lg bg-slate-50 p-3 text-slate-700 hover:bg-blue-50 hover:text-blue-700">
                        دانلودهای من
                    </a>
                    <a
                        href="<?php echo esc_url(
                                    wp_logout_url(home_url('/login/'))
                                ); ?>"
                        class="block rounded-lg bg-red-50 p-3 font-medium text-red-700 hover:bg-red-100">
                        خروج از حساب
                    </a>

                </nav>

                <div class="mt-6 border-t border-slate-100 pt-4">

                    <!-- <p class="text-sm text-slate-500">
                        نام کاربری
                    </p>

                    <p class="mt-1 break-words font-medium text-slate-800">
                        <?php echo esc_html($current_user->user_login); ?>
                    </p> -->

                </div>

            </aside>


            <!-- محتوای اصلی کنار منو -->
            <div class="space-y-6 md:col-span-3">

                <!-- اطلاعات شخصی -->
                <section
                    id="profile-info"
                    class="scroll-mt-6 rounded-2xl bg-white p-6 shadow-sm">

                    <h2 class="mb-6 text-xl font-bold text-slate-800">
                        اطلاعات شخصی
                    </h2>

                    <form
                        method="post"
                        action="<?php echo esc_url(home_url('/profile/')); ?>">

                        <?php
                        wp_nonce_field(
                            'ielts_profile_update',
                            'ielts_profile_nonce'
                        );
                        ?>

                        <input
                            type="hidden"
                            name="ielts_profile_update"
                            value="1">

                        <label
                            for="first_name"
                            class="mb-2 block font-medium text-slate-700">
                            نام
                        </label>

                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value="<?php echo esc_attr($first_name); ?>"
                            required
                            class="mb-5 w-full rounded-lg border border-slate-300 p-3">

                        <label for="last_name" class="mb-2 block font-medium text-slate-700">
                            نام خانوادگی
                        </label>

                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            value="<?php echo esc_attr($last_name); ?>"
                            required
                            class="mb-5 w-full rounded-lg border border-slate-300 p-3">

                        <label for="phone" class="mb-2 block font-medium text-slate-700">
                            شماره موبایل
                        </label>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="<?php echo esc_attr($phone); ?>"
                            inputmode="numeric"
                            placeholder="09123456789"
                            class="mb-5 w-full rounded-lg border border-slate-300 p-3">

                        <label for="profile_email" class="mb-2 block font-medium text-slate-700">
                            ایمیل حساب
                        </label>

                        <input
                            type="email"
                            id="profile_email"
                            value="<?php echo esc_attr($current_user->user_email); ?>"
                            readonly
                            class="mb-6 w-full rounded-lg border border-slate-200 bg-slate-100 p-3">

                        <button type="submit"
                            class="w-full rounded-lg bg-blue-600 px-5 py-3 font-bold text-white">
                            ذخیره تغییرات
                        </button>

                    </form>
                </section>

                <!-- بخش دوره‌ها -->
                <section id="my-courses"
                    class="rounded-2xl bg-white p-6 shadow-sm">

                    <h2 class="mb-5 text-xl font-bold text-slate-800">
                        دوره‌های من
                    </h2>

                    <?php if (empty($enrolled_courses)) : ?>

                        <p class="rounded-lg bg-slate-50 p-4 text-slate-600">
                            هنوز دوره‌ای به حساب شما اختصاص داده نشده است.
                        </p>

                    <?php else : ?>

                        <div class="grid gap-4">

                            <?php foreach ($enrolled_courses as $course) : ?>

                                <article class="rounded-xl border border-slate-200 p-5">

                                    <h3 class="mb-3 text-lg font-bold text-slate-800">
                                        <?php echo esc_html(get_the_title($course)); ?>
                                    </h3>

                                    <p class="leading-7 text-slate-600">
                                        <?php
                                        $description = has_excerpt($course)
                                            ? get_the_excerpt($course)
                                            : wp_trim_words(
                                                wp_strip_all_tags($course->post_content),
                                                24
                                            );

                                        echo esc_html($description);
                                        ?>
                                    </p>

                                </article>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </section>

                <!-- بخش جلسات مشاوره -->
                <section id="my-consultations"
                    class="rounded-2xl bg-white p-6 shadow-sm">

                    <h2 class="mb-5 text-xl font-bold text-slate-800">
                        جلسات مشاوره من
                    </h2>

                    <?php if (empty($student_consultations)) : ?>

                        <p class="rounded-lg bg-slate-50 p-4 text-slate-600">
                            هنوز جلسه مشاوره‌ای برای شما ثبت نشده است.
                        </p>

                    <?php else : ?>

                        <div class="space-y-4">

                            <?php foreach ($student_consultations as $consultation) : ?>

                                <?php
                                $consultation_date = get_post_meta(
                                    $consultation->ID,
                                    '_ielts_consultation_datetime',
                                    true
                                );

                                $consultation_status = get_post_meta(
                                    $consultation->ID,
                                    '_ielts_consultation_status',
                                    true
                                );

                                $status_label =
                                    $consultation_status_labels[$consultation_status]
                                    ?? 'در انتظار تأیید';
                                ?>

                                <article class="rounded-xl border border-slate-200 p-5">

                                    <h3 class="mb-3 font-bold text-slate-800">
                                        <?php echo esc_html(get_the_title($consultation)); ?>
                                    </h3>

                                    <p class="mb-3 text-sm text-slate-600">
                                        تاریخ و ساعت:
                                        <?php
                                        echo esc_html(
                                            $consultation_date ?: 'تعیین نشده'
                                        );
                                        ?>
                                    </p>

                                    <p class="mb-3 text-sm text-slate-700">
                                        وضعیت:
                                        <?php echo esc_html($status_label); ?>
                                    </p>

                                    <?php if (trim($consultation->post_content) !== '') : ?>
                                        <div class="mt-4 border-t pt-4 leading-7 text-slate-600">
                                            <?php
                                            echo wp_kses_post(
                                                wpautop($consultation->post_content)
                                            );
                                            ?>
                                        </div>
                                    <?php endif; ?>

                                </article>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </section>


                <section
                    id="my-orders"
                    class="rounded-2xl bg-white p-6 shadow-sm">
                    <h2 class="mb-5 text-xl font-bold text-slate-800">
                        سفارش‌های من
                    </h2>

                    <?php if (empty($student_orders)) : ?>

                        <p class="rounded-lg bg-slate-50 p-4 text-slate-600">
                            هنوز سفارشی برای حساب شما ثبت نشده است.
                        </p>

                    <?php else : ?>

                        <div class="space-y-4">

                            <?php foreach ($student_orders as $order) : ?>

                                <?php
                                $status = $order->get_status();

                                $status_label = $order_status_labels[$status]
                                    ?? $status;

                                $created = $order->get_date_created();

                                $created_text = $created
                                    ? wp_date(
                                        'Y/m/d H:i',
                                        $created->getTimestamp(),
                                        wp_timezone()
                                    )
                                    : 'نامشخص';
                                ?>

                                <article class="rounded-xl border border-slate-200 p-5">

                                    <div class="mb-3 flex flex-wrap items-center justify-between gap-2">

                                        <h3 class="font-bold text-slate-800">
                                            سفارش شماره
                                            <?php echo esc_html($order->get_order_number()); ?>
                                        </h3>

                                        <span class="rounded-lg bg-blue-50 px-3 py-2 text-sm text-blue-700">
                                            <?php echo esc_html($status_label); ?>
                                        </span>

                                    </div>

                                    <p class="mb-3 text-sm text-slate-600">
                                        تاریخ سفارش:
                                        <?php echo esc_html($created_text); ?>
                                    </p>

                                    <div class="mb-4 space-y-2">

                                        <?php foreach ($order->get_items('line_item') as $item) : ?>

                                            <div class="flex flex-wrap justify-between gap-2 text-sm">

                                                <span class="text-slate-700">
                                                    <?php echo esc_html($item->get_name()); ?>
                                                    ×
                                                    <?php echo esc_html($item->get_quantity()); ?>
                                                </span>

                                                <span class="text-slate-600">
                                                    <?php
                                                    echo wp_kses_post(
                                                        wc_price(
                                                            $item->get_total(),
                                                            ['currency' => $order->get_currency()]
                                                        )
                                                    );
                                                    ?>
                                                </span>

                                            </div>

                                        <?php endforeach; ?>

                                    </div>

                                    <div class="border-t border-slate-100 pt-4">

                                        <p class="font-bold text-slate-800">
                                            مبلغ سفارش:
                                            <?php
                                            echo wp_kses_post(
                                                wc_price(
                                                    $order->get_total(),
                                                    ['currency' => $order->get_currency()]
                                                )
                                            );
                                            ?>
                                        </p>

                                        <p class="mt-2 text-sm">
                                            وضعیت پرداخت:
                                            <?php if ($order->is_paid()) : ?>

                                                <span class="font-medium text-green-700">
                                                    پرداخت‌شده
                                                </span>

                                            <?php else : ?>

                                                <span class="font-medium text-amber-700">
                                                    پرداخت‌نشده
                                                </span>

                                            <?php endif; ?>
                                        </p>

                                    </div>

                                </article>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </section>
                <!-- دانلودهای مجاز دانشجو -->
<section
    id="my-downloads"
    class="rounded-2xl bg-white p-6 shadow-sm"
>
    <h2 class="mb-5 text-xl font-bold text-slate-800">
        دانلودهای من
    </h2>

    <?php if (empty($student_downloads)) : ?>

        <p class="rounded-lg bg-slate-50 p-4 leading-7 text-slate-600">
            هنوز فایل دانلودی برای حساب شما فعال نشده است.
            پس از تأیید سفارش و ایجاد مجوز دانلود، فایل‌ها در این قسمت نمایش داده می‌شوند.
        </p>

    <?php else : ?>

        <div class="space-y-4">

            <?php foreach ($student_downloads as $download) : ?>

                <?php
                $download_name = $download['download_name']
                    ?? $download['product_name']
                    ?? 'فایل آموزشی';

                $download_url = $download['download_url'] ?? '';
                $remaining = $download['downloads_remaining'] ?? '';
                ?>

                <article class="flex flex-col gap-4 rounded-xl border border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h3 class="font-bold text-slate-800">
                            <?php echo esc_html($download_name); ?>
                        </h3>

                        <p class="mt-2 text-sm text-slate-500">
                            تعداد دانلود باقی‌مانده:
                            <?php
                            echo esc_html(
                                $remaining === 'unlimited'
                                    ? 'نامحدود'
                                    : ($remaining === ''
                                        ? 'طبق مجوز ووکامرس'
                                        : $remaining)
                            );
                            ?>
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            شماره سفارش:
                            <?php echo esc_html($download['order_id'] ?? ''); ?>
                        </p>
                    </div>

                    <?php if ($download_url !== '') : ?>

                        <a
                            href="<?php echo esc_url($download_url); ?>"
                            class="inline-block rounded-lg bg-blue-600 px-5 py-3 text-center font-bold text-white hover:bg-blue-700"
                        >
                            دانلود فایل
                        </a>

                    <?php endif; ?>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>
</section>

            </div>

            <!-- پایان محتوای اصلی -->

        </div>

        <!-- پایان چیدمان پروفایل -->

    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const menuLinks = document.querySelectorAll(
            'nav[aria-label="منوی پروفایل"] a[href^="#"]'
        );

        const panelIds = [
            'profile-info',
            'my-courses',
            'my-consultations',
            'my-orders',
            'my-downloads'
        ];

        const panels = panelIds
            .map(function(id) {
                return document.getElementById(id);
            })
            .filter(Boolean);

        function showPanel(selectedId) {

            panels.forEach(function(panel) {
                panel.style.display =
                    panel.id === selectedId ? 'block' : 'none';
            });

            menuLinks.forEach(function(link) {
                const active =
                    link.getAttribute('href') === '#' + selectedId;

                link.classList.toggle('bg-blue-600', active);
                link.classList.toggle('text-white', active);
                link.classList.toggle('bg-slate-50', !active);
                link.classList.toggle('text-slate-700', !active);
            });
        }

        menuLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();

                const selectedId = link.getAttribute('href').substring(1);

                if (panelIds.includes(selectedId)) {
                    showPanel(selectedId);
                }
            });
        });

        showPanel('profile-info');
    });
</script>

<?php get_footer(); ?>