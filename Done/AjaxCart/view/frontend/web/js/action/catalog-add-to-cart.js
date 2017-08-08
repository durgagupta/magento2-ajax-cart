define([
    'jquery',
    'Done_AjaxCart/js/config',
    'Done_AjaxCart/js/action',
], function($, modal) {
    "use strict";
    jQuery.widget('done.catalogAddToCart', jQuery.done.action, {
        options: {
            bindSubmit: true,
            redirectToCatalog: false
        },
        _create: function() {
            if (this.options.bindSubmit) {
                this._super();
                this._on({
                    'submit': function(event) {
                        event.preventDefault();

						var data = this.element.serializeArray();
						data.push({
							name: 'action_url',
							value: this.element.attr('action')
						});
						this.fire(this.element,this.getActionId(), this.element.attr('action'), data, this.options.redirectToCatalog);
                    }
                });
            }
            
        },
        getActionId: function() {
            return 'catalog-add-to-cart-' + jQuery.now()
        }
    });

    return jQuery.done.catalogAddToCart;
});
