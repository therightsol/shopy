<?php

$labels = array(
    'name' => __( 'Testimonials', 'commercegurus' ),
    'singular_name' => __( 'Testimonial', 'commercegurus' ),
    'add_new' => __( 'Add New', 'commercegurus' ),
    'add_new_item' => __( 'Add New Testimonial', 'commercegurus' ),
    'edit_item' => __( 'Edit Testimonial', 'commercegurus' ),
    'new_item' => __( 'New Testimonial', 'commercegurus' ),
    'view_item' => __( 'View Testimonial', 'commercegurus' ),
    'search_items' => __( 'Search Testimonials', 'commercegurus' ),
    'not_found' => __( 'No Testimonials found', 'commercegurus' ),
    'not_found_in_trash' => __( 'No Testimonials in trash', 'commercegurus' ),
    'parent_item_colon' => ''
);

$args = array(
    'labels' => $labels,
    'public' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => false,
    'rewrite' => array( 'slug' => 'testimonials' ),
    'show_ui' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 35,
    'menu_icon' => 'dashicons-format-quote',
    'has_archive' => false,
    'supports' => array( 'title', 'editor' )
);

register_post_type( 'testimonials', $args );

function cg_testimonials_edit_columns( $columns ) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => __( 'Testimonial Title', 'commercegurus' ),
        "date" => __( 'Date', 'commercegurus' )
    );
    return $columns;
}

add_filter( 'manage_edit-testimonials_columns', 'cg_testimonials_edit_columns' );
