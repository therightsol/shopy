<?php
if( !defined( 'ABSPATH' ) )
    exit;

$min_req    =   sprintf( '%s {min_donation}',
                __( 'Minimum donation allowed is', 'ywcds')
    );

$max_req    =   sprintf( '%s {max_donation}',
    __( 'Maximum donation allowed is', 'ywcds')
);

$messages   =   array(

    'messages'  =>  array(

        'section_message_settings'     => array(
            'name' => __( 'Donation label settings', 'ywcds' ),
            'type' => 'title',
            'id'   => 'ywcds_section_message'
        ),
    'message_for_donation'  =>  array(
        'name'  =>  __('Message attached to your donation', 'ywcds'),
        'type'  => 'text',
        'std'   =>  __('Make a donation', 'ywcds'),
        'default'   =>  __('Make a donation', 'ywcds'),
        'id'    =>  'ywcds_message_for_donation',
        'css'   =>  'width:50%'
    ),

    'message_right_donation'    =>  array(
        'name'  =>  __( 'Thank you message' , 'ywcds' ),
        'type'  =>  'text',
        'std'   =>  __( 'Thanks for your donation', 'ywcds'),
        'default'  =>  __( 'Thanks for your donation', 'ywcds'),
        'id'    =>  'ywcds_message_right_donation',
        'css' =>  'width:50%;'
    ),


    'message_empty_donation'    =>  array(
        'name'  =>  __( 'Text displayed when donation field is empty', 'ywcds' ),
        'std'  =>  __( 'Please enter an amount', 'ywcds' ),
        'default'  =>  __( 'Please enter amount', 'ywcds' ),
        'type'  =>  'text',
        'id'    =>  'ywcds_message_empty_donation',
        'css' =>  'width:50%;'
    ),

    'message_invalid_donation'  =>  array(
        'name'  =>  __( 'Text displayed when donation field is invalid', 'ywcds' ),
        'type'  =>  'text',
        'id'    =>  'ywcds_message_invalid_donation',
        'std'   =>   __('Please enter a valid value', 'ywcds'),
        'default'   =>   __('Please enter a valid value', 'ywcds'),
        'css' =>  'width:50%;'
        ),

        'message_negative_donation' =>  array(
            'name'  =>  __('Text displayed when donation field value is negative', 'ywcds'),
            'type'  =>  'text',
            'id'    =>  'ywcds_message_negative_donation',
            'std'   =>  __( 'Please enter a number greater than 0', 'ywcsd' ),
            'default'   =>  __('Please enter a number greater than 0', 'ywcds'),
            'css'   =>  'width:50%'
        ),
    'message_min_donation'  =>  array(
            'name'  =>  __( 'Text displayed for minimum donation required', 'ywcds' ),
            'type'  =>  'text',
            'id'    =>  'ywcds_message_min_donation',
            'std'   =>   $min_req,
            'default'   =>  $min_req,
            'desc_tip'      =>  __('{min_donation} is replaced with minimum donation required', 'ywcds' ),
            'css' =>  'width:50%;'
        ),

    'message_max_donation'  =>  array(
            'name'  =>  __( 'Text displayed for maximum donation allowed', 'ywcds' ),
            'type'  =>  'text',
            'id'    =>  'ywcds_message_max_donation',
            'std'   =>   $max_req,
            'default'   =>  $max_req,
            'desc_tip'      =>  __('{max_donation} is replaced with maximum donation allowed', 'ywcds' ),
            'css' =>  'width:50%;'
        ),
    'message_obligatory_donation'   =>  array(
            'name'  =>  __( 'Text displayed for compulsory donation', 'ywcds' ),
            'type'  =>  'text',
            'id'    =>  'ywcds_message_obligatory_donation',
            'std'   =>  __( 'Sorry but for this product you must have added a donation first', 'ywcds' ),
            'default'   =>   __( 'Sorry but for this product you must have added a donation first', 'ywcds' ),
        'css' =>  'width:50%;'
    ),

      'section_message_end' => array(
          'type' => 'sectionend',
          'id'   => 'ywcds_section_message_end'
      ),

        'section_widget_text_start'     =>  array(
            'name'  =>  __('Customize text of the labels shown in the Widget "Summary"', 'ywcds'),
            'type'  =>  'title',
            'id'    =>  'ywcds_section_widget_text_start',
            'css' =>  'width:50%;'
        ),

        'widget_text_today' =>  array(
            'name'  =>  __('Today', 'ywcds'),
            'type'  =>  'text',
            'std'   =>  __( 'Today we have collected', 'ywcds' ),
            'default'   =>  __( 'Today we have collected', 'ywcds' ),
            'id'        =>  'ywcds_widget_text_day',
            'css' =>  'width:50%;'
        ),
        'widget_text_year' =>  array(
            'name'  =>  __('Year', 'ywcds'),
            'type'  =>  'text',
            'std'   =>   __( 'This year we have collected','ywcds' ),
            'default'   =>  __( 'This year we have collected','ywcds' ),
            'id'        =>  'ywcds_widget_text_year',
            'css' =>  'width:50%;'
        ),

        'widget_text_week' =>  array(
            'name'  =>  __('Last 7 days', 'ywcds'),
            'type'  =>  'text',
            'std'   =>    __( 'In the last 7 days we have collected', 'ywcds' ),
            'default'   =>   __( 'In the last 7 days we have collected', 'ywcds' ),
            'id'        =>  'ywcds_widget_text_week',
            'css' =>  'width:50%;'
        ),
        'widget_text_month' =>  array(
            'name'  =>  __('Month', 'ywcds'),
            'type'  =>  'text',
            'std'   =>   __( 'This month we have collected', 'ywcds'),
            'default'   =>  __( 'This month we have collected', 'ywcds'),
            'id'        =>  'ywcds_widget_text_month',
            'css' =>  'width:50%;'
        ),

        'widget_text_last_month' =>  array(
            'name'  =>  __('Last Month', 'ywcds'),
            'type'  =>  'text',
            'std'   =>   __( 'In the last month we collected', 'ywcds'),
            'default'   =>  __( 'In the last month we collected', 'ywcds'),
            'id'        =>  'ywcds_widget_text_last_month',
            'css' =>  'width:50%;'
        ),
        'widget_text_always' =>  array(
            'name'  =>  __('Always', 'ywcds'),
            'type'  =>  'text',
            'std'   =>  __( 'So far we have collected', 'ywcds'),
            'default'   => __( 'So far we have collected', 'ywcds'),
            'id'        =>  'ywcds_widget_text_always',
            'css' =>  'width:50%;'
        ),
        'section_widget_text_end'     =>  array(
            'type'  =>  'sectionend',
            'id'    =>  'ywcds_section_widget_text_start'
        ),

    )

);


return apply_filters( 'yith_wc_donations_message_settings', $messages );