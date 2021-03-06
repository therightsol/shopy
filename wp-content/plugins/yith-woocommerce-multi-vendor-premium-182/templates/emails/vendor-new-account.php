<?php
/**
 * Customer new account email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<p><?php printf( __( "Your vendor account has been approved %s.", 'yith_wc_product_vendors' ), esc_html( $blogname ) ); ?></p>

<p><?php printf( __( 'From your vendor dashboard you can view your recent commissions, view the sales report and manage your store and payment settings. Click <a href="%s">here</a> to access <strong>store dashboard</strong>.', 'yith_wc_product_vendors' ), admin_url() ); ?></p>

<?php do_action( 'woocommerce_email_footer' ); ?>
