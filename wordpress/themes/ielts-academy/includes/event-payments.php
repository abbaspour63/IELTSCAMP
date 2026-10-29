<?php

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Read Zarinpal configuration from environment variables.
 */
function ieltscamp_zarinpal_config() {
    $sandbox = getenv('ZARINPAL_SANDBOX') === 'true';

    return [
        'sandbox' => $sandbox,
        'merchant_id' => trim(
            (string) (getenv('ZARINPAL_MERCHANT_ID') ?: '')
        ),
        'request_url' => $sandbox
            ? 'https://sandbox.zarinpal.com/pg/v4/payment/request.json'
            : 'https://payment.zarinpal.com/pg/v4/payment/request.json',
        'verify_url' => $sandbox
            ? 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json'
            : 'https://payment.zarinpal.com/pg/v4/payment/verify.json',
        'start_pay_url' => $sandbox
            ? 'https://sandbox.zarinpal.com/pg/StartPay/'
            : 'https://www.zarinpal.com/pg/StartPay/',
        'callback_url' => add_query_arg(
            'ieltscamp_zarinpal_callback',
            '1',
            home_url('/')
        ),
    ];
}

/**
 * Render event registration form.
 */
function ieltscamp_render_event_registration_form($event_id) {
    $price = get_post_meta(
        $event_id,
        '_ielts_event_price',
        true
    );

    if ($price === '' || ! ctype_digit((string) $price) || (int) $price <= 0) {
        echo '<p class="rounded-xl bg-slate-100 p-4 text-sm leading-7 text-slate-600">';
        echo 'هزینه ثبت‌نام این رویداد هنوز تعیین نشده است.';
        echo '</p>';
        return;
    }

    ?>
    <?php

$payment_result = isset($_GET['event_payment'])
    ? sanitize_key(wp_unslash($_GET['event_payment']))
    : '';

$payment_messages = [
    'success'   => 'پرداخت آزمایشی با موفقیت تأیید شد.',
    'cancelled' => 'پرداخت لغو شد. برای ثبت‌نام دوباره تلاش کنید.',
    'failed'    => 'پرداخت تأیید نشد. اگر مبلغی کسر شده، وضعیت سفارش را بررسی کنید.',
];

if (isset($payment_messages[$payment_result])) :
    $is_success = 'success' === $payment_result;
?>
    <p class="mb-4 rounded-xl p-4 text-sm leading-7 <?php
        echo $is_success
            ? 'bg-green-100 text-green-800'
            : 'bg-amber-100 text-amber-800';
    ?>">
        <?php echo esc_html($payment_messages[$payment_result]); ?>
    </p>
<?php endif; ?>
  


    <form method="post" class="mt-6 space-y-4">
        <?php wp_nonce_field('ieltscamp_event_register_' . $event_id, 'ieltscamp_event_nonce'); ?>

        <input type="hidden" name="ieltscamp_event_register" value="1">
        <input type="hidden" name="event_id" value="<?php echo esc_attr($event_id); ?>">

        <div>
            <label for="ieltscamp_name" class="mb-1 block text-sm font-semibold">نام و نام خانوادگی</label>
            <input id="ieltscamp_name" name="attendee_name" type="text" required maxlength="120"
                autocomplete="name" class="w-full rounded-xl border border-slate-300 px-4 py-3">
        </div>

        <div>
            <label for="ieltscamp_email" class="mb-1 block text-sm font-semibold">ایمیل</label>
            <input id="ieltscamp_email" name="attendee_email" type="email" required maxlength="190"
                autocomplete="email" class="w-full rounded-xl border border-slate-300 px-4 py-3" dir="ltr">
        </div>

        <div>
            <label for="ieltscamp_phone" class="mb-1 block text-sm font-semibold">موبایل</label>
            <input id="ieltscamp_phone" name="attendee_phone" type="tel" required maxlength="30"
                autocomplete="tel" class="w-full rounded-xl border border-slate-300 px-4 py-3" dir="ltr">
        </div>

        <p class="text-sm font-semibold text-slate-700">
            هزینه ثبت‌نام: <?php echo esc_html(number_format_i18n((int) $price)); ?> تومان
        </p>

        <button type="submit"
            class="w-full rounded-xl bg-sky-600 px-5 py-3 text-center font-bold text-white transition hover:bg-sky-700">
            ادامه به پرداخت آزمایشی
        </button>
    </form>
    <?php
}
/**
 * Process event registration and create a pending WooCommerce order.
 */add_action(
    'template_redirect',
    'ieltscamp_process_event_registration',
    1
);

add_action(
    'template_redirect',
    'ieltscamp_process_event_registration',
    1
);

