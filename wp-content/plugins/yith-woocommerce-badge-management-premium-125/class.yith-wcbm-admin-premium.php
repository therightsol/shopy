<?php
if ( !defined( 'ABSPATH' ) || !defined( 'YITH_WCBM_PREMIUM' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Implements features of FREE version of YITH WooCommerce Badge Management
 *
 * @class   YWCM_Cart_Messages
 * @package YITH WooCommerce Badge Management
 * @since   1.0.0
 * @author  Yithemes
 */

require_once( 'functions.yith-wcbm.php' );

if ( !class_exists( 'YITH_WCBM_Admin_Premium' ) ) {
    /**
     * Admin class.
     * The class manage all the admin behaviors.
     *
     * @since 1.0.0
     */
    class YITH_WCBM_Admin_Premium extends YITH_WCBM_Admin {

        /**
         * Returns single instance of the class
         *
         * @return \YITH_WCBM_Admin_Premium
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

            // AJAX Action for metabox_options_premium.js
            add_action( 'wp_ajax_yith_get_advanced_badge_style', array( $this, 'get_advanced_badge_style' ) );
            add_action( 'wp_ajax_yith_get_css_badge_style', array( $this, 'get_css_badge_style' ) );

            add_filter( 'yith_wcbm_panel_settings_options', array( $this, 'add_advanced_options' ) );

            add_filter( 'yith_wcbm_settings_admin_tabs', array( $this, 'add_advanced_admin_tab' ) );

            // Add bulk edit for Badge assigned to a product
            add_filter( 'manage_product_posts_columns', array( $this, 'add_columns' ), 15 );
            add_action( 'manage_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );
            add_action( 'woocommerce_product_bulk_edit_end', array( $this, 'woocommerce_product_bulk_edit_end' ) );
            add_action( 'woocommerce_product_bulk_edit_save', array( $this, 'save_bulk_edit' ), 10, 2 );


            // register plugin to licence/update system
            add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
            add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );
        }


        function badge_settings_tabs() {
            global $post;
            $bm_meta    = get_post_meta( $post->ID, '_yith_wcbm_product_meta', true );
            $id_badge   = ( isset( $bm_meta[ 'id_badge' ] ) ) ? $bm_meta[ 'id_badge' ] : '';
            $start_date = ( isset( $bm_meta[ 'start_date' ] ) ) ? $bm_meta[ 'start_date' ] : '';
            $end_date   = ( isset( $bm_meta[ 'end_date' ] ) ) ? $bm_meta[ 'end_date' ] : '';
            ?>
            <p class="form-field">
                <select name="_yith_wcbm_product_meta[id_badge]" class="select">
                    <option value="" selected="selected"><?php echo __( 'none', 'yith-wcbm' ) ?></option>
                    <?php

                    $args   = ( array(
                        'posts_per_page'   => -1,
                        'post_type'        => 'yith-wcbm-badge',
                        'orderby'          => 'title',
                        'order'            => 'ASC',
                        'post_status'      => 'publish',
                        'suppress_filters' => false
                    ) );
                    $badges = get_posts( $args );

                    foreach ( $badges as $badge ) {
                        ?>
                        <option value="<?php echo $badge->ID ?>" <?php selected( $id_badge, $badge->ID ) ?>><?php echo get_the_title( $badge->ID ) ?></option><?php
                    }

                    ?>
                </select>
            </p>
            <div id="yith-wcbm-metabox-schedule-options">
                <h3><?php _e( 'Schedule', 'yith-wcbm' ) ?></h3>

                <div id="yith-wcbm-metabox-schedule-container">
                    <p>
                        <label for="yith-wcbm-badge-start-date"><?php _e( 'Starting Date', 'yith-wcbm' ) ?></label>
                        <input id="yith-wcbm-badge-start-date" type="text" class="yith-wcbm-datepicker" name="_yith_wcbm_product_meta[start_date]" value="<?php echo $start_date; ?>"
                               placeholder="YYYY-MM-DD">
                        <span class="dashicons dashicons-no-alt yith-wcbm-delete-input"></span>
                    </p>

                    <p>
                        <label for="yith-wcbm-badge-end-date"><?php _e( 'Ending Date', 'yith-wcbm' ) ?></label>
                        <input id="yith-wcbm-badge-end-date" type="text" class="yith-wcbm-datepicker" name="_yith_wcbm_product_meta[end_date]" value="<?php echo $end_date; ?>"
                               placeholder="YYYY-MM-DD">
                        <span class="dashicons dashicons-no-alt yith-wcbm-delete-input"></span>
                    </p>
                </div>
            </div>
            <?php
        }

        public function badge_settings_save( $post_id ) {
            if ( !empty( $_POST[ '_yith_wcbm_product_meta' ] ) ) {
                $product_meta               = $_POST[ '_yith_wcbm_product_meta' ];
                $product_meta[ 'id_badge' ] = ( !empty( $product_meta[ 'id_badge' ] ) ) ? $product_meta[ 'id_badge' ] : '';

                update_post_meta( $post_id, '_yith_wcbm_product_meta', $product_meta );
            }
        }


        public function add_advanced_admin_tab( $admin_tabs_free ) {
            $admin_tabs_free[ 'category' ] = __( 'Category Badges', 'yith-wcbm' );
            unset( $admin_tabs_free[ 'premium' ] );

            return $admin_tabs_free;
        }

        public function add_advanced_options() {
            require_once( 'plugin-options/advanced-options.php' );

            return $a_settings;
        }

        public function admin_enqueue_scripts() {
            parent::admin_enqueue_scripts();

            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );

            $screen = get_current_screen();

            if ( 'product' == $screen->id ) {
                wp_enqueue_script( 'yith_wcbm_product_badge_metabox', YITH_WCBM_ASSETS_URL . '/js/product_badge_metabox.js', array( 'jquery' ), '1.0.0', true );
            }

        }

        /**
         * Metabox Render [Override]
         *
         * @access public
         * @author Leanza Francesco <leanzafrancesco@gmail.com>
         * @since  1.0.0
         */
        public function metabox_render( $post ) {
            $bm_meta = get_post_meta( $post->ID, '_badge_meta', true );

            $default = array(
                'type'                        => 'text',
                'text'                        => '',
                'txt_color_default'           => '#000000',
                'txt_color'                   => '#000000',
                'bg_color_default'            => '#2470FF',
                'bg_color'                    => '#2470FF',
                'advanced_bg_color'           => '',
                'advanced_bg_color_default'   => '',
                'advanced_text_color'         => '',
                'advanced_text_color_default' => '',
                'advanced_badge'              => 1,
                'css_badge'                   => 1,
                'css_bg_color'                => '',
                'css_bg_color_default'        => '',
                'css_text_color'              => '',
                'css_text_color_default'      => '',
                'css_text'                    => '',
                'width'                       => '100',
                'height'                      => '50',
                'position'                    => 'top-left',
                'image_url'                   => '',
                'pos_top'                     => 0,
                'pos_bottom'                  => 0,
                'pos_left'                    => 0,
                'pos_right'                   => 0,
                'border_top_left_radius'      => 0,
                'border_top_right_radius'     => 0,
                'border_bottom_right_radius'  => 0,
                'border_bottom_left_radius'   => 0,
                'padding_top'                 => 0,
                'padding_bottom'              => 0,
                'padding_left'                => 0,
                'padding_right'               => 0,
                'font_size'                   => 13,
                'line_height'                 => -1,
                'opacity'                     => 100
            );

            if ( !isset( $bm_meta[ 'pos_top' ] ) ) {
                $position = ( isset( $bm_meta[ 'position' ] ) ) ? $bm_meta[ 'position' ] : 'top-left';
                if ( $position == 'top-right' ) {
                    $default[ 'pos_bottom' ] = 'auto';
                    $default[ 'pos_left' ]   = 'auto';
                } else if ( $position == 'bottom-left' ) {
                    $default[ 'pos_top' ]   = 'auto';
                    $default[ 'pos_right' ] = 'auto';
                } else if ( $position == 'bottom-right' ) {
                    $default[ 'pos_top' ]  = 'auto';
                    $default[ 'pos_left' ] = 'auto';
                } else {
                    $default[ 'pos_bottom' ] = 'auto';
                    $default[ 'pos_right' ]  = 'auto';
                }
            }

            $args = wp_parse_args( $bm_meta, $default );

            $args = apply_filters( 'yith_wcbm_metabox_options_content_args', $args );

            yith_wcbm_metabox_options_content_premium( $args );
        }

        public function metabox_save( $post_id ) {
            if ( !empty( $_POST[ '_badge_meta' ] ) ) {
                $badge_meta[ 'type' ]      = ( !empty( $_POST[ '_badge_meta' ][ 'type' ] ) ) ? $_POST[ '_badge_meta' ][ 'type' ] : '';
                $badge_meta[ 'text' ]      = ( !empty( $_POST[ '_badge_meta' ][ 'text' ] ) ) ? $_POST[ '_badge_meta' ][ 'text' ] : '';
                $badge_meta[ 'txt_color' ] = ( !empty( $_POST[ '_badge_meta' ][ 'txt_color' ] ) ) ? $_POST[ '_badge_meta' ][ 'txt_color' ] : '';
                $badge_meta[ 'bg_color' ]  = ( !empty( $_POST[ '_badge_meta' ][ 'bg_color' ] ) ) ? esc_url( $_POST[ '_badge_meta' ][ 'bg_color' ] ) : '';
                $badge_meta[ 'width' ]     = ( !empty( $_POST[ '_badge_meta' ][ 'width' ] ) ) ? $_POST[ '_badge_meta' ][ 'width' ] : '';
                $badge_meta[ 'height' ]    = ( !empty( $_POST[ '_badge_meta' ][ 'height' ] ) ) ? $_POST[ '_badge_meta' ][ 'height' ] : '';
                $badge_meta[ 'position' ]  = ( !empty( $_POST[ '_badge_meta' ][ 'position' ] ) ) ? $_POST[ '_badge_meta' ][ 'position' ] : 'top-left';
                $badge_meta[ 'image_url' ] = ( !empty( $_POST[ '_badge_meta' ][ 'image_url' ] ) ) ? $_POST[ '_badge_meta' ][ 'image_url' ] : '';
                // P R E M I U M
                $badge_meta[ 'advanced_bg_color' ]          = ( !empty( $_POST[ '_badge_meta' ][ 'advanced_bg_color' ] ) ) ? $_POST[ '_badge_meta' ][ 'advanced_bg_color' ] : '';
                $badge_meta[ 'advanced_text_color' ]        = ( !empty( $_POST[ '_badge_meta' ][ 'advanced_text_color' ] ) ) ? $_POST[ '_badge_meta' ][ 'advanced_text_color' ] : '';
                $badge_meta[ 'advanced_badge' ]             = ( !empty( $_POST[ '_badge_meta' ][ 'advanced_badge' ] ) ) ? $_POST[ '_badge_meta' ][ 'advanced_badge' ] : 1;
                $badge_meta[ 'css_bg_color' ]               = ( !empty( $_POST[ '_badge_meta' ][ 'css_bg_color' ] ) ) ? $_POST[ '_badge_meta' ][ 'css_bg_color' ] : '';
                $badge_meta[ 'css_text_color' ]             = ( !empty( $_POST[ '_badge_meta' ][ 'css_text_color' ] ) ) ? $_POST[ '_badge_meta' ][ 'css_text_color' ] : '';
                $badge_meta[ 'css_text' ]                   = ( !empty( $_POST[ '_badge_meta' ][ 'css_text' ] ) ) ? $_POST[ '_badge_meta' ][ 'css_text' ] : '';
                $badge_meta[ 'css_badge' ]                  = ( !empty( $_POST[ '_badge_meta' ][ 'css_badge' ] ) ) ? $_POST[ '_badge_meta' ][ 'css_badge' ] : 1;
                $badge_meta[ 'pos_top' ]                    = ( !empty( $_POST[ '_badge_meta' ][ 'pos_top' ] ) ) ? $_POST[ '_badge_meta' ][ 'pos_top' ] : 0;
                $badge_meta[ 'pos_bottom' ]                 = ( !empty( $_POST[ '_badge_meta' ][ 'pos_bottom' ] ) ) ? $_POST[ '_badge_meta' ][ 'pos_bottom' ] : 0;
                $badge_meta[ 'pos_left' ]                   = ( !empty( $_POST[ '_badge_meta' ][ 'pos_left' ] ) ) ? $_POST[ '_badge_meta' ][ 'pos_left' ] : 0;
                $badge_meta[ 'pos_right' ]                  = ( !empty( $_POST[ '_badge_meta' ][ 'pos_right' ] ) ) ? $_POST[ '_badge_meta' ][ 'pos_right' ] : 0;
                $badge_meta[ 'border_top_left_radius' ]     = ( !empty( $_POST[ '_badge_meta' ][ 'border_top_left_radius' ] ) ) ? $_POST[ '_badge_meta' ][ 'border_top_left_radius' ] : 0;
                $badge_meta[ 'border_top_right_radius' ]    = ( !empty( $_POST[ '_badge_meta' ][ 'border_top_right_radius' ] ) ) ? $_POST[ '_badge_meta' ][ 'border_top_right_radius' ] : 0;
                $badge_meta[ 'border_bottom_right_radius' ] = ( !empty( $_POST[ '_badge_meta' ][ 'border_bottom_right_radius' ] ) ) ? $_POST[ '_badge_meta' ][ 'border_bottom_right_radius' ] : 0;
                $badge_meta[ 'border_bottom_left_radius' ]  = ( !empty( $_POST[ '_badge_meta' ][ 'border_bottom_left_radius' ] ) ) ? $_POST[ '_badge_meta' ][ 'border_bottom_left_radius' ] : 0;
                $badge_meta[ 'padding_top' ]                = ( !empty( $_POST[ '_badge_meta' ][ 'padding_top' ] ) ) ? $_POST[ '_badge_meta' ][ 'padding_top' ] : 0;
                $badge_meta[ 'padding_bottom' ]             = ( !empty( $_POST[ '_badge_meta' ][ 'padding_bottom' ] ) ) ? $_POST[ '_badge_meta' ][ 'padding_bottom' ] : 0;
                $badge_meta[ 'padding_left' ]               = ( !empty( $_POST[ '_badge_meta' ][ 'padding_left' ] ) ) ? $_POST[ '_badge_meta' ][ 'padding_left' ] : 0;
                $badge_meta[ 'padding_right' ]              = ( !empty( $_POST[ '_badge_meta' ][ 'padding_right' ] ) ) ? $_POST[ '_badge_meta' ][ 'padding_right' ] : 0;
                $badge_meta[ 'font_size' ]                  = ( !empty( $_POST[ '_badge_meta' ][ 'font_size' ] ) ) ? $_POST[ '_badge_meta' ][ 'font_size' ] : 13;
                $badge_meta[ 'line_height' ]                = ( !empty( $_POST[ '_badge_meta' ][ 'line_height' ] ) ) ? $_POST[ '_badge_meta' ][ 'line_height' ] : -1;
                $badge_meta[ 'opacity' ]                    = ( !empty( $_POST[ '_badge_meta' ][ 'opacity' ] ) ) ? $_POST[ '_badge_meta' ][ 'opacity' ] : 100;


                //--wpml-------------
                yith_wcbm_wpml_register_string( 'yith-wcbm', sanitize_title( $badge_meta[ 'text' ] ), $badge_meta[ 'text' ] );
                yith_wcbm_wpml_register_string( 'yith-wcbm', sanitize_title( $badge_meta[ 'css_text' ] ), $badge_meta[ 'css_text' ] );
                //-------------------

                update_post_meta( $post_id, '_badge_meta', $badge_meta );
            }
        }

        public function get_advanced_badge_style() {
            if ( isset( $_POST[ 'id_badge_style' ], $_POST[ 'color' ], $_POST[ 'text_color' ] ) ) {
                $args = array(
                    'type'                => 'advanced',
                    'id_advanced_badge'   => null,
                    'id_badge_style'      => $_POST[ 'id_badge_style' ],
                    'advanced_bg_color'   => $_POST[ 'color' ],
                    'advanced_text_color' => $_POST[ 'text_color' ]
                );
                yith_wcbm_get_badge_style( $args );
                wp_die();
            }
        }

        public function get_css_badge_style() {
            if ( isset( $_POST[ 'id_badge_style' ], $_POST[ 'color' ], $_POST[ 'text_color' ] ) ) {
                $args = array(
                    'type'           => 'css',
                    'id_css_badge'   => null,
                    'id_badge_style' => $_POST[ 'id_badge_style' ],
                    'css_bg_color'   => $_POST[ 'color' ],
                    'css_text_color' => $_POST[ 'text_color' ]
                );
                yith_wcbm_get_badge_style( $args );
                wp_die();
            }
        }

        /**
         * Register plugins for activation tab
         *
         * @return void
         * @since 2.0.0
         */
        public function register_plugin_for_activation() {
            if ( !class_exists( 'YIT_Plugin_Licence' ) ) {
                require_once( YITH_WCAUTHNET_DIR . 'plugin-fw/lib/yit-plugin-licence.php' );
            }

            YIT_Plugin_Licence()->register( YITH_WCBM_INIT, YITH_WCBM_SECRET_KEY, YITH_WCBM_SLUG );
        }

        /**
         * Register plugins for update tab
         *
         * @return void
         * @since 2.0.0
         */
        public function register_plugin_for_updates() {
            if ( !class_exists( 'YIT_Plugin_Licence' ) ) {
                require_once( YITH_WCAUTHNET_DIR . 'plugin-fw/lib/yit-upgrade.php' );
            }

            YIT_Upgrade()->register( YITH_WCBM_SLUG, YITH_WCBM_INIT );
        }


        /** =========================================
         *             QUICK AND BULK EDIT
         *  =========================================
         */
        /**
         * Add column in product table list
         *
         * @access public
         * @since  1.0.0
         * @author Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public function add_columns( $columns ) {
            $date = isset( $columns[ 'date' ] ) ? $columns[ 'date' ] : '';
            unset( $columns[ 'date' ] );
            $columns[ 'yith_wcbm_badge' ] = _x( 'Badge', 'Admin:title of column in products table', 'yith-wcbm' );
            if ( !empty( $date ) ) {
                $columns[ 'date' ] = $date;
            }

            return $columns;
        }

        /**
         * Add content in custom column in product table list
         *
         * @access public
         * @since  1.0.0
         * @author Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public function custom_columns( $column, $post_id ) {
            if ( $column == 'yith_wcbm_badge' ) {
                $product_meta = get_post_meta( $post_id, '_yith_wcbm_product_meta', true );
                $badge_id     = ( !empty( $product_meta[ 'id_badge' ] ) ) ? $product_meta[ 'id_badge' ] : 0;
                $badge        = get_post( $badge_id );

                if ( !$badge || $badge->post_type != 'yith-wcbm-badge' ) {
                    echo '<span class="na">–</span>';

                    return;
                }

                $title       = $badge->post_title;
                $link        = get_edit_post_link( $badge_id );
                $html_return = "<a href='{$link}'>{$title}</a>";

                echo $html_return;
            }
        }

        /**
         * Add Bulk edit for badges assigned to a product
         *
         * @access public
         * @since  1.0.0
         * @author Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public function woocommerce_product_bulk_edit_end() {
            static $printNonce = true;
            if ( $printNonce ) {
                $printNonce = false;
                wp_nonce_field( YITH_WCBM_INIT, 'bulk_badge_edit_nonce' );
            }
            ?>
            <label>
                <span class="title"><?php esc_html_e( 'Select Badge', 'yith-wcbm' ); ?></span>
				<span class="input-text-wrap">
					<select name="yith_wcbm_bulk_badge_id">
                        <option value="" selected><?php _e( '— No Change —', 'woocommerce' ) ?></option>
                        <option value="none"><?php _e( 'None', 'yith-wcbm' ) ?></option>
                        <?php
                        $args   = ( array( 'posts_per_page' => -1, 'post_type' => 'yith-wcbm-badge', 'orderby' => 'title', 'order' => 'ASC', 'post_status' => 'publish' ) );
                        $badges = get_posts( $args );
                        foreach ( $badges as $badge ) {
                            $title = get_the_title( $badge->ID );
                            echo "<option value='{$badge->ID}'>{$title}</option>";
                        }
                        ?>
                    </select>
			</span>
            </label>
            <?php
        }

        /**
         * Save charts for bulk edit [AJAX]
         *
         * @param WC_Product $product
         *
         * @access public
         * @since  1.0.0
         * @author Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public function save_bulk_edit( $product ) {
            if ( isset( $_REQUEST[ 'yith_wcbm_bulk_badge_id' ] ) ) {
                $post_id  = $product->id;
                $badge_id = $_REQUEST[ 'yith_wcbm_bulk_badge_id' ];
                if ( $badge_id == 'none' ) {
                    delete_post_meta( $post_id, '_yith_wcbm_product_meta' );
                } else {
                    $bm_meta               = get_post_meta( $post_id, '_yith_wcbm_product_meta', true );
                    $bm_meta               = !empty( $bm_meta ) ? $bm_meta : array();
                    $bm_meta[ 'id_badge' ] = $badge_id;
                    update_post_meta( $post_id, '_yith_wcbm_product_meta', $bm_meta );
                }
            }
        }
    }
}

/**
 * Unique access to instance of YITH_WCBM_Admin_Premium class
 *
 * @return \YITH_WCBM_Admin_Premium
 * @since 1.0.0
 */
function YITH_WCBM_Admin_Premium() {
    return YITH_WCBM_Admin_Premium::get_instance();
}

?>
