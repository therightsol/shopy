<?php
// Exit if accessed directly

$custom_attributes = defined( 'YITH_WCPSC_PREMIUM' ) ? '' : array( 'disabled' => 'disabled' );
!defined( 'YITH_WCPSC' ) && exit();

$settings = array(
    'settings' => array(
        'popup-options'              => array(
            'title' => __( 'Popup Options', 'yith-wcpsc' ),
            'type'  => 'title',
            'desc'  => '',
            'id'    => 'yith-wcpsc-popup-options'
        ),
        'popup-style'                => array(
            'name'              => __( 'Style', 'yith-wcpsc' ),
            'type'              => 'select',
            'desc'              => __( 'Select the style you want to apply to all popups', 'yith-wcpsc' ),
            'id'                => 'yith-wcpsc-popup-style',
            'options'           => array(
                'default'  => __( 'Default', 'yith-wcpsc' ),
                'informal' => __( 'Informal', 'yith-wcpsc' ),
                'elegant'  => __( 'Elegant', 'yith-wcpsc' ),
                'casual'   => __( 'Casual', 'yith-wcpsc' ),
            ),
            'custom_attributes' => $custom_attributes,
            'default'           => 'default'
        ),
        'popup-base-color'           => array(
            'name'              => __( 'Main Color', 'yith-wcpsc' ),
            'type'              => 'color',
            'desc'              => __( 'Select the main color for popups', 'yith-wcpsc' ),
            'id'                => 'yith-wcpsc-popup-base-color',
            'custom_attributes' => $custom_attributes,
            'default'           => '#ffffff'
        ),
        'popup-position'             => array(
            'name'              => __( 'Position', 'yith-wcpsc' ),
            'type'              => 'select',
            'desc'              => __( 'Select the position you want to apply to all popups', 'yith-wcpsc' ),
            'id'                => 'yith-wcpsc-popup-position',
            'options'           => array(
                'center'        => __( 'Center', 'yith-wcpsc' ),
                'top-left'      => __( 'Top Left', 'yith-wcpsc' ),
                'top-rigth'     => __( 'Top Right', 'yith-wcpsc' ),
                'bottom-left'   => __( 'Bottom Left', 'yith-wcpsc' ),
                'bottom-right'  => __( 'Bottom Right', 'yith-wcpsc' ),
                'top-center'    => __( 'Top Center', 'yith-wcpsc' ),
                'bottom-center' => __( 'Bottom Center', 'yith-wcpsc' ),
            ),
            'custom_attributes' => $custom_attributes,
            'default'           => 'center'
        ),
        'popup-effect'               => array(
            'name'              => __( 'Effect', 'yith-wcpsc' ),
            'type'              => 'select',
            'desc'              => __( 'Select the effect you want to apply to all popups', 'yith-wcpsc' ),
            'id'                => 'yith-wcpsc-popup-effect',
            'options'           => array(
                'fade'    => __( 'Fade', 'yith-wcpsc' ),
                'slide'   => __( 'Slide', 'yith-wcpsc' ),
                'zoomIn'  => __( 'Zoom In', 'yith-wcpsc' ),
                'zoomOut' => __( 'Zoom Out', 'yith-wcpsc' ),
            ),
            'custom_attributes' => $custom_attributes,
            'default'           => 'fade'
        ),
        'popup-overlay-color'        => array(
            'name'              => __( 'Overlay Color', 'yith-wcpsc' ),
            'type'              => 'color',
            'desc'              => __( 'Select the color you want to apply to popup overlay', 'yith-wcpsc' ),
            'id'                => 'yith-wcpsc-popup-overlay-color',
            'custom_attributes' => $custom_attributes,
            'default'           => '#000000'
        ),
        'popup-overlay-opacity'      => array(
            'name'              => __( 'Overlay Opacity', 'yith-wcpsc' ),
            'type'              => 'number',
            'desc'              => __( 'Select the opacity you want to set for popup overlay', 'yith-wcpsc' ),
            'id'                => 'yith-wcpsc-popup-overlay-opacity',
            'custom_attributes' => array(
                'min'  => 0,
                'max'  => 1,
                'step' => 0.1
            ),
            'default'           => 0.8
        ),
        'popup-options-end'          => array(
            'type' => 'sectionend',
            'id'   => 'yith-wcpsc-popup-options'
        ),
        'popup-button-options'       => array(
            'title' => __( 'Popup Button Options', 'yith-wcpsc' ),
            'type'  => 'title',
            'desc'  => '',
            'id'    => 'yith-wcpsc-popup-button-options'
        ),
        'popup-button-position'      => array(
            'name'              => __( 'Button Position', 'yith-wcpsc' ),
            'type'              => 'select',
            'desc'              => __( 'Select the position you want to apply to buttons in all popups.', 'yith-wcpsc' ),
            'id'                => 'yith-wcpsc-popup-button-position',
            'options'           => array(
                'before_summary'     => __( 'Before summary', 'yith-wcpsc' ),
                'before_description' => __( 'Before description', 'yith-wcpsc' ),
                'after_description'  => __( 'After description', 'yith-wcpsc' ),
                'after_add_to_cart'  => __( 'After "Add to Cart" Button', 'yith-wcpsc' ),
                'after_summary'      => __( 'After summary', 'yith-wcpsc' ),
            ),
            'custom_attributes' => $custom_attributes,
            'default'           => 'after_add_to_cart'
        ),
        'popup-button-color'         => array(
            'name'              => __( 'Button Color', 'yith-wcpsc' ),
            'type'              => 'color',
            'desc'              => __( 'Select the color you want to apply to the popup button', 'yith-wcpsc' ),
            'id'                => 'yith-wcpsc-popup-button-color',
            'custom_attributes' => $custom_attributes,
            'default'           => '#b369a5'
        ),
        'popup-button-text-color'    => array(
            'name'              => __( 'Button Text Color', 'yith-wcpsc' ),
            'type'              => 'color',
            'desc'              => __( 'Select the color you want to apply to popup button text', 'yith-wcpsc' ),
            'id'                => 'yith-wcpsc-popup-button-text-color',
            'custom_attributes' => $custom_attributes,
            'default'           => '#ffffff'
        ),
        'popup-button-border-radius' => array(
            'name'              => __( 'Border Radius', 'yith-wcpsc' ),
            'type'              => 'number',
            'desc'              => __( 'Select the border radius for popup', 'yith-wcpsc' ),
            'id'                => 'yith-wcpsc-popup-button-border-radius',
            'custom_attributes' => array(
                'min' => 0,
            ),
            'default'           => 3
        ),
        'popup-button-padding'       => array(
            'name'              => __( 'Padding', 'yith-wcpsc' ),
            'type'              => 'multiinput',
            'id'                => 'yith-wcpsc-popup-button-padding',
            'custom_attributes' => array(
                'min' => 0,
            ),
            'options'           => array(
                'input_type' => 'number',
                'fields'     => array(
                    __( 'Top', 'yith-wcpsc' ),
                    __( 'Right', 'yith-wcpsc' ),
                    __( 'Bottom', 'yith-wcpsc' ),
                    __( 'Left', 'yith-wcpsc' )
                )
            ),
            'default'           => array(6, 15, 6, 15),
        ),
        'popup-button-shadow-color'  => array(
            'name'              => __( 'Button Shadow Color', 'yith-wcpsc' ),
            'type'              => 'color',
            'desc'              => __( 'Select the color you want to apply to popup button shadow', 'yith-wcpsc' ),
            'id'                => 'yith-wcpsc-popup-button-shadow-color',
            'custom_attributes' => $custom_attributes,
            'default'           => '#dddddd'
        ),
        'popup-button-options-end'   => array(
            'type' => 'sectionend',
            'id'   => 'yith-wcpsc-popup-button-options'
        ),
        'table-options'              => array(
            'title' => __( 'Table Options', 'yith-wcpsc' ),
            'type'  => 'title',
            'desc'  => '',
            'id'    => 'yith-wcpsc-table-options'
        ),
        'table-style'                => array(
            'name'              => __( 'Style', 'yith-wcpsc' ),
            'type'              => 'select',
            'desc'              => __( 'Select the style you want to apply to all tables', 'yith-wcpsc' ),
            'id'                => 'yith-wcpsc-table-style',
            'options'           => array(
                'default'  => __( 'Default', 'yith-wcpsc' ),
                'informal' => __( 'Informal', 'yith-wcpsc' ),
                'elegant'  => __( 'Elegant', 'yith-wcpsc' ),
                'casual'   => __( 'Casual', 'yith-wcpsc' ),
            ),
            'custom_attributes' => $custom_attributes,
            'default'           => 'default'
        ),
        'table-base-color'           => array(
            'name'              => __( 'Main Color', 'yith-wcpsc' ),
            'type'              => 'color',
            'desc'              => __( 'Select the main color for tables', 'yith-wcpsc' ),
            'id'                => 'yith-wcpsc-table-base-color',
            'custom_attributes' => $custom_attributes,
            'default'           => '#f9f9f9'
        ),
        'table-style-end'            => array(
            'type' => 'sectionend',
            'id'   => 'yith-wcpsc-table-style'
        ),
    )
);

return apply_filters( 'yith_wcpsc_panel_settings_options', $settings );