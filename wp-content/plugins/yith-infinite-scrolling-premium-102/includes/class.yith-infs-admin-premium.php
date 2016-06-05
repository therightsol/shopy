<?php
/*  Copyright 2013  Your Inspiration Themes  (email : plugins@yithemes.com)

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

/**
 * Admin class
 *
 * @author Yithemes
 * @package YITH Infinite Scrolling
 * @version 1.0.0
 */

if ( ! defined( 'YITH_INFS' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_INFS_Admin_Premium' ) ) {
	/**
	 * Admin class.
	 * The class manage all the admin behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_INFS_Admin_Premium extends YITH_INFS_Admin {

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_INFS_Admin_Premium
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Event pagination type array
		 */
		public $eventType = array();

		/**
		 *  Loading effect array
		 */
		public $loadEffect = array();

		/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct() {

			parent::__construct();

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

			// register plugin to licence/update system
			add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
			add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

			// add panel type
			add_action( 'yit_panel_options-section', array( $this, 'admin_options_section' ), 10, 2 );
			// panel type ajax action add
			add_action( 'wp_ajax_yith_infinite_scroll_section', array( $this, 'yith_infinite_scroll_section_ajax' ) );
			add_action( 'wp_ajax_nopriv_yith_infinite_scroll_section', array( $this, 'yith_infinite_scroll_section_ajax' ) );
			// panel type ajax action remove
			add_action( 'wp_ajax_yith_infinite_scroll_section_remove', array( $this, 'yith_infinite_scroll_section_remove_ajax' ) );
			add_action( 'wp_ajax_nopriv_yith_infinite_scroll_section_remove', array( $this, 'yith_infinite_scroll_section_remove_ajax' ) );

			$this->_eventType = array(
				'scroll'        => __( 'Infinite Scrolling', 'yith-infs' ),
				'button'        => __( 'Load More Button', 'yith-infs' ),
				'pagination'    => __( 'Ajax Pagination', 'yith-infs' )
			);

			$this->_loadEffect = array(
				'yith-infs-zoomIn'      => __( 'Zoom in', 'yith-infs' ),
				'yith-infs-bounceIn'    => __( 'Bounce in', 'yith-infs' ),
				'yith-infs-fadeIn'      => __( 'Fade in', 'yith-infs' ),
				'yith-infs-fadeInDown'  => __( 'Fade in from top to down', 'yith-infs' ),
				'yith-infs-fadeInLeft'  => __( 'Fade in from right to left', 'yith-infs' ),
				'yith-infs-fadeInRight' => __( 'Fade in from left to right', 'yith-infs' ),
				'yith-infs-fadeInUp'    => __( 'Fade in from down to top', 'yith-infs' )
			);
		}

		/**
		 * Template for admin section
		 *
		 * @since 1.0.0
		 * @access public
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function admin_options_section( $option, $db_value ) {
			include( YITH_INFS_TEMPLATE_PATH . '/admin/options-section.php' );
		}

		/**
		 * Add admin scripts
		 *
		 * @since 1.0.0
		 * @access public
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function admin_scripts() {
			wp_enqueue_script( 'yith-infs-admin', YITH_INFS_ASSETS_URL . '/js/admin.js', array( 'jquery' ), false, true );
			wp_enqueue_script( 'jquery-blockui', YITH_INFS_ASSETS_URL . '/js/jquery.blockUI.min.js', array( 'jquery' ), false, true );

			wp_localize_script( 'yith-infs-admin', 'yith_infs_admin', array(
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'block_loader'  => apply_filters( 'yith_infs_block_loader_admin', YITH_INFS_ASSETS_URL . '/images/block-loader.gif' ),
				'error_msg'     => apply_filters( 'yith_infs_error_msg_admin', __( 'Please insert a name for the section', 'yith-infs' ) ),
				'del_msg'       => apply_filters( 'yith_infs_delete_msg_admin', __( 'Do you really want to delete this section?', 'yith-infs' ) )
			));
		}

		/**
		 * Register plugins for activation tab
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_plugin_for_activation() {
			if( ! class_exists( 'YIT_Plugin_Licence' ) ){
				require_once( YITH_INFS_DIR . 'plugin-fw/lib/yit-plugin-licence.php' );
			}

			YIT_Plugin_Licence()->register( YITH_INFS_INIT, YITH_INFS_SECRET_KEY, YITH_INFS_SLUG );
		}

		/**
		 * Register plugins for update tab
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_plugin_for_updates() {
			if( ! class_exists( 'YIT_Plugin_Licence' ) ){
				require_once( YITH_INFS_DIR . 'plugin-fw/lib/yit-upgrade.php' );
			}

			YIT_Upgrade()->register( YITH_INFS_SLUG, YITH_INFS_INIT );
		}

		/**
		 * Add new infinite scroll options section
		 *
		 * @since 1.0.0
		 * @access public
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function yith_infinite_scroll_section_ajax() {

			if ( ! isset( $_REQUEST['section'] ) ) {
				die();
			}

			$key     = strip_tags( $_REQUEST['section'] );
			$id      = $_REQUEST['id'];
			$name    = $_REQUEST['name'];

			$presetLoader   = YITH_INFS_Frontend_Premium()->_presetLoader;
			$eventType      = $this->_eventType;
			$loadEffect     = $this->_loadEffect;

			include( YITH_INFS_TEMPLATE_PATH . '/admin/options-section-stamp.php' );

			die();
		}

		/**
		 * Remove infinite scroll options section
		 *
		 * @since 1.0.0
		 * @access public
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function yith_infinite_scroll_section_remove_ajax() {

			if ( ! isset( $_REQUEST['section'] ) ) {
				die();
			}

			$options = get_option( $this->plugin_options );
			$section = $_REQUEST['section'];

			unset( $options['yith-infs-section'][$section] );

			update_option( $this->plugin_options, $options );

			die();
		}
	}
}
/**
 * Unique access to instance of YITH_WCQV_Admin_Premium class
 *
 * @return \YITH_INFS_Admin_Premium
 * @since 1.0.0
 */
function YITH_INFS_Admin_Premium(){
	return YITH_INFS_Admin_Premium::get_instance();
}