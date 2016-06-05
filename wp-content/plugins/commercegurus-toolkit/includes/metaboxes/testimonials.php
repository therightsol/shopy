<?php

function cg_testimonial_metaboxes( $meta_boxes ) {
    $prefix = '_cg_'; // Prefix for all fields
    $meta_boxes['testimonial'] = array(
        'id' => 'testimonial_metabox',
        'title' => __( 'Testimonial Details', 'commercegurus' ),
        'pages' => array( 'testimonials' ), // post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        'fields' => array(
            array(
                'name' => __( 'Name of person providing the testimonial', 'commercegurus' ),
                'desc' => __( 'This can also be anonymous', 'commercegurus' ),
                'id' => $prefix . 'testimonial_name',
                'type' => 'text'
            ),
            array(
                'name' => __( 'Organization name', 'commercegurus' ),
                'desc' => __( 'Enter the organization your clients works for.', 'commercegurus' ),
                'id' => $prefix . 'testimonial_org_name',
                'type' => 'text'
            ),
            array(
                'name' => __( 'Testimonial Face Profile Image', 'commercegurus' ),
                'desc' => __( 'Upload an image or enter a URL.', 'commercegurus' ),
                'id' => $prefix . 'testimonial_image',
                'type' => 'file',
            ),
        ),
    );

    return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'cg_testimonial_metaboxes' );
