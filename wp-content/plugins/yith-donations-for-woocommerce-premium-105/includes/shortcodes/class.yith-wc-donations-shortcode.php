<?php
if( !defined( 'ABSPATH' ) )
    exit;

if( !class_exists( 'YITH_WC_Donations_Shortcode' ) ){

    class YITH_WC_Donations_Shortcode{


        public static function print_shortcode( $atts, $content='' ){

            global $YITH_Donations;
            $default   =   array(
                'message_for_donation' => get_option( 'ywcds_message_for_donation' ),
                'button_class' => 'ywcds_add_donation_product button alt',
                'product_id' => get_option('_ywcds_donation_product_id'),
                'button_text' => $YITH_Donations->get_message('text_button' ),

            );

            $atts   =   shortcode_atts( $default, $atts );

            return yith_wcds_get_template('add-donation-form-widget.php', $atts );
        }
    }
}

add_shortcode( 'yith_wcds_donations', array( 'YITH_WC_Donations_Shortcode', 'print_shortcode' ) );