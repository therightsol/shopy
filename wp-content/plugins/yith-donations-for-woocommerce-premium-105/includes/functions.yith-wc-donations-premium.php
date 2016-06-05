<?php
if( !defined( 'ABSPATH' ) )
    exit;

if( !function_exists(  'ywcds_get_gateway' ) ){

    function ywcds_get_gateway(){
        $payment = WC()->payment_gateways->payment_gateways();
        $gateways = array();
        foreach($payment as $gateway){
            if ( $gateway->enabled == 'yes' ){
                $gateways[$gateway->id] = $gateway->title;
            }
        }
        return $gateways;
    }
}

if( !function_exists( 'ywcds_get_donations_orders' ) ){

    function ywcds_get_donations_orders( $order_data_from='', $order_data_to='' ){

        global $wpdb;

        $query_data =   '';

        if( $order_data_from != '' && $order_data_to!='' )
            $query_data=" AND {$wpdb->posts}.post_date >= '".$order_data_from."' AND {$wpdb->posts}.post_date < '".$order_data_to."'";

        $query  =   "SELECT {$wpdb->posts}.ID FROM {$wpdb->posts} INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
                     WHERE {$wpdb->posts}.post_type='shop_order' AND {$wpdb->postmeta}.meta_key='_ywcds_order_has_donation' AND {$wpdb->postmeta}.meta_value='true' AND {$wpdb->posts}.post_status ='wc-completed'".$query_data;

        return $wpdb->get_col( $query );

    }
}
if( !function_exists('ywcds_get_donations_item' ) ){

    function ywcds_get_donations_item( $order_id ){

        $items_line      = array();

        $order   =   wc_get_order( $order_id );

        $donation_id    =   get_option('_ywcds_donation_product_id');

        foreach( $order->get_items() as $items ){

            if( $items['product_id']    == $donation_id ){

                $name = isset( $items['item_meta']['_ywcds_donation_name'] ) ? $items['item_meta']['_ywcds_donation_name'][0] : $items['name'];
                $total =    $items['line_total'];

                $items_line[]   =   array(
                        'product_name'  =>  $name,
                        'total'         =>  $total
                        );
            }
        }


        return $items_line;
    }
}

if( !function_exists('ywcds_format_price' ) ){

    function ywcds_format_price( $price ){

    }
}

