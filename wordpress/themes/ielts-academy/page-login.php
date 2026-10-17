<?php
if (!defined('ABSPATH')) {
    exit;
}
get_header();
?>

<main dir="rtl" class="min-h-screen bg-slate-50 px-4 py-16">
    <div class="mx-auto max-w-md rounded-2xl bg-white p-8 shadow-lg">

        <h1 class="mb-3 text-center text-2xl font-bold text-slate-800">
            ورود به IELTS Academy
        </h1>

        <p class="mb-8 text-center text-slate-500">
            روش ورود خود را انتخاب کنید.
        </p>

        <!-- تب‌ها -->
        <div class="mb-6 grid grid-cols-2 gap-3" role="tablist">
            <button
                type="button"
                id="ielts-tab-email"
                role="tab"
                aria-selected="true"
                aria-controls="ielts-panel-email"
                class="rounded-lg bg-blue-600 px-4 py-3 font-bold text-white"
            >
                ورود با ایمیل
            </button>

            <button
                type="button"
                id="ielts-tab-mobile"
                role="tab"
                aria-selected="false"
                aria-controls="ielts-panel-mobile"
                class="rounded-lg bg-slate-100 px-4 py-3 font-bold text-slate-700"
            >
                ورود با موبایل
            </button>
        </div>

        <!-- پنل ورود با ایمیل -->
        <section id="ielts-panel-email" role="tabpanel"
                 aria-labelledby="ielts-tab-email">

            <?php if (isset($_GET['login_error'])) : ?>
                <p class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-700">
                    ایمیل یا رمز عبور صحیح نیست.
                </p>
            <?php endif; ?>

            <form method="post" action="">
                <?php wp_nonce_field('ielts_email_login', 'ielts_email_nonce'); ?>

                <input type="hidden" name="ielts_email_login" value="1">

                <label for="email" class="mb-2 block font-medium">
                    ایمیل
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    autocomplete="email"
                    class="mb-5 w-full rounded-lg border border-slate-300 p-3"
                    placeholder="example@email.com"
                >

                <label for="password" class="mb-2 block font-medium">
                    رمز عبور
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="mb-6 w-full rounded-lg border border-slate-300 p-3"
                    placeholder="رمز عبور خود را وارد کنید"
                >

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-3 font-bold text-white hover:bg-blue-700"
                >
                    ورود به حساب
                </button>
                <div class="mt-8 border-t border-slate-200 pt-6 text-center">
                    <p class="text-sm text-slate-600">
                        حساب کاربری ندارید؟
                        <a href="<?php echo esc_url( home_url( '/register/' ) ); ?>"
                           class="font-bold text-blue-700 hover:text-blue-900 hover:underline">
                            ثبت‌نام کنید
                        </a>
                    </p>
                </div>
            </form>
        </section>

        <!-- پنل ورود با موبایل -->
        <section id="ielts-panel-mobile" role="tabpanel"
                 aria-labelledby="ielts-tab-mobile" hidden>

            <form id="ielts-login-form">
                <label for="phone" class="mb-2 block font-medium">
                    شماره موبایل
                </label>

                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    required
                    inputmode="numeric"
                    autocomplete="tel"
                    class="mb-5 w-full rounded-lg border border-slate-300 p-3"
                    placeholder="09123456789"
                >

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-3 font-bold text-white hover:bg-blue-700"
                >
                    دریافت کد تأیید
                </button>

                <p id="ielts-login-message"
                   class="mt-4 text-sm text-slate-600"
                   aria-live="polite"></p>
            </form>
        </section>

    </div>
</main>

<?php get_footer(); ?>
