<?php
if( !defined( 'ABSPATH' ) )
    exit;

if( !class_exists( 'YITH_WC_Donations_Email' ) ){

    class YITH_WC_Donations_Email extends  WC_Email{


        protected  $donation_list;
        public function  __construct(){

            $this->title            = __( 'YITH Donations','ywcds' );
            $this->template_html 	= 'emails/donation.php';
            $this->template_plain 	= 'emails/plain/donation.php';
            parent::__construct();
        }

        /**
         * Trigger email send
         *
         * @since   1.0.0
         * @param   $order_id int the order id
         * @param   $item_list array the list of items to review
         * @param   $days_ago int number of days after order completion
         * @param   $test_email
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function trigger( $order_id, $donation_list,  $test_email = '' ) {

            $this->email_type   = get_option( 'ywcds_mail_type' );
            $this->heading      = get_option( 'ywcds_mail_subject' );
            $this->subject      = get_option( 'ywcds_mail_subject' );
            $this->donation_list    =   $donation_list;
            $this->find['site-title']    = '{site_title}';
            $this->replace['site-title'] = $this->get_blogname();

            if ( $order_id ) {

                $this->object 		= wc_get_order( $order_id );
                $this->recipient	= $this->object->billing_email;

            } else {

                $this->object       = 0;
                $this->recipient	= $test_email;

            }

            if ( ! $this->get_recipient() ) {
                return;
            }

          $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), "" );

        }

        /**
         * Send the email.
         *
         * @since   1.0.3
         * @param   string $to
         * @param   string $subject
         * @param   string $message
         * @param   string $headers
         * @param   string $attachments
         * @return  bool
         * @author  Alberto Ruggiero
         */
        public function send( $to, $subject, $message, $headers, $attachments ) {

            add_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
            add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
            add_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

            $message = apply_filters( 'woocommerce_mail_content', $this->style_inline( $message ) );

            $return = wp_mail( $to, $subject, $message, $headers, $attachments );

            remove_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
            remove_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
            remove_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

            return $return;

        }

        /**
         * Get HTML content
         *
         * @since   1.0.0
         * @return  string
         * @author  Alberto Ruggiero
         */
        function get_content_html() {
            ob_start();
            wc_get_template( $this->template_html, array(
                'order' 		=> $this->object,
                'donation_list' =>  $this->donation_list,
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text'    => false
            ), YWCDS_TEMPLATE_PATH , YWCDS_TEMPLATE_PATH );
            return ob_get_clean();
        }

        /**
         * Get Plain content
         *
         * @since   1.0.0
         * @return  string
         * @author  Alberto Ruggiero
         */
        function get_content_plain() {
            ob_start();
            wc_get_template( $this->template_plain, array(
                'order' 		=> $this->object,
                'donation_list' =>  $this->donation_list,
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text'    => true
            ), YWCDS_TEMPLATE_PATH , YWCDS_TEMPLATE_PATH );
            return ob_get_clean();
        }

        /**
         * Admin Panel Options Processing - Saves the options to the DB
         *
         * @since   1.0.0
         * @return  boolean|null
         * @author  Alberto Ruggiero
         */
        function process_admin_options() {
            woocommerce_update_options( $this->form_fields['mail'] );
        }

        /**
         * Setup email settings screen.
         *
         * @since   1.0.0
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function admin_options() {
            ?>
            <table class="form-table">
                <?php woocommerce_admin_fields( $this->form_fields['mail'] ); ?>
            </table>
        <?php

        }

        /**
         * Initialise Settings Form Fields
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        function init_form_fields() {
            $this->form_fields = include( YWCDS_DIR . '/plugin-options/mail-options.php' );
        }

    }
}

return new YITH_WC_Donations_Email();