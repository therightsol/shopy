<?php

$labels = array(
    'name' => __( 'Logos', 'commercegurus' ),
    'singular_name' => __( 'Logo', 'commercegurus' ),
    'add_new' => __( 'Add New', 'commercegurus' ),
    'add_new_item' => __( 'Add New Logo', 'commercegurus' ),
    'edit_item' => __( 'Edit Logo', 'commercegurus' ),
    'new_item' => __( 'New Logo', 'commercegurus' ),
    'view_item' => __( 'View Logo', 'commercegurus' ),
    'search_items' => __( 'Search Logos', 'commercegurus' ),
    'not_found' => __( 'No Logos found', 'commercegurus' ),
    'not_found_in_trash' => __( 'No Logos in trash', 'commercegurus' ),
    'parent_item_colon' => ''
);

$args = array(
    'labels' => $labels,
    'public' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => false,
    'rewrite' => array( 'slug' => 'logos' ),
    'show_ui' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 35,
    'menu_icon' => 'dashicons-welcome-view-site',
    'has_archive' => false,
    'supports' => array( 'title', 'editor' )
);

register_post_type( 'logos', $args );

function cg_logos_edit_columns( $columns ) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => __( 'Logo Name', 'commercegurus' ),
        "date" => __( 'Date', 'commercegurus' )
    );
    return $columns;
}

add_filter( 'manage_edit-logos_columns', 'cg_logos_edit_columns' );
