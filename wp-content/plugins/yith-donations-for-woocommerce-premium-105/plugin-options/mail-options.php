<?php
if( !defined( 'ABSPATH' ) )
    exit;

$subject_mail   =   sprintf( '{site_title} %s', __( 'Thank you for your donation!', 'ywcds' ) );
$mail_content   =   sprintf( '%s {customer_name},'."\n\n".'%s {order_id}.'."\n\n".'%s:'."\n\n".'{donation_list}'."\n\n".'%s.'."\n\n".'%s.'."\n\n".'%s,'."\n\n".'{site_title}',
                    __( 'Dear', 'ywcds' ),
                    __( 'thank you so much for the donation in your order', 'ywcds' ),
                    __( 'We would like to remind you of your donations', 'ywcds' ),
                    __( 'Thank you again for your kindness and your generosity', 'ywcds'),
                    __( 'We hope to see you again on our site', 'ywcds'),
                    __( 'Best regards', 'ywcds' )
                );

$desc_tip   =   sprintf( '%s<ul><li>%s</li><li>%s</li><li>%s</li><li>%s</li><li>%s</li><li>%s</li><li>%s</li></ul>',
                        __('You can use these placeholders', 'ywcds'),
                        __('{site_title} replaced with site title', 'ywcds'),
                        __('{donation_list} replaced with donation details', 'ywcds'),
                        __('{customer_name} replaced with with customer\'s name', 'ywcds'),
                        __('{customer_email} replaced with customer\'s email address', 'ywcds'),
                        __('{order_id} replaced with order ID', 'ywcds'),
                        __('{order_date} replaced with the date and time of the order', 'ywcds'),
                        __('{order_date_completed} Replaced with the date on which the order was marked completed', 'ywcds')
                    );

$mail   =   array(

    'mail'  =>  array(

        'mail_section_start'    =>  array(
            'name'  =>  __( 'Email Settings', 'ywcds' ),
            'type'  =>  'title',
            'id'    =>  'ywcds_mail_section_start'
        ),

        'mail_type'   =>  array(
          'name'    =>  __('Email Type', 'ywcds'),
          'type'    =>  'select',
           'options'    =>  array(
               'html'   =>  __('HTML', 'ywcds'),
               'plain'  =>  __('Plain Text', 'ywcds')
           ),
           'std'    =>  'html',
            'default'   =>  'html',
            'id'    =>  'ywcds_mail_type'
        ),

        'mail_subject'  =>  array(
          'name'    =>  __( 'Email Subject', 'ywcds' ),
          'type'    =>  'text',
          'desc_tip'  =>  $desc_tip,
          'id'      =>  'ywcds_mail_subject',
          'std'     =>  $subject_mail,
          'default' =>  $subject_mail,
          'css'  =>  'width:400px'
        ),

        'mail_content'  =>  array(
          'name'    =>  __( 'Email Content', 'ywcds' ),
          'type'    =>  'textarea',
          'id'      =>  'ywcds_mail_content',
          'desc_tip'  =>  $desc_tip,
          'std'    =>  $mail_content,
          'default'   =>  $mail_content,
          'css'   =>  'width:100%; height:300px; resize:none;'
        ),

        'mail_template' =>  array(
          'name'    =>  __('Email template', 'ywcds'),
          'type'    =>  'select',
          'id'     =>  'ywcds_mail_template',
          'options' =>  array(
              'default' =>  __('WooCommerce Template', 'ywcds'),
              'ywcds_template'  =>  __('YITH Donations Template', 'ywcds'),
            ),
          'default' =>  'default',
          'std'     =>  'default'
         ),

        'mail_section_end' =>   array(
            'type'  =>  'sectionend',
            'id'    =>  'ywcds_mail_section_end'
        )

    )

);

return apply_filters( 'ywcds_mail_settings', $mail );