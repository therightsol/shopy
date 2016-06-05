<?php
/**
 * Plugin Name: YITH WooCommerce Review For Discounts Premium
 * Plugin URI: http://yithemes.com/themes/plugins/yith-woocommerce-review-for-discounts/
 * Description: Reward your customers with a gentle discount: approve their reviews and encourage them to purchase again in your shop!
 * Author: YIThemes
 * Text Domain: yith-woocommerce-review-for-discounts
 * Version: 1.0.0
 * Author URI: http://yithemes.com/
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( !function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function ywrfd_install_woocommerce_premium_admin_notice() {
    ?>
    <div class="error">
        <p><?php _e( 'YITH WooCommerce Review For Discounts is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-review-for-discounts' ); ?></p>
    </div>
    <?php
}

if ( !function_exists( 'yit_deactive_free_version' ) ) {
    require_once 'plugin-fw/yit-deactive-plugin.php';
}

yit_deactive_free_version( 'YWRFD_FREE_INIT', plugin_basename( __FILE__ ) );

if ( !defined( 'YWRFD_VERSION' ) ) {
    define( 'YWRFD_VERSION', '1.0.0' );
}

if ( !defined( 'YWRFD_INIT' ) ) {
    define( 'YWRFD_INIT', plugin_basename( __FILE__ ) );
}

if ( !defined( 'YWRFD_SLUG' ) ) {
    define( 'YWRFD_SLUG', 'yith-woocommerce-review-for-discounts' );
}

if ( !defined( 'YWRFD_SECRET_KEY' ) ) {
    define( 'YWRFD_SECRET_KEY', '4bhzoJIRHc1bT5KUzhUO' );
}

if ( !defined( 'YWRFD_PREMIUM' ) ) {
    define( 'YWRFD_PREMIUM', '1' );
}

if ( !defined( 'YWRFD_FILE' ) ) {
    define( 'YWRFD_FILE', __FILE__ );
}

if ( !defined( 'YWRFD_DIR' ) ) {
    define( 'YWRFD_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined( 'YWRFD_URL' ) ) {
    define( 'YWRFD_URL', plugins_url( '/', __FILE__ ) );
}

if ( !defined( 'YWRFD_ASSETS_URL' ) ) {
    define( 'YWRFD_ASSETS_URL', YWRFD_URL . 'assets' );
}

if ( !defined( 'YWRFD_TEMPLATE_PATH' ) ) {
    define( 'YWRFD_TEMPLATE_PATH', YWRFD_DIR . 'templates' );
}

/* Plugin Framework Version Check */
if ( !function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YWRFD_DIR . 'plugin-fw/init.php' ) ) {
    require_once( YWRFD_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YWRFD_DIR );

function ywrfd_init() {

    /* Load text domain */
    load_plugin_textdomain( 'yith-woocommerce-review-for-discounts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    /* === Global YITH WooCommerce Review For Discounts  === */
    YITH_WRFD();

}

add_action( 'ywrfd_init', 'ywrfd_init' );

function ywrfd_install() {

    if ( !function_exists( 'WC' ) ) {
        add_action( 'admin_notices', 'ywrfd_install_woocommerce_premium_admin_notice' );
    }
    else {
        do_action( 'ywrfd_init' );
    }

}

add_action( 'plugins_loaded', 'ywrfd_install', 11 );

/**
 * Init default plugin settings
 */
if ( !function_exists( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}

register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

if ( !function_exists( 'YITH_WRFD' ) ) {

    /**
     * Unique access to instance of YITH_WC_Anti_Fraud
     *
     * @since   1.0.0
     * @return  YITH_WC_Review_For_Discounts|YITH_WC_Review_For_Discounts_Premium
     * @author  Alberto Ruggiero
     */
    function YITH_WRFD() {

        // Load required classes and functions
        require_once( YWRFD_DIR . 'class.yith-wc-review-for-discounts.php' );

        if ( defined( 'YWRFD_PREMIUM' ) && file_exists( YWRFD_DIR . 'class.yith-wc-review-for-discounts-premium.php' ) ) {


            require_once( YWRFD_DIR . 'class.yith-wc-review-for-discounts-premium.php' );
            return YITH_WC_Review_For_Discounts_Premium::get_instance();
        }

        return YITH_WC_Review_For_Discounts::get_instance();

    }

}

if ( !function_exists( 'ywrfd_trash_coupon_schedule' ) ) {

    /**
     * Creates a cron job to handle daily expired coupon trash
     *
     * @since   1.0.0
     * @return  void
     * @author  Alberto Ruggiero
     */
    function ywrfd_trash_coupon_schedule() {
        wp_schedule_event( time(), 'daily', 'ywrfd_trash_coupon_cron' );
    }

}
register_activation_hook( __FILE__, 'ywrfd_trash_coupon_schedule' );