// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;(function ( $, window, document, undefined ) {

    "use strict";

    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.

    // window and document are passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = "duplicateable",
        defaults = {
            propertyName: "value"
        };

    // The actual plugin constructor
    function Plugin ( element, options ) {
        this.element = element;
        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.settings = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {
        init: function () {
            this.convertDuplicateAllIcons();
            this.createDuplicationButton();
        },
        /**
         * Wrap the 'duplicate-all' icons in the tabs with the necessary html
         *
         * @return void
         */
        convertDuplicateAllIcons: function() {
            var plugin = this;
            $.each($('.duplicateable-all-icon.not-converted'), function() {

                var icon = $(this).clone().removeClass('not-converted'),
                    btn = $('<a></a>')
                        .attr('href', '#')
                        .attr('data-duplicateable-language', icon.attr('data-language'))
                        .attr('data-pjax', 0)
                        .addClass('duplicateable-all-btn')
                        .html(icon),
                    container = $('<div></div>')
                        .addClass('duplicateable-all-container')
                        .html(btn);

                $(this).replaceWith(container);

                // Bind the click event to the button
                btn.on('click', plugin.duplicateAllContent);
            });
        },
        /**
         * Inserts a duplication button before the element
         *
         * @return void
         */
        createDuplicationButton: function() {

            // Split the name of the element into pieces
            // A valid element should have a name structure of modelName[language][attributeName]
            var parts = $(this.element).attr('name').match(/([\w\d]+)\[(\w+)\]\[(\w+)\]/);

            // Dont create the button if the element name does not matches the pattern
            if (!parts.length || parts.length < 4)
                return false;

            var icon = $('<i></i>')
                        .addClass('fa')
                        .addClass('fa-exchange'),
                btn = $('<a></a>')
                        .attr('href', '#')
                        .attr('data-duplicateable-model', parts[1])
                        .attr('data-duplicateable-language', parts[2])
                        .attr('data-duplicateable-attribute', parts[3])
                        .addClass('duplicateable-btn')
                        .html(icon),
                container = $('<div></div>')
                            .addClass('duplicateable-container')
                            .html(btn);

            // Insert the btn container after the label that belongs to the element
            $(this.element)
                .parents('.form-group')
                .eq(0)
                .find('label')
                .eq(0)
                .after(container);

            // Bind the click event to the button
            btn.on('click', this.duplicateContent);
        },
        /**
         * Duplicates the content of all duplicateable attributes in the active tab pane
         *
         * @param  Event
         * @return void
         */
        duplicateAllContent: function(e) {
            e.preventDefault();
            var language = $(this).attr('data-duplicateable-language');

            // Trigger a click on all the duplicate buttons with the same language in the active tab pane
            $('.tab-pane.active .duplicateable-btn[data-duplicateable-language="'+language+'"]').trigger('click');
        },
        /**
         * Duplicates the content of the element into the similar elements
         *
         * @param  Event
         * @return void
         */
        duplicateContent: function(e) {
            e.preventDefault();
            var btn = this,
                model = $(this).attr('data-duplicateable-model'),
                language = $(this).attr('data-duplicateable-language'),
                attribute = $(this).attr('data-duplicateable-attribute'),
                element = $('[name="'+model+'['+language+']['+attribute+']"]'),
                value = element.val();

            // The field is a CKeditor
            if (element.is('textarea') && element.next().hasClass('cke') && typeof CKEDITOR !== 'undefined') {
                var ckeditorId = model.toLowerCase() + '-' + language.toLowerCase() + '-' + attribute.toLowerCase(),
                    value = CKEDITOR.instances[ckeditorId].getData(),
                    // The name of the editor instance has to match this pattern
                    regex = new RegExp('^'+model.toLowerCase()+'-[a-z]{2}-'+attribute.toLowerCase()+'$');

                // Set the content of all other
                $.each(CKEDITOR.instances, function(i) {
                    if (i !== ckeditorId && regex.test(i)) {
                        CKEDITOR.instances[i].setData(value);
                    }
                });
            // The field is an input element
            } else {
                // Copy the value to the similar elements
                $('[name^="'+model+'"][name$="['+attribute+']"]')
                    .not('[name="'+model+'['+language+']['+attribute+']"]')
                    .val(value);
            }

            // Animate the btn style
            $(btn).addClass('copied');
            setTimeout(function() {
                $(btn).removeClass('copied');
            }, 750);
        }
    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[ pluginName ] = function ( options ) {
        return this.each(function() {
            if ( !$.data( this, "plugin_" + pluginName ) ) {
                $.data( this, "plugin_" + pluginName, new Plugin( this, options ) );
            }
        });
    };

})( jQuery, window, document );