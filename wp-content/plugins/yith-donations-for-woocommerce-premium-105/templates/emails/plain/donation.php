<?php
if( !defined( 'ABSPATH' ) )
    exit;

/**
 * Implements Donation Mail for YWCDS plugin (Plain text)
 *
 * @class YITH_WC_Donations_Email
 * @package Yithemes
 * @since 1.0.0
 * @author Your Inspiration Themes
 */
if ( ! $order ) {

    global $current_user;
    get_currentuserinfo();

    $billing_email      = $current_user->user_email;
    $order_date         = current_time( 'mysql' );
    $modified_date      = current_time( 'mysql' );
    $order_id           = '0';
    $customer_id        = $current_user->ID;
    $billing_first_name = $current_user->user_login;

} else {

    $billing_email      = $order->billing_email;
    $order_date         = $order->order_date;
    $modified_date      = $order->modified_date;
    $order_id           = $order->id;
    $customer_id        = $order->__get( 'user_id' );
    $billing_first_name = $order->billing_first_name;

}

$query_args = array(
    'id'    => urlencode( base64_encode( ! empty( $customer_id ) ? $customer_id : 0 ) ),
    'email' => urlencode( base64_encode( $billing_email ) )
);


$find = array(
    '{customer_name}',
    '{customer_email}',
    '{site_title}',
    '{order_id}',
    '{order_date}',
    '{order_date_completed}',
    '{donation_amount}',
    '{project_name}'

);

$replace = array(
    $billing_first_name,
    $billing_email,
    get_option( 'blogname' ),
    $order_id,
    $order_date,
    $modified_date,
    $donation_amount,
    $project_name
);

$mail_body = str_replace($find, $replace, get_option( 'ywcds_mail_content' ));

echo $email_heading . "\n\n";

echo $mail_body . "\n\n\n"  ;

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );