<?php
if( !defined( 'ABSPATH' ) )
    exit;

if( !class_exists( 'YITH_WC_Donations_Premium' ) ){

    class YITH_WC_Donations_Premium extends  YITH_WC_Donations
    {
        protected static $_instance;
        protected $_email_types = array();

        public function __construct() {
            parent::__construct();

            $this->_include();

            $this->_email_types = array(
                'donation' => array(
                    'class' => 'YITH_WC_Donations_Email',
                    'file' => 'class.ywcds-donations-email.php',
                    'hide' => false,
                )
            );

            add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
            add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );
            //add premium tabs
            add_filter( 'ywcds_add_premium_tab', array( $this, 'add_premium_tab' ) );
            add_action( 'ywcds_export_tab',  array( YWCDS_Order_Donation_Table(),'output' ) );
            add_action( 'ywcds_product_donation', array(YWCDS_Product_Donation_Table(),'output' ) );
            //add premium labels
            add_filter( 'ywcds_init_messages', array( $this, 'init_premium_messages' ) );
            //print custom typographu field in plugin option
            add_action( 'woocommerce_admin_field_typography', 'YWCDS_Typography::output' );
            //enqueue style and scripts
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_premium_style_script' ) );
            //set current available payement for donation
            add_filter( 'woocommerce_available_payment_gateways', array( $this, 'set_donation_gateways' ) );
            add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'validate_add_to_cart' ),10, 5 );
            add_filter( 'woocommerce_update_cart_validation', array( $this, 'update_cart_validation' ), 10, 4 );

            if ( get_option('ywcds_button_style_select') == 'custom' )
                add_action( 'wp_print_scripts', array($this, 'get_custom_button_style' ) );

            if ( get_option('ywcds_show_donation_in_cart') == 'yes' )
                 add_action( 'woocommerce_before_cart_totals', array($this, 'add_form_donation_in_cart' ) );

            //Add button Donation in loop
            add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'add_donation_in_shop_loop' ) , 10 , 2 );
            add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'add_url_donation_in_shop_loop' ), 10, 2 );
            add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'disable_ajax_add_to_cart_in_loop' ), 20, 2 );

            // add a custom post meta at orders that have a donation
            add_action( 'woocommerce_checkout_order_processed', array( $this, 'mark_order_as_donation' ) );
            add_action( 'woocommerce_process_shop_order_meta', array( $this, 'mark_order_as_donation' ) );
            add_action( 'woocommerce_api_create_order', array( $this, 'mark_order_as_donation' ) );

            //send email when a order with donation is completed
            add_action( 'woocommerce_order_status_completed', array( $this, 'prepare_email' ) );
            add_filter( 'woocommerce_email_classes', array( $this, 'ywcds_custom_email' ) );
            add_filter( 'woocommerce_get_sections_email', array( $this, 'ywcds_hide_sections' ) );

            add_action( 'wp_loaded', array( $this, 'remove_items_from_cart' ), 15 );

            //custom action for add custom email template
            add_action( 'ywcds_email_header', array( $this, 'ywcds_email_header' ) );
            add_action( 'ywcds_email_footer', array( $this, 'ywcds_email_footer' ) );


            //add quick button for add shortcode
            add_action( 'media_buttons_context', array( &$this, 'ywcds_media_buttons_context' ) );
            add_action( 'admin_print_footer_scripts', array( &$this, 'ywcds_add_quicktags' ) );
            add_action( 'admin_init', array( $this, 'ywcds_add_shortcodes_button' ) );



        }

        /** return single instance of class
         * @author YIThemes
         * @since 1.0.0
         * @return YITH_WC_Donations
         */
        public static function get_instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /** add tabs for premium version
         *@author YIThemes
         *@since 1.0.0
         */
        public function add_premium_tab( $tabs ) {

            unset($tabs['premium-landing']);

            $tabs['product-donation'] = __('Products & Donations', 'ywcds');
            $tabs['messages'] = __('Labels', 'ywcds');
            $tabs['mail'] = __('Email', 'ywcds');
            $tabs['export'] = __('Report', 'ywcds');

            return $tabs;
        }

        /* Register plugins for activation tab
        *
        * @return void
        * @since    1.0.0
        * @author   Andrea Grillo <andrea.grillo@yithemes.com>
        */
        public function register_plugin_for_activation() {
            if ( !class_exists( 'YIT_Plugin_Licence') ) {
                require_once  YWCDS_DIR.'plugin-fw/licence/lib/yit-licence.php';
                require_once YWCDS_DIR.'plugin-fw/licence/lib/yit-plugin-licence.php';
            }
            YIT_Plugin_Licence()->register( YWCDS_INIT, YWCDS_SECRET_KEY, YWCDS_SLUG );
        }

        /**
         * Register plugins for update tab
         *
         * @return void
         * @since    1.0.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function register_plugin_for_updates() {
            if (!class_exists('YIT_Upgrade')) {
                require_once(YWCDS_DIR.'plugin-fw/lib/yit-upgrade.php');
            }
            YIT_Upgrade()->register( YWCDS_SLUG, YWCDS_INIT );
        }

        /**include custom field for plugin option
         * @author YIThemes
         * @since 1.0.0
         */
        private function _include() {

            include_once( YWCDS_TEMPLATE_PATH . 'admin/typography.php' );
            include_once( YWCDS_TEMPLATE_PATH . 'admin/product-donation-table.php' );
            include_once( YWCDS_TEMPLATE_PATH . 'admin/order-donation-table.php' );
        }

        /**include premium style and script admin
         * @author YIThemes
         * @since 1.0.0
         */
        public function enqueue_premium_style_script() {

            wp_register_script('woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin' . $this->_suffix . '.js', array('jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip'));
            wp_register_script('jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip' . $this->_suffix . '.js', array('jquery'), false, true);
            wp_enqueue_script('woocommerce_admin');
            wp_enqueue_script('ywcds_premium_admin', YWCDS_ASSETS_URL . 'js/ywcds_premium_admin' . $this->_suffix . '.js', array('jquery'), YWCDS_VERSION, true);
            wp_enqueue_style('ywcds_premium_admin_style', YWCDS_ASSETS_URL . 'css/ywcds_premium_admin.css', array(), YWCDS_VERSION);

        }

        /**show donation form in cart
         * @author YIThemes
         * @since 1.0.0
         *
         */
        public function add_form_donation_in_cart() {

            $args = array(
                'message_for_donation' => get_option( 'ywcds_message_for_donation' ),
                'button_class' => 'ywcds_add_donation_product button alt',
                'product_id' => $this->_donation_id,
                'button_text' => $this->_messages['text_button'],
            );
            echo yith_wcds_get_template('add-donation-form-widget.php', $args, true);
        }

        /** add premium label for frontend
         * @author YIThemes
         * @since 1.0.0
         * @use ywcds_init_messages
         * @param $messages
         * @return array
         */
        public function init_premium_messages($messages) {
            $messages = array(
                'no_number' => get_option('ywcds_message_invalid_donation'),
                'empty'     => get_option('ywcds_message_empty_donation'),
                'success'   => get_option('ywcds_message_right_donation'),
                'min_don'   => str_replace('{min_donation}', wc_price( get_option('ywcds_min_donation') ),get_option('ywcds_message_min_donation') ),
                'max_don'   => str_replace('{max_donation}', wc_price( get_option('ywcds_max_donation') ), get_option('ywcds_message_max_donation') ),
                'text_button'   => get_option('ywcds_button_text'),
                'obligatory'    => get_option('ywcds_message_obligatory_donation'),
                'negative'      =>  get_option( 'ywcds_message_negative_donation')
            );

            return $messages;
        }

        /**print from donation, in single page product/s
         *
         * method overidden
         * @author YIThemes
         * @since 1.0.0
         */
        public function add_form_donation() {
            global $product;

            $is_associate = get_post_meta( $product->id, '_ywcds_donation_associate', true ) == 'yes';
            $is_obligatory = get_post_meta( $product->id, '_ywcds_donation_obligatory', true ) == 'yes';

            if ( $is_associate ) {

                $args = array(
                    'message_for_donation' => get_option( 'ywcds_message_for_donation' ),
                    'product_id' => $product->id,
                    'is_obligatory' => $is_obligatory ? 'true' : 'false',
                    'min_don'       =>  get_option( 'ywcds_min_donation' ),
                    'max_don'       =>  get_option( 'ywcds_max_donation' ),
                );
                echo yith_wcds_get_template( 'add-donation-form-single-product.php', $args, true );
            }
        }


        /**
         * overridden, mange add to cart for donation
         * @author YIThemes
         * @since 1.0.0
         */
        public function add( $product_id, $variation_id=-1, $quantity=1, $amount )
        {
            if ( !empty( $amount ) ) {

                if ( !is_numeric( $amount ) )
                    return 'no_number';

                $amount = floatval( $amount );

                if ( $amount != null && $amount > 0) {

                    if ( $amount < get_option('ywcds_min_donation') )
                        return 'min_don';

                    elseif ( $amount > get_option('ywcds_max_donation') )
                        return 'max_don';

                    else {
                        $cart_item_data = array(
                            'ywcds_amount' => $amount,
                            'ywcds_product_id'  =>  $product_id != $this->_donation_id ?   $product_id  :   -1 ,
                            'ywcds_variation_id'    =>  $variation_id,
                            'ywcds_data_added'      =>  date("Y-m-d H:i:s"),
                            'ywcds_quantity'        =>  $quantity
                        );

                        WC()->cart->add_to_cart( $this->_donation_id, 1, '', array(), $cart_item_data );

                        if( $product_id != $this->_donation_id )
                            wc_add_notice( $this->get_message('success') );

                        return 'true';
                    }
                }
                else
                    return 'negative';
            } else
                return 'empty';
        }

        /**overridden, manage add donation in single product page
         * @author YIThemes
         * @since 1.0.0
         *
         */
        public function add_donation_single_product(){

            if( isset( $_REQUEST['amount_single_product'] ) && $_REQUEST['amount_single_product']!='' && isset( $_REQUEST['add-to-cart'] ) ){

                $product_id     =   $_REQUEST['add-to-cart'];
                $variation_id   =   isset( $_REQUEST['variation_id'] ) ?    $_REQUEST['variation_id']   : '';
                $quantity       =   isset( $_REQUEST['quantity'] )? $_REQUEST['quantity']  : 1;
                $amount         =   $_REQUEST['amount_single_product'];
                $res    =   $this->add( $product_id, $variation_id,$quantity, $amount );
            }
        }


        /**call ajax for add a donation in cart
         * this method is overridden
         * @author YIThemes
         * @since 1.0.0
         */
        public function ywcds_add_donation_ajax() {

            if ( isset( $_GET['add_donation_to_cart'] ) ) {
                $product_id = $_GET['add_donation_to_cart'];
                $amount = $_GET['ywcds_amount'];
                $result = $this->add( $product_id,'',1, $amount );
                $message = '';

                switch ( $result ) {

                    case 'no_number':
                        $message = $this->_messages['no_number'];
                        break;

                    case 'empty':
                        $message = $this->_messages['empty'];
                        break;

                    case 'negative':
                        $message    =   $this->_messages['negative'];
                        break;

                    case 'min_don'  :
                        $message =  $this->_messages['min_don'];
                        break;
                    case 'max_don'  :
                        $message =  $this->_messages['max_don'];
                        break;

                    default :
                        $message = sprintf('<a href="%s" class="button wc-forward">%s</a> %s', wc_get_page_permalink('cart'), __('View Cart', 'woocommerce'), $this->_messages['success']);
                        break;
                }

                if ( $result == 'true' )
                    WC_AJAX::get_refreshed_fragments();
                else
                    wp_send_json(
                        array(
                            'result' => $result,
                            'message' => $message
                        )
                    );
            }
        }

        /**
         * print the button style in frontend
         * @author YIThemes
         * @since 1.0.0
         */
        public function get_custom_button_style(){

            $button_typ = get_option('ywcds_button_typography');

            $button_text_size = $button_typ['size'] . $button_typ['unit'];
            $button_text_transf = $button_typ['transform'];
            $button_text_style = '';
            $button_text_weight = '';

            switch ($button_typ['style']) {

                case 'bold' :
                    $button_text_style = 'normal';
                    $button_text_weight = '700';
                    break;

                case 'extra-bold' :
                    $button_text_style = 'normal';
                    $button_text_weight = '800';
                    break;

                case 'italic' :
                    $button_text_style = 'italic';
                    $button_text_weight = 'normal';
                    break;

                case 'bold-italic' :
                    $button_text_style = 'italic';
                    $button_text_weight = '700';
                    break;
                case 'regular' :
                case 'normal' :
                    $button_text_style = 'normal';
                    $button_text_weight = '400';
                    break;

                default:
                    if (is_numeric($button_typ['style'])) {
                        $button_text_style = 'normal';
                        $button_text_weight = $button_typ['style'];
                    } else {
                        $button_text_style = 'italic';
                        $button_text_weight = str_replace('italic', '', $button_typ['style']);
                    }
                    break;
            }

            $button_text_color = get_option('ywcds_text_color');
            $button_text_color_hov = get_option('ywcds_text_hov_color');

            $button_bg_color = get_option('ywcds_bg_color');
            $button_bg_color_hov = get_option('ywcds_bg_hov_color');

            ?>
            <style type="text/css">


                .ywcds_form_container .ywcds_button_field .ywcds_submit_widget, .woocommerce a.button.product_type_donation {

                    color: <?php echo $button_text_color;?> !important;
                    font-size: <?php echo $button_text_size;?> !important;
                    font-style: <?php echo $button_text_style;?> !important;
                    font-weight: <?php echo $button_text_weight;?> !important;
                    text-transform: <?php echo $button_text_transf;?> !important;
                    background-color: <?php echo $button_bg_color;?> !important;
                }

                .ywcds_form_container .ywcds_button_field .ywcds_submit_widget:hover, .woocommerce a.button.product_type_donation:hover {

                    color: <?php echo $button_text_color_hov;?> !important;
                    background-color: <?php echo $button_bg_color_hov;?> !important;
                }
            </style>

        <?php
        }

        /**set gateways in donation orders
         * @author YIThemes
         * @since 1.0.0
         * @param $gateways
         * @return mixed
         */
        public function set_donation_gateways( $gateways ){

            if ( $this->is_donation_in_cart() ) {

                $donation_payments = get_option('ywcds_select_gateway');

                if ( !empty( $donation_payments ) ) {

                    foreach ( $gateways as $key => $gateway) {
                        if ( !in_array( $key, $donation_payments ) ) {
                            unset( $gateways[$key] );
                        }
                    }
                }
            }
            return $gateways;
        }

        /** check is a donation is in cart
         * @author YIThemes
         * @since 1.0.0
         * @return boolean
         */
        public function is_donation_in_cart(){

            $cart = WC()->cart;

            if ( sizeof( $cart->get_cart() ) > 0) {

                foreach( $cart->get_cart() as $cart_item_key => $values ) {
                    $product = $values['data'];
                    if ( $this->_donation_id == $product->id )
                        return true;
                }
                return false;
            }
            return false;
        }

        /**check is custom query, ?add-to-cart= is valid
         * @author YIThemes
         * @return boolean
         * @since 1.0.0
         * @use woocommerce_add_to_cart_validation
         */
        public function validate_add_to_cart( $is_valid, $product_id, $quantity, $variation_id='', $variations='' ){

            $min =   get_option( 'ywcds_min_donation' );
            $max =   get_option( 'ywcds_max_donation' );

            //if product is a donation
            if( $product_id == $this->_donation_id ){

                if( isset( $_REQUEST['add_donation_to_cart'] ) && !isset( $_REQUEST['add-to-cart'] ) ) {

                    if ( isset($_REQUEST['ywcds_amount']) && !empty($_REQUEST['ywcds_amount'])) {

                        $amount = $_REQUEST['ywcds_amount'];
                        if (!is_numeric($amount)) {
                            wc_add_notice($this->get_message('no_number'), 'error');
                            return false;
                        } elseif ($amount < $min) {
                            wc_add_notice($this->get_message('min_don'), 'error');
                            return false;
                        } elseif ($amount > $max) {
                            wc_add_notice($this->get_message('max_don'), 'error');
                            return false;
                        } else return true;
                    }
                }
                else{
                    wc_add_notice( __('Invalid Add to cart','ywcds' ), 'error');
                    return false;
                }
            }
            //if is associate product
            else {
                $is_associate  = get_post_meta( $product_id, '_ywcds_donation_associate', true ) == 'yes';
                $is_obligatory = get_post_meta( $product_id, '_ywcds_donation_obligatory', true ) == 'yes';

                if( !$is_associate )
                    return $is_valid;

                if( isset( $_REQUEST['amount_single_product'] ) && !empty( $_REQUEST['amount_single_product'] ) ){

                    $amount =   $_REQUEST['amount_single_product'];
                    if( !is_numeric( $amount ) ){
                        wc_add_notice( $this->get_message('no_number'), 'error' );
                        return false;
                    }
                    elseif( $amount < $min ){
                        wc_add_notice( $this->get_message('min_don'), 'error' );
                        return false;
                    }
                    elseif( $amount> $max ){
                        wc_add_notice( $this->get_message('max_don'), 'error' );
                        return false;
                    }
                    else return true;
                }
                elseif( $is_obligatory ){

                    wc_add_notice( $this->get_message('obligatory'), 'error' );
                    return false;

                }
                else
                    return true;
            }
        }

        /**check is the update is valid
         * @author YIThemes
         * @since 1.0.0
         * @param $is_valid
         * @param $cart_item_key
         * @param $values
         * @param $quantity
         * @return bool
         */
        public function update_cart_validation( $is_valid, $cart_item_key, $values, $quantity ){

            if( $values['product_id']== $this->_donation_id && $values['ywcds_product_id']!=-1 ){

                wc_add_notice( __('You can\'t update the cart','ywcds'), 'error');
                return false;
            }
            else{
                $is_associate   =   get_post_meta( $values['product_id'], '_ywcds_donation_associate', true )   ==  'yes';
                $is_obligatory  =   get_post_meta( $values['product_id'], '_ywcds_donation_obligatory', true )  ==  'yes';

                if( $is_associate && $is_obligatory ){

                    wc_add_notice( __('You can\'t update the cart','ywcds'), 'error');
                    return false;
                }
                else
                    return $is_valid;
            }

            return $is_valid;
        }

        /**check if order has donation and set custom post meta
         * @param $post_id
         * @param array $post
         */
        public function mark_order_as_donation( $post_id, $post = array() )
        {

            if ((!is_admin() || ( defined('DOING_AJAX') && DOING_AJAX ) ) || ('shop_order' == $post->post_type) && ($post->post_status != 'auto-draft')) {

                $post_id = is_a( $post_id, 'WC_Order') ? $post_id->id : $post_id;
                $order = wc_get_order($post_id);

                foreach ($order->get_items() as $item) {
                    if ( $item['product_id'] == $this->_donation_id ) {

                        update_post_meta(   $post_id, '_ywcds_order_has_donation', 'true');

                        break;
                    }
                }
            }
        }

        /**
         * Add the YITH_WC_Donations_Email class to WooCommerce mail classes
         *
         * @since   1.0.0
         * @param   $email_classes
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function ywcds_custom_email($email_classes)
        {

            foreach ($this->_email_types as $type => $email_type) {

                $email_classes[$email_type['class']] = include( YWCDS_INC . "/emails/{$email_type['file']}" );
            }

            return $email_classes;
        }

        /**
         * Hides custom email settings from WooCommerce panel
         *
         * @since   1.0.0
         * @param   $sections
         * @return  array
         * @author  Andrea Grillo
         */
        public function ywcds_hide_sections($sections){
            foreach ($this->_email_types as $type => $email_type) {
                $class_name = strtolower($email_type['class']);
                if (isset($sections[$class_name]) && $email_type['hide'] == true) {
                    unset($sections[$class_name]);
                }
            }

            return $sections;
        }

        /**
         * Get the email header.
         *
         * @since   1.0.0
         * @param   $email_heading
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function ywcds_email_header($email_heading)
        {
            switch (get_option('ywcds_mail_template')) {
                case 'ywcds_template':
                    wc_get_template('emails/donations-template/email-header.php', array('email_heading' => $email_heading), YWCDS_TEMPLATE_PATH, YWCDS_TEMPLATE_PATH);
                    break;
                default:
                    wc_get_template('emails/email-header.php', array('email_heading' => $email_heading));
            }
        }

        /**
         * Get the email footer.
         *
         * @since   1.0.0
         * @param   $unsubscribe
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function ywcds_email_footer()
        {
            switch (get_option('ywcds_mail_template')) {
                case 'ywcds_template':
                    wc_get_template('emails/donations-template/email-footer.php', array(), YWCDS_TEMPLATE_PATH, YWCDS_TEMPLATE_PATH);
                    break;

                default:
                    wc_get_template('emails/email-footer.php');
            }
        }

        /** Send email for order donations
         * @author YIThemes
         * @since 1.0.0
         * @param $order_id
         */
        public function prepare_email($order_id) {

            $is_order_donation = get_post_meta ($order_id, '_ywcds_order_has_donation', true );
            if ( $is_order_donation ) {

                $donation_list = ywcds_get_donations_item( $order_id );
                $wc_email = WC_Emails::instance();
                $email = $wc_email->emails['YITH_WC_Donations_Email'];
                $email->trigger( $order_id, $donation_list );

            }
        }



        /**
         *Add shortcode button to TinyMCE editor, adding filter on mce_external_plugins
         * @author YIThemes
         * @since 1.0.0
         * @use admin_init
         *
         */
        public function ywcds_add_shortcodes_button(){
            if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
                return;
            }
            if (get_user_option('rich_editing') == 'true') {
                add_filter('mce_external_plugins', array(&$this, 'ywcds_add_shortcodes_tinymce_plugin'));
                add_filter('mce_buttons', array(&$this, 'ywcds_register_shortcodes_button'));

            }
        }

        /**
         * Add a script to TinyMCE script list
         *
         * @since   1.0.0
         *
         * @param   $plugin_array
         *
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function ywcds_add_shortcodes_tinymce_plugin($plugin_array)
        {

            $plugin_array['ywcds_shortcode'] = YWCDS_ASSETS_URL . 'js/ywcds-tinymce' . $this->_suffix . '.js';

            return $plugin_array;
        }

        /**
         * Make TinyMCE know a new button was included in its toolbar
         *
         * @since   1.0.0
         *
         * @param   $buttons
         *
         * @return  array()
         * @author  Alberto Ruggiero
         */
        public function ywcds_register_shortcodes_button($buttons)
        {

            array_push($buttons, "|", "ywcds_shortcode");

            return $buttons;

        }

        /**
         * The markup of shortcode
         *
         * @since   1.0.0
         *
         * @param   $context
         *
         * @return  mixed
         * @author  Alberto Ruggiero
         */
        public function ywcds_media_buttons_context($context)
        {

            $out = '<a id="ywcds_shortcode" style="display:none" href="#" class="hide-if-no-js" title="' . __('Add YITH Donations for WooCommerce  Form', 'ywcds') . '"></a>';

            return $context . $out;

        }

        /**
         * Add quicktags to visual editor
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function ywcds_add_quicktags()
        {

            global $post_ID, $temp_ID;

            $query_args = array(
                'post_id' => (int)(0 == $post_ID ? $temp_ID : $post_ID),
                'KeepThis' => true,
                'TB_iframe' => true
            );
            $lightbox_url = esc_url(add_query_arg($query_args, YWCDS_URL . '/templates/admin/lightbox.php'));

            ?>
            <script type="text/javascript">

                if (window.QTags !== undefined) {
                    QTags.addButton('ywcds_shortcode', 'add donation form', function () {
                        jQuery('#ywcds_shortcode').click()
                    });
                }

                jQuery('#ywcds_shortcode').on('click', function () {

                    tb_show('Add YITH Donations for WooCommerce Form', '<?php echo $lightbox_url ?>');

                    ywcds_resize_thickbox(500, 100);

                });

            </script>
        <?php
        }

        /**
         * register premium widget
         * @author YIThemes
         * @since 1.0.0
         */
        public function register_donations_widget()
        {

            parent::register_donations_widget();

            register_widget('YITH_Donations_Summary_Widget');
        }

        /**print custom tab in plugin option
         * @author YIThemes
         * @since 1.0.0
         */
        public function print_export_tab()
        {

            include(YWCDS_TEMPLATE_PATH . 'admin/export-tab.php');

        }

        /**hide add to car single if product is free
         * @author YIThemes
         * @since 1.0.0
         */
        public function hide_add_to_cart() {

            global $product;

            $hide_is_free = get_option('ywcds_hide_add_cart_in_free') == 'yes';

            if ($hide_is_free && $this->is_free($product)) {

                $priority = has_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart');

                if ($priority != false) {

                    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', $priority);
                }
            }
        }



        /**remove product and donation from cart
         * @author YIThemes
         * @since 1.0.0
         *
         */
        public function remove_items_from_cart(){

            if( isset( $_REQUEST['remove_item'] ) ){

                $cart_item_key  =   sanitize_text_field( $_REQUEST['remove_item'] );
                $cart_item      =   WC()->cart->get_cart_item( $cart_item_key );
                $product_id     =   $cart_item['product_id'];

                if( $this->_donation_id == $product_id ){

                    $product_ass_id =   !empty( $cart_item['ywcds_variation_id'] ) ? $cart_item['ywcds_variation_id']  : $cart_item['ywcds_product_id'];
                    $is_obligatory = get_post_meta( $cart_item['ywcds_product_id'], '_ywcds_donation_obligatory', true ) == 'yes';
                    if( $product_ass_id!= -1 && $is_obligatory ){

                        $product    =   wc_get_product( $product_ass_id );
                        $quantity_product_for_donation  =   $cart_item['ywcds_quantity'];

                        $cart_item_key_ass  =   $this->get_cart_item_key( WC()->cart, $cart_item['ywcds_product_id'], $cart_item['ywcds_variation_id'] );



                        $cart_item_ass  =   WC()->cart->get_cart_item( $cart_item_key_ass );
                        $quantity       =   $cart_item_ass['quantity'];

                        $new_quantity   =   $quantity-$quantity_product_for_donation;


                        WC()->cart->cart_contents[$cart_item_key_ass]['quantity']    =   $new_quantity;

                        $message    =   sprintf( __('Donation ( %s ) removed', 'ywcds'), $product->get_title() );
                        WC()->cart->remove_cart_item($cart_item_key);
                        wc_add_notice( $message );
                        $referer = wp_get_referer() ? remove_query_arg( array( 'remove_item', 'add-to-cart', 'added-to-cart', '_wpnonce' ) ) : WC()->cart->get_cart_url();
                        wp_safe_redirect( $referer );
                        exit;
                    }

                }
                else{

                    $is_associate  = get_post_meta( $product_id, '_ywcds_donation_associate', true ) == 'yes';

                    $product_id     =   !empty( $cart_item['variation_id'] ) ?  $cart_item['variation_id']  :   $cart_item['product_id'];

                    if( $is_associate ){

                        foreach( WC()->cart->get_cart() as $cart_item_key_ass=>$value ){

                            if( $value['product_id']== $this->_donation_id  ){

                                $product_ass_id     =   !empty( $value['ywcds_variation_id'] ) ?  $value['ywcds_variation_id']  : $value['ywcds_product_id']  ;

                                if( $product_ass_id == $product_id )
                                    WC()->cart->remove_cart_item( $cart_item_key_ass );
                            }
                        }

                        $product    =   wc_get_product( $product_id );
                        $message    =   sprintf( __('%s and all donations have been removed', 'ywcs'), $product->get_title() );
                        WC()->cart->remove_cart_item($cart_item_key);
                        wc_add_notice( $message );
                        $referer = wp_get_referer() ? remove_query_arg( array( 'remove_item', 'add-to-cart', 'added-to-cart', '_wpnonce' ) ) : WC()->cart->get_cart_url();
                        wp_safe_redirect( $referer );
                        exit;


                    }
                }
            }

        }

        /**get the right cart item key
         * @author YIThemes
         * @since 1.0.0
         * @param $cart
         * @param $product_id
         * @param string $variation_id
         * @return bool|int|string
         */
        public function get_cart_item_key( $cart, $product_id, $variation_id='' ){
            /**@var $cart WC_Cart*/


            foreach( $cart->get_cart() as $cart_item_key=>$value ){
                if(  $variation_id!='' ) {
                    if ( $value['variation_id'] == $variation_id )
                        return $cart_item_key;
                }
                elseif( $value['product_id']== $product_id )
                    return $cart_item_key;
            }

            return false;
        }

        /**
         * @author YIThemes
         * @since 1.0.4
         * get add to cart text for name your price product
         */
        public function add_donation_in_shop_loop( $text, $product ){

            $is_associate = get_post_meta( $product->id, '_ywcds_donation_associate', true ) == 'yes';
            $is_obligatory = get_post_meta( $product->id, '_ywcds_donation_obligatory', true ) == 'yes';

            if( $is_associate && $is_obligatory )
                return get_option( 'ywcds_button_text');
            else
                return $text;


        }

        /**
         * @author YIThemes
         * @since 1.0.4
         * @param $url
         * @param $product
         * @return false|string
         */
        public function add_url_donation_in_shop_loop( $url, $product ){

            $is_associate = get_post_meta( $product->id, '_ywcds_donation_associate', true ) == 'yes';
            $is_obligatory = get_post_meta( $product->id, '_ywcds_donation_obligatory', true ) == 'yes';

            if( $is_associate && $is_obligatory )
                return get_permalink( $product->id );
            else
                return $url;
        }

        /**
         * @author YIThemes
         * @since 1.0.4
         * @param $button_html
         * @param $product
         * @return mixed
         */
        public function disable_ajax_add_to_cart_in_loop( $button_html, $product ){

            $is_associate = get_post_meta( $product->id, '_ywcds_donation_associate', true ) == 'yes';
            $is_obligatory = get_post_meta( $product->id, '_ywcds_donation_obligatory', true ) == 'yes';
            if( $is_associate && $is_obligatory ){

                return str_replace( 'product_type_simple','product_type_donation', $button_html );
            }
            else
                return $button_html;

        }

    }

}