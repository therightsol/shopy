<?php

function cg_shopannouncements_metaboxes( $meta_boxes ) {
    $prefix = '_cg_'; // Prefix for all fields
    $meta_boxes['shopannouncements'] = array(
        'id' => 'shopannouncements_metabox',
        'title' => __( 'Shop announcement title', 'commercegurus' ),
        'pages' => array( 'shopannouncements' ), // post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        'fields' => array(
            array(
                'name' => __( 'Announcement background color', 'commercegurus' ),
                'desc' => __( 'The announcement has a solid background color', 'commercegurus' ),
                'id' => $prefix . 'shopannouncement_bgcolor',
                'type' => 'colorpicker',
                'default' => '#82b965'
            ),
            array(
                'name' => __( 'Announcement text color', 'commercegurus' ),
                'desc' => __( 'The text color for the announcement', 'commercegurus' ),
                'id' => $prefix . 'shopannouncement_txtcolor',
                'type' => 'colorpicker',
                'default' => '#fff'
            ),
        ),
    );

    return $meta_boxes;
}

//add_filter( 'cmb_meta_boxes', 'cg_shopannouncements_metaboxes' );
