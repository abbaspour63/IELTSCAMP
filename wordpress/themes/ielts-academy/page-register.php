<?php
/**
 * Template Name: IELTSCAMP Registration
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( is_user_logged_in() ) {
    wp_safe_redirect( home_url( '/profile/' ) );
    exit;
}

$errors = [];
$success = false;

$name  = '';
$email = '';
$phone = '';

if (
    'POST' === $_SERVER['REQUEST_METHOD']
    && isset( $_POST['ielts_register_submit'] )
) 
    if (
        ! isset( $_POST['ielts_register_nonce'] )
        || ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['ielts_register_nonce'] ) ),
            'ielts_register'
        )
    ) {
        $errors[] = 'درخواست معتبر نیست. صفحه را تازه‌سازی کنید.';
    } else {
        $name = isset( $_POST['full_name'] )
            ? sanitize_text_field( wp_unslash( $_POST['full_name'] ) )
            : '';

        $email = isset( $_POST['email'] )
            ? sanitize_email( wp_unslash( $_POST['email'] ) )
            : '';

        $phone = isset( $_POST['phone'] )
            ? sanitize_text_field( wp_unslash( $_POST['phone'] ) )
            : '';

        $password = isset( $_POST['password'] )
            ? (string) wp_unslash( $_POST['password'] )
            : '';

        $password_confirm = isset( $_POST['password_confirm'] )
            ? (string) wp_unslash( $_POST['password_confirm'] )
            : '';

        if ( '' === $name ) {
            $errors[] = 'نام و نام خانوادگی را وارد کنید.';
        }

        if ( ! is_email( $email ) ) {
            $errors[] = 'ایمیل معتبر وارد کنید.';
        } elseif ( email_exists( $email ) ) {
            $errors[] = 'این ایمیل قبلاً ثبت شده است.';
        }

        if ( strlen( $password ) < 8 ) {
            $errors[] = 'رمز عبور باید حداقل ۸ کاراکتر باشد.';
        }

        if ( $password !== $password_confirm ) {
            $errors[] = 'تکرار رمز عبور مطابقت ندارد.';
        }

        if ( ! empty( $phone ) && ! preg_match( '/^[0-9+\s()\-]{7,20}$/', $phone ) ) {
            $errors[] = 'شماره موبایل معتبر نیست.';
        }

        if ( empty( $errors ) ) {
            $username = sanitize_user( current( explode( '@', $email ) ), true );

            if ( '' === $username ) {
                $username = 'student';
            }

            $base_username = $username;
            $counter = 1;

            while ( username_exists( $username ) ) {
                $username = $base_username . $counter;
                $counter++;
            }

            $user_id = wp_insert_user( [
                'user_login'   => $username,
                'user_pass'    => $password,
                'user_email'   => $email,
                'display_name' => $name,
                'first_name'   => $name,
                'role'         => 'customer',
            ] );

            if ( is_wp_error( $user_id ) ) {
                $errors[] = 'ساخت حساب انجام نشد. لطفاً دوباره تلاش کنید.';
        
    }else {
    if ( '' !== $phone ) {
        update_user_meta(
            $user_id,
            'billing_phone',
            $phone
        );
    }

    if ( ielts_academy_send_verification_email( $user_id ) ) {
        $success = true;
    } else {
        // Email could not be queued by WordPress.
        require_once ABSPATH . 'wp-admin/includes/user.php';
        wp_delete_user( $user_id );

        $errors[] = 'ارسال ایمیل تأیید انجام نشد. لطفاً تنظیمات ایمیل سایت را بررسی و دوباره تلاش کنید.';
    }
}
}
    }
get_header();

?>

<main class="min-h-[70vh] bg-slate-50 px-4 py-12">
    <div class="mx-auto max-w-xl rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-10">

        <h1 class="mb-2 text-center text-3xl font-extrabold text-blue-950">

        </h1>

        <p class="mb-8 text-center text-sm text-slate-500">
            برای استفاده از خدمات IELTSCAMP ثبت‌نام کنید.
        </p>

        <?php if ( $success ) : ?>

            <div class="rounded-xl border border-green-200 bg-green-50 p-5 text-center text-green-800">
                <!-- <p class="mb-3 font-bold">حساب شما با موفقیت ساخته شد.</p>
                <p class="mb-5 text-sm">اکنون می‌توانید وارد حساب خود شوید.</p>
                <a href="<?php echo esc_url( home_url( '/login/' ) ); ?>"
                   class="inline-block rounded-xl bg-blue-700 px-6 py-3 font-bold text-white hover:bg-blue-800">
                    ورود به حساب
                </a>
            </div> -->

            <p class="mb-3 font-bold">
    حساب شما ساخته شد؛ اما هنوز فعال نیست.
</p>

<p class="mb-5 text-sm">
    لینک تأیید به ایمیل شما ارسال شد.
    صندوق ورودی و پوشه هرزنامه را بررسی کنید.
    پس از تأیید ایمیل می‌توانید وارد حساب شوید.
</p>

        <?php else : ?>

            <?php if ( ! empty( $errors ) ) : ?>
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc space-y-1 pr-5">
                        <?php foreach ( $errors as $error ) : ?>
                            <li><?php echo esc_html( $error ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" class="space-y-5">
                <?php wp_nonce_field( 'ielts_register', 'ielts_register_nonce' ); ?>

                <div>
                    <label for="full_name" class="mb-2 block text-sm font-bold text-slate-700">
                        نام و نام خانوادگی
                    </label>
                    <input id="full_name" name="full_name" type="text"
                           autocomplete="name" required
                           value="<?php echo esc_attr( $name ); ?>"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-600">
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-bold text-slate-700">
                        ایمیل
                    </label>
                    <input id="email" name="email" type="email"
                           autocomplete="email" required
                           value="<?php echo esc_attr( $email ); ?>"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-600">
                </div>

                <div>
                    <label for="phone" class="mb-2 block text-sm font-bold text-slate-700">
                        شماره موبایل
                    </label>
                    <input id="phone" name="phone" type="tel"
                           autocomplete="tel"
                           value="<?php echo esc_attr( $phone ); ?>"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-600">
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-bold text-slate-700">
                        رمز عبور (حداقل ۸ کاراکتر)
                    </label>
                    <input id="password" name="password" type="password"
                           autocomplete="new-password" minlength="8" required
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-600">
                </div>

                <div>
                    <label for="password_confirm" class="mb-2 block text-sm font-bold text-slate-700">
                        تکرار رمز عبور
                    </label>
                    <input id="password_confirm" name="password_confirm" type="password"
                           autocomplete="new-password" minlength="8" required
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-600">
                </div>

                <button type="submit" name="ielts_register_submit" value="1"
                        class="w-full rounded-xl bg-blue-700 px-5 py-3 font-bold text-white transition hover:bg-blue-800">
                    ایجاد حساب کاربری
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600">
                قبلاً ثبت‌نام کرده‌اید؟
                <a href="<?php echo esc_url( home_url( '/login/' ) ); ?>"
                   class="font-bold text-blue-700 hover:underline">
                    ورود به حساب
                </a>
            </p>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
<?php  ?>
