<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Multivendor Compatibility Class
 *
 * @class   YITH_WCPSC_Multivendor_Compatibility
 * @package Yithemes
 * @since   1.0.0
 * @author  Yithemes
 *
 */
class YITH_WCPSC_Multivendor_Compatibility {

    /**
     * Single instance of the class
     *
     * @var \YITH_WCPSC_Multivendor_Compatibility
     * @since 1.0.0
     */
    protected static $instance;

    /**
     * @var string The vendor taxonomy name
     */
    protected $_vendor_taxonomy_name = '';

    private $_size_charts_post_type = 'yith-wcpsc-wc-chart';

    /**
     * Returns single instance of the class
     *
     * @return \YITH_WCPSC_Multivendor_Compatibility
     * @since 1.0.0
     */
    public static function get_instance() {
        $self = __CLASS__;

        if ( is_null( $self::$instance ) ) {
            $self::$instance = new $self;
        }

        return $self::$instance;
    }

    /**
     * Constructor
     *
     * @access public
     * @since  1.0.0
     */
    public function __construct() {
        /* Add/Remove Membership capabilities to vendors */
        add_filter( 'yith_wcmv_size_charts_caps', array( $this, 'get_size_charts_cap' ) );

        if ( !YITH_WCPSC_Compatibility::has_multivendor_plugin() ) {
            return;
        }
        $this->_vendor_taxonomy_name = YITH_Vendors()->get_taxonomy_name();
        if ( is_admin() ) {
            if ( $this->is_enabled_management_for_vendors() ) {
                /* add Size Charts to vendor's allowed post types */
                add_filter( 'yith_wpv_vendors_allowed_post_types', array( $this, 'add_allowed_post_types_for_vendors' ) );

                /* Add Size Charts Menu in Vendors Admin */
                add_filter( 'yith_wpv_vendor_menu_items', array( $this, 'add_size_charts_to_vendors_admin_menu' ) );
            }
            /* Add Vendor taxonomy to Size Charts */
            add_filter( 'yith_wcmv_register_taxonomy_object_type', array( $this, 'add_taxonomy_object_types' ) );

            /* Filter Vendors Size Charts */
            add_action( 'pre_get_posts', array( $this, 'filter_vendor_size_charts' ) );
            add_filter( 'wp_count_posts', array( $this, 'vendor_count_posts' ), 10, 3 );
            add_action( 'save_post', array( $this, 'add_vendor_taxonomy_to_size_charts' ), 10, 2 );
        }
    }

    /**
     * check if vendors can manage Size Charts Plugin
     *
     * @return   bool
     * @author   Leanza Francesco <leanzafrancesco@gmail.com>
     * @since    1.0
     */
    public function is_enabled_management_for_vendors() {
        return 'yes' == get_option( 'yith_wpv_vendors_option_size_charts_management', 'no' );
    }

    /**
     * return size charts capabilities
     *
     * @param array $caps The capabilities for size charts
     *
     * @return   array
     * @author   Leanza Francesco <leanzafrancesco@gmail.com>
     * @since    1.0
     */
    public function get_size_charts_cap( $caps ) {

        $capability_type = 'size_chart';
        $caps            = array(
            "edit_{$capability_type}",
            "read_{$capability_type}",
            "delete_{$capability_type}",
            "edit_{$capability_type}s",
            "edit_others_{$capability_type}s",
            "publish_{$capability_type}s",
            "read_private_{$capability_type}s",
            "read",
            "delete_{$capability_type}s",
            "delete_private_{$capability_type}s",
            "delete_published_{$capability_type}s",
            "delete_others_{$capability_type}s",
            "edit_private_{$capability_type}s",
            "edit_published_{$capability_type}s",
            "edit_{$capability_type}s"
        );

        return $caps;
    }

    /**
     * Filter Vendors Size Charts
     *
     * @param $query object The query object
     *
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     * @since  1.0.0
     */
    public function filter_vendor_size_charts( $query ) {
        if ( isset( $query->query[ 'post_type' ] ) && $query->query[ 'post_type' ] == $this->_size_charts_post_type && !current_user_can( 'edit_users' ) )
            $query->set( 'author', get_current_user_id() );
    }