function ieltscamp_process_event_registration() {

    if (
        ! isset($_POST['ieltscamp_event_register']) ||
        '1' !== (string) wp_unslash($_POST['ieltscamp_event_register'])
    ) {
        return;
    }

    $event_id = isset($_POST['event_id'])
        ? absint($_POST['event_id'])
        : 0;

    $nonce = isset($_POST['ieltscamp_event_nonce'])
        ? sanitize_text_field(wp_unslash($_POST['ieltscamp_event_nonce']))
        : '';

    if (
        ! $event_id ||
        ! wp_verify_nonce(
            $nonce,
            'ieltscamp_event_register_' . $event_id
        )
    ) {
        wp_die('درخواست نامعتبر است. صفحه را تازه‌سازی کنید.');
    }

    $event = get_post($event_id);

    if (
        ! $event ||
        'ielts_event' !== $event->post_type ||
        'publish' !== $event->post_status
    ) {
        wp_die('رویداد معتبر نیست.');
    }

    $name = isset($_POST['attendee_name'])
        ? sanitize_text_field(wp_unslash($_POST['attendee_name']))
        : '';

    $email = isset($_POST['attendee_email'])
        ? sanitize_email(wp_unslash($_POST['attendee_email']))
        : '';

    $phone = isset($_POST['attendee_phone'])
        ? sanitize_text_field(wp_unslash($_POST['attendee_phone']))
        : '';

    if ('' === $name || ! is_email($email) || '' === $phone) {
        wp_die('نام، ایمیل معتبر و شماره تلفن را وارد کنید.');
    }

    $price = get_post_meta(
        $event_id,
        '_ielts_event_price',
        true
    );

    if (
        '' === $price ||
        ! ctype_digit((string) $price) ||
        (int) $price <= 0
    ) {
        wp_die('مبلغ رویداد معتبر نیست.');
    }

    $config = ieltscamp_zarinpal_config();

    if (
        '' === $config['merchant_id'] ||
        ! function_exists('wc_create_order')
    ) {
        wp_die('تنظیمات درگاه یا ووکامرس کامل نیست.');
    }

    $price_toman = (int) $price;
    $amount_rial = $price_toman * 10;

    if ($amount_rial <= 0) {
        wp_die('مبلغ پرداخت معتبر نیست.');
    }

    try {
        $order = wc_create_order([
            'customer_id' => get_current_user_id(),
            'status'      => 'pending',
        ]);

        if (is_wp_error($order) || ! $order) {
            wp_die('ساخت سفارش ناموفق بود.');
        }

        $item = new WC_Order_Item_Fee();
        $item->set_name('ثبت‌نام رویداد: ' . $event->post_title);
        $item->set_amount($price_toman);
        $item->set_total($price_toman);
        $item->set_tax_status('none');

        $order->add_item($item);

        $order->set_billing_first_name($name);
        $order->set_billing_email($email);
        $order->set_billing_phone($phone);

        $order->update_meta_data('_ieltscamp_event_id', $event_id);
        $order->update_meta_data('_ieltscamp_attendee_name', $name);
        $order->update_meta_data('_ieltscamp_attendee_email', $email);
        $order->update_meta_data('_ieltscamp_attendee_phone', $phone);
        $order->update_meta_data(
            '_ieltscamp_event_price_toman',
            $price_toman
        );
        $order->update_meta_data(
            '_ieltscamp_zarinpal_amount_rial',
            $amount_rial
        );

        $order->calculate_totals();
        $order->save();

        $response = wp_remote_post($config['request_url'], [
            'timeout' => 30,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],
            'body' => wp_json_encode([
                'merchant_id'  => $config['merchant_id'],
                'amount'       => $amount_rial,
                'callback_url' => $config['callback_url'],
                'description'  => 'ثبت‌نام رویداد: ' . $event->post_title,
                'metadata'     => [
                    'email'  => $email,
                    'mobile' => $phone,
                ],
            ]),
        ]);

        if (is_wp_error($response)) {
            $order->update_status(
                'failed',
                'خطا در اتصال به زرین‌پال.'
            );

            error_log(
                'IELTSCAMP ZarinPal request error: ' .
                $response->get_error_message()
            );

            wp_die('اتصال به درگاه پرداخت ناموفق بود.');
        }

        $http_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(
            wp_remote_retrieve_body($response),
            true
        );

        $authority = isset($body['data']['authority'])
            ? sanitize_text_field($body['data']['authority'])
            : '';

        $gateway_code = isset($body['data']['code'])
            ? (int) $body['data']['code']
            : 0;

        if (
            $http_code < 200 ||
            $http_code >= 300 ||
            100 !== $gateway_code ||
            '' === $authority
        ) {
            $order->update_status(
                'failed',
                'زرین‌پال درخواست پرداخت را نپذیرفت.'
            );

            error_log(
                'IELTSCAMP ZarinPal request failed. HTTP: ' .
                $http_code . '; response: ' .
                wp_json_encode($body)
            );

            wp_die('درخواست پرداخت ناموفق بود. گزارش خطای سرور را بررسی کنید.');
        }

        $order->update_meta_data(
            '_ieltscamp_zarinpal_authority',
            $authority
        );
        $order->save();

        wp_redirect(
            $config['start_pay_url'] . rawurlencode($authority)
        );
        exit;

    } catch (Throwable $exception) {
        error_log(
            'IELTSCAMP event payment error: ' .
            $exception->getMessage()
        );

        wp_die('خطایی هنگام آماده‌سازی پرداخت رخ داد.');
    }
}

   

