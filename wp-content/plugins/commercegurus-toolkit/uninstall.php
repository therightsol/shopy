<?php
/**
 * Uninstall CGToolKit
 *
 * @package CGToolKit
 * @since  1.0
 * @author CommerceGurus
 */

/* Make sure we're actually uninstalling the plugin. */
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	wp_die( sprintf( __( '%s should only be called when uninstalling the plugin.', 'commercegurus' ), '<code>' . __FILE__ . '</code>' ) );


// Delete Plugin Options
delete_option( 'cgtoolkit_options' );