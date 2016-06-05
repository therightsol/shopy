jQuery(document).ready(function($){
    var  admin_field_init   =   function(){

        var button_select   =   $('#ywcds_button_style_select'),
            button_typ      =   $('#ywcds_button_typography'),
            button_col      =   $('#ywcds_text_color'),
            button_bg       =   $('#ywcds_bg_color'),
            button_col_hov  =   $('#ywcds_text_hov_color'),
            button_bg_hov   =   $('#ywcds_bg_hov_color');


        if( button_select.val()=="wc" ){
            button_typ.hide();
            button_col.parents( 'tr').hide();
            button_bg.parents( 'tr').hide();
            button_col_hov.parents( 'tr').hide();
            button_bg_hov.parents( 'tr').hide();
        }
        else{

            button_typ.show();
            button_col.parents( 'tr').show();
            button_bg.parents( 'tr').show();
            button_col_hov.parents( 'tr').show();
            button_bg_hov.parents( 'tr').show();
        }

        button_select.on('change', function(){

            var t   =   $(this);

            if( t.val()=="wc" ){
                button_typ.hide();
                button_col.parents( 'tr' ).hide();
                button_bg.parents( 'tr' ).hide();
                button_col_hov.parents( 'tr' ).hide();
                button_bg_hov.parents( 'tr' ).hide();
            }
            else{

                button_typ.show();
                button_col.parents( 'tr' ).show();
                button_bg.parents( 'tr' ).show();
                button_col_hov.parents( 'tr' ).show();
                button_bg_hov.parents( 'tr' ).show();
            }

        });
    }

    var check_date_field    =   function(){

        var filter_button  =   $('#ywcds_filter_by_date');

        filter_button.on('click', function(e){

            if(  $("#start_date_filter").val() != '' &&  $("#end_date_filter").val()=='' ) {
                $('#end_date_filter').css("background-color", "#f85454");
                e.preventDefault();
            }

           else if( $("#start_date_filter").val() == '' &&  $("#end_date_filter").val()!='' ){
                $('#start_date_filter').css("background-color", "#f85454");

                e.preventDefault();
            }

           else if ( ( $.datepicker.parseDate("yy-mm-dd", $("#start_date_filter").val()) > $.datepicker.parseDate("yy-mm-dd", $("#end_date_filter").val()) )) {
                $('#start_date_filter').css("background-color", "#f85454");
                $('#end_date_filter').css("background-color", "#f85454");

                e.preventDefault();
            }

        });
    }



    $('body').on('ywcds-admin-field-init', function () {
        admin_field_init();
        check_date_field();
    }).trigger( 'ywcds-admin-field-init' );


    $('.date-picker').datepicker({
        dateFormat: 'yy-mm-dd'
    });

});

var  ywcds_resize_thickbox = function( w, h ) {

    w   =   w || 400;
    h   =   h || 350;


    var myWidth = w,
        myHeight = h;


    var tbWindow = jQuery('#TB_window'),
        tbFrame = jQuery('#TB_iframeContent'),
        wpadminbar = jQuery('#wpadminbar'),
        width = jQuery(window).width(),
        height = jQuery(window).height(),

        adminbar_height = 0;

    if (wpadminbar.length) {
        adminbar_height = parseInt(wpadminbar.css('height'), 10);
    }

    var TB_newWidth = ( width < (myWidth + 50)) ? ( width - 50) : myWidth;
    var TB_newHeight = ( height < (myHeight + 45 + adminbar_height)) ? ( height - 45 - adminbar_height) : myHeight;

    tbWindow.css({
        'marginLeft': -(TB_newWidth / 2),
        'marginTop' : -(TB_newHeight / 2),
        'top'       : '50%',
        'width'     : TB_newWidth,
        'height'    : TB_newHeight
    });

    tbFrame.css({
        'padding': '10px',
        'width'  : TB_newWidth - 20,
        'height' : TB_newHeight - 50
    });
}