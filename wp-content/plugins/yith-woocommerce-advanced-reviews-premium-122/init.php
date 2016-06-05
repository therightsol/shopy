<?php
/*
Plugin Name: YITH WooCommerce Advanced Reviews Premium
Plugin URI: http://yithemes.com/themes/plugins/yith-woocommerce-advanced-reviews/
Description: Extends the basic functionality of woocommerce reviews and add a histogram table to the reviews of your products, as well as you see in most trendy e-commerce sites.
Author: Yithemes
Text Domain: yith-woocommerce-advanced-reviews
Version: 1.2.2
Author URI: http://yithemes.com/
*/
/*  Copyright 2013-2015  Your Inspiration Themes  (email : plugins@yithemes.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined ( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

require_once 'functions.php';

yith_initialize_plugin_fw ( plugin_dir_path ( __FILE__ ) );

yith_define ( 'YITH_YWAR_INIT', plugin_basename ( __FILE__ ) );
yith_define ( 'YITH_YWAR_SLUG', 'yith-woocommerce-advanced-reviews' );
yith_define ( 'YITH_YWAR_SECRET_KEY', 'wbJGFwHx426IS4V4vYeB' );
yith_define ( 'YITH_YWAR_VERSION', '1.2.2' );
yith_define ( 'YITH_YWAR_PREMIUM', '1' );
yith_define ( 'YITH_YWAR_FILE', __FILE__ );
yith_define ( 'YITH_YWAR_DIR', plugin_dir_path ( __FILE__ ) );
yith_define ( 'YITH_YWAR_INCLUDES_DIR', YITH_YWAR_DIR . '/includes/' );
yith_define ( 'YITH_YWAR_URL', plugins_url ( '/', __FILE__ ) );
yith_define ( 'YITH_YWAR_ASSETS_URL', YITH_YWAR_URL . 'assets' );
yith_define ( 'YITH_YWAR_TEMPLATES_DIR', YITH_YWAR_DIR . 'templates/' );

yit_deactive_free_version ( 'YITH_YWAR_FREE_INIT', plugin_basename ( __FILE__ ) );
register_activation_hook ( __FILE__, 'yith_plugin_registration_hook' );
yit_maybe_plugin_fw_loader ( plugin_dir_path ( __FILE__ ) );

function yith_ywar_premium_init () {
    /**
     * Load text domain and start plugin
     */
    load_plugin_textdomain ( 'yith-woocommerce-advanced-reviews', false, dirname ( plugin_basename ( __FILE__ ) ) . '/languages/' );

    require_once ( YITH_YWAR_INCLUDES_DIR . 'class.yith-woocommerce-advanced-reviews.php' );
    require_once ( YITH_YWAR_INCLUDES_DIR . 'class.yith-woocommerce-advanced-reviews-premium.php' );

    global $YWAR_AdvancedReview;
    $YWAR_AdvancedReview = YITH_WooCommerce_Advanced_Reviews_Premium::get_instance ();
}

function yith_ywar_premium_install () {

    if ( ! function_exists ( 'WC' ) ) {
        add_action ( 'admin_notices', 'yith_ywar_premium_install_woocommerce_admin_notice' );
    } else {
        do_action ( 'yith_ywar_premium_init' );
    }
}

add_action ( 'yith_ywar_premium_init', 'yith_ywar_premium_init' );
add_action ( 'plugins_loaded', 'yith_ywar_premium_install', 11 );