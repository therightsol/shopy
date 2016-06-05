<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Compatibility Class
 *
 * @class   YITH_WCMV_Addons
 * @package Yithemes
 * @since   1.0.0
 * @author  Yithemes
 *
 */
class YITH_WCMV_Addons {

    /**
     * Single instance of the class
     *
     * @var \YITH_WCMV_Addons
     * @since 1.0.0
     */
    protected static $instance;

    /**
     * Plugins Supported Array
     *
     * @var array
     * @since 1.0.0
     */
    public $plugins;

    /**
     * Main Frontpage Instance
     *
     * @var YITH_WCMV_Addons_Compatibility
     * @since 1.0
     */
    public $compatibility;

    /**
     * Returns single instance of the class
     *
     * @return \YITH_WCMV_Addons
     * @since 1.0.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Constructor
     *
     * @access public
     * @since  1.0.0
     */
    public function __construct() {
        /* Custom option type */
        add_action( 'woocommerce_admin_field_yith_premium_addons', array( $this, 'premium_addons_field' ) );

        add_action( 'init', array( $this, 'load_compatibility_class' ) );
        $this->plugins = require_once( 'compatibility/plugins-list.php' );
    }

    /**
     * Load YITH_WCMV_Addons_Compatibility Class
     */
    public function load_compatibility_class(){
        if( is_admin() ) {
            $this->compatibility = YITH_WCMV_Addons_Compatibility::get_instance();
        }
    }

    /**
     * Check if user has YITH XXX Premium plugin
     *
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     * @since  1.0
     * @return bool
     */
    public function has_plugin( $plugin_name ) {
        $has_plugin = false;

        if( ! empty( $this->plugins[ $plugin_name ] ) ){
            $plugin = $this->plugins[ $plugin_name ];
            if(
                defined( $plugin['premium'] ) && constant( $plugin['premium'] ) &&
                defined( $plugin['installed_version'] ) && constant( $plugin['installed_version'] ) &&
                version_compare( constant( $plugin['installed_version'] ), $plugin['min_version'], $plugin['compare'] )
            ){
                $has_plugin = true;
            }
        }
        return $has_plugin;
    }

     /**
     * Get plughin option description
     *
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     * @since  1.0
     * @return bool
     */
    public function get_option_description( $plugin_name ){
        return $this->plugins[ $plugin_name ]['option_desc'];
    }

     /**
     * Get plugin landing page URI
     *
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     * @since  1.0
     * @return bool
     */
    public function get_plugin_landing_uri( $plugin_name, $context = 'uri' ){
        $plugin_link = '';
        if( 'display' == $context ){
            $plugin_link = $this->has_plugin( $plugin_name ) ? '' : sprintf( '<span class="yith-wcmv-required-plugin">(%s <a href="%s" target="_blank">%s</a> %s - %s %s %s).</span>',
            _x( 'Needs', 'Admin: means needs YITH xxx plugin to works', 'yith_wc_product_vendors' ),
            $this->plugins[ $plugin_name ]['landing_uri'],
            $this->plugins[ $plugin_name ]['name'],
            _x( 'plugin', 'Admin: means needs YITH xxx plugin to works', 'yith_wc_product_vendors' ),
            _x( 'version', 'means: plugin version', 'yith_wc_product_vendors' ),
            $this->plugins[ $plugin_name ]['min_version'],
            __( 'or greather', 'means: min version xxx or greater', 'yith_wc_product_vendors' )
        );
        }

        elseif( 'uri' == $context ){
            $plugin_link = $this->plugins[ $plugin_name ]['landing_uri'];
        }

        return $plugin_link;
    }

    /**
     * Premium addons fields
     *
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     * @since  1.0
     * @return bool
     */
    public function premium_addons_field( $value ) {
        $visbility_class = isset( $value['class'] ) ? $value['class'] : array(); ?>
        <tr valign="top" class="<?php echo esc_attr( implode( ' ', $visbility_class ) ); ?>">
            <th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <?php if ( !empty( $value['title'] ) ) : ?>
                        <legend class="screen-reader-text"><span><?php echo esc_html( $value['title'] ) ?></span>
                        </legend>
                    <?php endif; ?>
                    <label for="<?php echo $value['id'] ?>">
                        <?php echo $value['desc'] ?>
                        <?php printf( '<a href="%s">%s > %s > %s</a>', $value['settings_tab']['uri'], __( 'YIT Plugins', 'yith_wc_product_vendors' ), $value['settings_tab']['plugin_name'], $value['settings_tab']['desc'] );?>
                    </label>
                </fieldset>
            </td>
        </tr>
        <?php
    }
}