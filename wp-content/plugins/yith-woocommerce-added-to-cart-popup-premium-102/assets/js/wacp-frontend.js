/**
 * wacp-frontend.js
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Added to Cart Popup
 * @version 1.0.0
 */

jQuery(document).ready(function($) {
    "use strict";

    if( typeof yith_wacp == 'undefined' )
        return;


    var popup       = $('#yith-wacp-popup'),
        overlay     = popup.find( '.yith-wacp-overlay'),
        close       = popup.find( '.yith-wacp-close'),
        close_popup = function(){
            // remove class open
            popup.removeClass( 'open' );
            // after 2 sec remove content
            setTimeout(function () {
                popup.find('.yith-wacp-content').html('');
            }, 2000);
        },
        // center popup function
        center_popup = function () {
            var t = popup.find( '.yith-wacp-wrapper' );

            t.css({
                'left' : '50%',
                'top' : '50%',
                'margin-left' : - t.outerWidth()/2,
                'margin-top' : - t.outerHeight()/2
            });
        };



    $( window ).on( 'resize yith_wacp_popup_changed', center_popup );

    $('body').on( 'added_to_cart wacp_single_added_to_cart', function( ev, fragmentsJSON, cart_hash, button ){

        if( typeof fragmentsJSON == 'undefined' )
            fragmentsJSON = $.parseJSON( sessionStorage.getItem( wc_cart_fragments_params.fragment_name ) );

        $.each( fragmentsJSON, function( key, value ) {

            if ( key == 'yith_wacp_message' ) {

                popup.find('.yith-wacp-content').html( value );

                // init action in popup
                init_action_popup();

                // position popup
                center_popup();

                //scroll
                if( typeof $.fn.perfectScrollbar != 'undefined' ) {
                    popup.find('.yith-wacp-content').perfectScrollbar({
                        suppressScrollX : true
                    });
                }

                popup.addClass('open');

                popup.find( 'a.continue-shopping' ).on( 'click', function (e) {
                    e.preventDefault();
                    close_popup();
                });

                return false;
            }
        });
    });

    // Close box trigger
    overlay.on( 'click', close_popup );
    close.on( 'click', function(ev){
        ev.preventDefault();

        close_popup();
    });


    var init_action_popup = function(){
        // remove from cart ajax
        $( '.yith-wacp-remove-cart').off('click').on( 'click', function(ev) {
            ev.preventDefault();


            var table = $(this).parents('table'),
                data = {
                    action: yith_wacp.actionremove,
                    _nonce: yith_wacp.remove_nonce,
                    item_key: $(this).data('item_key')
                };

            table.block({
                message   : null,
                overlayCSS: {
                    background: '#fff url(' + yith_wacp.loader + ') no-repeat center',
                    opacity   : 0.5,
                    cursor    : 'none'
                }
            });

            $.ajax({
                url: yith_wacp.ajaxurl,
                data: data,
                dataType: 'json',
                success: function( res ) {

                    $( '.yith-wacp-content table.cart-list' ).replaceWith( $( res.html ).filter( 'table.cart-list' ) );
                    $( '.yith-wacp-content div.cart-info' ).replaceWith( $( res.html ).filter( 'div.cart-info' ) );

                    // re-init action in popup
                    init_action_popup();

                    $( window ).trigger( 'yith_wacp_popup_changed' );
                }
            });
        })
    };

    /*######################################
     ADD TO CART AJAX IN SINGLE PRODUCT PAGE
    ########################################*/

    $( 'div.summary form.cart').on('submit', function(ev){

        if( typeof wc_cart_fragments_params === 'undefined' || ! yith_wacp.enable_single ) {
            return;
        }

        // check if exluded
        var t = $(this),
            button = t.find( 'button[type="submit"]'),
            exclude = t.find( 'input[name="yith-wacp-is-excluded"]' );

        if( exclude.length )
            return;

        ev.preventDefault();

        button.addClass('loading')
            .removeClass('added');

        var form = t.serializeArray();

        // add action and nonce
        form.push({name: "_nonce", value: yith_wacp.add_nonce }, { name: "action", value: yith_wacp.actionadd } );

        $.ajax({
            url: yith_wacp.ajaxurl,
            data: $.param( form ),
            success: function( res ) {
                // add error notice
                if( res.msg ) {

                    // add mess and scroll to Top
                    t.parents( 'div.product' ).before( res.msg );
                    $('body').animate({
                        scrollTop: 0
                    }, 500);

                    // reset button
                    button.removeAttr( 'disabled')
                        .removeClass( 'loading');

                    return;
                }

                // refresh fragments
                var $supports_html5_storage;
                try {
                    $supports_html5_storage = ( 'sessionStorage' in window && window.sessionStorage !== null );

                    window.sessionStorage.setItem( 'wc', 'test' );
                    window.sessionStorage.removeItem( 'wc' );
                } catch( err ) {
                    $supports_html5_storage = false;
                }

                $.ajax({
                    url: wc_cart_fragments_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_refreshed_fragments' ),
                    type: 'POST',
                    data: {
                        product_id: res.prod_id
                    },
                    success: function( data ) {
                        if ( data && data.fragments ) {

                            $.each( data.fragments, function( key, value ) {
                                $( key ).replaceWith( value );
                            });

                            if ( $supports_html5_storage ) {
                                sessionStorage.setItem( wc_cart_fragments_params.fragment_name, JSON.stringify( data.fragments ) );
                                sessionStorage.setItem( 'wc_cart_hash', data.cart_hash );
                            }

                            $( 'body' ).trigger( 'wc_fragments_refreshed' )
                                       .trigger( 'wacp_single_added_to_cart' );

                            // remove disabled from submit button
                            button.removeAttr( 'disabled')
                                .removeClass( 'loading')
                                .addClass('added');
                        }
                    }
                });
            }
        });
    });
});