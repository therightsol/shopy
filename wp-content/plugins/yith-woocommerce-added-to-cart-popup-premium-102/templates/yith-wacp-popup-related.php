<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */


if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}

global $product;

$loop = 0;
$args = apply_filters( 'yith_wacp_related_products_args', array(
	'post_type'            => 'product',
	'ignore_sticky_posts'  => 1,
	'no_found_rows'        => 1,
	'posts_per_page'       => $posts_per_page,
	'post__in'             => $items,
	'post__not_in'         => array( $current_product_id )
) );

$products = new WP_Query( $args );

if ( $products->have_posts() ) : ?>

<div class="woocommmerce yith-wacp-related">

	<h3><?php echo $title ?></h3>

	<ul class="products columns-<?php echo $columns ?>">

	<?php

	while ( $products->have_posts() ) :
		$products->the_post();

		// Increase loop count
		$loop++;

		// Extra post classes
		$classes = array( 'product', 'yith-wacp-related-product' );
		if ( 0 == ( $loop - 1 ) % $columns || 1 == $columns ) {
			$classes[] = 'first';
		}
		if ( 0 == $loop % $columns ) {
			$classes[] = 'last';
		}
		?>

		<li <?php post_class( $classes ); ?>>

			<?php do_action( 'yith_wacp_before_related_item' ) ?>

			<a href="<?php the_permalink(); ?>">

				<div class="product-image">
					<?php
					wc_get_template( 'loop/sale-flash.php' );
					echo woocommerce_get_product_thumbnail( 'shop_catalog' );
					?>
				</div>

				<h3 class="product-title">
					<?php the_title() ?>
				</h3>

				<div class="product-price">
					<?php wc_get_template( 'loop/price.php' ); ?>
				</div>

			</a>

			<?php do_action( 'yith_wacp_after_related_item' ) ?>

		</li>

	<?php endwhile; // end of the loop. ?>

	</ul>

</div>

<?php endif;

wp_reset_postdata();