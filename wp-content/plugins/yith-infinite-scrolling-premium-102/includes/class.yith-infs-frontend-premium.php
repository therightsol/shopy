<?php
/**
 * Frontend class
 *
 * @author Yithemes
 * @package YITH Infinite Scrolling
 * @version 1.0.0
 */

if ( ! defined( 'YITH_INFS' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_INFS_Frontend_Premium' ) ) {
	/**
	 * YITH Infinite Scrolling
	 *
	 * @since 1.0.0
	 */
	class YITH_INFS_Frontend_Premium extends YITH_INFS_Frontend {

		/**
		 * Array of preset loader
		 *
		 * @var array
		 * @since 1.0.0
		 */
		public $presetLoader = array();

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_INFS_Frontend_Premium
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
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function __construct() {

			parent::__construct();

			$this->_presetLoader = array(
				'loader'    => YITH_INFS_ASSETS_URL . '/images/loader.gif',
				'loader1'   => YITH_INFS_ASSETS_URL . '/images/loader1.gif',
				'loader2'   => YITH_INFS_ASSETS_URL . '/images/loader2.gif',
				'loader3'   => YITH_INFS_ASSETS_URL . '/images/loader3.gif',
			);

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_premium' ) );
		}

		/**
		 * Pass premium options to script
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function enqueue_scripts_premium(){

			wp_enqueue_script( 'jquery-blockui', YITH_INFS_ASSETS_URL . '/js/jquery.blockUI.min.js', array( 'jquery' ), false, true );

			$options = YITH_INFS_Admin()->get_option( 'yith-infs-section' );

			wp_localize_script( 'yith-infs', 'yith_infs_premium', array(
				'options'           => $options,
				'presetLoader'      => apply_filters( 'yith_infs_preset_loader', $this->_presetLoader )
			));

			wp_localize_script( 'yith-infinitescroll', 'yith_infs_script', array(
				'shop'              => function_exists( 'WC' ) && is_shop(),
				'block_loader'      => apply_filters( 'yith_infs_block_loader_frontend', YITH_INFS_ASSETS_URL . '/images/block-loader.gif' )
			));
		}
	}
}

/**
 * Unique access to instance of YITH_INFS_Frontend class
 *
 * @return \YITH_INFS_Frontend_Premium
 * @since 1.0.0
 */
function YITH_INFS_Frontend_Premium(){
	return YITH_INFS_Frontend_Premium::get_instance();
}