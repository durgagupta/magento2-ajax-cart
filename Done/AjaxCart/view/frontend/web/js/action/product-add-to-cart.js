require([
    'jquery',
    'magnificPopup',
], function($) {
    jQuery('#product-addtocart-button').click(function(){
        var form = jQuery('#product_addtocart_form');
        var isValid = form.valid();
        if(isValid){
            jQuery(this).addClass('disabled');
            jQuery(this).text('Adding...');
            jQuery(this).attr('title','Adding...');
            var data = form.serializeArray();
            data.push({
                name: 'ajax',
                value: 1
            });
            jQuery.ajax({
                url: form.attr('action'),
                data: jQuery.param(data),
                type: 'post',
                dataType: 'json',
                beforeSend: function(xhr, options) {
                    jQuery('#done-ajax-loading').show();
                },
                success: function(response, status) {
                    if (status == 'success') {
                        if (response.ui) {
                            if(response.animationType){
                                var $content = '<div class="popup__main popup--result">'+response.ui + response.related + '</div>';                             
                                parent.jQuery.magnificPopup.open({
                                    items: {
                                        src: $content,
                                        type: 'inline'
                                    },
                                    closeOnBgClick: false,
                                    preloader: true,
                                    tLoading: ''
                                });
                            }else{
                                parent.jQuery.magnificPopup.close();
                            }                       
                        }
                    }
                }
            });
            return false;
        }
    });
});