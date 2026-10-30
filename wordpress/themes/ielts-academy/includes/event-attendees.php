<?php
/**
 * Event attendees admin page.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Shared order statuses.
 */
function ieltscamp_event_attendee_statuses() {
    return [
        'pending',
        'failed',
        'processing',
        'completed',
        'cancelled',
        'refunded',
    ];
}

/**
 * Register attendees submenu.
 */
add_action( 'admin_menu', 'ieltscamp_register_event_attendees_menu', 20 );

function ieltscamp_register_event_attendees_menu() {
    add_submenu_page(
        'edit.php?post_type=ielts_event',
        'ثبت‌نام‌کنندگان رویداد',
        'ثبت‌نام‌کنندگان',
        'manage_woocommerce',
        'ieltscamp-event-attendees',
        'ieltscamp_render_event_attendees_page'
    );
}

/**
 * Read filters from request.
 */
function ieltscamp_get_event_attendee_filters() {
    return [
        'event_id' => isset( $_GET['event_id'] )
            ? absint( wp_unslash( $_GET['event_id'] ) )
            : 0,

        'status' => isset( $_GET['status'] )
            ? sanitize_key( wp_unslash( $_GET['status'] ) )
            : '',

        'search' => isset( $_GET['attendee_search'] )
            ? sanitize_text_field( wp_unslash( $_GET['attendee_search'] ) )
            : '',
    ];
}

/**
 * Get event registration orders.
 */
function ieltscamp_get_event_attendee_orders( $filters = [] ) {

    if ( ! function_exists( 'wc_get_orders' ) ) {
        return [];
    }

    $args = [
        'limit'      => -1,
        'orderby'    => 'date',
        'order'      => 'DESC',
        'status'     => ieltscamp_event_attendee_statuses(),
        'meta_query' => [
            [
                'key'     => '_ieltscamp_event_id',
                'compare' => 'EXISTS',
            ],
        ],
    ];

    if ( ! empty( $filters['event_id'] ) ) {
        $args['meta_query'][] = [
            'key'     => '_ieltscamp_event_id',
            'value'   => absint( $filters['event_id'] ),
            'compare' => '=',
            'type'    => 'NUMERIC',
        ];
    }

    if (
        ! empty( $filters['status'] ) &&
        in_array(
            $filters['status'],
            ieltscamp_event_attendee_statuses(),
            true
        )
    ) {
        $args['status'] = [ $filters['status'] ];
    }

    $orders = wc_get_orders( $args );

    if ( ! empty( $filters['search'] ) ) {
        $search = $filters['search'];

        $orders = array_filter(
            $orders,
            function ( $order ) use ( $search ) {

                $name = $order->get_meta( '_ieltscamp_attendee_name' );
                $email = $order->get_meta( '_ieltscamp_attendee_email' );
                $phone = $order->get_meta( '_ieltscamp_attendee_phone' );

                $name = $name ?: $order->get_billing_first_name();
                $email = $email ?: $order->get_billing_email();
                $phone = $phone ?: $order->get_billing_phone();

                $searchable = [
                    $name,
                    $email,
                    $phone,
                    $order->get_order_number(),
                ];

                foreach ( $searchable as $value ) {
                    if (
                        false !== mb_stripos(
                            (string) $value,
                            $search
                        )
                    ) {
                        return true;
                    }
                }

                return false;
            }
        );
    }

    return array_values( $orders );
}

/**
 * Get attendee information from an order.
 */
