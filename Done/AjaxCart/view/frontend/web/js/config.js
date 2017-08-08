define([
    'jquery'
], function($) {
    "use strict";

    var doneConfig = {
        requestParamName: 'ajax',
    };
    doneConfig.setOptions = function(options) {
        for (var optionName in options) {
            this[optionName] = options[optionName];
        }
    };

    return doneConfig;
});
