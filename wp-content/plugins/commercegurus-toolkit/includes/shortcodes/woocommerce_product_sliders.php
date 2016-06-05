<?php

function cg_owlslider_js($owlsliderjsid){

//global $wp_locale;
//$rtl = '';
//$rtl = ( isset( $wp_locale ) && ('rtl' == $wp_locale->text_direction ) );


	?>
	<script>

	jQuery(document).ready(function($) {

		$("#owlslider_<?php echo $owlsliderjsid ?>").owlCarousel({

			<?php 
			//if ( $rtl ) {
			//echo 'rtl : true,';
			//}
			?>
			items : 5,
			lazyLoad : true,
			navigation : true,
			//nav : true,
			itemsMobile: [768,2],
		    // responsive:{
		    //     0:{
		    //         items:1
		    //     },
		    //     768:{
		    //         items:2
		    //     },
		    //     1000:{
		    //         items:5
		    //     }
		    // }
			navigationText: [
			//navText : [
		  	"<i class='fa fa-angle-left'></i>",
		  	"<i class='fa fa-angle-right'></i>",
		  	]
		}); 

	});

	</script>

<?php }

// Latest Products. 

function cg_woo_latest_products($atts, $content = null) {
	global $woocommerce;
	$owlid = rand();
	extract(shortcode_atts(array(
		"introtext" => 'Bestsellers',
		'products'  => '12',
        'orderby' => 'date',
        'order' => 'desc'
	), $atts));
	ob_start();
	?>
    
    <?php 
	/**
	* Check if WooCommerce is active
	**/
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	?>
    
    <!-- Open CommerceGurus Latest Products  -->
 	<?php cg_owlslider_js($owlid)?>

	<section class="slider">
		<div class="row">
				<div class="col-lg-12">
					<div class="titlewrap">
						<h2><?php echo $introtext; ?></h2>
					</div>
				</div>
				<div id="owlslider_<?php echo $owlid ?>" class="owl-carousel">

					<?php
					$args = array(
					    'post_type' => 'product',
						'post_status' => 'publish',
						'ignore_sticky_posts'   => 1,
						'posts_per_page' => $products
					);
					// Hide hidden items
					$args['meta_query'][] = WC()->query->visibility_meta_query();

					$products = new WP_Query( $args );

					if ( $products->have_posts() ) : ?>
					            
					    <?php while ( $products->have_posts() ) : $products->the_post(); ?>

					    	<div class="item">
					    	<ul>
					    		<?php woocommerce_get_template_part( 'content', 'product' ); ?>
					    	</ul>
					    	</div>

					    <?php endwhile; // end of the loop. ?>
					    
					<?php
					endif; 
					wp_reset_query();
					?>
				</div>
		</div>
	</section>

    <!-- Close CommerceGurus Latest Products  -->

    <?php } ?>

	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

// Featured Products 

function cg_woo_featured_products($atts, $content = null) {
	global $woocommerce;
	$owlid = rand();
	extract(shortcode_atts(array(
		"introtext" => 'Recommended for you',
		'products'  => '12',
        'orderby' => 'date',
        'order' => 'desc'
	), $atts));
	ob_start();
	?>
    
    <?php 
	/**
	* Check if WooCommerce is active
	**/
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	?>
    
    <!-- Open CommerceGurus featured Products  -->
 	<?php cg_owlslider_js($owlid)?>

	<section class="slider">
		<div class="row">
			<div class="col-lg-12">
				<div class="titlewrap">
					<h2><?php echo $introtext; ?></h2>
				</div>
			</div>
			<div id="owlslider_<?php echo $owlid ?>" class="owl-carousel">
				<?php
				$args = array(
	                'post_status' => 'publish',
	                'post_type' => 'product',
					'ignore_sticky_posts'   => 1,
	                'meta_key' => '_featured',
	                'meta_value' => 'yes',
	                'posts_per_page' => $products,
					'orderby' => $orderby,
					'order' => $order,
				);
				// Hide hidden items
				$args['meta_query'][] = WC()->query->visibility_meta_query();

				$products = new WP_Query( $args );

				if ( $products->have_posts() ) : ?>
				            
				    <?php while ( $products->have_posts() ) : $products->the_post(); ?>

				    	<div class="item">
				    	<ul>
				    		<?php woocommerce_get_template_part( 'content', 'product' ); ?>
				    	</ul>
				    	</div>

				    <?php endwhile; // end of the loop. ?>
				    
				<?php
				endif; 
				wp_reset_query();
				?>
			</div>
		</div>
	</section>

    <!-- Close CommerceGurus featured Products  -->

    <?php } ?>

	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode("cg_woo_latest_products", "cg_woo_latest_products");
add_shortcode("cg_woo_featured_products", "cg_woo_featured_products");


