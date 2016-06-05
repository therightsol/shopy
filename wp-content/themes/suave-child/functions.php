<?php
/**
 * CommerceGurus Child theme functions
 */

// Dequeue parent css
function cg_dequeue_parent_css() {
	wp_dequeue_style( 'cg-commercegurus' ); 
	wp_dequeue_style( 'cg-responsive' ); 
}

add_action( 'wp_enqueue_scripts', 'cg_dequeue_parent_css', 100 );

// Reorder parent css
function cg_reorder_child_css() {
	wp_dequeue_style( 'cg-style' );
	wp_register_style( 'cg-child-styles', get_stylesheet_uri() );
	wp_enqueue_style( 'cg-commercegurus' );
	wp_enqueue_style( 'cg-responsive' );
	wp_enqueue_style( 'cg-child-styles' );
}

add_action( 'wp_enqueue_scripts', 'cg_reorder_child_css', 101 );

?>