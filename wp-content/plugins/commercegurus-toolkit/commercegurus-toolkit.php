<?php

/**
 * Plugin Name: CommerceGurus Toolkit
 * Plugin URI: https://wordpress.org/plugins/commercegurus-toolkit/
 * Description: A suite of custom post types for CommerceGurus themes.
 * Version: 1.2.8
 * Author: CommerceGurus
 * Author URI: http://commercegurus.com
 * License: GPL2
 * Requires at least: 4.1
 * Tested up to: 4.1
 * Credits to CodeStag for providing StagTools to the community which formed the basis for the initial inspiration for this plugin - https://github.com/mauryaratan/stagtools/
 *
 * Text Domain: commercegurus
 * Domain Path: /languages/
 */
if ( !defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

if ( !class_exists( 'CGToolKit' ) ) {
    /**
     * Setup sortable gallery custom CMB field type.
     * Credits to mustardBees for most of this https://github.com/mustardBees/cmb-field-gallery
     *
     * @return void
     */
    define( 'CG_GALLERY_URL', plugin_dir_url( __FILE__ ) );
    define( 'CG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

    /**
     * Render field
     */
    function cg_gallery_field( $field, $meta ) {
        wp_enqueue_script( 'cg_gallery_init', CG_GALLERY_URL . 'js/script.js', array( 'jquery' ), null );

        if ( !empty( $meta ) ) {
            $meta = implode( ',', $meta );
        }
        echo '<div class="cg-gallery">';
        echo '	<input type="hidden" id="' . $field['id'] . '" name="' . $field['id'] . '" value="' . $meta . '" />';
        echo '	<input type="button" class="button" value="' . (!empty( $field['button'] ) ? $field['button'] : 'Manage gallery' ) . '" />';
        echo '</div>';

        if ( !empty( $field['desc'] ) ) {
            echo '<p class="cmb_metabox_description">' . $field['desc'] . '</p>';
        }
    }

    add_filter( 'cmb_render_cg_gallery', 'cg_gallery_field', 10, 2 );

    /**
     * Split CSV string into an array of values
     */
    function cg_gallery_field_sanitise( $meta_value, $field ) {
        if ( empty( $meta_value ) ) {
            $meta_value = '';
        } else {
            $meta_value = explode( ',', $meta_value );
        }

        return $meta_value;
    }

    function cg_folio_recentthumb( $postid, $related = FALSE ) {
        $thumb = get_the_post_thumbnail( $postid, 'showcase-4col', array( 'class' => 'cg-thumbnail' ) );

        if ( $thumb == '' ) {
            $thumb = '<img src="' . $thumb . '" alt="' . get_the_title() . '" class="cg-thumbnail" />';
        }

        $output = '<span class="cg-folio-img"><a title="' . get_the_title( $postid ) . '" href="' . get_permalink( $postid ) . '">' . $thumb . '</a></span>';

        echo $output;
    }

    function cg_showcasethumb( $postid, $related = FALSE ) {
        if ( (is_page_template( 'template-showcase-4col.php' )) || (is_page_template( 'single-tf_showcase.php' )) ) {
            $thumb = get_the_post_thumbnail( $postid, 'showcase-4col', array( 'class' => 'cg-thumbnail' ) );

            if ( $thumb == '' ) {
                $thumb = '<img src="' . $thumb . '" alt="' . get_the_title() . '" class="cg-thumbnail" />';
            }

            $output = '<span class="cg-folio-img"><a title="' . get_the_title( $postid ) . '" href="' . get_permalink( $postid ) . '">' . $thumb . '</a></span>';

            echo $output;
        } elseif ( is_page_template( 'template-showcase-3col.php' ) ) {
            $thumb = get_the_post_thumbnail( $postid, 'showcase-3col', array( 'class' => 'cg-thumbnail' ) );
            if ( $thumb == '' ) {
                $thumb = '<img src="' . $thumb . '" alt="' . get_the_title() . '" class="cg-thumbnail" />';
            }

            $output = '<span class="cg-folio-img"><a title="' . get_the_title( $postid ) . '" href="' . get_permalink( $postid ) . '">' . $thumb . '</a></span>';

            echo $output;
        } elseif ( is_page_template( 'template-showcase-2col.php' ) ) {
            $thumb = get_the_post_thumbnail( $postid, 'showcase-2col', array( 'class' => 'cg-thumbnail' ) );
            if ( $thumb == '' ) {
                $thumb = '<img src="' . $thumb . '" alt="' . get_the_title() . '" class="cg-thumbnail" />';
            }

            $output = '<span class="cg-folio-img"><a title="' . get_the_title( $postid ) . '" href="' . get_permalink( $postid ) . '">' . $thumb . '</a></span>';

            echo $output;
        }
    }

    /**
     * Main CGToolKit Class
     *
     * @package CGToolKit
     * @version 1.0
     * @author Colm Troy
     * @link http://www.commercegurus.com
     */
    class CGToolKit {

        /**
         * @var string
         */
        public $version = '1.0';

        /**
         * @var string
         */
        public $plugin_url;

        /**
         * @var string
         */
        public $plugin_path;

        /**
         * @var string
         */
        public $template_url;

        /**
         * CGToolKit Constructor.
         *
         * @access public
         * @return void
         */
        public function __construct() {
            // Define version constant
            define( 'CGTOOLKIT_VERSION', $this->version );
            // Hooks
            //add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
            add_action( 'init', array( &$this, 'init' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        }

        public function enqueue_styles() {
            wp_enqueue_style( 'cg_toolkit_styles', CG_PLUGIN_URL . 'css/cg_toolkit.css' );
        }

        /**
         * Add custom links on plugins page.
         *
         * @access public
         * @param mixed $links
         * @return void
         */
        public function action_links( $links ) {
            $plugin_links = array(
                '<a href="' . admin_url( 'options-general.php?page=commercegurustoolkit' ) . '">' . __( 'Settings', 'commercegurus' ) . '</a>'
            );

            return array_merge( $plugin_links, $links );
        }

        /**
         * Initialise CommerceGurus Toolkit .
         *
         * @return void
         */
        function init() {
            $this->cg_load_textdomain();

            add_filter( 'body_class', array( &$this, 'body_class' ) );

            /**
             * Include the Custom Post Types, Taxonomies, Custom fields and Shortcodes
             */
            // Post Types
            include_once( 'includes/post-types/testimonials.php');
            include_once( 'includes/post-types/topreviews.php');
            include_once( 'includes/post-types/logos.php');
            include_once( 'includes/post-types/shopannouncements.php');
            include_once( 'includes/post-types/showcases.php');

            // Taxonomies
            include_once( 'includes/taxonomies/showcasecategory.php');

            // Custom fields
            include_once( 'includes/metaboxes/testimonials.php');
            include_once( 'includes/metaboxes/topreviews.php');
            include_once( 'includes/metaboxes/logos.php');
            include_once( 'includes/metaboxes/shopannouncements.php');
            include_once( 'includes/metaboxes/showcases.php');

            // Shortcodes

            include_once( 'includes/shortcodes/google_maps.php');
            include_once( 'includes/shortcodes/woocommerce_product_sliders.php');
            include_once( 'includes/shortcodes/latest_posts.php');
            include_once( 'includes/shortcodes/promo_message_box.php');
            include_once( 'includes/shortcodes/video_banner.php');
            include_once( 'includes/shortcodes/general-markup.php');
            include_once( 'includes/shortcodes/testimonials.php');
            include_once( 'includes/shortcodes/topreviews.php');
            include_once( 'includes/shortcodes/logos.php');
            include_once( 'includes/shortcodes/portfolio_slider.php');
            include_once( 'includes/shortcodes/content_strips.php');

            // Initialize the metabox class
            add_action( 'init', 'cg_initialize_cmb_meta_boxes', 9999 );

            function cg_initialize_cmb_meta_boxes() {
                if ( !class_exists( 'cmb_Meta_Box' ) ) {
                    require_once( 'includes/metaboxes/cmb/init.php' );
                }
            }

        }

        /**
         * Setup localisation.
         *
         * @return void
         */
        function cg_load_textdomain() {
            // Set filter for plugin's languages directory
            $cgtoolkit = '';
            $cgtoolkit_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
            $cgtoolkit_lang_dir = apply_filters( 'cgtoolkit_languages_directory', $cgtoolkit_lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), 'commercegurus' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'commercegurus', $locale );

            // Setup paths to current locale file
            $mofile_local = $cgtoolkit_lang_dir . $mofile;
            $mofile_global = WP_LANG_DIR . '/commercegurus-toolkit/' . $mofile;

            if ( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/commercegurus-toolkit folder
                load_textdomain( 'commercegurus', $mofile_global );
            } elseif ( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/commercegurus-toolkit/languages/ folder
                load_textdomain( 'commercegurus', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'commercegurus', false, $cgtoolkit_lang_dir );
            }
        }

        /**
         * Plugin path.
         *
         * @return string Plugin path
         */
        public function plugin_path() {
            if ( $this->plugin_path ) {
                return $this->plugin_path;
            }

            return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
        }

        /**
         * Plugin url.
         *
         * @return string Plugin url
         */
        public function plugin_url() {
            if ( $this->plugin_url ) {
                return $this->plugin_url;
            }

            return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
        }

        /**
         * Add cgtoolkit to body class for use on frontend.
         *
         * @since 1.0.0
         * @return array $classes List of classes
         */
        public function body_class( $classes ) {
            $classes[] = 'cgtoolkit';
            return $classes;
        }

    }

    $GLOBALS['cgtoolkit'] = new CGToolKit();
}

/**
 * Flush the rewrite rules on activation
 */
function cgtoolkit_activation() {
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'cgtoolkit_activation' );

/**
 * Also flush the rewrite rules on deactivation
 */
function cgtoolkit_deactivation() {
    flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'cgtoolkit_activation' );


// Hide LayerSlider nag - this causes so much confusion to customers who think they need to enter a purchase code when they don't that we're forced to remove this notification for now until LayerSlider give us a better way of managing this.
function remove_ls_nag() {
    if ( is_plugin_active( 'LayerSlider/layerslider.php' ) ) {
        //plugin is activated
        remove_action('after_plugin_row_'.LS_PLUGIN_BASE, 'layerslider_plugins_purchase_notice', 10, 3 );
    }     
}

add_action( 'admin_init', 'remove_ls_nag' );