function ieltscamp_get_attendee_data( $order ) {

    $event_id = absint(
        $order->get_meta( '_ieltscamp_event_id' )
    );

    $date = $order->get_date_created();

    return [
        'order_id' => $order->get_order_number(),
        'name' => $order->get_meta( '_ieltscamp_attendee_name' )
            ?: $order->get_billing_first_name(),
        'email' => $order->get_meta( '_ieltscamp_attendee_email' )
            ?: $order->get_billing_email(),
        'phone' => $order->get_meta( '_ieltscamp_attendee_phone' )
            ?: $order->get_billing_phone(),
        'event_id' => $event_id,
        'event_title' => $event_id
            ? get_the_title( $event_id )
            : 'رویداد نامشخص',
        'total' => $order->get_total(),
        'currency' => $order->get_currency(),
        'status' => wc_get_order_status_name( $order->get_status() ),
        'ref_id' => $order->get_meta( '_ieltscamp_zarinpal_ref_id' ),
        'date' => $date
            ? $date->date_i18n( 'Y/m/d H:i' )
            : '',
    ];
}

/**
 * Export filtered attendees as CSV.
 */
add_action(
    'admin_post_ieltscamp_export_event_attendees',
    'ieltscamp_export_event_attendees'
);

function ieltscamp_export_event_attendees() {

    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        wp_die( 'شما اجازه دسترسی به این بخش را ندارید.' );
    }

    check_admin_referer( 'ieltscamp_export_event_attendees' );

    $filters = ieltscamp_get_event_attendee_filters();
    $orders = ieltscamp_get_event_attendee_orders( $filters );

    nocache_headers();

    header( 'Content-Type: text/csv; charset=UTF-8' );
    header(
        'Content-Disposition: attachment; filename=ieltscamp-attendees-' .
        gmdate( 'Y-m-d' ) . '.csv'
    );

    // UTF-8 BOM for Excel compatibility.
    echo "\xEF\xBB\xBF";

    $output = fopen( 'php://output', 'w' );

    fputcsv( $output, [
        'شماره سفارش',
        'نام ثبت‌نام‌کننده',
        'ایمیل',
        'شماره تماس',
        'عنوان رویداد',
        'مبلغ',
        'واحد پول',
        'وضعیت پرداخت',
        'کد پیگیری زرین‌پال',
        'تاریخ ثبت‌نام',
    ] );

    foreach ( $orders as $order ) {

        $data = ieltscamp_get_attendee_data( $order );

        fputcsv( $output, [
            $data['order_id'],
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['event_title'],
            $data['total'],
            $data['currency'],
            $data['status'],
            $data['ref_id'],
            $data['date'],
        ] );
    }

    fclose( $output );
    exit;
}

/**
 * Render attendees admin page.
 */
