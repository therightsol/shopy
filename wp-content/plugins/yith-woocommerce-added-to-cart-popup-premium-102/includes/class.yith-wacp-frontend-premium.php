<?php
/**
 * Frontend Premium class
 *
 * @author Yithemes
 * @package YITH WooCommerce Added to Cart Popup Premium
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WACP' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WACP_Frontend_Premium' ) ) {
	/**
	 * Frontend class.
	 * The class manage all the frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WACP_Frontend_Premium extends YITH_WACP_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WACP_Frontend_Premium
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YITH_WACP_VERSION;

		/**
		 * Remove action
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $action_remove = 'yith_wacp_remove_item_cart';

		/**
		 * Add to cart action
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $action_add = 'yith_wacp_add_item_cart';

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WACP_Frontend_Premium
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct() {

			parent::__construct();

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_premium' ), 20 );

			// action remove ajax
			add_action( 'wp_ajax_' . $this->action_remove, array( $this, 'yith_wacp_remove_item_cart_ajax' ) );
			add_action( 'wp_ajax_nopriv_' . $this->action_remove, array( $this, 'yith_wacp_remove_item_cart_ajax' ) );

			// action add to cart ajax
			add_action( 'wp_ajax_' . $this->action_add, array( $this, 'yith_wacp_add_item_cart_ajax' ) );
			add_action( 'wp_ajax_nopriv_' . $this->action_add, array( $this, 'yith_wacp_add_item_cart_ajax' ) );

			// prevent redirect after ajax add to cart
			add_filter( 'woocommerce_add_to_cart_redirect', array( $this, 'prevent_redirect_url' ), 99, 1 );

			// prevent add to cart ajax
			add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'exclude_single' ) );

			// add popup message
			add_action( 'yith_wacp_before_popup_content', array( $this, 'add_message' ), 10, 1 );
			// add action button to popup
			add_action( 'yith_wacp_after_popup_content', array( $this, 'add_actions_button' ), 10, 1 );
			// add related to popup
			add_action( 'yith_wacp_after_popup_content', array( $this, 'add_related' ), 20, 1 );

			// add args to popup template
			add_filter( 'yith_wacp_popup_template_args', array( $this, 'popup_args' ), 10, 1 );

		}

		/**
		 * Enqueue scripts and styles premium
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function enqueue_premium() {

			$inline_css = yith_wacp_get_style_options();

			wp_add_inline_style( 'yith-wacp-frontend', $inline_css );

			// scroll plugin
			wp_enqueue_style( 'wacp-scroller-plugin-css', YITH_WACP_ASSETS_URL . '/css/perfect-scrollbar.css' );
			wp_enqueue_script( 'wacp-scroller-plugin-js', YITH_WACP_ASSETS_URL . '/js/perfect-scrollbar.js', array('jquery'), false, true );

			wp_localize_script( 'yith-wacp-frontend-script', 'yith_wacp', array(
				'ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'actionadd'    => $this->action_add,
				'add_nonce'    => wp_create_nonce( $this->action_add ),
				'actionremove' => $this->action_remove,
				'remove_nonce' => wp_create_nonce( $this->action_remove ),
				'loader'       => YITH_WACP_ASSETS_URL . '/images/loader.gif',
				'enable_single' => get_option( 'yith-wacp-enable-on-single' ) == 'yes' ? true : false
			) );
		}

		/**
		 * Add args to popup template
		 *
		 * @since 1.0.0
		 * @param mixed $args
		 * @return mixed
		 * @author Francesco Licandro
		 */
		public function popup_args( $args ) {

			// set new animation
			$args['animation'] = get_option( 'yith-wacp-box-animation' );

			return $args;
		}

		/**
		 * Get content html for added to cart popup
		 *
		 * @access public
		 * @since 1.0.0
		 * @param object|boolean $product current product added
		 * @return mixed
		 * @author Francesco Licandro
		 */
		private function get_popup_content( $product = false ) {

			$layout = get_option( 'yith-wacp-layout-popup', 'product' );
			// set args
			$args = array(
				'thumb'          => get_option( 'yith-wacp-show-thumbnail' ) == 'yes' ? true : false,
				'cart_total'     => get_option( 'yith-wacp-show-cart-totals' ) == 'yes' ? true : false,
				'cart_shipping'  => get_option( 'yith-wacp-show-cart-shipping' ) == 'yes' ? true : false,
				'product'        => $product
			);

			// add to cart popup
			ob_start();

			do_action( 'yith_wacp_before_popup_content', $product );

			wc_get_template( 'yith-wacp-popup-' . $layout . '.php', $args, '', YITH_WACP_TEMPLATE_PATH . '/' );

			do_action( 'yith_wacp_after_popup_content', $product );

			return ob_get_clean();
		}

		/**
		 * Added to cart success popup box
		 *
		 * @param array
		 * @return array
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function add_to_cart_success_ajax( $datas ) {

			$enable = get_option( 'yith-wacp-enable-on-archive' ) == 'yes' ? true : false;

			if ( ! isset( $_REQUEST['product_id' ] ) || ! $enable ) {
				return $datas;
			}

			$product_id = intval( $_REQUEST['product_id' ] );
			// get product
			$product = wc_get_product( $product_id );

			// check if is excluded
			if( $this->is_in_exclusion( $product->id ) ) {
				return $datas;
			}

			$datas['yith_wacp_message'] = $this->get_popup_content( $product );

			return $datas;
		}

		/**
		 * Action ajax for remove item from cart
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function yith_wacp_remove_item_cart_ajax() {

			if( ! isset( $_REQUEST['_nonce'] ) || ! wp_verify_nonce( $_REQUEST['_nonce'], $this->action_remove )
			    || ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_remove || ! isset( $_REQUEST['item_key'] ) ) {

				die();
			}

			$item_key = sanitize_text_field( $_REQUEST['item_key'] );

			// remove item
			WC()->cart->remove_cart_item( $item_key );

			// then reload cart popup content
			$args = array(
				'thumb'          => get_option( 'yith-wacp-show-thumbnail' ) == 'yes' ? true : false,
				'cart_total'     => get_option( 'yith-wacp-show-cart-totals' ) == 'yes' ? true : false,
				'cart_shipping'  => get_option( 'yith-wacp-show-cart-shipping' ) == 'yes' ? true : false,
			);

			ob_start();

			wc_get_template( 'yith-wacp-popup-cart.php', $args, '', YITH_WACP_TEMPLATE_PATH . '/' );

			$html = ob_get_clean();

			wp_send_json( array(
				'html' => $html
			));
		}

		/**
		 * Action ajax for add to cart in single product page
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function yith_wacp_add_item_cart_ajax() {

			if( ! isset( $_REQUEST['_nonce'] ) || ! wp_verify_nonce( $_REQUEST['_nonce'], $this->action_add )
			    || ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_add || ! isset( $_REQUEST['add-to-cart'] ) ) {

				die();
			}

			// get woocommerce error notice
			$error = wc_get_notices( 'error' );
			$html = '';

			if( $error ){
				// print notice
				ob_start();
				foreach( $error as $value ) {
					wc_print_notice( $value, 'error' );
				}
				$html = ob_get_clean();
			}

			// clear other notice
			wc_clear_notices();

			wp_send_json( array(
				'msg'   => $html,
				'prod_id' => $_REQUEST['add-to-cart']
			) );
		}

		/**
		 * Prevent url redirect in add to cart ajax for single product page
		 *
		 * @since 1.0.0
		 * @param $url
		 * @return boolean | string
		 * @author Francesco Licandro
		 */
		public function prevent_redirect_url( $url ) {

			if( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == $this->action_add ) {
				return false;
			}

			return $url;
		}

		/**
		 * Add message before popup content
		 *
		 * @access public
		 * @since 1.0.0
		 * @param object $product
		 * @author Francesco Licandro
		 */
		public function add_message( $product ) {
			// get message
			$message = get_option( 'yith-wacp-popup-message' );

			if( ! $message ) {
				return;
			}

			ob_start();
			?>

			<div class="yith-wacp-message">
				<span><?php echo $message ?></span>
			</div>

			<?php
			$html = ob_get_clean();

			echo apply_filters( 'yith_wacp_message_popup_html', $html, $product );
		}

		/**
		 * Add action button to popup
		 *
		 * @access public
		 * @since 1.0.0
		 * @param object $product
		 * @author Francesco Licandro
		 */
		public function add_actions_button( $product) {

			$cart = get_option( 'yith-wacp-show-go-cart' ) == 'yes' ? true : false;
			$checkout = get_option( 'yith-wacp-show-go-checkout' ) == 'yes' ? true : false;
			$continue = get_option( 'yith-wacp-show-continue-shopping' ) == 'yes' ? true : false;

			if( ! $cart && ! $checkout && ! $continue ) {
				return;
			}

			ob_start();
			?>

			<div class="popup-actions">
				<?php if( $cart ) : ?>
					<a class="<?php echo apply_filters( 'yith_wacp_go_cart_class', 'button go-cart' ) ?>" href="<?php echo WC()->cart->get_cart_url(); ?>"><?php echo get_option( 'yith-wacp-text-go-cart', '' ) ?></a>
				<?php endif ?>
				<?php if( $checkout ) : ?>
					<a class="<?php echo apply_filters( 'yith_wacp_go_checkout_class', 'button go-checkout' ) ?>" href="<?php echo WC()->cart->get_checkout_url(); ?>"><?php echo get_option( 'yith-wacp-text-go-checkout', '' ) ?></a>
				<?php endif ?>
				<?php if( $continue ) : ?>
					<a class="<?php echo apply_filters( 'yith_wacp_continue_shopping_class', 'button continue-shopping' ) ?>" href="#"><?php echo get_option( 'yith-wacp-text-continue-shopping', '' ) ?></a>
				<?php endif; ?>
			</div>

			<?php

			$html = ob_get_clean();

			echo apply_filters( 'yith_wacp_actions_button_html', $html, $product );
		}

		/**
		 * Add related product to popup
		 *
		 * @access public
		 * @since 1.0.0
		 * @param object $product
		 * @author Francesco Licandro
		 */
		public function add_related( $product ) {

			if( get_option( 'yith-wacp-show-related' ) != 'yes' ) {
				return;
			}

			$related = array_filter( explode( ',', get_option( 'yith-wacp-related-products' ) ) );
			$layout = get_option( 'yith-wacp-layout-popup', 'product' );

			// get standard WC related if option is empty
			if( empty( $related ) ) {
				$related = $layout == 'product' ? $product->get_related() : WC()->cart->get_cross_sells();
			}

			$args = array(
				'title' => get_option( 'yith-wacp-related-title', '' ),
				'items' => $related,
				'posts_per_page' => get_option( 'yith-wacp-related-number', 4 ),
				'columns'   => get_option( 'yith-wacp-related-columns', 4 ),
				'current_product_id' => $product->id
			);

			wc_get_template( 'yith-wacp-popup-related.php', $args, '', YITH_WACP_TEMPLATE_PATH . '/' );

		}

		/**
		 * Exclude product from added to cart popup process in single product page
		 *
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function exclude_single(){
			global $product;

			if( $this->is_in_exclusion( $product->id ) ) {
				echo '<input type="hidden" name="yith-wacp-is-excluded" value="1" />';
			}
		}

		/**
		 * Check if product is in exclusion list
		 *
		 * @since 1.0.0
		 * @param int $product_id
		 * @return boolean
		 * @author Francesco Licandro
		 */
		public function is_in_exclusion( $product_id ){

			$exclusion_prod = array_filter( explode( ',', get_option( 'yith-wacp-exclusions-prod-list', '' ) ) );
			$exclusion_cat  = array_filter( explode( ',', get_option( 'yith-wacp-exclusions-cat-list', '' ) ) );

			$product_cat = array();
			$product_categories = get_the_terms( $product_id, 'product_cat' );

			foreach( $product_categories as $cat ) {
				$product_cat[] = $cat->term_id;
			}

            $intersect = array_intersect( $product_cat, $exclusion_cat );
			if( in_array( $product_id, $exclusion_prod) || ! empty( $intersect ) ) {
				return true;
			}

			return false;
		}
	}
}

/**
 * Unique access to instance of YITH_WACP_Frontend_Premium class
 *
 * @return \YITH_WACP_Frontend_Premium
 * @since 1.0.0
 */
function YITH_WACP_Frontend_Premium(){
	return YITH_WACP_Frontend_Premium::get_instance();
}