<?php
/**
 * Main class
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce Zoom Magnifier
 * @version 1.1.2
 */

if ( ! defined ( 'YITH_WCMG' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists ( 'YITH_WooCommerce_Zoom_Magnifier_Premium' ) ) {
    /**
     * YITH WooCommerce Zoom Magnifier Premium
     *
     * @since 1.0.0
     */
    class YITH_WooCommerce_Zoom_Magnifier_Premium extends YITH_WooCommerce_Zoom_Magnifier {

        /**
         * Constructor
         *
         * @return mixed|YITH_WCMG_Admin|YITH_WCMG_Frontend
         * @since 1.0.0
         */
        public function __construct () {
            // actions
            add_action ( 'init', array ( $this, 'init' ) );

            if ( is_admin () && ( ! isset( $_REQUEST[ 'action' ] ) || ( isset( $_REQUEST[ 'action' ] ) && $_REQUEST[ 'action' ] != 'yith_load_product_quick_view' ) ) ) {
                $this->obj = new YITH_WCMG_Admin( $this->version );
            } else {
                /** Stop the plugin on mobile devices */
                if ( ( 'yes' != get_option ( 'yith_wcmg_enable_mobile' ) ) && wp_is_mobile () ) {
                    return;
                }

                $this->obj = new YITH_WCMG_Frontend_Premium( $this->version );
            }

            $this->set_plugin_options ();

            add_action ( 'ywzm_products_exclusion', array ( $this, 'show_products_exclusion_table' ) );

            add_action ( 'woocommerce_admin_field_ywzm_category_exclusion', array (
                $this,
                'show_product_category_exclusion_table',
            ) );

            return $this->obj;
        }


        public function show_product_category_exclusion_table ( $args = array () ) {
            if ( ! empty( $args ) ) {
                $args[ 'value' ] = ( get_option ( $args[ 'id' ] ) ) ? get_option ( $args[ 'id' ] ) : $args[ 'default' ];
                extract ( $args );

                $exclusion_list = get_option ( 'ywzm_category_exclusion' );

                ?>
                <tr valign="top">
                    <th scope="row" class="image_upload">
                        <label for="<?php echo $id ?>"><?php echo $name ?></label>
                    </th>
                    <td class="forminp forminp-color plugin-option">
                        <div class="categorydiv">
                            <div class="tabs-panel">
                                <ul id="product_catchecklist" data-wp-lists="list:product_cat"
                                    class="categorychecklist form-no-clear">
                                    <?php
                                    $args = array (
                                        'hide_empty' => false,
                                    );

                                    $terms = get_terms ( 'product_cat', $args );

                                    if ( ! empty( $terms ) && ! is_wp_error ( $terms ) ) {
                                        foreach ( $terms as $term ) {

                                            $checked_status = is_array ( $exclusion_list ) && in_array ( $term->woocommerce_term_id, $exclusion_list ) ? 'checked = checked' : '';


                                            echo '<li><label class="selectit"><input value="' . $term->woocommerce_term_id . '" type="checkbox" ' . $checked_status . ' name="ywzm_category_exclusion[]" id="in-product_cat-' . $term->woocommerce_term_id . '">' . $term->name . '</label></li>';

                                        }
                                    }

                                    ?>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php
            }
        }

        public function show_products_exclusion_table () {


            YWZM_Products_Exclusion::output ();
        }

        public function set_plugin_options () {
            add_filter ( 'yith_ywzm_general_settings', array ( $this, 'add_product_category_exclusion_list' ) );
            add_filter ( 'yith_ywzm_magnifier_settings', array ( $this, 'set_zoom_box_options' ) );
        }

        public function add_product_category_exclusion_list ( $args ) {
            $new_item = array (
                'id'   => 'ywzm_category_exclusion',
                'type' => 'ywzm_category_exclusion',
                'name' => __ ( 'Exclude product categories', 'yith-woocommerce-zoom-magnifier' ),
            );

            $args = array_slice ( $args, 0, count ( $args ) - 1, true ) +
                array ( 'category_exclusion' => $new_item ) +
                array_slice ( $args, 3, count ( $args ) - 1, true );

            return $args;
        }

        public function set_zoom_box_options ( $args ) {
            if ( isset( $args[ 'zoom_box_position' ] ) ) {
                $box_position = &$args[ 'zoom_box_position' ];

                $box_position[ 'options' ] = array (
                    'top'    => __ ( 'Top', 'yith-woocommerce-zoom-magnifier' ),
                    'right'  => __ ( 'Right', 'yith-woocommerce-zoom-magnifier' ),
                    'bottom' => __ ( 'Bottom', 'yith-woocommerce-zoom-magnifier' ),
                    'left'   => __ ( 'Left', 'yith-woocommerce-zoom-magnifier' ),
                    'inside' => __ ( 'Inside', 'yith-woocommerce-zoom-magnifier' ),
                );

            }

            return $args;
        }
    }
}