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
    exit;
} // Exit if accessed directly

/**
 * Main class
 *
 * @class   YITH_WC_Anti_Fraud
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 */

if ( !class_exists( 'YITH_WC_Review_For_Discounts' ) ) {

    class YITH_WC_Review_For_Discounts {

        /**
         * Single instance of the class
         *
         * @var \YITH_WC_Review_For_Discounts
         * @since 1.0.0
         */
        protected static $instance;

        /**
         * Panel object
         *
         * @var     /Yit_Plugin_Panel object
         * @since   1.0.0
         * @see     plugin-fw/lib/yit-plugin-panel.php
         */
        protected $_panel = null;

        /**
         * @var $_premium string Premium tab template file name
         */
        protected $_premium = 'premium.php';

        /**
         * @var string Premium version landing link
         */
        protected $_premium_landing = 'http://yithemes.com/themes/plugins/yith-woocommerce-review-for-discounts/';

        /**
         * @var string Plugin official documentation
         */
        protected $_official_documentation = 'http://yithemes.com/docs-plugins/yith-woocommerce-review-for-discounts/';

        /**
         * @var string YITH WooCommerce Review For Discounts panel page
         */
        protected $_panel_page = 'yith-wc-review-for-discounts';

        /**
         * @var array
         */
        protected $_email_types = array();

        /**
         * @var bool
         */
        protected $_moderation_on = false;

        /**
         * Returns single instance of the class
         *
         * @return \YITH_WC_Review_For_Discounts
         * @since 1.0.0
         */
        public static function get_instance() {

            if ( is_null( self::$instance ) ) {

                self::$instance = new self;

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

            if ( !function_exists( 'WC' ) ) {
                return;
            }

            $this->_email_types = array(
                'coupon' => array(
                    'class' => 'YWRFD_Coupon_Mail',
                    'file'  => 'class-ywrfd-coupon-email.php',
                    'hide'  => false,
                ),
            );

            //Load plugin framework
            add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 12 );
            add_filter( 'plugin_action_links_' . plugin_basename( YWRFD_DIR . '/' . basename( YWRFD_FILE ) ), array( $this, 'action_links' ) );
            add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );
            add_action( 'yith_wrfd_premium', array( $this, 'premium_tab' ) );
            add_action( 'admin_menu', array( $this, 'add_menu_page' ), 5 );

            if ( get_option( 'ywrfd_enable_plugin' ) == 'yes' ) {

                $this->includes();

                add_action( 'comment_unapproved_to_approved', array( $this, 'on_comment_approvation' ) );
                add_filter( 'woocommerce_email_classes', array( $this, 'add_ywrfd_custom_email' ) );

                if ( is_admin() ) {
                    add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
                    add_action( 'ywrfd_howto', array( $this, 'get_howto_content' ) );
                    add_action( 'admin_notices', array( $this, 'set_notices' ) );

                }

            }

        }

        /**
         * Files inclusion
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        private function includes() {

            include_once( 'includes/class-ywrfd-discounts.php' );
            include_once( 'includes/class-ywrfd-emails.php' );

            if ( is_admin() ) {

                include_once( 'includes/class-ywrfd-ajax.php' );
                include_once( 'templates/admin/class-ywrfd-custom-send.php' );
                include_once( 'templates/admin/class-yith-wc-custom-textarea.php' );

            }

        }

        /**
         * ADMIN FUNCTIONS
         */

        /**
         * Add a panel under YITH Plugins tab
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         * @use     /Yit_Plugin_Panel class
         * @see     plugin-fw/lib/yit-plugin-panel.php
         */
        public function add_menu_page() {

            if ( !empty( $this->_panel ) ) {
                return;
            }

            $admin_tabs = array();

            if ( defined( 'YWRFD_PREMIUM' ) ) {
                $admin_tabs['premium-general'] = __( 'General Settings', 'yith-woocommerce-review-for-discounts' );
                $admin_tabs['mandrill']        = __( 'Mandrill Settings', 'yith-woocommerce-review-for-discounts' );
            }
            else {
                $admin_tabs['general']         = __( 'General Settings', 'yith-woocommerce-review-for-discounts' );
                $admin_tabs['premium-landing'] = __( 'Premium Version', 'yith-woocommerce-review-for-discounts' );
            }

            $admin_tabs['howto'] = __( 'How To', 'yith-woocommerce-review-for-discounts' );


            $args = array(
                'create_menu_page' => true,
                'parent_slug'      => '',
                'page_title'       => _x( 'Review For Discounts', 'plugin name in admin page title', 'yith-woocommerce-review-for-discounts' ),
                'menu_title'       => _x( 'Review For Discounts', 'plugin name in admin WP menu', 'yith-woocommerce-review-for-discounts' ),
                'capability'       => 'manage_options',
                'parent'           => '',
                'parent_page'      => 'yit_plugin_panel',
                'page'             => $this->_panel_page,
                'admin-tabs'       => $admin_tabs,
                'options-path'     => YWRFD_DIR . 'plugin-options'
            );

            $this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );

        }

        /**
         * Initializes CSS and javascript
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function admin_scripts() {

            global $post;

            wp_enqueue_style( 'ywrfd-admin', YWRFD_ASSETS_URL . '/css/ywrfd-admin.css', array(), YWRFD_VERSION );

            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

            wp_enqueue_script( 'ywrfd-admin', YWRFD_ASSETS_URL . '/js/ywrfd-admin' . $suffix . '.js', array( 'jquery' ), YWRFD_VERSION );

            $params = apply_filters( 'ywrfd_admin_scripts_filter', array(
                'ajax_url'                   => admin_url( 'admin-ajax.php' ),
                'post_id'                    => isset( $post->ID ) ? $post->ID : '',
                'vendor_id'                  => '0',
                'ywar_active'                => false,
                'comment_moderation'         => $this->is_moderation_required(),
                'comment_moderation_warning' => __( 'This option cannot be modified because it is essential for YITH WooCommerce Review for Discounts to work correctly.', 'yith-woocommerce-review-for-discounts' ),
                'before_send_test_email'     => __( 'Sending test email...', 'yith-woocommerce-review-for-discounts' ),
                'after_send_test_email'      => __( 'Test email has been sent successfully!', 'yith-woocommerce-review-for-discounts' ),
                'test_mail_wrong'            => __( 'Please insert a valid email address', 'yith-woocommerce-review-for-discounts' )
            ) );

            wp_localize_script( 'ywrfd-admin', 'ywrfd_admin', $params );

        }

        /**
         * Get placeholder reference content.
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function get_howto_content() {

            ?>
            <div id="plugin-fw-wc">
                <h3>
                    <?php _e( 'Placeholder list', 'yith-woocommerce-review-for-discounts' ); ?>
                </h3>
                <table class="form-table">
                    <tbody>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <b>{coupon_description}</b>
                        </th>
                        <td class="forminp">
                            <?php _e( 'How coupon works. This placeholder must be included.', 'yith-woocommerce-review-for-discounts' ); ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <b>{site_title}</b>
                        </th>
                        <td class="forminp">
                            <?php _e( 'Site title', 'yith-woocommerce-review-for-discounts' ); ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <b>{customer_name}</b>
                        </th>
                        <td class="forminp">
                            <?php _e( 'Customer\'s name', 'yith-woocommerce-review-for-discounts' ) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <b>{customer_last_name}</b>
                        </th>
                        <td class="forminp">
                            <?php _e( 'Customer\'s last name', 'yith-woocommerce-review-for-discounts' ) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <b>{customer_email}</b>
                        </th>
                        <td class="forminp">
                            <?php _e( 'Customer\'s email', 'yith-woocommerce-review-for-discounts' ) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <b>{product_name}</b>
                        </th>
                        <td class="forminp">
                            <?php _e( 'Name of the reviewed product', 'yith-woocommerce-review-for-discounts' ) ?>
                        </td>
                    </tr>

                    <?php if ( defined( 'YWRFD_PREMIUM' ) ) : ?>

                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <b>{total_reviews}</b>
                            </th>
                            <td class="forminp">
                                <?php _e( 'Total reviews of an user', 'yith-woocommerce-review-for-discounts' ) ?>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <b>{remaining_reviews}</b>
                            </th>
                            <td class="forminp">
                                <?php _e( 'How many reviews the user has to write to achieve an objective', 'yith-woocommerce-review-for-discounts' ) ?>
                            </td>
                        </tr>

                        <?php if ( $this->is_multivendor_active() ): ?>
                            <tr valign="top">
                                <th scope="row" class="titledesc">
                                    <b>{vendor_name}</b>
                                </th>
                                <td class="forminp">
                                    <?php _e( 'Name of the vendor', 'yith-woocommerce-review-for-discounts' ) ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php
        }

        /**
         * Check if Comment moderation is required
         *
         * @since   1.0.0
         * @return  bool
         * @author  Alberto Ruggiero
         */
        public function is_moderation_required() {

            $coupon_send = get_option( 'ywrfd_coupon_sending' );

            return ( $coupon_send == '' || $coupon_send == 'moderated' );

        }

        /**
         * Advise if comment moderation is activated
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function set_notices() {

            if ( $this->is_moderation_required() ) {

                if ( get_option( 'comment_moderation' ) == 0 ) {

                    update_option( 'comment_moderation', 1 );
                    ?>
                    <div class="update-nag">
                        <p>
                            <?php _e( 'Comment moderation has been enabled to make YITH WooCommerce Review for Discounts work correctly.', 'yith-woocommerce-review-for-discounts' ); ?>
                        </p>
                    </div>
                    <?php
                }

            }
            else {

                if ( get_option( 'comment_moderation' ) == 1 ) {

                    ?>
                    <div class="update-nag">
                        <p>
                            <?php printf( __( 'Comment moderation is enabled but is not essential to make YITH WooCommerce Review for Discounts work correctly. If you want to change this option click %s here %s', 'yith-woocommerce-review-for-discounts' ), '<a href="' . esc_url( admin_url( 'options-discussion.php' ) ) . '" target="_blank">', '</a>' ); ?>
                        </p>
                    </div>
                    <?php

                }

            }

        }

        /**
         * On comment approvation
         *
         * @since   1.0.0
         *
         * @param   $comment
         *
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function on_comment_approvation( $comment ) {

            if ( !$this->user_already_commented( $comment ) && $this->is_moderation_required() && !$this->comment_already_approved( $comment ) ) {

                $this->get_coupons( $comment );

            }

        }

        /**
         * Check if the comment has already approved
         *
         * @since   1.0.0
         *
         * @param   $comment
         *
         * @return  bool
         * @author  Alberto Ruggiero
         */
        public function comment_already_approved( $comment ) {

            $result = get_comment_meta( $comment->comment_ID, '_ywrfd_approved', true );

            return $result;

        }

        /**
         * Check if an user has already commented
         *
         * @since   1.0.0
         *
         * @param   $comment
         *
         * @return  bool
         * @author  Alberto Ruggiero
         */
        public function user_already_commented( $comment ) {

            $args = array(
                'author_email' => $comment->comment_author_email,
                'post_id'      => $comment->comment_post_ID,
                'status'       => 'all',
                'count'        => true,

            );

            $comments = new WP_Comment_Query();

            $count = $comments->query( $args );

            if ( $count > 1 ) {

                return true;

            }

            return false;

        }

        /**
         * Get coupons to be sent
         *
         * @since   1.0.0
         *
         * @param   $comment
         *
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function get_coupons( $comment ) {

            if ( 'product' != get_post_type( $comment->comment_post_ID ) ) {
                return;
            }

            if ( $comment->comment_parent ) {
                return;
            }

            $product_id = $comment->comment_post_ID;
            $user_id    = $comment->user_id;
            $user_mail  = $comment->comment_author_email;

            $user_info = array(
                'nickname' => ( $user_id ) ? get_user_meta( $user_id, 'nickname', true ) : $comment->comment_author,
                'email'    => $user_mail,
            );

            $discounts = apply_filters( 'ywrfd_get_discounts', array(
                new YWRFD_Discounts()
            ), $product_id, $user_mail );

            if ( !empty( $discounts ) ) {

                foreach ( $discounts as $discount ) {

                    $coupon_code = $this->create_coupon( $discount, $user_info );
                    YWRFD_Emails()->prepare_coupon_mail( $user_id, $coupon_code, $discount->trigger, array( 'nickname' => $user_info['nickname'], 'product_id' => $product_id, 'total_reviews' => $discount->trigger_threshold ), $user_mail, $discount->vendor_id );

                }

            }

            update_comment_meta( $comment->comment_ID, '_ywrfd_approved', 1 );

            do_action( 'ywrfd_notification_sending', $user_id, $user_mail, $user_info['nickname'] );

        }

        /**
         * Creates a coupon with specific settings
         *
         * @since   1.0.0
         *
         * @param   $discount
         * @param   $user_info
         *
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function create_coupon( $discount, $user_info ) {

            $coupon_code = $user_info['nickname'] . '-' . current_time( 'YmdHis' );

            $coupon_data = array(
                'post_title'     => $coupon_code,
                'post_author'    => apply_filters( 'ywrfd_set_coupon_author', 0, $discount->vendor_id ),
                'post_excerpt'   => $discount->description,
                'post_date'      => date( "Y-m-d H:i:s", time() ),
                'post_status'    => 'publish',
                'comment_status' => 'closed',
                'ping_status'    => 'closed',
                'post_name'      => $coupon_code,
                'post_parent'    => 0,
                'menu_order'     => 0,
                'post_type'      => 'shop_coupon'
            );

            $coupon_id = wp_insert_post( $coupon_data );

            //Set coupon expiration date
            $expiry_date = '';
            if ( $discount->expiry_days > 0 && !empty( $discount->expiry_days ) ) {
                $expiry_date = date( 'Y-m-d', strtotime( '+' . $discount->expiry_days . ' days' ) );
            }

            //Set products to apply coupon
            $product_ids = '';
            if ( !empty( $discount->product_ids ) ) {
                $product_ids = implode( ',', $discount->product_ids );
            }

            //Set categories to apply coupon
            $product_categories = '';
            if ( !empty( $discount->product_categories ) ) {
                $product_categories = implode( ',', $discount->product_categories );
            }

            update_post_meta( $coupon_id, 'discount_type', $discount->discount_type );
            update_post_meta( $coupon_id, 'coupon_amount', $discount->coupon_amount );
            update_post_meta( $coupon_id, 'expiry_date', $expiry_date );
            update_post_meta( $coupon_id, 'free_shipping', $discount->free_shipping );
            update_post_meta( $coupon_id, 'individual_use', $discount->individual_use );
            update_post_meta( $coupon_id, 'product_ids', $product_ids );
            update_post_meta( $coupon_id, 'product_categories', $product_categories );
            update_post_meta( $coupon_id, 'minimum_amount', $discount->minimum_amount );
            update_post_meta( $coupon_id, 'maximum_amount', $discount->maximum_amount );
            update_post_meta( $coupon_id, 'usage_limit', 1 );
            update_post_meta( $coupon_id, 'usage_limit_per_user', 1 );
            update_post_meta( $coupon_id, 'customer_email', $user_info['email'] );

            if ( $discount->vendor_id != 0 ) {
                update_post_meta( $coupon_id, 'vendor_id', $discount->vendor_id );
            }

            update_post_meta( $coupon_id, 'generated_by', 'ywrfd' );

            return $coupon_code;

        }

        /**
         * Add the YWRFD_Coupon_Mail class to WooCommerce mail classes
         *
         * @since   1.0.0
         *
         * @param   $email_classes
         *
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function add_ywrfd_custom_email( $email_classes ) {

            foreach ( $this->_email_types as $type => $email_type ) {
                $email_classes[$email_type['class']] = include( "includes/{$email_type['file']}" );
            }

            return $email_classes;

        }

        /**
         * YITH FRAMEWORK
         */

        /**
         * Load plugin framework
         *
         * @since   1.0.0
         * @return  void
         * @author  Andrea Grillo
         * <andrea.grillo@yithemes.com>
         */
        public function plugin_fw_loader() {
            if ( !defined( 'YIT_CORE_PLUGIN' ) ) {
                global $plugin_fw_data;
                if ( !empty( $plugin_fw_data ) ) {
                    $plugin_fw_file = array_shift( $plugin_fw_data );
                    require_once( $plugin_fw_file );
                }
            }
        }

        /**
         * Premium Tab Template
         *
         * Load the premium tab template on admin page
         *
         * @since   1.0.0
         * @return  void
         * @author  Andrea Grillo
         * <andrea.grillo@yithemes.com>
         */
        public function premium_tab() {
            $premium_tab_template = YWRFD_TEMPLATE_PATH . '/admin/' . $this->_premium;
            if ( file_exists( $premium_tab_template ) ) {
                include_once( $premium_tab_template );
            }
        }

        /**
         * Get the premium landing uri
         *
         * @since   1.0.0
         * @return  string The premium landing link
         * @author  Andrea Grillo
         * <andrea.grillo@yithemes.com>
         */
        public function get_premium_landing_uri() {
            return defined( 'YITH_REFER_ID' ) ? $this->_premium_landing . '?refer_id=' . YITH_REFER_ID : $this->_premium_landing;
        }

        /**
         * Action Links
         *
         * add the action links to plugin admin page
         * @since   1.0.0
         *
         * @param   $links | links plugin array
         *
         * @return  mixed
         * @author  Andrea Grillo
         * <andrea.grillo@yithemes.com>
         * @use     plugin_action_links_{$plugin_file_name}
         */
        public function action_links( $links ) {

            $links[] = '<a href="' . admin_url( "admin.php?page={$this->_panel_page}" ) . '">' . __( 'Settings', 'yith-woocommerce-review-for-discounts' ) . '</a>';

            if ( defined( 'YWRFD_FREE_INIT' ) ) {
                $links[] = '<a href="' . $this->get_premium_landing_uri() . '" target="_blank">' . __( 'Premium Version', 'yith-woocommerce-review-for-discounts' ) . '</a>';
            }

            return $links;
        }

        /**
         * Plugin row meta
         *
         * add the action links to plugin admin page
         *
         * @since   1.0.0
         *
         * @param   $plugin_meta
         * @param   $plugin_file
         * @param   $plugin_data
         * @param   $status
         *
         * @return  Array
         * @author  Andrea Grillo <andrea.grillo@yithemes.com>
         * @use     plugin_row_meta
         */
        public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
            if ( ( defined( 'YWRFD_INIT' ) && ( YWRFD_INIT == $plugin_file ) ) ||
                ( defined( 'YWRFD_FREE_INIT' ) && ( YWRFD_FREE_INIT == $plugin_file ) )
            ) {

                $plugin_meta[] = '<a href="' . $this->_official_documentation . '" target="_blank">' . __( 'Plugin Documentation', 'yith-woocommerce-review-for-discounts' ) . '</a>';
            }

            return $plugin_meta;
        }

    }

}