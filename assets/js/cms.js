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
            .on('afterValidate', '.tabbed-form', CMS.showFirstFormTabWithErrors)
            .on('click', '#grid-pjax [data-toggle-active]', CMS.pjaxGridItemToggleActive);    
    };
    
    // Shows the first tab on a tabbed form that contains validation errors
    CMS.showFirstFormTabWithErrors = function(e) {
        if ($(".has-error").length)
            $("a[href=#"+$(".has-error").parents(".tab-pane").attr("id")+"]").tab("show");    
    };
    
    // Toggles the 'active' state of an item in a PJAX grid
    CMS.pjaxGridItemToggleActive = function(e) {
        e.preventDefault();
        
        var id = $(this).data('toggle-active'),
            request = $.post('active', {id:id});
        
        request.done(function(response) {
            // Succes, reload PJAX grid
            if (response == 1) {
                $.pjax.reload({container:'#grid-pjax'});
            } else {
                // @todo: catch error
                return false;                
            }
        });
    };
    
    return CMS;    
});