    /**
     * Filter the post count for vendor
     *
     * @author   Andrea Grillo <andrea.grillo@yithemes.com>
     *
     * @param $counts   The post count
     * @param $type     Post type
     * @param $perm     The read permission
     *
     * @return arr  Modified request
     * @since    1.0
     * @use      wp_post_count action
     */
    public function vendor_count_posts( $counts, $type, $perm ) {
        $vendor = yith_get_vendor( 'current', 'user' );

        if ( !$vendor || !$type == $this->_size_charts_post_type || $vendor->is_super_user() || !$vendor->is_user_admin() ) {
            return $counts;
        }

        /**
         * Get a list of post statuses.
         */
        $stati = get_post_stati();

        // Update count object
        foreach ( $stati as $status ) {
            $posts           = $this->get_vendor_posts( $vendor, $type, "post_status=$status" );
            $counts->$status = count( $posts );
        }

        return $counts;
    }

    /**
     * Get query results of this vendor
     *
     * @param YITH_Vendor $vendor    the vendor
     * @param string      $post_type the post type to get
     * @param array       $extra     More arguments to append
     *
     * @return array
     *
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     */
    public function get_vendor_posts( $vendor, $post_type, $extra = array() ) {
        $args = wp_parse_args( $extra, array(
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            'fields'         => 'ids'
        ) );

        $args = $this->get_vendor_query_posts_args( $vendor, $args );

        return get_posts( $args );
    }

    /**
     * Return the arguments to make a query for the posts of this vendor
     *
     * @param YITH_Vendor $vendor the vendor
     * @param array       $extra  More arguments to append
     *
     * @return array
     *
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     */
    public function get_vendor_query_posts_args( $vendor, $extra = array() ) {
        return wp_parse_args( $extra, array(
            'tax_query' => array(
                array(
                    'taxonomy' => $vendor::$taxonomy,
                    'field'    => 'id',
                    'terms'    => $vendor->id
                )
            )
        ) );
    }


    /**
     * Add vendor taxonomy to size charts
     *
     * @param       int $post_id Product ID
     *
     * @author      Andrea Grillo <andrea.grillo@yithemes.com>
     * @return      void
     * @since       1.0
     * @use         save_post action
     */
    public function add_vendor_taxonomy_to_size_charts( $post_id, $post ) {
        $vendor = yith_get_vendor( 'current', 'user' );

        if ( $vendor->is_valid() && in_array( $post->post_type, array( $this->_size_charts_post_type ) ) && current_user_can( 'edit_post', $post_id ) && $vendor->has_limited_access() ) {
            wp_set_object_terms( $post_id, $vendor->term->slug, $vendor->term->taxonomy, false );
        }
    }

    /**
     * Add Vendor taxonomy to charts
     *
     * @param  array $types array of object types associated to vendor taxonomy. Default: 'product'
     *
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     * @return array
     */
    public function add_taxonomy_object_types( $types ) {
        $my_types = array( $this->_size_charts_post_type );
        $types    = array_merge( $types, $my_types );

        return array_unique( $types );
    }

    /**
     * Add Size Charts to Admin Menu of Vendors
     *
     * @param array $menu_items array of menu items allowed for Vendors
     *
     * @return array
     *
     * @access public
     * @since  1.0.0
     */
    public function add_size_charts_to_vendors_admin_menu( $menu_items ) {
        $menu_items = array_merge( $menu_items, array( 'edit.php?post_type=' . $this->_size_charts_post_type ) );

        return $menu_items;
    }

    /**
     * Add Allowed Post Types for Vendors
     *
     * @param array $allowed_post_types the allowed post types for Vendors; default are 'product' and 'shop_coupon'
     *
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     * @since  1.0.0
     * @return array
     */
    public function add_allowed_post_types_for_vendors( $allowed_post_types ) {
        $allowed_post_types[] = $this->_size_charts_post_type;

        return $allowed_post_types;
    }

}

/**
 * Unique access to instance of YITH_WCPSC_Multivendor_Compatibility class
 *
 * @return YITH_WCPSC_Multivendor_Compatibility
 * @since 1.0.0
 */
function YITH_WCPSC_Multivendor_Compatibility() {
    return YITH_WCPSC_Multivendor_Compatibility::get_instance();
}

YITH_WCPSC_Multivendor_Compatibility();