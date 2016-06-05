<?php

    $labels = array(
        'name' => _x('Showcase Categories', 'taxonomy general name', 'commercegurus'),
        'singular_name' => _x('Showcase Category', 'taxonomy singular name', 'commercegurus'),
        'search_items' => __('Search Showcase Categories', 'commercegurus'),
        'popular_items' => __('Popular Showcase Categories', 'commercegurus'),
        'all_items' => __('All Showcase Categories', 'commercegurus'),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit Showcase Category', 'commercegurus'),
        'update_item' => __('Update Showcase Category', 'commercegurus'),
        'add_new_item' => __('Add New Showcase Category', 'commercegurus'),
        'new_item_name' => __('New Showcase Category Name', 'commercegurus'),
        'separate_items_with_commas' => __('Separate showcase categories with commas', 'commercegurus'),
        'add_or_remove_items' => __('Add or remove showcase categories', 'commercegurus'),
        'choose_from_most_used' => __('Choose from the most used showcase categories', 'commercegurus'),
    );

    register_taxonomy('cg_showcasecategory', 'showcases', array(
        'label' => __('Showcase Category', 'commercegurus'),
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'showcase-category'),
    ));

?>