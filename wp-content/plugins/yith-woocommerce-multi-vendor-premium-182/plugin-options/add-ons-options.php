<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
return array(

    'add-ons' => array(
        'vendors_seller_vacation_start'      => array(
            'type' => 'sectionstart',
        ),

        'vendors_seller_vacation_title'      => array(
            'title' => __( 'Seller vacation', 'yith_wc_product_vendors' ),
            'type'  => 'title',
            'desc'  => '',
            'id'    => 'yith_wpv_vendors_seller_vacation_title'
        ),

        'vendors_seller_vacation_management' => array(
            'title'   => __( 'Enable seller vacation module', 'yith_wc_product_vendors' ),
            'type'    => 'checkbox',
            'desc'    => __( 'If you enable this option, each vendor will be able to close his/her shop for vacation.', 'yith_wc_product_vendors' ),
            'id'      => 'yith_wpv_vendors_option_seller_vacation_management',
            'default' => 'no'
        ),

        'vendors_seller_vacation_end'        => array(
            'type' => 'sectionend',
        ),

        'vendors_live_chat_start'            => array(
            'type' => 'sectionstart',
        ),

        'vendors_live_chat_title'            => array(
            'title' => __( 'Live Chat', 'yith_wc_product_vendors' ),
            'type'  => 'title',
            'desc'  => YITH_Vendors()->addons->get_plugin_landing_uri( 'live-chat', 'display' ),
            'id'    => 'yith_wpv_vendors_live_chat_title',
        ),

        'vendors_enable_chat'                => array(
            'title'             => __( 'Enable live chat for vendors', 'yith_wc_product_vendors' ),
            'type'              => 'checkbox',
            'desc'              => YITH_Vendors()->addons->get_option_description( 'live-chat' ),
            'id'                => 'yith_wpv_vendors_option_live_chat_management',
            'default'           => 'no',
            'custom_attributes' => YITH_Vendors()->addons->has_plugin( 'live-chat' ) ? false : array(
                'disabled' => 'disabled',
            )
        ),

        'vendors_live_chat_end'              => array(
            'type' => 'sectionend',
        ),

        'vendors_membership_start'           => array(
            'type' => 'sectionstart',
        ),

        'vendors_membership_title'           => array(
            'title' => __( 'Membership', 'yith_wc_product_vendors' ),
            'type'  => 'title',
            'desc'  => YITH_Vendors()->addons->get_plugin_landing_uri( 'membership', 'display' ),
            'id'    => 'yith_wpv_vendors_membership_title',
        ),

        'vendors_enable_membership'          => array(
            'title'             => __( 'Enable membership for vendors', 'yith_wc_product_vendors' ),
            'type'              => 'checkbox',
            'desc'              => YITH_Vendors()->addons->get_option_description( 'membership' ),
            'id'                => 'yith_wpv_vendors_option_membership_management',
            'default'           => 'no',
            'custom_attributes' => YITH_Vendors()->addons->has_plugin( 'membership' ) ? false : array(
                'disabled' => 'disabled',
            )
        ),

        'vendors_membership_end'             => array(
            'type' => 'sectionend',
        ),

        'vendors_subscription_start'         => array(
            'type' => 'sectionstart',
        ),

        'vendors_subscription_title'         => array(
            'title' => __( 'Subscription', 'yith_wc_product_vendors' ),
            'type'  => 'title',
            'desc'  => YITH_Vendors()->addons->get_plugin_landing_uri( 'subscription', 'display' ),
            'id'    => 'yith_wpv_vendors_subscription_title',
        ),

        'vendors_enable_subscription'        => array(
            'title'             => __( 'Enable subscription for vendors', 'yith_wc_product_vendors' ),
            'type'              => 'checkbox',
            'desc'              => YITH_Vendors()->addons->get_option_description( 'subscription' ),
            'id'                => 'yith_wpv_vendors_option_subscription_management',
            'default'           => 'no',
            'custom_attributes' => YITH_Vendors()->addons->has_plugin( 'subscription' ) ? false : array(
                'disabled' => 'disabled',
            )
        ),

        'vendors_subscription_end'           => array(
            'type' => 'sectionend',
        ),

        'vendors_badge_management_start'           => array(
            'type' => 'sectionstart',
        ),

        'vendors_badge_management_title'           => array(
            'title' => __( 'Badge Management', 'yith_wc_product_vendors' ),
            'type'  => 'title',
            'desc'  => YITH_Vendors()->addons->get_plugin_landing_uri( 'badge-management', 'display' ),
            'id'    => 'yith_wpv_vendors_badge_management_title',
        ),

        'vendors_enable_badge_management'          => array(
            'title'             => __( 'Enable badge management for vendors', 'yith_wc_product_vendors' ),
            'type'              => 'checkbox',
            'desc'              => YITH_Vendors()->addons->get_option_description( 'badge-management' ),
            'id'                => 'yith_wpv_vendors_option_badge_management_management',
            'default'           => 'no',
            'custom_attributes' => YITH_Vendors()->addons->has_plugin( 'badge-management' ) ? false : array(
                'disabled' => 'disabled',
            )
        ),

        'vendors_badge_management_end'             => array(
            'type' => 'sectionend',
        ),

        'vendors_size_charts_start'          => array(
            'type' => 'sectionstart',
        ),

        'vendors_size_charts_title'          => array(
            'title' => __( 'Product Size Charts', 'yith_wc_product_vendors' ),
            'type'  => 'title',
            'desc'  => YITH_Vendors()->addons->get_plugin_landing_uri( 'size-charts', 'display' ),
            'id'    => 'yith_wpv_vendors_size_charts_title',
        ),

        'vendors_enable_size_charts'         => array(
            'title'             => __( 'Enable product size charts for vendors', 'yith_wc_product_vendors' ),
            'type'              => 'checkbox',
            'desc'              => YITH_Vendors()->addons->get_option_description( 'size-charts' ),
            'id'                => 'yith_wpv_vendors_option_size_charts_management',
            'default'           => 'no',
            'custom_attributes' => YITH_Vendors()->addons->has_plugin( 'size-charts' ) ? false : array(
                'disabled' => 'disabled',
            )
        ),

        'vendors_size_charts_end'            => array(
            'type' => 'sectionend',
        ),

        'vendors_name_your_price_start'      => array(
            'type' => 'sectionstart',
        ),

        'vendors_name_your_price_title'      => array(
            'title' => __( 'Name Your Price', 'yith_wc_product_vendors' ),
            'type'  => 'title',
            'desc'  => YITH_Vendors()->addons->get_plugin_landing_uri( 'name-your-price', 'display' ),
            'id'    => 'yith_wpv_vendors_name_your_price_title',
        ),

        'vendors_enable_name_your_price'     => array(
            'title'             => __( 'Enable Name Your Price for vendors', 'yith_wc_product_vendors' ),
            'type'              => 'checkbox',
            'desc'              => YITH_Vendors()->addons->get_option_description( 'name-your-price' ),
            'id'                => 'yith_wpv_vendors_option_name_your_price_management',
            'default'           => 'no',
            'custom_attributes' => YITH_Vendors()->addons->has_plugin( 'name-your-price' ) ? false : array(
                'disabled' => 'disabled',
            )
        ),

        'vendors_name_your_price_end'        => array(
            'type' => 'sectionend',
        ),

        'vendors_order_tracking_start'       => array(
            'type' => 'sectionstart',
        ),

        'vendors_order_tracking_title'       => array(
            'title' => __( 'Order Tracking', 'yith_wc_product_vendors' ),
            'type'  => 'title',
            'desc'  => YITH_Vendors()->addons->get_plugin_landing_uri( 'order-tracking', 'display' ),
            'id'    => 'yith_wpv_vendors_order_tracking_title',
        ),

        'vendors_enable_order_tracking'      => array(
            'title'             => __( 'Enable Order Tracking for vendors', 'yith_wc_product_vendors' ),
            'type'              => 'checkbox',
            'desc'              => YITH_Vendors()->addons->get_option_description( 'order-tracking' ),
            'id'                => 'yith_wpv_vendors_option_order_tracking_management',
            'default'           => 'no',
            'custom_attributes' => YITH_Vendors()->addons->has_plugin( 'order-tracking' ) ? false : array(
                'disabled' => 'disabled',
            )
        ),

        'vendors_order_tracking_end'         => array(
            'type' => 'sectionend',
        ),

        'vendors_waiting_list_start'       => array(
            'type' => 'sectionstart',
        ),

        'vendors_waiting_list_title'       => array(
            'title' => __( 'Waiting List', 'yith_wc_product_vendors' ),
            'type'  => 'title',
            'desc'  => YITH_Vendors()->addons->get_plugin_landing_uri( 'waiting-list', 'display' ),
            'id'    => 'yith_wpv_vendors_waiting_list_title',
        ),

        'vendors_enable_waiting_list'      => array(
            'title'             => __( 'Enable Waiting List for vendors', 'yith_wc_product_vendors' ),
            'type'              => 'checkbox',
            'desc'              => YITH_Vendors()->addons->get_option_description( 'waiting-list' ),
            'id'                => 'yith_wpv_vendors_option_waiting_list_management',
            'default'           => 'no',
            'custom_attributes' => YITH_Vendors()->addons->has_plugin( 'waiting-list' ) ? false : array(
                'disabled' => 'disabled',
            )
        ),

        'vendors_waiting_list_end'         => array(
            'type' => 'sectionend',
        ),

        'vendors_surveys_start'          => array(
            'type' => 'sectionstart',
        ),

        'vendors_surveys_title'          => array(
            'title' => __( 'Surveys', 'yith_wc_product_vendors' ),
            'type'  => 'title',
            'desc'  => YITH_Vendors()->addons->get_plugin_landing_uri( 'surveys', 'display' ),
            'id'    => 'yith_wpv_vendors_surveys_title',
        ),

        'vendors_enable_surveys'         => array(
            'title'             => __( 'Enable surveys for vendors', 'yith_wc_product_vendors' ),
            'type'              => 'checkbox',
            'desc'              => YITH_Vendors()->addons->get_option_description( 'surveys' ),
            'id'                => 'yith_wpv_vendors_option_surveys_management',
            'default'           => 'no',
            'custom_attributes' => YITH_Vendors()->addons->has_plugin( 'surveys' ) ? false : array(
                'disabled' => 'disabled',
            )
        ),

        'vendors_surveys_end'            => array(
            'type' => 'sectionend',
        ),

        'vendors_review_discounts_start'          => array(
            'type' => 'sectionstart',
        ),

        'vendors_review_discounts_title'          => array(
            'title' => __( 'Review For Discounts', 'yith_wc_product_vendors' ),
            'type'  => 'title',
            'desc'  => YITH_Vendors()->addons->get_plugin_landing_uri( 'review-discounts', 'display' ),
            'id'    => 'yith_wpv_vendors_review_discounts_title',
        ),

        'vendors_enable_review_discounts'         => array(
            'title'             => __( 'Enable Review for Discounts for vendors', 'yith_wc_product_vendors' ),
            'type'              => 'checkbox',
            'desc'              => YITH_Vendors()->addons->get_option_description( 'review-discounts' ),
            'id'                => 'yith_wpv_vendors_option_review_discounts_management',
            'default'           => 'no',
            'custom_attributes' => YITH_Vendors()->addons->has_plugin( 'review-discounts' ) ? false : array(
                'disabled' => 'disabled',
            )
        ),

        'vendors_review_discounts_end'            => array(
            'type' => 'sectionend',
        ),

        'vendors_coupon_email_system_start'  => array(
            'type' => 'sectionstart',
        ),

        'vendors_coupon_email_system_title'  => array(
            'title' => __( 'Coupon Email System', 'yith_wc_product_vendors' ),
            'type'  => 'title',
            'desc'  => YITH_Vendors()->addons->get_plugin_landing_uri( 'coupon-email-system', 'display' ),
            'id'    => 'yith_wpv_vendors_coupon_email_system_title',
        ),

        'vendors_enable_coupon_email_system' => array(
            'title'             => __( 'Coupon Email System for vendors', 'yith_wc_product_vendors' ),
            'type'              => 'yith_premium_addons',
            'desc'              => sprintf( '%s:',  __( 'You can manage this features here', 'yith_wc_product_vendors' ) ),
            'settings_tab' => array(
                'uri'         => add_query_arg( array( 'page' => 'yith-wc-coupon-email-system', 'tab' => 'admin-vendor' ), admin_url( 'admin.php' ) ),
                'desc'        => __( 'Vendor Settings', 'yith-woocommerce-coupon-email-system' ),
                'plugin_name' => __( 'Coupon Email System', 'yith_wc_product_vendors' )
            ),
            'id'                => 'yith_wpv_vendors_option_coupon_email_system_management',
            'default'           => 'no',
            'custom_attributes' => YITH_Vendors()->addons->has_plugin( 'coupon-email-system' ) ? false : array(
                'disabled' => 'disabled',
            )
        ),

        'vendors_coupon_email_system_end'    => array(
            'type' => 'sectionend',
        ),
    ),
);