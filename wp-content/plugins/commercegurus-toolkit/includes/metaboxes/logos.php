<?php

function cg_logos_metaboxes( $meta_boxes ) {
    $prefix = '_cg_'; // Prefix for all fields
    $meta_boxes['logo'] = array(
        'id' => 'logo_metabox',
        'title' => __( 'Logo Details', 'commercegurus' ),
        'pages' => array( 'logos' ), // post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        'fields' => array(
            array(
                'name' => __( 'Logo link destination', 'commercegurus' ),
                'desc' => __( 'Where the logo should link to. Please do not forget to include the http://', 'commercegurus' ),
                'id' => $prefix . 'logo_url',
                'type' => 'text'
            ),
            array(
                'name' => __( 'Logo image', 'commercegurus' ),
                'desc' => __( 'Upload an image or enter a URL.', 'commercegurus' ),
                'id' => $prefix . 'logo_image',
                'type' => 'file',
            ),
        ),
    );

    return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'cg_logos_metaboxes' );
