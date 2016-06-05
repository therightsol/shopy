<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Displays the product donation table in YITh_WooCommerce_Donations plugin admin tab
 *
 * @class   YWCDS_Product_Donation_Table
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 *
 */
class YWCDS_Order_Donation_Table {

    /**
     * Outputs the exclusions table template with insert form in plugin options panel
     *
     * @since   1.0.0
     * @author  Alberto Ruggiero
     * @return  string
     */

    /**
     * Single instance of the class
     *
     * @var \YWCDS_Order_Donation_Table
     * @since 1.0.0
     */
    protected static  $instance;

    public function __construct(){
        add_filter( 'set-screen-option', array( $this, 'set_options' ), 10, 3 );
        add_action( 'current_screen', array( $this, 'add_options' ) );

    }

    /**
     * Returns single instance of the class
     *
     * @return \YWCDS_Order_Donation_Table
     * @since 1.0.0
     */
    public static function get_instance() {

        if ( is_null( self::$instance ) ) {

            self::$instance = new self( $_REQUEST );

        }

        return self::$instance;
    }

    public function output() {


        global $wpdb;

        $table = new YITH_Custom_Table( array(
            'singular'  => __('order', 'ywcds'),
            'plural'    => __('orders', 'ywcds')
        ) );

        $start_date =   isset( $_GET['start_date'] ) ?  $_GET['start_date']  :  '';
        $end_date   =   isset( $_GET['end_date'] )  ?   $_GET['end_date']    :  '';
        $filter_date    ='';
        if( $start_date!='' && $end_date!='' ) {

            $start_date = date( 'Y-m-d', strtotime( $start_date ) ) ;
            $end_date = date( 'Y-m-d', strtotime( '+1 DAY', strtotime( $end_date ) ) );
            $filter_date = "AND a.post_date>='" . $start_date . "' AND a.post_date<'" . $end_date . "'";
        }
        $table->options = array(
            'select_table'      => $wpdb->prefix . 'posts a INNER JOIN ' . $wpdb->prefix . 'postmeta b ON a.ID = b.post_id',
            'select_columns'    => array(
                                    'a.ID','a.post_date'
                                    ),
            'select_where'      => 'a.post_type = "shop_order" AND b.meta_key="_ywcds_order_has_donation" AND b.meta_value="true" AND a.post_status IN ( '.self::in_status().' ) '.$filter_date,
            'select_group'      => 'a.ID',
            'select_order'      => isset( $_GET['orderby'] )?  $_GET['orderby'] : 'a.ID',
            'select_order_dir' => 'DESC',
            'per_page_option'  => 'items_per_page',
            'count_table'       =>  $wpdb->prefix . 'posts a INNER JOIN ' . $wpdb->prefix . 'postmeta b ON a.ID = b.post_id',
            'count_where'       => 'post_type="shop_order" AND b.meta_key="_ywcds_order_has_donation" AND a.post_status IN ( '.self::in_status().' ) '.$filter_date,
            'key_column'        => 'ID',
            'view_columns'      => array(
               // 'cb'            => '<input type="checkbox" />',
                'order_status'       => '<span class="status_head tips" data-tip="' . esc_attr__( 'Status', 'woocommerce' ) . '">' . esc_attr__( 'Status', 'woocommerce' ) . '</span>',
                'order_title'   => __('Order', 'ywcds'),
                'order_items'  => __('Purchased', 'woocommerce'),
                'shipping_address'    => __('Ship to', 'woocommerce'),
                'order_date'      =>  __('Date', 'woocommerce'),
                'order_total'     =>  __('Total', 'woocommerce')
            ),
            'hidden_columns'    => array(),
            'sortable_columns'  => array(
                'order_title'    => array( 'ID', true ),
                'order_date'      =>  array( 'post_date', true ),
            ),
            'custom_columns'    => array(
                'column_order_status'   =>  function( $item, $me ){

                    $order  =   wc_get_order( $item['ID'] );
                    printf( '<mark class="%s tips" data-tip="%s">%s</mark>', sanitize_title( $order->get_status() ), wc_get_order_status_name( $order->get_status() ), wc_get_order_status_name( $order->get_status() ) );

                },
                'column_order_title'  =>  function( $item , $me ){

                    /*get extra information for order*/

                    $order  =   wc_get_order( $item['ID'] );
                    $order_query_args = array(
                        'post'      => $item['ID'],
                        'action'    => 'edit'
                    );
                    $order_url        = esc_url( add_query_arg( $order_query_args, admin_url( 'post.php' ) ) );

                    $user_query_args    =   array(
                        'user_id'  =>   $order->get_user_id(),
                        );

                    $user_url   =   esc_url( add_query_arg( $user_query_args, admin_url('user-edit.php' ) ) );

                    $user_info  =   sprintf( '<a href="%s" target="_blank">%s</a><small class="meta mail"><a href="mailto:%s">%s</a></small>',$user_url, $order->get_user()->user_nicename, $order->get_user()->user_email, $order->get_user()->user_email );
                    $order_info =   sprintf('<a class="tips" target="_blank" href="%s" data-tip="%s">#%d </a>by %s', $order_url, __( 'View order','ywcds' ), $item['ID'], $user_info );

                    $div    =   '<div>'.$order_info.'</div>';
                    return $div;
                },
                'column_order_items'  =>  function( $item, $me ){

                    $the_order  =   wc_get_order( $item['ID'] );

                    if ( version_compare(WC()->version, "2.4.0" . '>=')) {

                        echo '<a href="#" class="show_order_items">' . apply_filters('woocommerce_admin_order_item_count', sprintf(_n('%d item', '%d items', $the_order->get_item_count(), 'woocommerce'), $the_order->get_item_count()), $the_order) . '</a>';

                        if (sizeof($the_order->get_items()) > 0) {

                            echo '<table class="order_items" cellspacing="0">';

                            foreach ($the_order->get_items() as $item) {
                                $product = apply_filters('woocommerce_order_item_product', $the_order->get_product_from_item($item), $item);
                                $item_meta = new WC_Order_Item_Meta($item, $product);
                                $item_meta_html = $item_meta->display(true, true);
                                ?>
                                <tr class="<?php echo apply_filters('woocommerce_admin_order_item_class', '', $item); ?>">
                                    <td class="qty"><?php echo absint($item['qty']); ?></td>
                                    <td class="name">
                                        <?php if ($product) : ?>
                                            <?php echo (wc_product_sku_enabled() && $product->get_sku()) ? $product->get_sku() . ' - ' : ''; ?>
                                            <a href="<?php echo get_edit_post_link($product->id); ?>"
                                               title="<?php echo apply_filters('woocommerce_order_item_name', $item['name'], $item, false); ?>"><?php echo apply_filters('woocommerce_order_item_name', $item['name'], $item, false); ?></a>
                                        <?php else : ?>
                                            <?php echo apply_filters('woocommerce_order_item_name', $item['name'], $item, false); ?>
                                        <?php endif; ?>
                                        <?php if (!empty($item_meta_html)) : ?>
                                            <a class="tips" href="#"
                                               data-tip="<?php echo esc_attr($item_meta_html); ?>">[?]</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                            }

                            echo '</table>';

                        } else echo '&ndash;';
                    }
                    else {
                        echo '<a href="#" class="show_order_items">' . apply_filters('woocommerce_admin_order_item_count', sprintf(_n('%d item', '%d items', $the_order->get_item_count(), 'woocommerce'), $order->get_item_count()), $order) . '</a>';

                        if (sizeof($the_order->get_items()) > 0) {

                            echo '<table class="order_items" cellspacing="0">';

                            foreach ($the_order->get_items() as $order_item) {
                                $_product = apply_filters('woocommerce_order_item_product', $order->get_product_from_item($order_item), $order_item);
                                $item_meta = new WC_Order_Item_Meta($order_item['item_meta']);
                                $item_meta_html = $item_meta->display(true, true);
                                ?>
                                <tr class="<?php echo apply_filters('woocommerce_admin_order_item_class', '', $item); ?>">
                                    <td class="qty"><?php echo absint($order_item['qty']); ?></td>
                                    <td class="name">
                                        <?php if ($_product) : ?>
                                            <?php echo (wc_product_sku_enabled() && $_product->get_sku()) ? $_product->get_sku() . ' - ' : ''; ?>
                                            <a href="<?php echo get_edit_post_link($_product->id); ?>"
                                               title="<?php echo apply_filters('woocommerce_order_item_name', $order_item['name'], $order_item); ?>"><?php echo apply_filters('woocommerce_order_item_name', $order_item['name'], $order_item); ?></a>
                                        <?php else : ?>
                                            <?php echo apply_filters('woocommerce_order_item_name', $order_item['name'], $order_item); ?>
                                        <?php endif; ?>
                                        <?php if ($item_meta_html) : ?>
                                            <a class="tips" href="#"
                                               data-tip="<?php echo esc_attr($item_meta_html); ?>">[?]</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                            }

                            echo '</table>';

                        } else echo '&ndash;';
                    }
                },
                'column_shipping_address'    =>  function( $item, $me ){

                    $order  =   wc_get_order( $item['ID'] );

                    if ( $address = $order->get_formatted_shipping_address() ) {
                        echo '<a target="_blank" href="' . esc_url( $order->get_shipping_address_map_url() ) . '">'. esc_html( preg_replace( '#<br\s*/?>#i', ', ', $address ) ) .'</a>';
                    } else {
                        echo '&ndash;';
                    }

                    if ( $order->get_shipping_method() ) {
                        echo '<small class="meta">' . __( 'Via', 'woocommerce' ) . ' ' . esc_html( $order->get_shipping_method() ) . '</small>';
                    }


                },
                'column_order_date'   =>  function( $item, $me ){

                    return sprintf('<abbr title="%s">%s</abbr>',date("Y/m/d g:i:s A", strtotime( $item['post_date'] ) ),date('Y/m/d', strtotime( $item['post_date'] )  ) );
                },

                'column_order_total' =>   function( $item, $me ){

                    $order  =   wc_get_order( $item['ID'] );
                    if ( $order->get_total_refunded() > 0 ) {
                        echo '<del>' . strip_tags( $order->get_formatted_order_total() ) . '</del> <ins>' . wc_price( $order->get_total() - $order->get_total_refunded(), array( 'currency' => $order->get_order_currency() ) ) . '</ins>';
                    } else {
                        echo esc_html( strip_tags( $order->get_formatted_order_total() ) );
                    }

                    if ( $order->payment_method_title ) {
                        echo '<small class="meta">' . __( 'Via', 'woocommerce' ) . ' ' . esc_html( $order->payment_method_title ) . '</small>';
                    }

                }

            ),
            'bulk_actions'      => array(
                'actions'   => array(
                ),
                'functions' => array(
                'filter_actions'      => array(),
                    )
            ),
        );

        $table->prepare_items();




        $list_query_args = array(
            'page'  => $_GET['page'],
            'tab'   => $_GET['tab']
        );

        $list_url = esc_url( add_query_arg( $list_query_args, admin_url( 'admin.php' ) ) );?>
        <div class="wrap">
            <div class="icon32 icon32-posts-post" id="icon-edit"><br/></div>
            <h2><?php _e('Donations list', 'ywcds');?></h2>
                <form id="custom-table" method="GET" action="<?php echo $list_url; ?>">
                    <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>"/>
                    <input type="hidden" name="tab" value="<?php echo $_GET['tab']; ?>"/>
                    <?php $table->display(); ?>
                </form>
        </div>
        <?php
    }

