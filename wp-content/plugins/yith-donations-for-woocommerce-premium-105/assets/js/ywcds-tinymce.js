jQuery(function ($) {

    //TinyMCE Button
    var image_url = '';
    tinymce.create('tinymce.plugins.YITH_WooCommerce_Donations', {
        init : function(ed, url) {
            ed.addButton('ywcds_shortcode', {
                title : 'Add Donation',
                image : url+'/../images/donate-icon.png',
                onclick : function() {
                    $('#ywcds_shortcode').click();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo      : function () {
            return {
                longname : 'YITH WooCommerce Donations',
                author   : 'YITHEMES',
                authorurl: 'http://yithemes.com/',
                infourl  : 'http://yithemes.com/',
                version  : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('ywcds_shortcode', tinymce.plugins.YITH_WooCommerce_Donations );

});
