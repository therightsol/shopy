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

<table class="cart-list">
	<tbody>
	<?php foreach( WC()->cart->get_cart() as $item_key => $item ) : $_product = $item['data']; ?>
	<tr class="single-cart-item">

		<td class="item-remove">
			<a href="<?php echo esc_url( WC()->cart->get_remove_url( $item_key ) ) ?>" class="yith-wacp-remove-cart" title="<?php _e( 'Remove item', 'yith-wacp' ) ?>"
			   data-item_key="<?php echo $item_key ?>">X</a>
		</td>

		<?php if( $thumb ) : ?>
		<td class="item-thumb">
			<?php
			$thumbnail = $_product->get_image( 'shop_thumbnail' );

			if ( ! $_product->is_visible() ) {
			echo $thumbnail;
			} else {
			printf( '<a href="%s">%s</a>', esc_url( $_product->get_permalink( $item ) ), $thumbnail );
			}
			?>
		</td>
		<?php endif; ?>

		<td class="item-info">
			<?php
			if ( $_product->is_visible() ) {
				echo '<a class="item-name" href="' . esc_url( $_product->get_permalink( $item ) ) . '">' . $_product->get_title() . '</a>';
			}
			else {
				echo '<span class="item-name">' . $_product->get_title() . '</span>';
			}

			echo '<span class="item-price">' . $item['quantity'] . ' x ' . WC()->cart->get_product_price( $_product ) . '</span>';

			// Meta data
			echo WC()->cart->get_item_data( $item );

			?>
		</td>

	</tr>
	<?php endforeach; ?>
	</tbody>
</table>

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