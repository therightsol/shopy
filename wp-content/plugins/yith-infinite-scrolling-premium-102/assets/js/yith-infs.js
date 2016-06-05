/**
 * yith-infs.js
 *
 * @author Your Inspiration Themes
 * @package YITH Infinite Scrolling
 * @version 1.0.0
 */

jQuery(document).ready( function($) {
    "use strict";

    if( typeof yith_infs_premium !== 'undefined' && yith_infs_premium.options ) {
        $.each( yith_infs_premium.options, function (key, value) {

            // set loader url
            value['presetLoader']   = yith_infs_premium.presetLoader[ value['presetLoader'] ];

            $.yit_infinitescroll( value );
        });
    }
});