    /**
     * Add screen options for list table template
     *
     * @since   1.0.0
     * @return  void
     * @author  Alberto Ruggiero
     */
    public function add_options() {



        if ( 'yit-plugins_page_yith_wc_donations' == get_current_screen()->id && ( isset( $_GET['tab'] ) && $_GET['tab'] == 'product-donation' ) && ( !isset( $_GET['action'] ) || ( $_GET['action'] != 'edit' && $_GET['action'] != 'insert' ) ) ) {

            $option = 'per_page';

            $args = array(
                'label'   => __( 'Orders', 'ywcds' ),
                'default' => 10,
                'option'  => 'items_per_page'
            );

            add_screen_option( $option, $args );

        }

    }

    /**
     * Set screen options for list table template
     *
     * @since   1.0.0
     *
     * @param   $status
     * @param   $option
     * @param   $value
     *
     * @return  mixed
     * @author  Alberto Ruggiero
     */
    public function set_options( $status, $option, $value ) {

        return ( 'items_per_page' == $option ) ? $value : $status;

    }



    public function in_status( ){

        $in_clause=array();

        foreach( wc_get_order_statuses() as $key=> $status ){


            $in_clause[]="'$key'";
        }
        return implode(',', $in_clause ) ;
    }
}

/**
 * Unique access to instance of YWCDS_Order_Donation_Table class
 *
 * @return \YWCDS_Order_Donation_Table
 */
function YWCDS_Order_Donation_Table() {

    return YWCDS_Order_Donation_Table::get_instance();

}

new YWCDS_Order_Donation_Table();