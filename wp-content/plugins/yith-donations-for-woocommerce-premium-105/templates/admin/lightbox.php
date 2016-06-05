<?php
/**
 * Add new field for contact customize panel.
 *
 * Page for adding new field to contact module.
 *
 * @package    Wordpress
 * @subpackage Kassyopea
 * @since      1.1
 */

if ( !defined( 'IFRAME_REQUEST' ) ) {
    define( 'IFRAME_REQUEST', true );
}

$wp_load = dirname( dirname( __FILE__ ) );

for ( $i = 0; $i < 10; $i ++ ) {
    if ( file_exists( $wp_load . '/wp-load.php' ) ) {
        require_once "$wp_load/wp-load.php";
        break;
    }
    else {
        $wp_load = dirname( $wp_load );
    }
}

@header( 'Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' ) );

?>
<html <?php if ( yit_ie_version() < 9 && yit_ie_version() > 0 ) {
    echo 'class="ie8"';
} ?>xmlns="http://www.w3.org/1999/xhtml" <?php do_action( 'admin_xml_ns' ); ?> <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php echo get_option( 'blog_charset' ); ?>" />
    <title><?php _e( "Add shortcode", 'yit' ) ?></title>
    <?php if ( isset( $sitepress ) ) : ?>
        <script type="text/javascript">
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script>
    <?php endif; ?>
    <?php
    wp_admin_css( 'wp-admin', true );

    $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
    wp_enqueue_script( 'ywcds_admin_premium_script', YWCDS_ASSETS_URL . 'js/ywcds_premium_admin'.$suffix.'.js', array('jquery'), '1.0.0');
    remove_action('admin_print_styles', array( 'WC_Name_Your_Price_Admin', 'add_help_tab' ), 20 );

    do_action( 'admin_print_styles' );
    do_action( 'admin_print_scripts' );
    do_action( 'admin_head' );
    ?>
    <style type="text/css">
        html, body {
            background: #fff;
        }

        .button {
            background: #00a0d2;
            border-color: #0073aa;
            -webkit-box-shadow: inset 0 1px 0 rgba(120, 200, 230, .5), 0 1px 0 rgba(0, 0, 0, .15);
            box-shadow: inset 0 1px 0 rgba(120, 200, 230, .5), 0 1px 0 rgba(0, 0, 0, .15);
            color: #fff;
            text-decoration: none;
            display: inline-block;
            font-size: 13px;
            line-height: 26px;
            height: 28px;
            margin: 0;
            padding: 0 10px 1px;
            cursor: pointer;
            border-width: 1px;
            border-style: solid;
            -webkit-appearance: none;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            white-space: nowrap;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            font-family: inherit;
            font-weight: inherit;
        }

        .button:focus {
            border-color: #0e3950;
            -webkit-box-shadow: inset 0 1px 0 rgba(120, 200, 230, .6), 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, .8);
            box-shadow: inset 0 1px 0 rgba(120, 200, 230, .6), 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, .8);
        }

        .button:hover {
            background: #0091cd;
            border-color: #0073aa;
            -webkit-box-shadow: inset 0 1px 0 rgba(120, 200, 230, .6);
            box-shadow: inset 0 1px 0 rgba(120, 200, 230, .6);
            color: #fff;
        }

    </style>
</head>
<body>

<div class="widget-control-actions">
    <div class="alignright" style="margin-right: 10px;">
        <input type="submit" name="ywcca_shortcode_insert" id="ywcca_shortcode_insert" class="button" value="<?php _e( 'Insert Donation Form', 'ywcds' ); ?>">
    </div>
    <br class="clear">
</div>
<script type="text/javascript">


    jQuery(document).on('click', '.button', function () {


        var    str = '[yith_wcds_donations]',
            win = window.dialogArguments || opener || parent || top;

        win.send_to_editor(str);
        var ed;
        if (typeof tinyMCE != 'undefined' && ( ed = tinyMCE.activeEditor ) && !ed.isHidden()) {
            ed.setContent(ed.getContent());
        }

    });

</script>
</body>
</html>