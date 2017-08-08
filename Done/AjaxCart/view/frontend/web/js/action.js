define([
    'jquery',
    'Done_AjaxCart/js/config',
    'magnificPopup'
], function($, doneConfig) {
    "use strict";

    jQuery.widget('done.action', {
        options: {
            requestParamName: doneConfig.requestParamName
        },
        fire: function(tag, actionId, url, data, redirectToCatalog) {
            this._fire(tag, actionId, url, data);
        },
        _fire: function(tag, actionId, url, data) {
            var $addToCart = '';
            if(tag.find('.tocart').length){
                $addToCart = tag.find('.tocart').text();
            }else{
                $addToCart = tag.text();
            }            
            var self = this;
            data.push({
                name: this.options.requestParamName,
                value: 1
            });
            
            jQuery.ajax({
                url: url,
                data: jQuery.param(data),
                type: 'post',
                dataType: 'json',
                beforeSend: function(xhr, options) {
                	
                    if(doneConfig.animationType || typeof doneConfig.animationType == 'undefined'){  
                        jQuery('#done-ajax-loading').show();
                    }else{
                        if(tag.find('.tocart').length){
                            tag.find('.tocart').addClass('disabled');
                            tag.find('.tocart').text('Adding...');
                            tag.find('.tocart').attr('title','Adding...');
                        }else{
                            tag.addClass('disabled');
                            tag.text('Adding...');
                            tag.attr('title','Adding...');
                        } 
                        
                    }
                },
                success: function(response, status) {
                    if (status == 'success') {
                        if(response.backUrl){
                            data.push({
                                name: 'action_url',
                                value: response.backUrl
                            });
                            self._fire(tag, actionId, response.backUrl, data);
                        } else {
                            if (response.ui) {
                            	
                                if(response.productView) {
                                    jQuery('#done-ajax-loading').hide();
                                        jQuery.magnificPopup.open({
                                            items: {
                                                src: response.ui,
                                                type: 'iframe'
                                            },
											mainClass: 'success-ajax--popup',
                                            closeOnBgClick: false,
                                            preloader: true,
                                            tLoading: '',
                                            callbacks: {
                                                open: function() {
                                                    jQuery('#done-ajax-loading').hide();
                                                    jQuery('.mfp-preloader').css('display', 'block');
                                                },
                                                beforeClose: function() {
                                                    var url_cart_update = doneConfig.updateCartUrl;
                                                    jQuery('[data-block="minicart"]').trigger('contentLoading');
                                                    jQuery.ajax({
                                                        url: url_cart_update,
                                                        method: "POST"
                                                    });
                                                },
                                                close: function() {
                                                    jQuery('.mfp-preloader').css('display', 'none');
                                                },
                                                afterClose: function() {
                                                    if(!response.animationType) {
                                                        var $source = '';
                                                        if(tag.find('.tocart').length){
                                                            tag.find('.tocart').removeClass('disabled');
                                                            tag.find('.tocart').text($addToCart);
                                                            tag.find('.tocart').attr('title',$addToCart);
                                                            if(tag.closest('.product-item-info').length){
                                                                $source = tag.closest('.product-item-info');
																var width = $source.outerWidth();
																var height = $source.outerHeight();
                                                            }else{
                                                                $source = tag.find('.tocart');
																var width = 300;
																var height = 300;
                                                            }
                                                            
                                                        }else{
                                                            tag.removeClass('disabled');
                                                            tag.text($addToCart);
                                                            tag.attr('title',$addToCart);
                                                            $source = tag.closest('.product-item-info');
															var width = $source.outerWidth();
															var height = $source.outerHeight();
                                                        }
                                                        
                                                        jQuery('html, body').animate({
                                                            'scrollTop' : jQuery(".minicart-wrapper").position().top
                                                        },2000);
                                                        var $animatedObject = jQuery('<div class="flycart-animated-add" style="position: absolute;z-index: 99999;">'+response.image+'</div>');
                                                        var left = $source.offset().left;
                                                        var top = $source.offset().top;
                                                        $animatedObject.css({top: top-1, left: left-1, width: width, height: height});
                                                        jQuery('html').append($animatedObject);
                                                        var divider = 3;
                                                        var gotoX = jQuery(".minicart-wrapper").offset().left + (jQuery(".minicart-wrapper").width() / 2) - ($animatedObject.width()/divider)/2;
                                                        var gotoY = jQuery(".minicart-wrapper").offset().top + (jQuery(".minicart-wrapper").height() / 2) - ($animatedObject.height()/divider)/2;                                               
                                                        $animatedObject.animate({
                                                            opacity: 0.6,
                                                            left: gotoX,
                                                            top: gotoY,
                                                            width: $animatedObject.width()/2,
                                                            height: $animatedObject.height()/2
                                                        }, 2000,
                                                        function () {
                                                            jQuery(".minicart-wrapper").fadeOut('fast', function () {
                                                                jQuery(".minicart-wrapper").fadeIn('fast', function () {
                                                                    $animatedObject.fadeOut('fast', function () {
                                                                        $animatedObject.remove();
                                                                    });
                                                                });
                                                            });
                                                        });
                                                    }
                                                }
                                            }
                                        });
                                } else {
                                    var $content = '<div class="popup__main popup--result">'+response.ui + response.related + '</div>';
                                    if(response.animationType) {
                                        jQuery('#done-ajax-loading').hide();
                                        jQuery.magnificPopup.open({
											mainClass: 'success-ajax--popup',
                                            items: {
                                                src: $content,
                                                type: 'inline'
                                            },
                                            callbacks: {
                                                open: function() {
                                                    jQuery('#done-ajax-loading').hide();
                                                },
                                                beforeClose: function() {
                                                    var url_cart_update = doneConfig.updateCartUrl;
                                                    jQuery('[data-block="minicart"]').trigger('contentLoading');
                                                    jQuery.ajax({
                                                        url: url_cart_update,
                                                        method: "POST"
                                                    });
                                                }  
                                            }
                                        });
                                    }else {
                                        var $source = '';
                                        if(tag.find('.tocart').length){
                                            tag.find('.tocart').removeClass('disabled');
                                            tag.find('.tocart').text($addToCart);
                                            tag.find('.tocart').attr('title',$addToCart);
                                            if(tag.closest('.product-item-info').length){
                                                $source = tag.closest('.product-item-info');
												var width = $source.outerWidth();
												var height = $source.outerHeight();
                                            }else{
                                                $source = tag.find('.tocart');
												var width = 300;
												var height = 300;
                                            }
                                            
                                        }else{
                                            tag.removeClass('disabled');
                                            tag.text($addToCart);
                                            tag.attr('title',$addToCart);
                                            $source = tag.closest('.product-item-info');
											var width = $source.outerWidth();
											var height = $source.outerHeight();
                                        }
                                        
                                        jQuery('html, body').animate({
                                            'scrollTop' : jQuery(".minicart-wrapper").position().top
                                        },2000);
                                        var $animatedObject = jQuery('<div class="flycart-animated-add" style="position: absolute;z-index: 99999;">'+response.image+'</div>');
                                        var left = $source.offset().left;
                                        var top = $source.offset().top;
                                        $animatedObject.css({top: top-1, left: left-1, width: width, height: height});
                                        jQuery('html').append($animatedObject);
                                        var divider = 3;
                                        var gotoX = jQuery(".minicart-wrapper").offset().left + (jQuery(".minicart-wrapper").width() / 2) - ($animatedObject.width()/divider)/2;
                                        var gotoY = jQuery(".minicart-wrapper").offset().top + (jQuery(".minicart-wrapper").height() / 2) - ($animatedObject.height()/divider)/2;                                               
                                        $animatedObject.animate({
                                            opacity: 0.6,
                                            left: gotoX,
                                            top: gotoY,
                                            width: $animatedObject.width()/2,
                                            height: $animatedObject.height()/2
                                        }, 2000,
                                        function () {
                                            jQuery(".minicart-wrapper").fadeOut('fast', function () {
                                                jQuery(".minicart-wrapper").fadeIn('fast', function () {
                                                    $animatedObject.fadeOut('fast', function () {
                                                        $animatedObject.remove();
                                                    });
                                                });
                                            });
                                        });
                                    }
                                }
                            }
                        }                            
                    }
                },
                error: function() {
                    jQuery('#done-ajax-loading').hide();
                    window.location.href = doneConfig.redirectCartUrl;
                }
            });
        }
    });

    return jQuery.done.action;
});