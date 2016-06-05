<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( !class_exists( 'YWRFD_Emails' ) ) {

    /**
     * Implements email functions for YWRFD plugin
     *
     * @class   YWRFD_Emails
     * @package Yithemes
     * @since   1.0.0
     * @author  Your Inspiration Themes
     *
     */
    class YWRFD_Emails {

        /**
         * Single instance of the class
         *
         * @var \YWRFD_Emails
         * @since 1.0.0
         */
        protected static $instance;

        /**
         * Returns single instance of the class
         *
         * @return \YWRFD_Emails
         * @since 1.0.0
         */
        public static function get_instance() {

            if ( is_null( self::$instance ) ) {

                self::$instance = new self( $_REQUEST );

            }

            return self::$instance;

        }

        /**
         * Constructor
         *
         * @since   1.0.0
         * @return  mixed
         * @author  Alberto Ruggiero
         */
        public function __construct() {

        }

        /**
         * Send the coupon mail
         *
         * @since   1.0.0
         *
         * @param   $mail_body
         * @param   $mail_subject
         * @param   $mail_address
         * @param   $type
         * @param   $vendor_id
         *
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function send_email( $mail_body, $mail_subject, $mail_address, $type, $vendor_id = '' ) {

            $wc_email = WC_Emails::instance();
            $email    = $wc_email->emails['YWRFD_Coupon_Mail'];

            $email->trigger( $mail_body, $mail_subject, $mail_address, $type, $vendor_id );

        }

        /**
         * Set the coupon email
         *
         * @since   1.0.0
         *
         * @param   $user_id
         * @param   $coupon_code
         * @param   $type
         * @param   $args
         * @param   $user_email
         * @param   $vendor_id
         *
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function prepare_coupon_mail( $user_id, $coupon_code, $type, $args = array(), $user_email, $vendor_id = '' ) {

            if ( $user_id ) {

                $first_name = get_user_meta( $user_id, 'billing_first_name', true );

                if ( $first_name == '' ) {
                    $first_name = get_user_meta( $user_id, 'nickname', true );
                }

                $last_name = get_user_meta( $user_id, 'billing_last_name', true );

                if ( $last_name == '' ) {
                    $last_name = get_user_meta( $user_id, 'nickname', true );
                }

            }
            else {

                $first_name = $args['nickname'];
                $last_name  = $args['nickname'];

            }

            $mail_body    = $this->get_mail_body( $coupon_code, $type, $first_name, $last_name, $user_email, $args, $vendor_id );
            $mail_subject = $this->get_subject( $type, $first_name, $last_name, $vendor_id );

            $this->send_email( $mail_body, $mail_subject, $user_email, $type, $vendor_id );

        }

        /**
         * Set the mail body
         *
         * @since   1.0.0
         *
         * @param   $coupon_code
         * @param   $type
         * @param   $first_name
         * @param   $last_name
         * @param   $user_email
         * @param   $args
         * @param   $vendor_id
         *
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function get_mail_body( $coupon_code, $type, $first_name, $last_name, $user_email, $args = array(), $vendor_id = '' ) {

            $mail_body = get_option( 'ywrfd_email_' . $type . '_mailbody' . ( apply_filters( 'ywrfd_set_vendor_id', '', $vendor_id ) ) );
            $coupon    = $this->get_coupon_info( $coupon_code );
            $find      = array(
                '{coupon_description}',
                '{site_title}',
                '{customer_name}',
                '{customer_last_name}',
                '{customer_email}',
                '{vendor_name}',
            );
            $replace   = array(
                $coupon,
                get_option( 'blogname' ),
                $first_name,
                $last_name,
                $user_email,
                apply_filters( 'ywrfd_get_vendor_name', '', $vendor_id ),
            );

            switch ( $type ) {

                case 'multiple':
                    $find[]    = '{total_reviews}';
                    $replace[] = $args['total_reviews'];
                    break;

                case 'notify':
                    $find[]    = '{remaining_reviews}';
                    $replace[] = $args['remaining_reviews'];
                    break;

                default:
                    $find[]    = '{product_name}';
                    $replace[] = ( !isset( $args['product_id'] ) ) ? '' : $this->render_mailbody_link( $args['product_id'], 'product' );

            }

            $mail_body = str_replace( $find, $replace, nl2br( $mail_body ) );

            return $mail_body;

        }

        /**
         * Set the subject and mail heading
         *
         * @since   1.0.0
         *
         * @param   $type
         * @param   $first_name
         * @param   $last_name
         * @param   $vendor_id
         *
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function get_subject( $type, $first_name, $last_name, $vendor_id = '' ) {

            $subject = get_option( 'ywrfd_email_' . $type . '_subject' . ( apply_filters( 'ywrfd_set_vendor_id', '', $vendor_id ) ) );
            $find    = array(
                '{site_title}',
                '{customer_name}',
                '{customer_last_name}',
                '{vendor_name}',
            );
            $replace = array(
                get_option( 'blogname' ),
                $first_name,
                $last_name,
                apply_filters( 'ywrfd_get_vendor_name', '', $vendor_id ),
            );
            $subject = str_replace( $find, $replace, $subject );

            return $subject;
        }

        /**
         * Get coupon info
         *
         * @since   1.0.0
         *
         * @param   $coupon_code
         *
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function get_coupon_info( $coupon_code ) {

            $result = '';
            $coupon = new WC_Coupon( $coupon_code );

            if ( $coupon->id ) {

                $post = get_post( $coupon->id );
                if ( $post ) {

                    $amount_suffix = get_woocommerce_currency_symbol();

                    if ( function_exists( 'wc_price' ) ) {

                        $amount_suffix = null;

                    }

                    if ( $coupon->discount_type == 'percent' || $coupon->discount_type == 'percent_product' ) {

                        $amount_suffix = '%';

                    }

                    $amount = $coupon->coupon_amount;
                    if ( $amount_suffix === null ) {
                        $amount        = wc_price( $amount );
                        $amount_suffix = '';
                    }

                    $products            = array();
                    $products_excluded   = array();
                    $categories          = array();
                    $categories_excluded = array();

                    if ( count( $coupon->product_ids ) > 0 ) {
                        foreach ( $coupon->product_ids as $product_id ) {
                            $product = wc_get_product( $product_id );
                            if ( $product ) {
                                $products[] = $this->render_mailbody_link( $product_id, 'product' );
                            }
                        }
                    }

                    if ( count( $coupon->exclude_product_ids ) > 0 ) {
                        foreach ( $coupon->exclude_product_ids as $product_id ) {
                            $product = wc_get_product( $product_id );
                            if ( $product ) {
                                $products_excluded[] = $this->render_mailbody_link( $product_id, 'product' );
                            }
                        }
                    }

                    if ( count( $coupon->product_categories ) > 0 ) {
                        foreach ( $coupon->product_categories as $term_id ) {
                            $term = get_term_by( 'id', $term_id, 'product_cat' );
                            if ( $term ) {
                                $categories[] = $this->render_mailbody_link( $term_id, 'category' );
                            }
                        }
                    }

                    if ( count( $coupon->exclude_product_categories ) > 0 ) {
                        foreach ( $coupon->exclude_product_categories as $term_id ) {
                            $term = get_term_by( 'id', $term_id, 'product_cat' );
                            if ( $term ) {
                                $categories_excluded[] = $this->render_mailbody_link( $term_id, 'category' );
                            }
                        }
                    }

                    ob_start();
                    ?>

                    <h2>
                        <?php echo __( 'Coupon code: ', 'yith-woocommerce-review-for-discounts' ) . $coupon->code; ?>
                    </h2>

                    <?php if ( !empty( $post->post_excerpt ) ) : ?>

                        <i>
                            <?php echo $post->post_excerpt; ?>
                        </i>

                    <?php endif; ?>

                    <p>
                        <b>
                            <?php printf( __( 'Coupon amount: %s%s off', 'yith-woocommerce-review-for-discounts' ), $amount, $amount_suffix ); ?>
                            <?php if ( $coupon->free_shipping == 'yes' ) : ?>
                                + <?php _e( 'Free shipping', 'yith-woocommerce-review-for-discounts' ); ?>
                                <br />
                            <?php endif; ?>
                        </b>
                        <span>
                            <?php if ( $coupon->minimum_amount != '' && $coupon->maximum_amount == '' ) : ?>
                                <?php printf( __( 'Valid for a minimum purchase of %s', 'yith-woocommerce-review-for-discounts' ), wc_price( $coupon->minimum_amount ) ); ?>
                            <?php endif; ?>
                            <?php if ( $coupon->minimum_amount == '' && $coupon->maximum_amount != '' ) : ?>
                                <?php printf( __( 'Valid for a maximum purchase of %s', 'yith-woocommerce-review-for-discounts' ), wc_price( $coupon->maximum_amount ) ); ?>
                            <?php endif; ?>
                            <?php if ( $coupon->minimum_amount != '' && $coupon->maximum_amount != '' ) : ?>
                                <?php printf( __( 'Valid for a minimum purchase of %s and a maximum of %s', 'yith-woocommerce-review-for-discounts' ), wc_price( $coupon->minimum_amount ), wc_price( $coupon->maximum_amount ) ); ?>
                            <?php endif; ?>
                        </span>
                    </p>

                    <?php if ( count( $products ) > 0 || count( $categories ) > 0 ) : ?>
                        <p>
                            <b><?php echo __( 'Valid for:' ); ?></b>
                            <br />
                            <?php if ( count( $products ) > 0 ) : ?>
                                <?php printf( __( 'Products: %s', 'yith-woocommerce-review-for-discounts' ), implode( ',', $products ) ); ?>
                                <br />
                            <?php endif; ?>

                            <?php if ( count( $categories ) > 0 ) : ?>
                                <?php printf( __( 'Products of these categories: %s', 'yith-woocommerce-review-for-discounts' ), implode( ',', $categories ) ); ?>
                                <br />
                            <?php endif; ?>

                        </p>
                    <?php endif; ?>

                    <?php if ( count( $products_excluded ) > 0 || count( $categories_excluded ) > 0 ) : ?>
                        <p>
                            <b><?php echo __( 'Not valid for:' ); ?></b>
                            <br />
                            <?php if ( count( $products_excluded ) > 0 ): ?>
                                <?php printf( __( 'Products: %s', 'yith-woocommerce-review-for-discounts' ), implode( ',', $products_excluded ) ) ?>
                                <br />
                            <?php endif; ?>

                            <?php if ( count( $categories_excluded ) > 0 ): ?>
                                <?php printf( __( 'Products of these categories: %s', 'yith-woocommerce-review-for-discounts' ), implode( ',', $categories_excluded ) ) ?>
                                <br />
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>

                    <span>
                        <?php if ( $coupon->individual_use == 'yes' ) : ?>
                            &bull; <?php _e( 'This coupon cannot be used with other coupons', 'yith-woocommerce-review-for-discounts' ); ?>
                            <br />
                        <?php endif; ?>
                        <?php if ( $coupon->exclude_sale_items == 'yes' ) : ?>
                            &bull; <?php _e( 'This coupon will not be applied to items on sale', 'yith-woocommerce-review-for-discounts' ); ?>
                            <br />
                        <?php endif; ?>
                    </span>

                    <?php if ( $coupon->expiry_date != '' ) : ?>
                        <p>
                            <br />
                            <b>
                                <?php printf( __( 'Expiration date: %s', 'yith-woocommerce-review-for-discounts' ), get_date_from_gmt( date( 'Y-m-d H:i:s', $coupon->expiry_date ), get_option( 'date_format' ) ) ); ?>
                            </b>
                        </p>
                    <?php endif; ?>

                    <?php

                    $result = ob_get_clean();

                }

            }

            return $result;

        }

        /**
         * Renders links for products or categories
         *
         * @since   1.0.0
         *
         * @param   $object_id
         * @param   $type
         *
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function render_mailbody_link( $object_id, $type ) {

            if ( $type == 'product' ) {

                $product = wc_get_product( $object_id );

                $url   = esc_url( get_permalink( $product->id ) );
                $title = $product->get_title();

            }
            else {

                $term = get_term_by( 'id', $object_id, 'product_cat' );

                $url   = get_term_link( $term->slug, 'product_cat' );
                $title = esc_html( $term->name );

            }

            return sprintf( '<a href="%s">%s</a>', $url, $title );
        }

    }

    /**
     * Unique access to instance of YWRFD_Emails class
     *
     * @return \YWRFD_Emails
     */
    function YWRFD_Emails() {

        return YWRFD_Emails::get_instance();

    }

}