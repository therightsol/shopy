<?php
if( !defined( 'ABSPATH' ) )
    exit;

if( !class_exists( 'YITH_Donations_Form_Widget' ) ) {

    class YITH_Donations_Form_Widget extends WP_Widget
    {

        public function __construct()
        {
            parent::__construct(
                'yith_wc_donations_form',
                __('YITH Donations for WooCommerce - Form', 'ywcds'),
                array('description' => __('Add a simple form to let your customers add donations to the cart!', 'ywcds'))
            );

        }


        public function form( $instance )
        {

            $title = isset( $instance['title'] ) ? $instance['title'] : '';

            ?>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title'));?>"><?php _e('Title', 'ywcds');?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title'));?>"
                       name="<?php echo esc_attr($this->get_field_name('title'));?>" value="<?php echo $title;?>"/>
            </p>
        <?php

        }


        public function update($new_instance, $old_instance) {

            $instance   =   array();

            $instance['title']  =   isset( $new_instance['title'] ) ? $new_instance['title'] : '';


            return $instance;

        }


        public function widget( $args, $instance ){


            $title  =   apply_filters( 'widget_title', $instance['title'] );

            echo $args['before_widget'];
            echo $args['before_title'].$title.$args['after_title'];
            echo do_shortcode('[yith_wcds_donations]');//yith_wcds_get_template( 'add-donation-form.php', $args_form, true );
            echo $args['after_widget'];
        }
    }
}