<?php
if( !defined( 'ABSPATH' ) )
    exit;


$export   =   array(

    'export'  =>  array(
        'export_tab_action'  =>  array(
            'type'  =>  'custom_tab',
            'action' => 'ywcds_export_tab'
        ),
    )
);


return apply_filters( 'ywcds_export_option', $export );