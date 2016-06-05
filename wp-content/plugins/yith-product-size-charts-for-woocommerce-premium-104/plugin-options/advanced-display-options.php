<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

$custom_attributes = defined( 'YITH_WCPSC_PREMIUM' ) ? '' : array( 'disabled' => 'disabled' );

// Create Array for badge select
$carts_array = array();
$args = ( array(
    'posts_per_page' => -1,
    'post_type'      => 'yith-wcpsc-wc-chart',
    'orderby'        => 'title',
    'order'          => 'ASC',
    'post_status'    => 'publish'
) );
$carts = get_posts( $args );
if ( !empty( $carts ) ) {
    foreach ( $carts as $c ) {
        $carts_array[ $c->ID ] = $c->post_title;
    }
}
//get categories of products and create an array of catagories
$cat_args = array(
    'type'         => 'post',
    'orderby'      => 'name',
    'order'        => 'ASC',
    'hide_empty'   => 0,
    'hierarchical' => 1,
    'taxonomy'     => 'product_cat'
);

$list_category_opt = array(
    'advanced-options' => array(
        'title' => __( 'Advanced Options', 'yith-wcpsc' ),
        'type'  => 'title',
        'desc'  => '',
        'id'    => 'yith-wcpsc-advanced-options'
    ),
    'charts-for-all-product' => array(
        'name'              => __( 'Charts for all products', 'yith-wcpsc' ),
        'type'              => 'multiselect',
        'desc'              => __( 'Select the Product Size Charts you want to display in all products', 'yith-wcpsc' ),
        'id'                => 'yith-wcpsc-category-charts-all-products',
        'options'           => $carts_array,
        'custom_attributes' => $custom_attributes,
        'class'             => 'yith-chosen'
    ),
    'advanced-options-end' => array(
        'type' => 'sectionend',
        'id'    => 'yith-wcpsc-advanced-options'
    ),
    'category-options' => array(
        'title' => __( 'Category Options', 'yith-wcpsc' ),
        'type'  => 'title',
        'desc'  => '',
        'id'    => 'yith-wcpsc-category-options'
    )
);

$categories = get_categories( $cat_args );
if ( !empty( $categories ) ) {
    foreach ( $categories as $cat ) {
        $id = $cat->term_id;
        $name = $cat->name;

        $list_category_opt[ 'category-charts-' . $id ] = array(
            'name'              => $name,
            'type'              => 'multiselect',
            'desc'              => sprintf( __( 'Select the Product Size Charts you want to display in all products belonging to category %s', 'yith-wcpsc' ), $name ),
            'id'                => 'yith-wcpsc-category-charts-' . $id,
            'options'           => $carts_array,
            'custom_attributes' => $custom_attributes,
            'class'             => 'yith-chosen'
        );
    }
}

$list_category_opt[ 'category-options-end' ] = array(
    'type' => 'sectionend',
    'id'   => 'yith-wcpsc-category-options'
);

$settings = array(
    'advanced-display' => $list_category_opt
);

return $settings;