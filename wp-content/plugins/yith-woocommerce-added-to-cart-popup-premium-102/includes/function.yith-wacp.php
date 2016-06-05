<?php
/**
 * General Function
 *
 * @author Yithemes
 * @package YITH WooCommerce Added to cart popup
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WACP' ) ) {
	exit;
} // Exit if accessed directly


if( ! function_exists( 'yith_wacp_get_style_options' ) ) {
	/**
	 * Get style options from Plugin Options
	 *
	 * @since 1.0.0
	 * @return string
	 * @author Francesco Licandro
	 */
	function yith_wacp_get_style_options() {

		$inline_css = '';

		// box size
		$size = get_option( 'yith-wacp-box-size' );

		$inline_css .= '
			#yith-wacp-popup .yith-wacp-content {
				max-width: ' . $size['width'] . 'px;
				max-height: ' . $size['height'] . 'px;
			}';

		// get message icon
		$icon = get_option( 'yith-wacp-message-icon', '' );

		if( $icon ) {
			$inline_css .= '
				#yith-wacp-popup .yith-wacp-message:before {
					min-width: 30px;
					min-height: 30px;
					background: url(' . $icon . ') no-repeat center center;
				}';
		}

		$inline_css .= '
			#yith-wacp-popup .yith-wacp-main {
				background-color: ' . get_option( 'yith-wacp-popup-background' ) . ';
			}
			#yith-wacp-popup .yith-wacp-overlay {
				background-color: ' . get_option( 'yith-wacp-overlay-color' ). ';
			}
			#yith-wacp-popup.open .yith-wacp-overlay {
				opacity: ' . get_option( 'yith-wacp-overlay-opacity' ) . ';
			}
			#yith-wacp-popup .yith-wacp-close {
				color: ' . get_option( 'yith-wacp-close-color' ) . ';
			}
			#yith-wacp-popup .yith-wacp-close:hover {
				color: ' . get_option( 'yith-wacp-close-color-hover' ) . ';
			}
			#yith-wacp-popup .yith-wacp-message {
				color: ' . get_option( 'yith-wacp-message-text-color' ) . ';
				background-color: ' . get_option( 'yith-wacp-message-background-color' ) . ';
			}
			.yith-wacp-content .cart-info > div {
				color: ' . get_option( 'yith-wacp-cart-info-label-color' ) . ';
			}
			.yith-wacp-content .cart-info > div span {
				color: ' . get_option( 'yith-wacp-cart-info-amount-color' ) . ';
			}
			.yith-wacp-content table.cart-list td.item-info .item-name:hover,
			.yith-wacp-content h3.product-title:hover {
				color: ' . get_option( 'yith-wacp-product-name-color-hover' ) . ';
			}
			.yith-wacp-content table.cart-list td.item-info .item-name,
			.yith-wacp-content table.cart-list td.item-info dl,
			.yith-wacp-content h3.product-title {
				color: ' . get_option( 'yith-wacp-product-name-color' ) . ';
			}
			.yith-wacp-content table.cart-list td.item-info .item-price,
			.yith-wacp-content .product-price,
			.yith-wacp-content ul.products li.product .price {
				color: ' . get_option( 'yith-wacp-product-price-color' ) . ';
			}';

		return $inline_css;
	}

    if( ! function_exists( 'get_array_column' ) ) {

        /**
         * Get column of last names from a recordset
         *
         * @since 1.0.0
         * @author Alessio Torrisi
         */
        function get_array_column($array, $array_column)
        {
            if( function_exists('array_column') ) return array_column($array, $array_column);

            $return = array();
            foreach ($array AS $row) {
                if (isset($row[$array_column])) $return[] = $row[$array_column];
            }

            return $return;
        }

    }

}