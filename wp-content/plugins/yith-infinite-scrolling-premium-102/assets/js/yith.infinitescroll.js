(function ($, window, document) {
    "use strict";

    $.yit_infinitescroll = function (options) {

        var opts = $.extend({

                nextSelector    : '',
                navSelector     : '',
                itemSelector    : '',
                contentSelector : '',
                eventType       : 'scroll',
                presetLoader    : '',
                customLoader    : '',
                buttonLabel     : '',
                buttonClass     : '',
                loadEffect      : 'fadeIn'

            }, options),

            is_shop         =  ( typeof yith_infs_script !== 'undefined' ) ? yith_infs_script.shop : false,
            block_loader    =  ( typeof yith_infs_script !== 'undefined' ) ? yith_infs_script.block_loader : false,

            loading  = false,
            finished = false,
            loader   = false,
            button   = false,
            desturl  = $( opts.nextSelector ).attr( 'href' ); // init next url

        // validate selectors
        if( ! ( $( opts.nextSelector ).length && $( opts.navSelector ).length && $( opts.itemSelector ).length && $( opts.contentSelector ).length ) ) {
            return;
        }

        // hide std navigation
        if( opts.eventType != 'pagination' ) {
            $( opts.navSelector ).hide();
        }

        // set elem columns ( in shop page )
        if( is_shop ) {
            console.log('here');
            var first_elem  = $( opts.contentSelector ).find( opts.itemSelector ).first(),
                columns = first_elem.nextUntil( '.first', opts.itemSelector ).length + 1;
        }

        // main ajax function
        var main_ajax = function () {

            // get last elem
            var last_elem   = $( opts.contentSelector ).find( opts.itemSelector ).last();

            // add loader if any
            if( loader ) {
                $( opts.navSelector ).after('<div class="yith-infs-loader">' + loader + '</div>');
            }

            // set loading true
            loading = true;

            // ajax call
            $.ajax({
                // params
                url         : desturl,
                dataType    : 'html',
                success     : function (data) {

                    var obj  = $( data ),
                        cont = obj.find( opts.contentSelector ),
                        elem = obj.find( opts.itemSelector ),
                        nav  = obj.find( opts.navSelector ),
                        next = obj.find( opts.nextSelector );

                    if( next.length ) {
                        desturl = next.attr( 'href' );
                    }
                    else {
                        // set finished var true
                        finished = true;
                        $( document ).trigger( 'yith-infs-scroll-finished' );
                    }

                    // recalculate element position in shop
                    if( is_shop && ! last_elem.hasClass( 'last' ) && opts.eventType != 'pagination' ) {
                        position_elem( last_elem, columns, elem );
                    }

                    if( opts.eventType != 'pagination' ) {
                        last_elem.after( elem );
                    }
                    else {
                        $(opts.contentSelector).replaceWith(cont);
                        //change nav
                        $(opts.navSelector).replaceWith( nav );

                        $( window ).scrollTop(  $( opts.contentSelector).offset().top );
                    }

                    // remove loader if any
                    $( '.yith-infs-loader' ).remove();

                    $(document).trigger( 'yith_infs_adding_elem' );

                    elem.addClass( 'yith-infs-animated' );
                    elem.addClass( opts.loadEffect );

                    setTimeout( function(){
                        loading = false;
                    }, 1000 );
                }
            });
        };

        // recalculate element position
        var position_elem = function( last, columns, elem ) {

            var offset  = ( columns - last.prevUntil( '.last', opts.itemSelector ).length ),
                loop    = 0;

            elem.each(function () {

                var t = $(this);
                loop++;

                t.removeClass('first');
                t.removeClass('last');

                if ( ( ( loop - offset ) % columns ) === 0 ) {
                    t.addClass('first');
                }
                else if ( ( ( loop - ( offset - 1 ) ) % columns ) === 0 ) {
                    t.addClass('last');
                }
            });
        };


        // set event
        if( opts.eventType == 'scroll' ) {

            var loader_src = ( opts.customLoader == '' ) ? opts.presetLoader : opts.customLoader;
            loader = '<img src="' + loader_src + '">';

            // scroll event
            $( window ).on( 'scroll touchstart', function (){
                var t       = $(this),
                    offset  = $( opts.itemSelector ).last().offset();

                if ( ! loading && ! finished && ( t.scrollTop() + t.height() ) >= offset.top ) {
                    main_ajax();
                }
            });
        }
        else if( opts.eventType == 'button' ) {

            button = '<div class="yith-infs-button-wrapper"><button id="yith-infs-button" class="' + opts.buttonClass + '">' + opts.buttonLabel + '</button></div>';

            // add button if selector is valid
            $(opts.navSelector).after(button);

            // remove button if scroll is finished
            $( document ).on( 'yith-infs-scroll-finished', function() {
                $( '#yith-infs-button' ).remove();
            });


            // button event
            $( '#yith-infs-button' ).on( 'click', function() {

                var t = $(this);

                if( ! loading ) {
                    if( block_loader ) {
                        t.block({
                            message   : null,
                            overlayCSS: {
                                background: '#fff url(' + block_loader + ') no-repeat center',
                                opacity   : 0.5,
                                cursor    : 'none'
                            }
                        });
                    }
                    main_ajax();
                }

                $( document ).on( 'yith_infs_adding_elem', function(){
                    if( block_loader )
                        t.unblock();
                });
            })

        }
        else if( opts.eventType == 'pagination' ) {

            $( document ).on( 'click', opts.navSelector + ' a', function(e) {

                e.preventDefault();
                desturl = $(this).attr( 'href' );

                if( block_loader ) {
                    $( opts.navSelector ).block({
                        message   : null,
                        overlayCSS: {
                            background: '#fff url(' + block_loader + ') no-repeat center',
                            opacity   : 0.5,
                            cursor    : 'none'
                        }
                    });
                }

                main_ajax();
            })

        }
    }

})( jQuery, window, document );