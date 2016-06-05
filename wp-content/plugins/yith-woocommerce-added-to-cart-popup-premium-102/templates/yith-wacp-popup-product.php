<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
?>

<?php if( $thumb ) : ?>
	<div class="product-thumb">
			<?php
			$thumbnail = $product->get_image( 'yith_wacp_image_size' );

			if ( ! $product->is_visible() ) {
				echo $thumbnail;
			} else {
				printf( '<a href="%s">%s</a>', esc_url( $product->get_permalink() ), $thumbnail );
			}

			?>
	</div>
<?php endif; ?>

<div class="info-box">

	<div class="product-info">
		<h3 class="product-title">
			<a href="<?php echo esc_url( $product->get_permalink() ) ?>">
				<?php echo $product->get_title(); ?>
			</a>
		</h3>
		<span class="product-price">
			<?php echo $product->get_price_html(); ?>
		</span>
	</div>

	<?php $cart = WC()->cart->get_cart();
    if( ( $cart_shipping || $cart_total ) && ! empty( $cart ) ) : ?>

	<div class="cart-info">

		<?php if( $cart_shipping && WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) :
			// calculate shipping
			WC()->cart->calculate_shipping();
			?>
			<div class="cart-shipping">
				<?php echo __( 'Shipping Cost', 'yith-wacp' ) . ':' ?>
				<span class="shipping-cost">
					<?php echo WC()->cart->get_cart_shipping_total() ?>
				</span>
			</div>
		<?php endif; ?>

		<?php if( $cart_total ) : ?>
			<div class="cart-totals">
				<?php echo __( 'Cart Total', 'yith-wacp' ) . ':' ?>
				<span class="cart-cost">
					<?php
					//calculate totals
					WC()->cart->calculate_totals();

					echo WC()->cart->get_cart_total();
					?>
				</span>
			</div>
		<?php endif; ?>
	</div>

	<?php endif; ?>

</div>