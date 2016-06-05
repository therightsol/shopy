<?php

$settings = array(

	'general' => array(

		'header'    => array(

			array(
				'name' => __( 'General Settings', 'yiw' ),
				'type' => 'title'
			),

			array( 'type' => 'close' )
		),


		'settings' => array(

			array( 'type' => 'open' ),

			array(
				'id'      => 'yith-infs-enable',
				'name'    => __( 'Enable Infinite Scrolling', 'yith-infs' ),
				'desc'    => '',
				'type'    => 'on-off',
				'std'     => 'yes'
			),

			array(
				'id'      => 'yith-infs-section',
				'name'    => __( 'Add section and set options', 'yith-infs' ),
				'desc'    => '',
				'type'    => 'options-section',
				'deps'    => array(
					'ids'       => 'yith-infs-enable',
					'values'    => 'yes'
				)
			),

			array( 'type' => 'close' ),
		)
	)
);

return apply_filters( 'yith_infs_panel_settings_options', $settings );