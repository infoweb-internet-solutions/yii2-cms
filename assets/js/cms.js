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
    
    var CMS = {
        'widgets': []
    };
    
    /**
     * Initializes the module:
     *  - Set global eventhandlers
     * 
     * @return  void 
     */
    CMS.init = function() {
        
        // Create the sidebar widget
        CMS.widgets.sidebar = new sidebar();
        
        // Set global eventhandlers
        $(document)
            .on('click', '.navbar-minimalize', CMS.toggleSidebar)
            .on('afterValidate', '.tabbed-form', CMS.showFirstFormTabWithErrors)
            .on('click', '#grid-pjax [data-toggleable=true]', CMS.pjaxGridItemToggle)
            .on('keyup change', '[data-slugable=true]', CMS.slugifyAttribute)
            .on('keydown', '[data-slugified=true]', CMS.validateSlug)
            .on('pjax:complete', CMS.pjaxComplete);    
    };
    
    /**
     * Toggles the sidebar by adding/removing a specific class on the body
     * 
     * @param   object  Event
     * @return  void
     */
    CMS.toggleSidebar = function(e) {
        $('body').toggleClass('mini-navbar');    
    };
    
    /**
     * Shows the first tab on a tabbed form that contains validation errors 
     * 
     * @param   object  Event
     * @return  void
     */
    CMS.showFirstFormTabWithErrors = function(e) {
        if ($(".has-error").length) {
            $("a[href=#"+$(".has-error").eq(0).parents(".tab-pane").attr("id")+"]").tab("show");
            CMS.scrollToElement('.has-error');
        }    
    };
    
    /**
     * Toggles the state of a 'toggleable' item in a PJAX grid 
     * 
     * @param   object  Event
     * @return  void
     */
    CMS.pjaxGridItemToggle = function(e) {
        e.preventDefault();
        
        var action = $(this).attr('href'),
            id = $(this).data('toggle-id'),
            request = $.post(action, {id:id});
        
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
    
    /**
     * Slugifies an attribute and uses it as the value for an other attribute 
     * 
     * @param   object  Event
     * @return  void
     */
    CMS.slugifyAttribute = function(e) {
        var targetElement = $($(this).data('slug-target')),
            targetPlaceholder = targetElement.prop('placeholder'),
            slug = I18N.slugify($(this).val());
            
        targetElement.val(targetPlaceholder + slug);
    };
    
    /**
     * Validates the content of a slug 
     * 
     * @param   object  Event
     * @return  boolean
     */
    CMS.validateSlug = function(e) {
        var el = $(this),
            placeholder = el.prop('placeholder'),
            value = el.val(),
            keyCode = e.keyCode || e.which;
                        
        if (keyCode == 191 || keyCode == 111)
            return false;
            
        return true;    
    };
    
    /**
     * Scrolls to an element with a given speed
     * 
     * @param   string      The selector
     * @return  void 
     */
    CMS.scrollToElement = function(selector, speed) {
        // Select the element (limit to the first)
        var el = $(selector)[0],
            speed = speed || 250;
        
        // Check for the existance of the element
        if($(el).length == 0)
            return false;
            
        // Scroll
        $('html,body').animate({scrollTop: $(el).offset().top - 91}, speed); 
    };
    
    /**
     * Performs actions when a pjax request is completed 
     *
     * @param   Event
     * @return  void
     */
    CMS.pjaxComplete = function(e) {
        // Remove tooltips that are left behind in the DOM
        $('.tooltip').remove();
        
        // Re-initializes tooltips
        $('[data-toggle]').tooltip();    
    };
    
    // Sidebar widget
    var sidebar = function() {
        this.state = 'open';
        
        // Check it's state
        this.checkState();        
    };
    
    sidebar.prototype.checkState = function() {
   
    };

    return CMS;    
});