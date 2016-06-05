<?php
if ( !defined( 'ABSPATH' ) || !defined( 'YITH_WCBM_PREMIUM' ) ) {
    exit; // Exit if accessed directly
}

require_once( 'functions.yith-wcbm-premium.php' );

/**
 * Implements features of FREE version of YITH WooCommerce Badge Management
 *
 * @class   YWCM_Cart_Messages
 * @package YITH WooCommerce Badge Management
 * @since   1.0.0
 * @author  Yithemes
 */


if ( !class_exists( 'YITH_WCBM_Frontend_Premium' ) ) {
    /**
     * Frontend class.
     * The class manage all the Frontend behaviors.
     *
     * @since 1.0.0
     */
    class YITH_WCBM_Frontend_Premium extends YITH_WCBM_Frontend {

        /**
         * Returns single instance of the class
         *
         * @return \YITH_WCQV_Frontend
         * @since 1.0.0
         */
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Constructor
         *
         * @access public
         * @author Leanza Francesco <leanzafrancesco@gmail.com>
         * @since  1.0.0
         */
        public function __construct() {
            parent::__construct();
        }


        /**
         * Get the badge Id based on current language
         *
         * @access public
         * @return int
         *
         * @param $id_badge string id of badge
         *
         * @since  1.0.0
         * @author Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public function get_wmpl_badge_id( $id_badge ) {
            $id_badge = absint( $id_badge );
            global $sitepress;

            if ( isset( $sitepress ) ) {
                if ( function_exists( 'wpml_object_id_filter' ) ) {
                    $id_badge = wpml_object_id_filter( $id_badge, 'post', true );
                } elseif ( function_exists( 'icl_object_id' ) ) {
                    $id_badge = icl_object_id( $id_badge, 'post', true );
                }
            }

            return $id_badge;
        }

        /**
         * Edit image in products
         *
         * @access public
         *
         * @param string $val        product image
         * @param int    $product_id product id
         *
         * @return string
         *
         * @since  1.0.0
         * @author Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public function show_badge_on_product( $val, $product_id ) {
            global $bm_single_product_id;
            if ( !$bm_single_product_id && is_product() ) {
                $bm_single_product_id = $product_id;
            }
            $is_current_product = $bm_single_product_id == $product_id;

            $product_id = $this->get_wpml_parent_id( $product_id );

            // Hide on single product page
            $hide_on_single = get_option( 'yith-wcbm-hide-on-single-product' ) == 'yes';

            if ( is_product() && $hide_on_single && $is_current_product ) {
                return $val;
            } else {
                return parent::show_badge_on_product( $val, $product_id );
            }
        }

        /**
         * Hide or show default sale flash badge
         *
         * @access public
         * @return string
         *
         * @param $val value of filter woocommerce_sale_flash
         *
         * @since  1.0.0
         * @author Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public function sale_flash( $val, $post ) {
            $on_sale_badge  = get_option( 'yith-wcbm-on-sale-badge' );
            $hide_on_single = get_option( 'yith-wcbm-hide-on-single-product' );
            if ( $on_sale_badge != 'none' || ( is_product() && $hide_on_single == 'yes' ) ) {
                return '';
            }

            return parent::sale_flash( $val, $post );
        }

        public function enqueue_scripts() {
            parent::enqueue_scripts();
            $advanced_badge = get_option( 'yith-wcbm-advanced-on-sale-badge' );
            if ( !empty( $advanced_badge ) && $advanced_badge != 'none' ) {
                wp_enqueue_style( 'yith_wcbm_advanced_badge_style_' . $advanced_badge, YITH_WCBM_ASSETS_URL . '/css/advanced-on-sale/' . $advanced_badge . '.css' );
            }
        }
    }
}
/**
 * Unique access to instance of YITH_WCBM_Frontend_Premium class
 *
 * @return \YITH_WCBM_Frontend_Premium
 * @since 1.0.0
 */
function YITH_WCBM_Frontend_Premium() {
    return YITH_WCBM_Frontend_Premium::get_instance();
}

?>
