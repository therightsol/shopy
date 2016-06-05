<?php
if( !defined( 'ABSPATH' ) )
    exit;


$setting    =    array(

    'settings'  =>  array(
        'section_general_settings'     => array(
            'name' => __( 'General settings', 'ywcds' ),
            'type' => 'title',
            'id'   => 'ywcds_section_general'
        ),


        'show_donation_in_cart' =>  array(
            'name'      =>  __( 'Show in Cart ', 'ywcds' ),
            'desc'      =>  __('Show donation form in cart', 'ywcds'),
            'type'      =>  'checkbox',
            'std'       =>  'no',
            'default'   =>  'no',
            'id'        =>  'ywcds_show_donation_in_cart'
        ),


        'min_donation'  =>  array(
            'name'  =>  __( 'Minimun Donation Required', 'ywcds' ),
            'type'  =>  'number',
            'id'    =>  'ywcds_min_donation',
            'custom_attributes' =>  array(
                'min'   =>  1,
            ),
            'std'   =>  10,
            'default'   =>  10
        ),

        'max_donation'  =>  array(
            'name'  =>  __( 'Maximun Donation Allowed', 'ywcds' ),
            'type'  =>  'number',
            'id'    =>  'ywcds_max_donation',
            'custom_attributes' =>  array(
                'min'   =>  1,
            ),
            'std'   =>  100,
            'default'   =>  100
        ),

        'select_gateway'    =>  array(
            'name'  =>  __( 'Payment method', 'ywcds' ),
            'desc'  =>  __( 'Select payment method for donations', 'ywcds'),
            'type'  =>  'multiselect',
            'class' =>  'chosen_select',
            'id'    =>  'ywcds_select_gateway',
            'options'   => ywcds_get_gateway(),
            'std'   =>  '',
            'default'   =>  '',
            'css'       =>  'width:50%;'

        ),

        'link_product_donation' =>  array(
            'name'  =>  '',
            'desc'  =>  '',
            'type'  =>  'donation-product-link',
            'link_text'  =>  __( 'click here', 'ywcds' ),
            'before_text'   =>  __( 'To let the plugin work correctly, a special product has been created to let you manage your donations. ', 'ywcds' ),
            'after_text'   =>  __(' Show it', 'ywcds' ),
            'post_id'   =>  get_option( '_ywcds_donation_product_id' ),
            'id'    =>  'ywcds_product_link'
        ),

        'section_general_settings_end' => array(
            'type' => 'sectionend',
            'id'   => 'ywcds_section_general_end'
        ),

        'section_button_settings'     => array(
            'name' => __( 'Button settings', 'ywcds' ),
            'type' => 'title',
            'id'   => 'ywcds_button_general'
        ),

        'button_text'   =>  array(
            'name'  =>  __( 'Button Text', 'ywcds'),
            'desc'  =>  __('Set a text for your donation button', 'ywcds' ),
            'type'  =>  'text',
            'std'   =>  __( 'Add Donation', 'ywcds' ),
            'default'   =>  __( 'Add Donation', 'ywcds' ),
            'id'        =>  'ywcds_button_text',
        ),

        'button_style_select'   =>  array(
            'name'  =>  __( 'Button Style', 'ywcds' ),
            'type'  =>  'select',
            'options'   =>  array(
                'wc'        =>  __('WooCommerce Style', 'ywcds'),
                'custom'    =>  __('Custom Style', 'ywcds'),
            ),
            'std'   =>  'wc',
            'default'   =>  'wc',
            'id'    =>  'ywcds_button_style_select',
        ),

        'button_typography' =>  array(
            'name'  =>  __( 'Button Typography', 'ywcds' ),
            'type'  =>  'typography',
            'id'    =>  'ywcds_button_typography',
            'default'   => array(
                'size'   => 13,
                'unit'   => 'px',
                'style'  => 'regular',
                'transform' =>  'uppercase',
            )
        ),

        'button_text_color' =>  array(
          'name'    =>  __( 'Button Text Color', 'ywcds'),
          'type'    =>  'color',
          'id'      =>  'ywcds_text_color',
          'std'     =>  '#fff',
          'default' =>  '#fff'
        ),

        'button_bg_color' =>  array(
            'name'    =>  __( 'Button Background Color', 'ywcds'),
            'type'    =>  'color',
            'id'      =>  'ywcds_bg_color',
            'std'     =>  '#333',
            'default' =>  '#333'
        ),

        'button_text_hov_color' =>  array(
            'name'    =>  __( 'Button Text Hover Color', 'ywcds'),
            'type'    =>  'color',
            'id'      =>  'ywcds_text_hov_color',
            'std'     =>  '#333',
            'default' =>  '#333'
        ),

        'button_bg_hov_color' =>  array(
            'name'    =>  __( 'Button Background Hover Color', 'ywcds'),
            'type'    =>  'color',
            'id'      =>  'ywcds_bg_hov_color',
            'std'     =>  '#fff',
            'default' =>  '#fff'
        ),

        'section_button_settings_end' => array(
            'type' => 'sectionend',
            'id'   => 'ywcds_section_button_end'
        ),

    )
);

return apply_filters( 'yith_wc_donations_settings', $setting );