<?php

$settings = array(

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
		)
	)
);

return apply_filters( 'yith_wcbm_panel_settings_options', $settings );