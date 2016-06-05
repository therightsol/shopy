<?php
/**
 * GENERAL ARRAY OPTIONS
 */

$general = array(

	'general'  => array(

		array(
			'title' => __( 'General Options', 'yith-wacp' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'yith-wacp-general-options'
		),

		array(
			'name'      => __( 'Popup Size', 'yith-wacp' ),
			'desc'      => __( 'Set popup size.', 'yith-wacp' ),
			'type'      => 'yith_wacp_box_size',
			'default'   => array(
				'width'     => '700',
				'height'    => '700'
			),
			'id'        => 'yith-wacp-box-size'
		),

		array(
			'name'      => __( 'Popup Animation', 'yith-wacp' ),
			'desc'      => __( 'Select popup animation', 'yith-wacp' ),
			'type'      => 'select',
			'options'   => array(
				'fade-in'   => __( 'Fade In', 'yith-wacp' ),
				'slide-in-right' => __( 'Slide In (Right)', 'yith-wacp' ),
				'slide-in-left' => __( 'Slide In (Left)', 'yith-wacp' ),
				'slide-in-bottom' => __( 'Slide In (Bottom)', 'yith-wacp' ),
				'slide-in-top' => __( 'Slide In (Top)', 'yith-wacp' ),
				'tred-flip-h' => __( '3D Flip (Horizontal)', 'yith-wacp' ),
				'tred-flip-v' => __( '3D Flip (Vertical)', 'yith-wacp' ),
				'scale-up' => __( 'Scale Up', 'yith-wacp' ),
			),
			'default'   => 'fade-in',
			'id'        => 'yith-wacp-box-animation'
		),

		array(
			'name' => __( 'Enable Popup', 'yith-wacp' ),
			'desc'  => __( 'On Archive Page', 'yith-wacp' ),
			'type'  => 'checkbox',
			'default'   => 'yes',
			'id'        => 'yith-wacp-enable-on-archive',
			'checkboxgroup' => 'start'
		),

		array(
			'name' => '',
			'desc'  => __( 'On Single Product Page', 'yith-wacp' ),
			'type'  => 'checkbox',
			'default'   => 'yes',
			'id'        => 'yith-wacp-enable-on-single',
			'checkboxgroup' => 'end'
		),

		array(
			'name' => __( 'Popup message', 'yith-wacp' ),
			'desc'  => '',
			'type'  => 'text',
			'default' => __( 'Product successfully added to your cart!', 'yith-wacp' ),
			'id'    => 'yith-wacp-popup-message'
		),

		array(
			'name' => __( 'Select content', 'yith-wacp' ),
			'desc' => __( 'Choose whether to show the added product or the cart', 'yith-wacp' ),
			'type' => 'select',
			'options'   => array(
				'product'   => __( 'Added product', 'yith-wacp' ),
				'cart'   => __( 'Cart', 'yith-wacp' ),
			),
			'default'   => 'product',
			'id' => 'yith-wacp-layout-popup'
		),

		array(
			'name' => __( 'Show product thumbnail', 'yith-wacp' ),
			'desc' => __( 'Choose to show the product thumbnail in the popup', 'yith-wacp' ),
			'type' => 'checkbox',
			'default'   => 'yes',
			'id' => 'yith-wacp-show-thumbnail'
		),

		array(
			'id'        => 'yith-wacp-image-size',
			'name'      => __( 'Thumbnail Size', 'yith-wacp' ),
			'desc'      => sprintf( __( 'Set image size (in px). After changing these settings, you may need to %s.', 'yith-wacp' ), '<a href="http://wordpress.org/extend/plugins/regenerate-thumbnails/">' . __( 'regenerate your thumbnails', 'yith-wacp' ) . '</a>' ),
			'type'      => 'yith_wacp_image_size',
			'default'   => array(
				'width'     => '170',
				'height'    => '170',
				'crop'      => 1
			),
			'custom_attributes' => array(
				'data-deps_id' => 'yith-wacp-show-thumbnail'
			)
		),

		array(
			'name' => __( 'Show cart total', 'yith-wacp' ),
			'desc' => __( 'Choose to show cart total in the popup', 'yith-wacp' ),
			'type' => 'checkbox',
			'default'   => 'yes',
			'id' => 'yith-wacp-show-cart-totals'
		),

		array(
			'name' => __( 'Show shipping fees', 'yith-wacp' ),
			'desc' => __( 'Choose to show shipping fees in the popup', 'yith-wacp' ),
			'type' => 'checkbox',
			'default'   => 'yes',
			'id' => 'yith-wacp-show-cart-shipping'
		),

		array(
			'name' => __( 'Show "View Cart" Button', 'yith-wacp' ),
			'desc' => __( 'Choose to show "View Cart" button in the popup', 'yith-wacp' ),
			'type' => 'checkbox',
			'default'   => 'yes',
			'id' => 'yith-wacp-show-go-cart'
		),

		array(
			'name' => __( '"View Cart" Button Text', 'yith-wacp' ),
			'desc' => __( 'Text for "View Cart" button', 'yith-wacp' ),
			'type' => 'text',
			'default'   => __( 'View Cart', 'yith-wacp' ),
			'id' => 'yith-wacp-text-go-cart',
			'custom_attributes' => array(
				'data-deps_id'  => 'yith-wacp-show-go-cart'
			)
		),

		array(
			'name' => __( 'Show "Continue Shopping" Button', 'yith-wacp' ),
			'desc' => __( 'Choose to show "Continue Shopping" button in the popup', 'yith-wacp' ),
			'type' => 'checkbox',
			'default'   => 'yes',
			'id' => 'yith-wacp-show-continue-shopping'
		),

		array(
			'name' => __( '"Continue Shopping" Button Text', 'yith-wacp' ),
			'desc' => __( 'Text for "Continue Shopping" button', 'yith-wacp' ),
			'type' => 'text',
			'default'   => __( 'Continue Shopping', 'yith-wacp' ),
			'id' => 'yith-wacp-text-continue-shopping',
			'custom_attributes' => array(
				'data-deps_id'  => 'yith-wacp-show-continue-shopping'
			)
		),

		array(
			'name' => __( 'Show "Go to Checkout" Button', 'yith-wacp' ),
			'desc' => __( 'Choose to show "Go to Checkout" button in the popup', 'yith-wacp' ),
			'type' => 'checkbox',
			'default'   => 'yes',
			'id' => 'yith-wacp-show-go-checkout'
		),

		array(
			'name' => __( '"Go to Checkout" Button Text', 'yith-wacp' ),
			'desc' => __( 'Text for "Go to Checkout" button', 'yith-wacp' ),
			'type' => 'text',
			'default'   => __( 'Checkout', 'yith-wacp' ),
			'id' => 'yith-wacp-text-go-checkout',
			'custom_attributes' => array(
				'data-deps_id'  => 'yith-wacp-show-go-checkout'
			)
		),

		array(
			'name' => __( 'Enable on mobile', 'yith-wacp' ),
			'desc'  => __( 'Enable the plugin features on mobile devices', 'yith-wacp' ),
			'type'  => 'checkbox',
			'default'   => 'yes',
			'id'    => 'yith-wacp-enable-mobile'
		),

		array(
			'type'      => 'sectionend',
			'id'        => 'yith-wacp-general-options'
		),

		array(
			'title' => __( 'Related Products', 'yith-wacp' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'yith-wacp-related-options'
		),

		array(
			'name' => __( 'Show related products', 'yith-wacp' ),
			'desc' => __( 'Choose to show related products in popup', 'yith-wacp' ),
			'type'  => 'checkbox',
			'default'   => 'yes',
			'id'    => 'yith-wacp-show-related'
		),

		array(
			'name' => __( '"Related Products" title', 'yith-wacp' ),
			'desc' => __( 'The title for "Related Products" section', 'yith-wacp' ),
			'type'  => 'text',
			'default'   => __( 'Related Products', 'yith-wacp' ),
			'id'    => 'yith-wacp-related-title',
			'custom_attributes' => array(
				'data-deps_id'  => 'yith-wacp-show-related'
			)
		),

		array(
			'name' => __( 'Number of related products', 'yith-wacp' ),
			'desc' => __( 'Choose how many related products to show', 'yith-wacp' ),
			'type'  => 'number',
			'default'   => 4,
			'id'    => 'yith-wacp-related-number',
			'custom_attributes' => array(
				'min'   => 1,
				'data-deps_id'  => 'yith-wacp-show-related'
			)
		),

		array(
			'name' => __( 'Columns of related products', 'yith-wacp' ),
			'desc' => __( 'Choose how many columns to show in related products', 'yith-wacp' ),
			'type' => 'yith_wacp_slider',
			'default' => 4,
			'min'   => 2,
			'max'   => 6,
			'step'  => 1,
			'id'    => 'yith-wacp-related-columns',
			'custom_attributes' => array(
				'data-deps_id'  => 'yith-wacp-show-related'
			)
		),

		array(
			'id'   => 'yith-wacp-related-products',
			'name'  => __( 'Select products', 'yith-wacp' ),
			'desc'  => __( 'Select related products. Leave blank to get standard WooCommerce related products.', 'yith-wacp' ),
			'type'  => 'yith_wacp_select_prod',
			'default'   => '',
			'custom_attributes' => array(
				'data-deps_id'  => 'yith-wacp-show-related'
			)
		),

		array(
			'type'      => 'sectionend',
			'id'        => 'yith-wacp-related-options'
		),
	)
);

return apply_filters( 'yith_wacp_panel_general_options', $general );