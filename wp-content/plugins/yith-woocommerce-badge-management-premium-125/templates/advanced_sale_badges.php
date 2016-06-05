<?php
global $product;

if ( $product->product_type == 'variable' ) {

    $children = $product->get_children();
    $sale     = 0;
    $regular  = 0;
    $first    = false;

    $saved_money   = 0;
    $saved_display = true;

    foreach ( $children as $child_id ) {
        $child = wc_get_product( $child_id );
        if ( !$child )
            continue;

        if ( !$first ) {
            $first       = true;
            $sale        = $child->get_sale_price();
            $regular     = $child->get_regular_price();
            $saved_money = $regular - $sale;
        } else {
            $relation_old  = absint( $sale > 0 ? $regular / $sale : 1 );
            $sale          = $child->get_sale_price();
            $regular       = $child->get_regular_price();
            $relation_this = absint( $sale > 0 ? $regular / $sale : 1 );

            if ( $saved_money != $regular - $sale )
                $saved_display = false;

            if ( $relation_old != $relation_this ) {
                return;
            }
            $sale_price    = $sale;
            $regular_price = $regular;
        }

    }
} else {
    $sale_price    = $product->get_sale_price();
    $regular_price = $product->get_regular_price();
}

if ( $regular_price != 0 ) {
    $percentual_sale = 100 - intval( $sale_price / $regular_price * 100 );
} else {
    $percentual_sale = 0;
}
if ( $product->product_type != 'variable' ) {
    $saved_money = intval( $regular_price - $sale_price );
}
$args              = array( 'decimals' => 0 );
$saved             = wc_price( $saved_money, $args );
$id_advanced_badge = ( isset( $id_advanced_badge ) ) ? '-' . $id_advanced_badge : '-advanced';
?>

<div class="yith-wcbm yith-wcbm-on-sale-badge<?php echo $id_advanced_badge ?>">
    <div class="yith-wcbm yith-wcbm-shape1"></div>
    <div class="yith-wcbm yith-wcbm-shape2"></div>
    <div class="yith-wcbm yith-wcbm-simbol-sale"><?php _e( 'Sale', 'yith-wcbm' ) ?></div>
    <div class="yith-wcbm yith-wcbm-simbol-percent">%</div>
    <div class="yith-wcbm yith-wcbm-simbol-off"><?php _e( 'Off', 'yith-wcbm' ) ?></div>
    <div class="yith-wcbm yith-wcbm-sale-percent"><?php echo $percentual_sale ?></div>
    <?php if ( $saved_display ): ?>
        <div class="yith-wcbm yith-wcbm-simbol-save"><?php _e( 'Save', 'yith-wcbm' ) ?></div>
        <div class="yith-wcbm yith-wcbm-sale-save"><?php echo $saved ?></div>
    <?php endif; ?>
</div>