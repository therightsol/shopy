<?php
/**
 * Frontend class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Zoom Magnifier
 * @version 1.1.2
 */

if ( ! defined( 'YITH_WCMG' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCMG_Frontend_Premium' ) ) {
	/**
	 * Admin class.
	 * The class manage all the Frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCMG_Frontend_Premium extends YITH_WCMG_Frontend {


		/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct( $version ) {


			parent::__construct( $version );
		}


		/**
		 * Enqueue styles and scripts
		 *
		 * @access public
		 * @return void
		 * @since 1.0.0
		 */
		public function enqueue_styles_scripts() {

			global $post;

			if ( $this->is_product_excluded() ) {
				return;
			}

			parent::enqueue_styles_scripts();

		}

		/**
		 * Check if current product have to be ignored by the plugin.
		 * We want to be alerted only if we are working on a valid product on which a product rule or catefory rule is active.
		 *
		 * @return bool product should be ignored
		 */
		public function is_product_excluded() {
			global $post;

			//  if current post is not a product, there is nothing to report.
			if ( ! is_product() ) {
				return false;
			}

			//  Check single product exclusion rule
			$is_excluded = get_post_meta( $post->ID, '_ywzm_exclude', true );

			if ( 'yes' != $is_excluded ) {
				$category_excluded = $this->is_product_category_excluded();

				return $category_excluded;
			}


			return true;
		}

		/**
		 * Check if current product is associated with a product category excluded by plugin option
		 */
		public function is_product_category_excluded() {
			global $post;

			//  if current post is not a product, there is nothing to report.
			if ( ! is_product() ) {
				return false;
			}

			$exclusion_list = get_option( 'ywzm_category_exclusion' );
			if ( ! $exclusion_list ) {
				return false;
			}

			$terms = get_the_terms( $post->ID, 'product_cat' );

			if ( $terms && ! is_wp_error( $terms ) ) {

				foreach ( $terms as $term ) {

					if ( in_array( $term->term_id, $exclusion_list ) ) {

						return true;
					}
				}
			}

			return false;
		}

		public function render() {

			//  Check if the plugin have to interact with current product
			if ( $this->is_product_excluded() ) {
				return;
			}

			//  Call the parent method
			parent::render();

		}
	}
}
