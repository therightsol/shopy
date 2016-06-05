<?php

$labels = array(
    'name' => __( 'Showcases', 'commercegurus' ),
    'singular_name' => __( 'Showcase', 'commercegurus' ),
    'rewrite' => array( 'slug' => __( 'showcase', 'commercegurus' ) ),
    'add_new' => _x( 'Add New', 'showcase', 'commercegurus' ),
    'add_new_item' => __( 'Add New Showcase', 'commercegurus' ),
    'edit_item' => __( 'Edit Showcase', 'commercegurus' ),
    'new_item' => __( 'New Showcase', 'commercegurus' ),
    'view_item' => __( 'View Showcase', 'commercegurus' ),
    'search_items' => __( 'Search Showcases', 'commercegurus' ),
    'not_found' => __( 'No showcases found', 'commercegurus' ),
    'not_found_in_trash' => __( 'No showcases found in Trash', 'commercegurus' ),
    'parent_item_colon' => ''
);

$args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'showcase' ),
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => null,
    'menu_icon' => 'dashicons-welcome-view-site',
    'supports' => array( 'title', 'excerpt', 'editor', 'thumbnail' )
);

register_post_type( 'showcases', $args );

function cg_showcases_edit_columns( $columns ) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => __( 'Showcase Title', 'commercegurus' ),
        "date" => __( 'Date', 'commercegurus' )
    );
    return $columns;
}

add_filter( 'manage_edit-showcases_columns', 'cg_showcases_edit_columns' );
