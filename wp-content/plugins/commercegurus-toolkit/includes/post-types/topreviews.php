<?php

$labels = array(
    'name' => __( 'Top Reviews', 'commercegurus' ),
    'singular_name' => __( 'Top Review', 'commercegurus' ),
    'add_new' => __( 'Add New', 'commercegurus' ),
    'add_new_item' => __( 'Add New Top Review', 'commercegurus' ),
    'edit_item' => __( 'Edit Top Review', 'commercegurus' ),
    'new_item' => __( 'New Top Review', 'commercegurus' ),
    'view_item' => __( 'View Top Review', 'commercegurus' ),
    'search_items' => __( 'Search Top Reviews', 'commercegurus' ),
    'not_found' => __( 'No Top Reviews found', 'commercegurus' ),
    'not_found_in_trash' => __( 'No Top Reviews in trash', 'commercegurus' ),
    'parent_item_colon' => ''
);

$args = array(
    'labels' => $labels,
    'public' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => false,
    'rewrite' => array( 'slug' => 'topreviews' ),
    'show_ui' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 35,
    'menu_icon' => 'dashicons-groups',
    'has_archive' => false,
    'supports' => array( 'title', 'editor' )
);

register_post_type( 'topreviews', $args );

function cg_topreviews_edit_columns( $columns ) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => __( 'Top Review Title', 'commercegurus' ),
        "date" => __( 'Date', 'commercegurus' )
    );
    return $columns;
}

add_filter( 'manage_edit-topreviews_columns', 'cg_topreviews_edit_columns' );
