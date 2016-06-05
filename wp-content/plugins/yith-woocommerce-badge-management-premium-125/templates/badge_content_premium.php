<?php

$position_css = "";

$pos_top    = (is_numeric($pos_top)) ? ($pos_top . "px") : $pos_top;
$pos_bottom = (is_numeric($pos_bottom)) ? ($pos_bottom . "px") : $pos_bottom;
$pos_left   = (is_numeric($pos_left)) ? ($pos_left . "px") : $pos_left;
$pos_right  = (is_numeric($pos_right)) ? ($pos_right . "px") : $pos_right;

$position_css .= "top: " . $pos_top . ";";
$position_css .= "bottom: " . $pos_bottom . ";";
$position_css .= "left: " . $pos_left . ";";
$position_css .= "right: " . $pos_right . ";";

//--wpml-------------
$text = yith_wcbm_wpml_string_translate( 'yith-wcbm', sanitize_title( $text ), $text  );
$css_text = yith_wcbm_wpml_string_translate( 'yith-wcbm', sanitize_title( $css_text ), $css_text  );
//-------------------


switch( $type ){
    case 'text':
    case 'custom':
        ?>
        <style>
            .yith-wcbm-badge-<?php echo $product_id ?>-<?php echo $id_badge ?>
            {
                <?php $line_height = ($line_height > -1) ? $line_height : $height; ?>
                color: <?php echo $txt_color ?>; 
                background-color: <?php echo $bg_color ?>; 
                width: <?php echo $width ?>px; 
                height: <?php echo $height ?>px; 
                line-height: <?php echo $line_height ?>px;
                border-top-left-radius: <?php echo $border_top_left_radius ?>px;
                border-bottom-left-radius: <?php echo $border_bottom_left_radius ?>px;
                border-top-right-radius: <?php echo $border_top_right_radius ?>px;
                border-bottom-right-radius: <?php echo $border_bottom_right_radius ?>px;
                padding-top: <?php echo $padding_top ?>px;
                padding-bottom: <?php echo $padding_bottom ?>px;
                padding-left: <?php echo $padding_left ?>px;
                padding-right: <?php echo $padding_right ?>px;
                font-size: <?php echo $font_size ?>px;
                <?php echo $position_css ?>
                opacity: <?php echo $opacity/100 ?>;
            }
        </style>
        <div class='yith-wcbm-badge yith-wcbm-badge-<?php echo $product_id ?>-<?php echo $id_badge?>'>
            <?php echo $text ?>
        </div><!--yith-wcbm-badge-->
        <?php
        break;

    case 'image':
        //if the badge was created by free version
        if (strlen($image_url) < 6){
            $image_url = YITH_WCBM_ASSETS_URL . '/images/image-badge/' . $image_url;
        }
        $text = '<img src="'. $image_url . '" />';
        ?>
        <style>
            .yith-wcbm-badge-<?php echo $product_id ?>-<?php echo $id_badge ?>
            {
                <?php echo $position_css ?>
                opacity: <?php echo $opacity/100 ?>;
            }
        </style>
        <div class='yith-wcbm-badge yith-wcbm-badge-<?php echo $product_id ?>-<?php echo $id_badge?>'>
            <?php echo $text ?>
        </div><!--yith-wcbm-badge-->
        <?php
        break;

    case 'css':
        ?>
        <style>
            <?php
                $id_css_badge = $product_id . '-' . $id_badge;
                $args = array(
                    'type'                  => 'css',
                    'id_css_badge'          => $id_css_badge,
                    'id_badge_style'        => $css_badge,
                    'css_bg_color'          => $css_bg_color,
                    'css_text_color'        => $css_text_color,
                    'position_css'          => $position_css,
                    'opacity'               => $opacity
                    );
                yith_wcbm_get_badge_style( $args );
            ?>
        </style>
        <div class="yith-wcbm yith-wcbm-css-badge-<?php echo $product_id ?>-<?php echo $id_badge?>">
            <div class="yith-wcbm-css-s1"></div>
            <div class="yith-wcbm-css-s2"></div>
            <div class="yith-wcbm-css-text"><?php echo $css_text ?></div>
        </div>
        <?php
        break;
    case 'advanced':
        global $product;
        if( $product->is_on_sale() ){
            ?>
            <style>
                <?php
                    $id_advanced_badge = $product_id . '-' . $id_badge;
                    
                    $args = array(
                        'type'                  => 'advanced',
                        'id_advanced_badge'     => $id_advanced_badge,
                        'id_badge_style'        => $advanced_badge,
                        'advanced_bg_color'     => $advanced_bg_color,
                        'advanced_text_color'   => $advanced_text_color,
                        'position_css'          => $position_css,
                        'opacity'               => $opacity
                        );
                    yith_wcbm_get_badge_style( $args );
                ?>
            </style>
            <?php
                $id_advanced_badge = $product_id . '-' . $id_badge;
                include(YITH_WCBM_TEMPLATE_PATH . '/advanced_sale_badges.php');
        }
        break;
}


?>


