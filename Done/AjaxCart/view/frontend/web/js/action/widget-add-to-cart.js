define([
    'jquery',
    'Done_AjaxCart/js/config',
    'Done_AjaxCart/js/action'
], function($, doneConfig) {
    "use strict";

    jQuery.widget('done.widgetAddToCart', jQuery.done.action, {
        options: {
            formKey: ''
        },
        _create: function() {
            var self = this;
            this._super();
            this.element.one('mouseup', function() {
                if (!self.element.closest('form').is(":data('doneCatalogAddToCart')")){
                    self.element.off('click');
                    self._on({
                        'click': self.onClick
                    });
                }
                
            });
        },
        getActionId: function() {
            return 'widget-add-to-cart-' + jQuery.now()
        },
        onClick: function(event) {
            event.preventDefault();
            event.stopImmediatePropagation();
            var postData = this.element.data('post');
            if (postData) {
                this.fire(this.element, this.getActionId(), postData.action, this._preparePostData(postData));
            } else if (this.element.is(":data('mageRedirectUrl')")) {
                var url = this.element.redirectUrl('option', 'url');
                if (url) {
                    this.fire(this.element, this.getActionId(), url, this._preparePostData({action: url}));
                }
            }
        },
        _preparePostData: function(postData) {
            var result = [];
            var data = postData.data || {};
            for (var name in data) {
                result.push({
                    'name': name,
                    'value': data[name]
                });
            }
            result.push({
                name: 'action_url',
                value: postData.action
            });
            result.push({
                name: 'form_key',
                value: this.options.formKey
            });
            return result;
        }
    });

    return jQuery.done.widgetAddToCart;
});
