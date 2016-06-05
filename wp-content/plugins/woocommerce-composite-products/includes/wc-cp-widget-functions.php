<?php
/**
 * Composite Widget Functions.
 *
 * Register a widget that displays a Composite configuration summary in Multi-page mode.
 *
 * @version  3.6.3
 * @since    3.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Widgets.
 *
 * @since 3.0.0
 */
function wc_cp_register_widgets() {

	// Include widget classes.
	include_once( 'widgets/class-wc-widget-composite.php' );

	register_widget( 'WC_Widget_Composite' );
}

/**
 * Adds a filter to conditionally remove the Composite Summary Widget instead of short-circuiting the widget() callback.
 *
 * @since 3.6.0
 */
function wc_cp_add_widgets_filter() {
	if ( WC_Widget_Composite::is_active() ) {
		add_filter( 'sidebars_widgets', 'wc_cp_remove_composite_summary_widget' );
	}
}

/**
 * Conditionally removes the Composite Summary Widget.
 *
 * @since 3.6.0
 */
function wc_cp_remove_composite_summary_widget( $widget_areas ) {

	$filtered_widget_data = array();

	foreach ( $widget_areas as $area_id => $widgets ) {

		$filtered_widget_data[ $area_id ] = array();

		if ( is_array( $widgets ) ) {

			foreach ( $widgets as $widget_id ) {

				$add = true;

				if ( 0 === strpos( $widget_id, WC_Widget_Composite::BASE_ID ) ) {

					$is_summary_widget_visible = WC_CP()->api->cache_get( 'cp_summary_widget_visible' );

					if ( null === $is_summary_widget_visible ) {
						$is_summary_widget_visible = WC_Widget_Composite::is_visible();
						WC_CP()->api->cache_set( 'cp_summary_widget_visible', $is_summary_widget_visible );
					}

					if ( ! $is_summary_widget_visible ) {
						$add = false;
					}
				}

				if ( $add ) {
					$filtered_widget_data[ $area_id ][] = $widget_id;
				}
			}
		}
	}

	return $filtered_widget_data;
}

add_action( 'widgets_init', 'wc_cp_register_widgets', 11 );

if ( ! is_admin() ) {
	add_action( 'wp', 'wc_cp_add_widgets_filter' );
}
