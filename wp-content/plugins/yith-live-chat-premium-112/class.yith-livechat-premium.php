<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Main class
 *
 * @class   YITH_Livechat_Premium
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 */

if ( !class_exists( 'YITH_Livechat_Premium' ) ) {

    class YITH_Livechat_Premium extends YITH_Livechat {

        /**
         * @var string Yith Live Chat Offline Messages
         */
        protected $_offline_messages_page = 'ylc_offline_messages';

        /**
         * @var string Yith Live Chat Logs
         */
        protected $_chat_logs_page = 'ylc_chat_logs';

        /**
         * Returns single instance of the class
         *
         * @return \YITH_Livechat_Premium
         * @since 1.1.0
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
         * Initialize plugin and registers actions and filters to be used
         *
         * @since   1.0.0
         * @return  mixed
         * @author  Alberto Ruggiero
         */
        public function __construct() {

            parent::__construct();

            $this->includes_premium();

            add_filter( 'ylc_templates_premium', array( $this, 'get_premium_templates' ), 10, 2 );
            add_filter( 'ylc_js_premium', array( $this, 'load_livechat_js_premium' ) );
            add_filter( 'ylc_default_avatar', array( $this, 'encoded_default_avatar_url' ), 10, 2 );
            add_filter( 'ylc_vendor', array( $this, 'get_vendor_info' ) );
            add_filter( 'ylc_vendor_only', array( $this, 'set_vendor_only' ) );

            if ( !is_admin() || defined( 'DOING_AJAX' ) ) {

                add_action( 'init', array( &$this, 'show_chat_button' ) );
                add_action( 'wp_footer', array( &$this, 'custom_css' ) );
                add_action( 'wp_enqueue_scripts', array( $this, 'premium_scripts_frontend' ), 11 );

                add_filter( 'ylc_plugin_opts_premium', array( $this, 'get_frontend_premium_options' ) );
                add_filter( 'ylc_max_guests', array( $this, 'get_max_guests' ) );
                add_filter( 'ylc_send_transcript', array( $this, 'set_send_transcript' ) );
                add_filter( 'ylc_chat_evaluation', array( $this, 'set_chat_evaluation' ) );
                add_filter( 'ylc_busy_form', array( $this, 'set_busy_form' ) );
                add_filter( 'ylc_autoplay_opts', array( $this, 'get_autoplay_opts' ) );
                add_filter( 'ylc_company_avatar', array( $this, 'get_company_avatar' ) );


            }
            else {

                add_action( 'yit_panel_custom-email', array( $this, 'custom_email_template' ), 10, 3 );
                add_action( 'yit_panel_custom-colorpicker', array( $this, 'custom_colorpicker_template' ), 10, 3 );
                add_action( 'yit_panel_custom-number', array( $this, 'custom_number_template' ), 10, 3 );
                add_action( 'yit_panel_custom-upload', array( $this, 'custom_upload_template' ), 10, 3 );

                add_action( 'admin_menu', array( $this, 'add_console_submenu' ), 5 );
                add_action( 'admin_init', array( $this, 'update_operators' ) );
                add_action( 'admin_enqueue_scripts', array( $this, 'premium_admin_scripts' ) );

                add_filter( 'yith_wcmv_live_chat_caps', array( $this, 'add_vendor_capability' ) );
                add_filter( 'yith_wpv_vendor_menu_items', array( $this, 'activate_vendor' ) );
                add_filter( 'ylc_nickname', array( $this, 'get_nickname' ) );
                add_filter( 'ylc_avatar_type', array( $this, 'get_avatar_type' ) );
                add_filter( 'ylc_avatar_image', array( $this, 'get_avatar_image' ) );

                if ( current_user_can( 'answer_chat' ) ) {
                    add_action( 'show_user_profile', array( &$this, 'custom_operator_fields' ), 10 );
                    add_action( 'personal_options_update', array( &$this, 'save_custom_operator_fields' ) );
                }

                if ( current_user_can( 'edit_user' ) ) {

                    add_action( 'edit_user_profile', array( &$this, 'custom_operator_fields' ), 10 );
                    add_action( 'edit_user_profile_update', array( &$this, 'save_custom_operator_fields' ) );

                }

            }

            // register plugin to licence/update system
            add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
            add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

        }

        /**
         * Include required core files
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function includes_premium() {

            // Back-end includes
            if ( is_admin() ) {
                include_once( 'includes/admin/class-yith-custom-table.php' );
                include_once( 'templates/admin/ylc-offline-table.php' );
                include_once( 'templates/admin/ylc-chat-log-table.php' );
            }

            // Include core files
            include_once( 'includes/functions-ylc-ajax-premium.php' );
            include_once( 'includes/functions-ylc-email-premium.php' );
            include_once( 'includes/functions-ylc-commons-premium.php' );

        }

        /**
         * Load Live Chat premium scripts for frontend/console
         *
         * @since   1.0.0
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function load_livechat_js_premium() {

            include( YLC_DIR . 'assets/js/ylc-engine-premium' . $this->is_script_debug_active() . '.js' );

        }

        /**
         * Load Premium Templates for frontend/console
         *
         * @since   1.0.0
         *
         * @param   $value
         * @param   $context
         *
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function get_premium_templates( $value, $context ) {

            if ( $context == 'frontend' ) {

                $value = array(
                    'chat_offline_form_premium'   => file_get_contents( YLC_DIR . 'templates/chat-frontend/chat-offline-form-premium.php' ),
                    'chat_evaluation_premium'     => file_get_contents( YLC_DIR . 'templates/chat-frontend/chat-evaluation-premium.php' ),
                    'chat_transcript_premium'     => file_get_contents( YLC_DIR . 'templates/chat-frontend/chat-transcript-premium.php' ),
                    'chat_transcript_btn_premium' => file_get_contents( YLC_DIR . 'templates/chat-frontend/chat-transcript-btn-premium.php' ),
                );

            }
            elseif ( $context == 'console' ) {

                $value = array(
                    'console_user_tools_premium' => file_get_contents( YLC_DIR . 'templates/chat-backend/console-user-tools-premium.php' ),
                    'console_user_timer_premium' => file_get_contents( YLC_DIR . 'templates/chat-backend/console-user-timer-premium.php' ),
                );

            }

            return $value;

        }

        /**
         * Get vendor info (if is active YITH WooCommerce Multi Vendor premium)
         *
         * @since   1.1.0
         * @return  array|int
         * @author  Alberto Ruggiero
         */
        public function get_vendor_info() {

            $result = array(
                'vendor_id'   => 0,
                'vendor_name' => ''
            );

            $vendor = '';

            if ( defined( 'YITH_WPV_PREMIUM' ) ) {

                if ( !is_admin() ) {

                    if ( YITH_Vendors()->frontend->is_vendor_page() ) {
                        $vendor = yith_get_vendor( get_query_var( 'term' ) );
                    }
                    else {

                        global $post;

                        if ( $post && 'product' == $post->post_type ) {
                            $_product = is_singular( 'product' ) ? WC()->product_factory->get_product( absint( $post->ID ) ) : __return_null();
                            $vendor   = yith_get_vendor( $_product, 'product' );
                        }

                    }

                }
                else {

                    $vendor = yith_get_vendor( 'current', 'user' );

                }

                if ( $vendor ) {
                    $result['vendor_id']   = $vendor->id;
                    $result['vendor_name'] = ( $vendor->id != 0 ) ? $vendor->term->name : '';
                }

            }

            return $result;

        }

        /**
         * Encode default avatar URLs for Gravatar
         *
         * @since   1.0.0
         *
         * @param   $value
         * @param   $type
         *
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function encoded_default_avatar_url( $value, $type ) {

            return urlencode( YLC_ASSETS_URL . '/images/default-avatar-' . $type . '.png' );

        }

        /**
         * Get premium options defaults
         *
         * @since   1.1.0
         *
         * @param   $defaults
         *
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function ylc_get_defaults_premium( $defaults ) {

            $premium_defaults = array(
                'offline-mail-sender'          => '',
                'offline-mail-addresses'       => '',
                'offline-send-visitor'         => 'yes',
                'offline-message-body'         => __( 'Thanks for contacting us. We will answer as soon as possible.', 'yith-live-chat' ),
                'offline-busy'                 => 'no',
                'chat-evaluation'              => 'yes',
                'transcript-send'              => 'yes',
                'transcript-mail-sender'       => '',
                'transcript-message-body'      => __( 'Below you can find a copy of the chat conversation you have requested.', 'yith-live-chat' ),
                'transcript-send-admin'        => 'no',
                'transcript-send-admin-emails' => '',
                'header-button-color'          => '#009EDB',
                'chat-button-width'            => 260,
                'chat-conversation-width'      => 370,
                'form-width'                   => 260,
                'chat-position'                => 'right-bottom',
                'border-radius'                => 5,
                'chat-animation'               => 'bounceIn',
                'custom-css'                   => '',
                'operator-role'                => 'editor',
                'operator-avatar'              => '',
                'max-chat-users'               => 2,
                'only-vendor-chat'             => 'no',
                'hide-chat-offline'            => 'no'
            );

            return array_merge( $defaults, $premium_defaults );

        }

        /**
         * ADMIN FUNCTIONS
         */

        /**
         * Add YITH Live Chat to vendor admin panel (if is active YITH WooCommerce Multi Vendor premium)
         *
         * @since   1.1.0
         *
         * @param   $pages
         *
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function activate_vendor( $pages ) {

            $pages[] = $this->_console_page;
            $pages[] = $this->_offline_messages_page;
            $pages[] = $this->_chat_logs_page;

            return $pages;

        }

        /**
         * Add chat capability to vendor operators (if is active YITH WooCommerce Multi Vendor premium)
         *
         * @since   1.1.0
         * @return  array|int
         * @author  Alberto Ruggiero
         */
        public function add_vendor_capability() {
            return array( 'answer_chat' => true );
        }

        /**
         * Add submenu under YITH Live Chat console page
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function add_console_submenu() {

            $console_title = __( 'Chat console', 'yith-live-chat' );
            $offline_title = __( 'Offline messages', 'yith-live-chat' );
            $logs_title    = __( 'Chat logs', 'yith-live-chat' );

            if ( current_user_can( 'manage_options' ) ) {

                add_submenu_page( $this->_console_page, $console_title, $console_title, 'manage_options', $this->_console_page, array( $this, 'get_console_template' ) );
                add_submenu_page( $this->_console_page, $offline_title, $offline_title, 'manage_options', $this->_offline_messages_page, array( YLC_Offline_Messages(), 'output' ) );
                add_submenu_page( $this->_console_page, $logs_title, $logs_title, 'manage_options', $this->_chat_logs_page, array( YLC_Chat_Logs(), 'output' ) );

            }
            else {
                if ( current_user_can( 'answer_chat' ) ) {

                    add_submenu_page( $this->_console_page, $console_title, $console_title, 'answer_chat', $this->_console_page, array( $this, 'get_console_template' ) );
                    add_submenu_page( $this->_console_page, $offline_title, $offline_title, 'answer_chat', $this->_offline_messages_page, array( YLC_Offline_Messages(), 'output' ) );
                    add_submenu_page( $this->_console_page, $logs_title, $logs_title, 'answer_chat', $this->_chat_logs_page, array( YLC_Chat_Logs(), 'output' ) );

                }
            }

        }

        /**
         * Add styles and scripts for options panel
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function premium_admin_scripts() {

            switch ( ylc_get_current_page() ) {

                case $this->_offline_messages_page:
                case $this->_chat_logs_page:
                case $this->_panel_page:
                case 'profile.php':
                case 'user-edit.php':

                    //Load FontAwesome
                    $this->load_fontawesome();

                    wp_register_style( 'ylc-tiptip', YLC_ASSETS_URL . '/css/tipTip.css' );
                    wp_enqueue_style( 'ylc-tiptip' );

                    wp_register_style( 'ylc-styles-premium', YLC_ASSETS_URL . '/css/ylc-styles-premium.css' );
                    wp_enqueue_style( 'ylc-styles-premium' );

                    wp_enqueue_style( 'wp-color-picker' );

                    wp_register_script( 'ylc-tiptip', YLC_ASSETS_URL . '/js/jquery.tipTip' . $this->is_script_debug_active() . '.js', array( 'jquery' ) );
                    wp_enqueue_script( 'ylc-tiptip' );

                    wp_register_script( 'ylc-admin-premium', YLC_ASSETS_URL . '/js/ylc-admin-premium' . $this->is_script_debug_active() . '.js', array( 'jquery', 'wp-color-picker', 'ylc-tiptip' ), '1.0.0', true );
                    wp_enqueue_script( 'ylc-admin-premium' );

                    break;

                case $this->_console_page:

                    // Console stylesheet
                    wp_register_style( 'ylc-console-premium', YLC_ASSETS_URL . '/css/ylc-console-premium.css' );
                    wp_enqueue_style( 'ylc-console-premium' );

                    break;

            }

        }

        /**
         * Get user nickname
         *
         * @since   1.0.0
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function get_nickname() {

            return get_the_author_meta( 'ylc_operator_nickname', $this->user->ID );

        }

        /**
         * Add custom operator fields
         *
         * @since   1.0.0
         *
         * @param   $user
         *
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function custom_operator_fields( $user ) {

            include( YLC_TEMPLATE_PATH . '/admin/custom-operator-fields.php' );

        }

        /**
         * Save custom operator fields
         *
         * @since   1.0.0
         *
         * @param   $user_id
         *
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function save_custom_operator_fields( $user_id ) {

            if ( empty( $_POST['ylc_operator_nickname'] ) ) {

                $op_name = get_the_author_meta( 'nickname', $user_id );

            }
            else {
                $op_name = $_POST['ylc_operator_nickname'];
            }

            // Update user meta now
            update_user_meta( $user_id, 'ylc_operator_nickname', $op_name );
            update_user_meta( $user_id, 'ylc_operator_avatar_type', $_POST['ylc_operator_avatar_type'] );
            update_user_meta( $user_id, 'ylc_operator_avatar', $_POST['ylc_operator_avatar'] );

        }

        /**
         * Load Custom Email Template
         *
         * @since   1.0.0
         *
         * @param   $option
         * @param   $db_value
         * @param   $custom_attributes
         *
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function custom_email_template( $option, $db_value, $custom_attributes ) {

            include( YLC_TEMPLATE_PATH . '/admin/custom-email.php' );

        }

        /**
         * Load Custom Colorpicker Template
         *
         * @since   1.0.0
         *
         * @param   $option
         * @param   $db_value
         * @param   $custom_attributes
         *
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function custom_colorpicker_template( $option, $db_value, $custom_attributes ) {

            include( YLC_TEMPLATE_PATH . '/admin/custom-colorpicker.php' );

        }

        /**
         * Load Custom Number Template
         *
         * @since   1.0.0
         *
         * @param   $option
         * @param   $db_value
         * @param   $custom_attributes
         *
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function custom_number_template( $option, $db_value, $custom_attributes ) {

            include( YLC_TEMPLATE_PATH . '/admin/custom-number.php' );

        }

        /**
         * Load Custom Upload Template
         *
         * @since   1.0.0
         *
         * @param   $option
         * @param   $db_value
         * @param   $custom_attributes
         *
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function custom_upload_template( $option, $db_value, $custom_attributes ) {

            include( YLC_TEMPLATE_PATH . '/admin/custom-upload.php' );

        }

        /**
         * Updates operator roles
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function update_operators() {

            if ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) {

                $this->ylc_operator_role( $this->options['operator-role'] );

            }

        }

        /**
         * Get user avatar image
         *
         * @since   1.0.0
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function get_avatar_image() {

            $avatar_type = get_the_author_meta( 'ylc_operator_avatar_type', $this->user->ID );

            if ( $avatar_type == 'default' ) {

                $company_avatar = $this->get_company_avatar();

                if ( $company_avatar != '' ) {
                    return $company_avatar;

                }
                else {

                    return '';
                }

            }
            else {

                return get_the_author_meta( 'ylc_operator_avatar', $this->user->ID );

            }

        }

        /**
         * Get user avatar type
         *
         * @since   1.0.0
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function get_avatar_type() {

            $avatar_type = get_the_author_meta( 'ylc_operator_avatar_type', $this->user->ID );

            if ( $avatar_type == 'default' ) {

                $company_avatar = $this->get_company_avatar();

                if ( $company_avatar != '' ) {
                    $avatar_type = 'image';
                }

            }

            return $avatar_type;

        }

        /**
         * Get company avatar
         *
         * @since   1.0.0
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function get_company_avatar() {

            $op_avatar = esc_html( $this->options['operator-avatar'] );

            if ( $op_avatar != '' ) {

                return $op_avatar;

            }
            else {

                return '';

            }

        }

        /**
         * FRONTEND FUNCTIONS
         */

        /**
         * Add styles for chat frontend
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function premium_scripts_frontend() {

            if ( $this->show_chat ) {

                wp_register_style( 'ylc-frontend-premium', YLC_ASSETS_URL . '/css/ylc-frontend-premium.css' );
                wp_enqueue_style( 'ylc-frontend-premium' );

            }

        }

        /**
         * Get Premium Options
         *
         * @since   1.0.0
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function get_frontend_premium_options() {

            return array(
                'bg_color'       => $this->options['header-button-color'],
                'x_pos'          => $this->get_chat_position_x( $this->options['chat-position'] ),
                'y_pos'          => $this->get_chat_position_y( $this->options['chat-position'] ),
                'border_radius'  => $this->set_round_corners( $this->options['border-radius'], $this->options['chat-position'] ),
                'popup_width'    => $this->options['chat-conversation-width'],
                'btn_width'      => $this->options['chat-button-width'],
                'form_width'     => $this->options['form-width'],
                'animation_type' => $this->options['chat-animation'],
                'autoplay'       => false,//(int) $this->set_chat_autoplay( $this->options['autoplay-enabled'] ),
                'autoplay_delay' => 0, //$this->set_autoplay_delay( $this->options['autoplay-delay'] )
            );
        }

        /**
         * Get horizontal chat position
         *
         * @since   1.0.0
         *
         * @param   $pos
         *
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function get_chat_position_x( $pos ) {

            return substr( $pos, 0, strpos( $pos, '-' ) );

        }

        /**
         * Get vertical chat position
         *
         * @since   1.0.0
         *
         * @param   $pos
         *
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function get_chat_position_y( $pos ) {

            return substr( $pos, strpos( $pos, '-' ) + 1, strlen( $pos ) );

        }

        /**
         * Set border radius
         *
         * @since   1.0.0
         *
         * @param   $pos
         * @param   $radius
         *
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function set_round_corners( $radius, $pos ) {

            if ( $radius != 0 ) {

                if ( substr( $pos, strpos( $pos, '-' ) + 1, strlen( $pos ) ) == 'bottom' ) {

                    return $radius . 'px ' . $radius . 'px 0 0';

                }
                else {

                    return '0 0 ' . $radius . 'px ' . $radius . 'px';

                }

            }
            else {

                return 0;

            }

        }

        /**
         * Set chat autoplay
         *
         * @since   1.0.0
         *
         * @param   $autoplay
         *
         * @return  bool
         * @author  Alberto Ruggiero
         */
        public function set_chat_autoplay( $autoplay ) {

            if ( $autoplay == 'yes' ) {

                return true;

            }
            else {

                return false;

            }

        }

        /**
         * Set chat autoplay delay
         *
         * @since   1.0.0
         *
         * @param   $delay
         *
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function set_autoplay_delay( $delay ) {

            return $delay * 1000;

        }

        /**
         * Get autoplay options
         *
         * @since   1.0.0
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function get_autoplay_opts() {

            return array(
                'company_name' => '', //esc_html( $this->options['autoplay-operator'] ),
                'auto_msg'     => '', //$this->options['autoplay-welcome'],
            );

        }

        /**
         * Add Custom CSS
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function custom_css() {

            if ( $this->show_chat ) {

                $custom_css = $this->options['custom-css'];

                if ( $custom_css != '' ) :

                    ?>

                    <style type="text/css">
                        <?php
                        echo stripslashes( $custom_css );
                        ?>
                    </style>

                <?php

                endif;

            }
        }

        /**
         * Get max chat users
         *
         * @since   1.0.0
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function get_max_guests() {

            return $this->options['max-chat-users'];

        }

        /**
         * Set send chat transcripts on or off
         *
         * @since   1.0.0
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function set_send_transcript() {

            $transcript_send = $this->options['transcript-send'];

            if ( $transcript_send == 'yes' ) {

                return true;

            }
            else {

                return false;

            }

        }

        /**
         * Set Chat evaluation on or off
         *
         * @since   1.0.0
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function set_chat_evaluation() {
            $chat_evaluation = $this->options['chat-evaluation'];

            if ( $chat_evaluation == 'yes' ) {

                return true;

            }
            else {

                return false;

            }
        }

        /**
         * Set offline form when busy
         *
         * @since   1.0.0
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function set_busy_form() {
            $busy_form = $this->options['offline-busy'];

            if ( $busy_form == 'yes' ) {

                return true;

            }
            else {

                return false;

            }
        }

        /**
         * Set chat for vendor sections only (if is active YITH WooCommerce Multi Vendor premium)
         *
         * @since   1.1.2
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function set_vendor_only() {

            $vendor_only = $this->options['only-vendor-chat'];

            if ( $vendor_only == 'yes' ) {

                return true;

            }
            else {

                return false;

            }

        }

        /**
         * Checks if chat can be showed
         *
         * @since   1.1.2
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function show_chat_button() {

            $value = true;

            if ( defined( 'YITH_WPV_PREMIUM' ) && $this->options['only-vendor-chat'] == 'yes' ) {

                $vendor_info = $this->get_vendor_info();

                if ( $vendor_info['vendor_id'] == 0 ) {

                    $value = false;

                }

            }

            if ( $this->options['hide-chat-offline'] == 'yes' ) {

                $token      = YITH_Live_Chat()->user_auth();
                $request    = wp_remote_get( 'https://' . $this->options['firebase-appurl'] . '.firebaseio.com/chat_users.json?auth=' . $token );
                $users      = json_decode( wp_remote_retrieve_body( $request ) );
                $online_ops = 0;

                if ( $users ) {

                    foreach ( $users as $user ) {

                        $valid_op = false;

                        if ( isset( $user->user_type ) && $user->user_type == 'operator' ) {

                            if ( defined( 'YITH_WPV_PREMIUM' ) && $this->options['only-vendor-chat'] == 'yes' ) {

                                $vendor_info = $this->get_vendor_info();

                                if ( $vendor_info['vendor_id'] == $user->vendor_id && $user->status == 'online' ) {

                                    $valid_op = true;

                                }

                            }
                            else {

                                if ( $user->status == 'online' ) {

                                    $valid_op = true;

                                }

                            }


                            if ( $valid_op ) {

                                $online_ops ++;

                            }

                        }


                    }

                }

                if ( $online_ops === 0 ) {

                    $value = false;

                }

            }

            $this->show_chat = $value;

        }

        /**
         * YITH FRAMEWORK
         */

        /**
         * Register plugins for activation tab
         *
         * @since   1.0.0
         * @return  void
         * @author  Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function register_plugin_for_activation() {
            if ( !class_exists( 'YIT_Plugin_Licence' ) ) {
                require_once 'plugin-fw/licence/lib/yit-licence.php';
                require_once 'plugin-fw/licence/lib/yit-plugin-licence.php';
            }
            YIT_Plugin_Licence()->register( YLC_INIT, YLC_SECRET_KEY, YLC_SLUG );
        }

        /**
         * Register plugins for update tab
         *
         * @since   2.0.0
         * @return  void
         * @author  Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function register_plugin_for_updates() {
            if ( !class_exists( 'YIT_Upgrade' ) ) {
                require_once( 'plugin-fw/lib/yit-upgrade.php' );
            }
            YIT_Upgrade()->register( YLC_SLUG, YLC_INIT );
        }

    }

}