function ieltscamp_handle_zarinpal_callback() {

    if (
        ! isset($_GET['ieltscamp_zarinpal_callback']) ||
        '1' !== (string) wp_unslash($_GET['ieltscamp_zarinpal_callback'])
    ) {
        return;
    }

    $authority = isset($_GET['Authority'])
        ? sanitize_text_field(wp_unslash($_GET['Authority']))
        : '';

    $status = isset($_GET['Status'])
        ? sanitize_text_field(wp_unslash($_GET['Status']))
        : '';

    if ('' === $authority) {
        wp_die('شناسه پرداخت در پاسخ درگاه وجود ندارد.');
    }

    $orders = wc_get_orders([
        'limit'      => 1,
        'status'     => ['pending', 'failed', 'processing', 'completed'],
        'meta_key'   => '_ieltscamp_zarinpal_authority',
        'meta_value' => $authority,
        'return'     => 'objects',
    ]);

    if (empty($orders)) {
        wp_die('سفارش مرتبط با این پرداخت پیدا نشد.');
    }

    $order = $orders[0];

    $event_id = absint(
        $order->get_meta('_ieltscamp_event_id')
    );

    $event_url = get_permalink($event_id);

    if (! $event_url) {
        $event_url = home_url('/');
    }

    // A successful callback must not cause a second payment completion.
    if ($order->is_paid()) {
        wp_safe_redirect(
            add_query_arg('event_payment', 'success', $event_url)
        );
        exit;
    }

    if ('OK' !== strtoupper($status)) {
        $order->update_status(
            'failed',
            'کاربر پرداخت را تکمیل نکرد یا پرداخت لغو شد.'
        );

        wp_safe_redirect(
            add_query_arg('event_payment', 'cancelled', $event_url)
        );
        exit;
    }

    $config = ieltscamp_zarinpal_config();

    $amount_rial = absint(
        $order->get_meta('_ieltscamp_zarinpal_amount_rial')
    );

    if (
        '' === $config['merchant_id'] ||
        $amount_rial <= 0
    ) {
        wp_die('تنظیمات یا مبلغ سفارش برای تأیید معتبر نیست.');
    }

    $response = wp_remote_post($config['verify_url'], [
        'timeout' => 30,
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ],
        'body' => wp_json_encode([
            'merchant_id' => $config['merchant_id'],
            'amount'      => $amount_rial,
            'authority'   => $authority,
        ]),
    ]);

    if (is_wp_error($response)) {
        error_log(
            'IELTSCAMP ZarinPal verify connection error: ' .
            $response->get_error_message()
        );

        wp_die('ارتباط با سرویس تأیید پرداخت برقرار نشد. وضعیت سفارش را بررسی کنید.');
    }

    $http_code = wp_remote_retrieve_response_code($response);
    $body = json_decode(
        wp_remote_retrieve_body($response),
        true
    );

    $verify_code = isset($body['data']['code'])
        ? (int) $body['data']['code']
        : 0;

    $ref_id = isset($body['data']['ref_id'])
        ? sanitize_text_field((string) $body['data']['ref_id'])
        : '';

    if (
        $http_code >= 200 &&
        $http_code < 300 &&
        100 === $verify_code &&
        '' !== $ref_id
    ) {
        $order->update_meta_data(
            '_ieltscamp_zarinpal_ref_id',
            $ref_id
        );

        $order->payment_complete($ref_id);

        $order->add_order_note(
            'پرداخت زرین‌پال تأیید شد. کد پیگیری: ' . $ref_id
        );

        $order->save();

        wp_safe_redirect(
            add_query_arg('event_payment', 'success', $event_url)
        );
        exit;
    }

    error_log(
        'IELTSCAMP ZarinPal verification failed. HTTP: ' .
        $http_code .
        '; response: ' .
        wp_json_encode($body)
    );

    $order->update_status(
        'failed',
        'تأیید پرداخت زرین‌پال موفق نبود.'
    );

    wp_safe_redirect(
        add_query_arg('event_payment', 'failed', $event_url)

    );

    exit;
}

  