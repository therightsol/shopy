<?php
if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

$custom_attributes = defined( 'YITH_WCBM_PREMIUM' ) ? '' : array( 'disabled' => 'disabled' );

// Create Array for badge select
$badge_array = array( 
	'none' 		=> __( 'None', 'yith-wcbm' )
	);

$args = ( array('posts_per_page' => -1, 'post_type' => 'yith-wcbm-badge', 'orderby' => 'title', 'order' => 'ASC', 'post_status'=> 'publish') );
$badges = get_posts( $args );
foreach ($badges as $badge) {
    $badge_array[$badge->ID] = get_the_title($badge->ID);
}

$a_settings = array(

	'settings'  => array(

		'general-options' => array(
			'title' => __( 'General Options', 'yith-wcbm' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'yith-wcbm-general-options'
		),

		'hide-on-sale-default-badge' => array(
			'id'        => 'yith-wcbm-hide-on-sale-default',
			'name'      => __( 'Hide "On sale" badge', 'yith-wcbm' ),
			'type'      => 'checkbox',
			'desc'      => __( 'Select to hide the default Woocommerce "On sale" badge.', 'yith-wcbm' ),
			'default'   => 'no'
		),

		'hide-in-sidebar' => array(
				'id'      => 'yith-wcbm-hide-in-sidebar',
				'name'    => __( 'Hide in sidebars', 'yith-wcbm' ),
				'type'    => 'checkbox',
				'desc'    => __( 'Select to hide the badges in sidebars and widgets.', 'yith-wcbm' ),
				'default' => 'yes'
		),

		'product-badge-overrides-default-on-sale' => array(
				'id'      => 'yith-wcbm-product-badge-overrides-default-on-sale',
				'name'    => __( 'Product Badge overrides default on sale badge', 'yith-wcbm' ),
				'type'    => 'checkbox',
				'desc'    => __( 'Select if you want to hide WooCommerce default "On Sale" badge when the product has another badge', 'yith-wcbm' ),
				'default' => 'yes'
		),

		'general-options-end' => array(
			'type'      => 'sectionend',
			'id'        => 'yith-wcqv-general-options'
		),

		'recent-badge-options' => array(
			'title' => __( 'Recent Products', 'yith-wcbm' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'yith-wcbm-recent-badge-options'
		),

		'recent-products-badge' => array(
			'name'              => __( 'Badge for Recent products', 'yith-wcbm' ),
			'type'              => 'select',
			'desc'              => __( 'Select the badge you want to apply to all recent products.', 'yith-wcbm' ),
			'id'                => 'yith-wcbm-recent-products-badge',
			'options'           => $badge_array,
			'custom_attributes' => $custom_attributes,
			'default'           => 'none'
		),

		'badge-newer-than'      => array(
			'name'              => __( 'Newer than', 'yith-wcbm' ),
			'type'              => 'number',
			'desc'              => __( 'Show the badge for products that are newer than X days.', 'yith-wcbm' ),
			'id'                => 'yith-wcbm-badge-newer-than',
			'custom_attributes' => $custom_attributes,
			'default'           => '0'
		),

		'recent-badge-options-end' => array(
			'type'      => 'sectionend',
			'id'        => 'yith-wcbm-recent-badge-options'
		),

		'on-sale-badge-options' => array(
			'title' => __( 'On Sale [Automatic]', 'yith-wcbm' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'yith-wcbm-on-sale-badge-options'
		),

		'on-sale-badge' => array(
			'name'              => __( 'On sale Badge', 'yith-wcbm' ),
			'type'              => 'select',
			'desc'              => __( 'Select the Badge for products on sale.', 'yith-wcbm' ),
			'id'                => 'yith-wcbm-on-sale-badge',
			'options'           => $badge_array,
			'custom_attributes' => $custom_attributes,
			'default'           => 'none'
		),

		'on-sale-badge-options-end' => array(
			'type'      => 'sectionend',
			'id'        => 'yith-wcbm-on-sale-badge-options'
		),

		'featured-badge-options' => array(
			'title' => __( 'Featured [Automatic]', 'yith-wcbm' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'yith-wcbm-featured-badge-options'
		),

		'featured-badge' => array(
			'name'              => __( 'Featured badge', 'yith-wcbm' ),
			'type'              => 'select',
			'desc'              => __( 'Select the badge for featured products.', 'yith-wcbm' ),
			'id'                => 'yith-wcbm-featured-badge',
			'options'           => $badge_array,
			'custom_attributes' => $custom_attributes,
			'default'           => 'none'
		),

		'featured-badge-options-end' => array(
			'type'      => 'sectionend',
			'id'        => 'yith-wcbm-featured-badge-options'
		),

		'out-of-stock-badge-options' => array(
				'title' => __( 'Out of stock [Automatic]', 'yith-wcbm' ),
				'type' => 'title',
				'desc' => '',
				'id' => 'yith-wcbm-out-of-stock-badge-options'
		),

		'out-of-stock-badge' => array(
				'name'              => __( 'Out of stock Badge', 'yith-wcbm' ),
				'type'              => 'select',
				'desc'              => __( 'Select the Badge for products out of stock.', 'yith-wcbm' ),
				'id'                => 'yith-wcbm-out-of-stock-badge',
				'options'           => $badge_array,
				'custom_attributes' => $custom_attributes,
				'default'           => 'none'
		),

		'out-of-stock-badge-options-end' => array(
				'type'      => 'sectionend',
				'id'        => 'yith-wcbm-out-of-stock-badge-options'
		),

		'single-product-badge-options' => array(
			'title' => __( 'Single Product', 'yith-wcbm' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'yith-wcbm-single-product-badge-options'
		),

		'hide-on-single-product' => array(
			'name'              => __( 'Hide on Single Product', 'yith-wcbm' ),
			'type'              => 'checkbox',
			'desc'              => __( 'Select to hide badges on Single Product Page.', 'yith-wcbm' ),
			'id'                => 'yith-wcbm-hide-on-single-product',
			'custom_attributes' => $custom_attributes,
			'default'           => 'no'
		),

		'single-product-badge-options-end' => array(
			'type'      => 'sectionend',
			'id'        => 'yith-wcbm-single-product-badge-options'
		)
	)
);

//return $settings;