function ieltscamp_render_event_attendees_page() {

    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        wp_die( 'شما اجازه دسترسی به این بخش را ندارید.' );
    }

    if ( ! function_exists( 'wc_get_orders' ) ) {
        echo '<div class="notice notice-error"><p>ووکامرس فعال نیست.</p></div>';
        return;
    }

    $filters = ieltscamp_get_event_attendee_filters();
    $orders = ieltscamp_get_event_attendee_orders( $filters );

    $events = get_posts( [
        'post_type'      => 'ielts_event',
        'post_status'    => [ 'publish', 'draft', 'private' ],
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );

    $export_url = admin_url( 'admin-post.php' );

    echo '<div class="wrap">';
    echo '<h1>ثبت‌نام‌کنندگان رویداد</h1>';
    echo '<p>مدیریت ثبت‌نام‌ها بر اساس سفارش‌های ووکامرس.</p>';

    // Search and filters.
    echo '<form method="get" action="">';

    echo '<input type="hidden" name="post_type" value="ielts_event">';
    echo '<input type="hidden" name="page" value="ieltscamp-event-attendees">';

    echo '<div style="display:flex;gap:10px;flex-wrap:wrap;margin:20px 0;">';

    echo '<input type="search" name="attendee_search" value="' .
        esc_attr( $filters['search'] ) .
        '" placeholder="نام، ایمیل، موبایل یا سفارش...">';

    echo '<select name="event_id">';
    echo '<option value="0">همه رویدادها</option>';

    foreach ( $events as $event ) {
        echo '<option value="' . esc_attr( $event->ID ) . '" ' .
            selected( $filters['event_id'], $event->ID, false ) . '>' .
            esc_html( $event->post_title ) .
            '</option>';
    }

    echo '</select>';

    echo '<select name="status">';
    echo '<option value="">همه وضعیت‌ها</option>';

    foreach ( ieltscamp_event_attendee_statuses() as $status ) {
        echo '<option value="' . esc_attr( $status ) . '" ' .
            selected( $filters['status'], $status, false ) . '>' .
            esc_html( wc_get_order_status_name( $status ) ) .
            '</option>';
    }

    echo '</select>';

    submit_button( 'اعمال فیلتر', 'primary', '', false );

    echo '<a class="button" href="' .
        esc_url(
            admin_url(
                'edit.php?post_type=ielts_event&page=ieltscamp-event-attendees'
            )
        ) . '">پاک کردن فیلترها</a>';

    echo '</div>';
    echo '</form>';

    // CSV export form.
    echo '<form method="get" action="' . esc_url( $export_url ) . '">';

    echo '<input type="hidden" name="action" value="ieltscamp_export_event_attendees">';

    wp_nonce_field(
        'ieltscamp_export_event_attendees'
    );

    echo '<input type="hidden" name="event_id" value="' .
        esc_attr( $filters['event_id'] ) . '">';

    echo '<input type="hidden" name="status" value="' .
        esc_attr( $filters['status'] ) . '">';

    echo '<input type="hidden" name="attendee_search" value="' .
        esc_attr( $filters['search'] ) . '">';

    echo '<p><button type="submit" class="button button-secondary">دانلود خروجی CSV</button></p>';
    echo '</form>';

    echo '<p>تعداد ثبت‌نام‌های مطابق فیلتر: <strong>' .
        esc_html( count( $orders ) ) .
        '</strong></p>';

    echo '<table class="widefat striped">';
    echo '<thead><tr>';
    echo '<th>شماره سفارش</th>';
    echo '<th>نام</th>';
    echo '<th>ایمیل</th>';
    echo '<th>موبایل</th>';
    echo '<th>رویداد</th>';
    echo '<th>مبلغ</th>';
    echo '<th>وضعیت پرداخت</th>';
    echo '<th>کد پیگیری</th>';
    echo '<th>تاریخ</th>';
    echo '</tr></thead><tbody>';

    if ( empty( $orders ) ) {

        echo '<tr><td colspan="9">ثبت‌نامی مطابق فیلترهای انتخاب‌شده پیدا نشد.</td></tr>';

    } else {

        foreach ( $orders as $order ) {

            $data = ieltscamp_get_attendee_data( $order );

            echo '<tr>';

            echo '<td><a href="' .
                esc_url( $order->get_edit_order_url() ) .
                '">#' . esc_html( $data['order_id'] ) .
                '</a></td>';

            echo '<td>' . esc_html( $data['name'] ) . '</td>';
            echo '<td>' . esc_html( $data['email'] ) . '</td>';
            echo '<td>' . esc_html( $data['phone'] ) . '</td>';

            echo '<td>';

            if ( $data['event_id'] ) {
                echo '<a href="' .
                    esc_url( get_edit_post_link( $data['event_id'] ) ) .
                    '">' . esc_html( $data['event_title'] ) . '</a>';
            } else {
                echo esc_html( $data['event_title'] );
            }

            echo '</td>';

            echo '<td>' .
                wp_kses_post( $order->get_formatted_order_total() ) .
                '</td>';

            echo '<td>' . esc_html( $data['status'] ) . '</td>';
            echo '<td>' . esc_html( $data['ref_id'] ?: '—' ) . '</td>';
            echo '<td>' . esc_html( $data['date'] ?: '—' ) . '</td>';

            echo '</tr>';
        }
    }

    echo '</tbody></table>';
    echo '</div>';
}
