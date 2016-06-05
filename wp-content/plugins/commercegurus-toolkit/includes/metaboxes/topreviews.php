<?php

function cg_topreviews_metaboxes( $meta_boxes ) {
    $prefix = '_cg_'; // Prefix for all fields
    $meta_boxes['topreview'] = array(
        'id' => 'topreview_metabox',
        'title' => __( 'Top Review Details', 'commercegurus' ),
        'pages' => array( 'topreviews' ), // post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        'fields' => array(
            array(
                'name' => __( 'Name of person providing the testimonial', 'commercegurus' ),
                'desc' => __( 'This can also be anonymous', 'commercegurus' ),
                'id' => $prefix . 'topreview_name',
                'type' => 'text'
            ),
            array(
                'name' => __( 'Organization name', 'commercegurus' ),
                'desc' => __( 'Enter the organization your clients works for.', 'commercegurus' ),
                'id' => $prefix . 'topreview_org_name',
                'type' => 'text'
            ),
        ),
    );

    return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'cg_topreviews_metaboxes' );
