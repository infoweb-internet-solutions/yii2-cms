(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        define(factory);
    } else if (typeof exports === 'object') {
        module.exports = factory;
    } else {
        root.CMS = factory();
    }    
})(this, function () {

    'use strict';
    
    var CMS = {};
    
    // Module initialization
    CMS.init = function() {
        // Set global eventhandlers
        $(document)
            .on('afterValidate', '.tabbed-form', CMS.showFirstFormTabWithErrors);    
    };
    
    // Shows the first tab on a tabbed form that contains validation errors
    CMS.showFirstFormTabWithErrors = function(e) {
        if ($(".has-error").length)
            $("a[href=#"+$(".has-error").parents(".tab-pane").attr("id")+"]").tab("show");    
    };
    
    return CMS;    
});