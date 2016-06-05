<?php

$labels = array(
    'name' => __( 'Announcements', 'commercegurus' ),
    'singular_name' => __( 'Announcement', 'commercegurus' ),
    'add_new' => __( 'Add New', 'commercegurus' ),
    'add_new_item' => __( 'Add New Announcement', 'commercegurus' ),
    'edit_item' => __( 'Edit Announcement', 'commercegurus' ),
    'new_item' => __( 'New Announcement', 'commercegurus' ),
    'view_item' => __( 'View Announcement', 'commercegurus' ),
    'search_items' => __( 'Search Announcements', 'commercegurus' ),
    'not_found' => __( 'No Announcements found', 'commercegurus' ),
    'not_found_in_trash' => __( 'No Announcements in trash', 'commercegurus' ),
    'parent_item_colon' => ''
);

$args = array(
    'labels' => $labels,
    'public' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => false,
    'rewrite' => array( 'slug' => 'shopannouncements' ),
    'show_ui' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 35,
    'menu_icon' => 'dashicons-megaphone',
    'has_archive' => false,
    'supports' => array( 'title', 'editor' )
);

register_post_type( 'shopannouncements', $args );

function cg_shopannouncements_edit_columns( $columns ) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => __( 'Shop announcement Title', 'commercegurus' ),
        "date" => __( 'Date', 'commercegurus' )
    );
    return $columns;
}

add_filter( 'manage_edit-shopannouncements_columns', 'cg_shopannouncements_edit_columns' );
