<?php
/**
 * Admin View: Exclusions List Table
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//$mess = isset( $_GET['wcwtl_mess'] ) ? $_GET['wcwtl_mess'] : '';
//
//switch( $mess ) {
//	case 1:
//		$notice = __( 'Select at least one product to remove.', 'yith-wcwtl' );
//		break;
//	case 2:
//		$message = __( 'Products removed successfully.', 'yith-wcwtl' );
//		break;
//	case 3:
//		$message = __( 'Products added successfully.', 'yith-wcwtl' );
//		break;
//	case 4:
//		$notice = __( 'You must select at least one product to add', 'yith-wcwtl' );
//		break;
//	default:
//		break;
//}

$list_query_args = array(
	'page' => $_GET['page'],
	'tab'  => $_GET['tab']
);

$list_url = add_query_arg( $list_query_args, admin_url( 'admin.php' ) );

?>
<div class="wrap">
	<div class="icon32 icon32-posts-post" id="icon-edit"><br /></div>
	<h2><?php _e( 'Product exclusion list', 'yith-wacp' ); ?></h2>

	<?php if ( ! empty( $notice ) ) : ?>
		<div id="notice" class="error below-h2"><p><?php echo $notice; ?></p></div>
	<?php endif;

	if ( ! empty( $message ) ) : ?>
		<div id="message" class="updated below-h2"><p><?php echo $message; ?></p></div>
	<?php endif;

	?>
	<form id="yith-add-exclusion-prod" method="POST">
		<input type="hidden" name="_nonce" value="<?php echo wp_create_nonce( 'yith_wacp_add_exclusions_prod' ); ?>" />
		<label for="add_products"><?php _e( 'Select products to exclude', 'yith-wacp' ); ?></label>
		<input type="text" id="add_products" name="add_products" class="wc-product-search" data-multiple="true" placeholder="<?php _e( 'Search product...', 'yith-wacp' ) ?>" data-action="yith_wacp_search_products" />
		<input type="submit" value="<?php _e( 'Exclude', 'yith-wacp' ); ?>" id="add" class="button" name="add">
	</form>

	<?php $table->display(); ?>

</div>