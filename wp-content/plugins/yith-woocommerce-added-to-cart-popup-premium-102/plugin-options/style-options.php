<?php
/**
 * STYLE ARRAY OPTIONS
 */

$style = array(

	'style'  => array(

		array(
			'title' => __( 'Style Options', 'yith-wacp' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'yith-wacp-style-options'
		),

		array(
			'name' => __( 'Overlay color', 'yith-wacp' ),
			'desc' => __( 'Choose popup overlay color', 'yith-wacp' ),
			'type' => 'color',
			'default' => '#000000',
			'id'    => 'yith-wacp-overlay-color'
		),

		array(
			'name' => __( 'Overlay opacity', 'yith-wacp' ),
			'desc' => __( 'Choose popup overlay opacity (from 0 to 1)', 'yith-wacp' ),
			'type' => 'yith_wacp_slider',
			'default' => 0.8,
			'min'   => 0,
			'max'   => 1,
			'step'  => 0.1,
			'id'    => 'yith-wacp-overlay-opacity'
		),

		array(
			'name'  => __( 'Popup background', 'yith-wacp' ),
			'desc'  => __( 'Choose popup background color', 'yith-wacp' ),
			'type'  => 'color',
			'default'   => '#ffffff',
			'id'    => 'yith-wacp-popup-background'
		),

		array(
			'name' => __( 'Closing link color', 'yith-wacp' ),
			'desc' => __( 'Choose closing link color', 'yith-wacp' ),
			'type' => 'color',
			'default' => '#ffffff',
			'id'    => 'yith-wacp-close-color'
		),

		array(
			'name' => __( 'Closing link hover color', 'yith-wacp' ),
			'desc' => __( 'Choose closing link hover color', 'yith-wacp' ),
			'type' => 'color',
			'default' => '#c0c0c0',
			'id'    => 'yith-wacp-close-color-hover'
		),

		array(
			'name'  => __( 'Message Text Color', 'yith-wacp' ),
			'desc'  => __( 'Choose popup message text color', 'yith-wacp' ),
			'type'  => 'color',
			'default' => '#000000',
			'id'    => 'yith-wacp-message-text-color'
		),

		array(
			'name'  => __( 'Message Background Color', 'yith-wacp' ),
			'desc'  => __( 'Choose popup message background color', 'yith-wacp' ),
			'type'  => 'color',
			'default' => '#e6ffc5',
			'id'    => 'yith-wacp-message-background-color'
		),

		array(
			'name'  => __( 'Message Icon', 'yith-wacp' ),
			'desc'  => __( 'Upload a popup message icon (suggested size 25x25 px)', 'yith-wacp' ),
			'type'  => 'yith_wacp_upload',
			'default' => YITH_WACP_ASSETS_URL . '/images/message-icon.png',
			'id'    => 'yith-wacp-message-icon'
		),

		array(
			'name'  => __( 'Product Name', 'yith-wacp' ),
			'desc'  => __( 'Choose color for product name', 'yith-wacp' ),
			'type'  => 'color',
			'default' => '#000000',
			'id'    => 'yith-wacp-product-name-color'
		),

		array(
			'name'  => __( 'Product Name Hover', 'yith-wacp' ),
			'desc'  => __( 'Choose hover color for product name', 'yith-wacp' ),
			'type'  => 'color',
			'default' => '#565656',
			'id'    => 'yith-wacp-product-name-color-hover'
		),

		array(
			'name'  => __( 'Product Price', 'yith-wacp' ),
			'desc'  => __( 'Choose color for product price', 'yith-wacp' ),
			'type'  => 'color',
			'default' => '#565656',
			'id'    => 'yith-wacp-product-price-color'
		),

		array(
			'name'  => __( 'Total and Shipping label', 'yith-wacp' ),
			'desc'  => __( 'Choose color for total and shipping label', 'yith-wacp' ),
			'type'  => 'color',
			'default' => '#565656',
			'id'    => 'yith-wacp-cart-info-label-color'
		),

		array(
			'name'  => __( 'Total and Shipping amount', 'yith-wacp' ),
			'desc'  => __( 'Choose color for total and shipping amount', 'yith-wacp' ),
			'type'  => 'color',
			'default' => '#000000',
			'id'    => 'yith-wacp-cart-info-amount-color'
		),

		array(
			'name' => __( 'Button Background', 'yith-wacp' ),
			'desc'  => __( 'Select the button background color', 'yith-wacp' ),
			'type'  => 'color',
			'default'   => '#ebe9eb',
			'id'    => 'yith-wacp-button-background'
		),

		array(
			'name' => __( 'Button Background on Hover', 'yith-wacp' ),
			'desc'  => __( 'Select the button background color on mouseover', 'yith-wacp' ),
			'type'  => 'color',
			'default'   => '#dad8da',
			'id'    => 'yith-wacp-button-background-hover'
		),

		array(
			'name' => __( 'Button Text', 'yith-wacp' ),
			'desc'  => __( 'Select the color of the text of the button', 'yith-wacp' ),
			'type'  => 'color',
			'default'   => '#515151',
			'id'    => 'yith-wacp-button-text'
		),

		array(
			'name' => __( 'Button Text on Hover', 'yith-wacp' ),
			'desc'  => __( 'Select the color of the text of the button on mouseover', 'yith-wacp' ),
			'type'  => 'color',
			'default'   => '#515151',
			'id'    => 'yith-wacp-button-text-hover'
		),

		array(
			'name' => __( 'Related Title', 'yith-wacp' ),
			'desc'  => __( 'Select the color of the related product section title', 'yith-wacp' ),
			'type'  => 'color',
			'default'   => '#565656',
			'id'    => 'yith-wacp-related-title-color'
		),

		array(
			'type'      => 'sectionend',
			'id'        => 'yith-wacp-style-options'
		)
	)
);

return apply_filters( 'yith_wacp_panel_style_options', $style );