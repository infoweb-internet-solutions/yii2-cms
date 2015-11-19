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

        // Remove eventhandlers that are bound by the kartik sidenav plugin
        $('.kv-toggle').unbind('click');

        // Add scrollbar to side menu
        $('.sidebar .kv-sidenav').height(window.innerHeight - 90 - 40).perfectScrollbar();

        // Toggle bootstrap tooltip
        $("[data-toggle='tooltip']").tooltip({
            container: 'body'
        });

        // Toggle bootstrap popover
        //$("[data-toggle='popover']").popover();

        // Set global eventhandlers
        $(document)
            .on('click', '.navbar-minimalize', CMS.toggleSidebar)
            .on('afterValidate', '.tabbed-form', CMS.showFirstFormTabWithErrors)
            .on('click', '[id^=grid-pjax] [data-toggleable]', CMS.pjaxGridItemToggle)
            .on('keyup change', '[data-slugable=true]', CMS.slugifyAttribute)
            .on('keydown', '[data-slugified=true]', CMS.validateSlug)
            .on('pjax:complete', CMS.pjaxComplete)
            .on('mouseover mouseout', '.mini-navbar .sidebar-nav li', function(e) {
                $(this).has('.nav-pills').find('.kv-toggle:first').trigger('click', [e.type]);
            })
            .on('click', '.kv-toggle', function(e, originalEventType) {
                e.preventDefault();

                // Triggered by mouseover
                /*if (originalEventType === 'mouseover') {
                    $(this).parent().addClass('active');
                    $(this).parent().children('ul').show();

                // Triggered by mouseout
                } else if(originalEventType === 'mouseout') {
                    $(this).parent().removeClass('active');
                    $(this).parent().children('ul').hide();

                // Default
                } else {*/
                    $(this).parent().toggleClass('active');

                    if ($(this).parent().hasClass('active')) {
                        $(this).parent().children('ul').show();
                    } else {
                        $(this).parent().children('ul').hide();
                    }

                //}
            })
            //.on('pjax:complete', '#grid-pjax', CMS.initSortable);

        // Trigger validation if a tabbed form is loaded
        if ($('.tabbed-form').length)
            $('.tabbed-form').trigger('afterValidate');

        // Init the duplicateable jquery plugin
        $('[data-duplicateable="true"]').duplicateable();
    };

    /**
     * Refresh sortable
     */
    CMS.initSortable = function() {
        $('#sortable tbody').sortable('refresh');
    };

    CMS.toggleSidebar = function(e) {
        CMS.widgets.sidebar.toggle();
    };

    /**
     * Shows the first tab on a tabbed form that contains validation errors
     *
     * @param   object  Event
     * @return  void
     */
    CMS.showFirstFormTabWithErrors = function(e) {
        if ($(".has-error").length) {
            var parentTabs = $(".has-error").eq(0).parents(".tab-pane");

            // Show the last parent tab
            $("a[href=#"+parentTabs.last().attr("id")+"]").tab("show");

            // If there are multiple parent tabs, show the first one
            if (parentTabs.length > 1)
                $("a[href=#"+parentTabs.first().attr("id")+"]").tab("show");

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

        var category = $(this).data('category');

        var action = $(this).attr('href'),
            id = $(this).data('toggle-id'),
            request = $.post(action, {id:id});

        request.done(function(response) {
            // Succes, reload PJAX grid
            if (response == 1) {
                $.pjax.reload({container: '#grid-pjax' + ((category) ? '-' + category : '')});
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

    CMS.validateSlug = function(e) {
        var el = $(this),
            placeholder = el.prop('placeholder'),
            value = el.val(),
            keyCode = e.keyCode || e.which;

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

    /**
     * Resizes an iframe so that it takes the dimensions of its content
     *
     * @param   object      The iframe
     * @param   int         An amount of pixels that will be added to the height as an adjustment
     * @return  void
     */
    CMS.autoSizeIframe = function(obj, adjustment) {
        var adjustment = adjustment || 0;

        obj.style.height = obj.contentWindow.document.body.scrollHeight + parseInt(adjustment) + 'px';
    };

    CMS.addLoaderClass = function(obj) {
        var h = (Math.max(document.documentElement.clientHeight, window.innerHeight || 0) - 91) / 2;

        obj.addClass('element-loading');
        obj.css('background-position', 'center '+h+'px');
    };

    CMS.removeLoaderClass = function(obj) {
        obj.removeClass('element-loading');
    };


    // Sidebar widget
    var sidebar = function() {
        this.state = 'open';
    };

    /**
     * Saves the state of the sidebar in a cookie
     *
     * @param   string      The state of the sidebar (open|closed)
     * @return  void
     */
    sidebar.prototype.setStateCookie = function(state) {
        var state = state || 'open';
        Cookies.set('infoweb-admin-sidebar-state', state, { expires: Infinity });
    };

    /**
     * Toggles the sidebar by adding a custom class to the body element.
     * The state is saved in a cookie
     *
     * @return  void
     */
    sidebar.prototype.toggle = function() {
        $('body').toggleClass('mini-navbar');

        var state = ($('body').hasClass('mini-navbar')) ? 'closed' : 'open';

        if (state == 'closed') {
            //$('.nav-pills').hide();
        }

        this.setStateCookie(state);
        this.state = state;
    };

    return CMS;
});