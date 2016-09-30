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
                btn.on('click', plugin.showDuplicateableModal);
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
            var parts = $(this.element).attr('name').match(/([\w\d]+)\[([-\w]+)\]\[([-\w]+)\]/);

            // Dont create the button if the element name does not matches the pattern
            if (!parts || !parts.length || parts.length < 4)
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

            // Specific code to insert button for bootstrap switch
            var bootstrapSwitch = $(this.element).attr('data-krajee-bootstrapSwitch'),
                isBootstrapSwitch = typeof bootstrapSwitch !== typeof undefined && bootstrapSwitch !== false;

            // Check parents element
            var eq = (isBootstrapSwitch) ? 1 : 0;

            // Insert the btn container after the label that belongs to the element
            $(this.element)
                .parents('.form-group')
                .eq(eq)
                .find('label')
                .eq(0)
                .after(container);

            // Bind the click event to the button
            btn.on('click', this.showDuplicateableModal);
        },
        showDuplicateableModal: function(e) {
            // Collect data attributes of the pushed btn
            var modal = $('#duplicateable-modal'),
                duplicateableData = {
                    scope: 'all',
                    language: $(this).attr('data-duplicateable-language')
                };

            // Collect extra data attributes
            if ($(this).hasClass('duplicateable-btn')) {
                duplicateableData.scope = 'element';
                duplicateableData.model = $(this).attr('data-duplicateable-model');
                duplicateableData.attribute = $(this).attr('data-duplicateable-attribute');
            }

            // Set the data attributes of the modal
            $.each(duplicateableData, function(i) {
                modal.data('duplicateable.'+i, this);
            });

            // Reset the checkboxes in the modal:
            // - Re-enable and check all languages
            // - Disable and uncheck the language of the clicked toggler
            // - Uncheck the "duplicate empty values" setting
            // - Check the "overwrite values" setting
            modal.find('.duplicateable-languages :checkbox').prop('checked', true).prop('disabled', false);
            modal.find('.duplicateable-languages :checkbox[value="'+duplicateableData.language+'"]').prop('checked', false).prop('disabled', true);
            modal.find('.duplicate-empty-values').prop('checked', false);
            modal.find('.overwrite-values').prop('checked', true);
            modal.modal('show');
        },
        /**
         * Creates a settings object, based on the state of the duplicateable modal
         *
         * @return object
         */
        extractSettingsFromModal: function() {
            // Create the settings object
            var settings = {
                    modal: $('#duplicateable-modal'),
                    duplicateEmptyValues: $('.duplicate-empty-values').is(':checked'),
                    overwriteValues: $('.overwrite-values').is(':checked'),
                };
            settings.scope = settings.modal.data('duplicateable.scope');
            settings.language = settings.modal.data('duplicateable.language');
            settings.selectedLanguages = settings.modal.find('.duplicateable-languages:checked').map(function() {
                return this.value;
            }).get();

            return settings;
        },
        /**
         * Duplicates content
         *
         * @param {Event} e
         */
        duplicate: function(e) {
            e.preventDefault();
            // Create the settings object
            var settings = Plugin.prototype.extractSettingsFromModal();

            // Duplicate single element
            if (settings.scope == 'element') {

                settings.model = settings.modal.data('duplicateable.model');
                settings.attribute = settings.modal.data('duplicateable.attribute');
                // The original element
                var element = $('[name="'+settings.model+'['+settings.language+']['+settings.attribute+']"]');

                Plugin.prototype.duplicateElement(element, settings).done(function() {
                    // Close the modal
                    $('#duplicateable-modal').modal('hide');
                });

            // Duplicate all elements
            } else {
                var promises = [],
                    elements = $('.tab-pane.active .duplicateable-btn[data-duplicateable-language="'+settings.language+'"]');

                $.each(elements, function() {
                    var dfd = $.Deferred();
                    settings.model = $(this).attr('data-duplicateable-model');
                    settings.language = $(this).attr('data-duplicateable-language');
                    settings.attribute = $(this).attr('data-duplicateable-attribute');
                    // The original element
                    var element = $('[name="'+settings.model+'['+settings.language+']['+settings.attribute+']"]');

                    Plugin.prototype.duplicateElement(element, settings).done(function() {
                        dfd.resolve();
                    });

                    promises.push(dfd);
                });

                $.when.apply($, promises).done(function() {
                    // Close the modal
                    $('#duplicateable-modal').modal('hide');
                });
            }
        },
        /**
         * Duplicates a single element
         *
         * @param {jQuery object}  element     The element who's content has to be duplicated
         * @param {object} settings
         * @return {Promise}
         */
        duplicateElement: function(element, settings) {
            var promises = [];
            // Extend the settings
            settings.value = element.val(),
            settings.ckeditorId = false;
            settings.bootstrapSwitch = false;

            // The field is a CKeditor
            if (element.is('textarea') && element.next().hasClass('cke') && typeof CKEDITOR !== 'undefined') {
                settings.ckeditorId = settings.model.toLowerCase() + '-' + settings.language.toLowerCase() + '-' + settings.attribute.toLowerCase();
                settings.value = CKEDITOR.instances[settings.ckeditorId].getData();
            }

            // The field is a Bootstrap Switch
            var bootstrapSwitch = $(element).eq(1).attr('data-krajee-bootstrapSwitch');
            if(typeof bootstrapSwitch !== typeof undefined && bootstrapSwitch !== false) {
                settings.value = $(this).prop('checked') ? 1 : 0;
                settings.ckeditorId = false;
                settings.bootstrapSwitch = true;
            }

            $.each(settings.selectedLanguages, function() {
                var dfd = $.Deferred();
                Plugin.prototype.copyContent(settings, this).done(function() {
                    dfd.resolve();
                });
                promises.push(dfd);
            });

            return $.when.apply($, promises).promise();
        },
        /**
         * Copy content to an element, based on the settings that are passed:
         *     - model: The model name
         *     - attribute: The attribute name
         *     - value: The value that has to be copied
         *     - ckeditorId: false or the id of the ckeditor instance
         *     - duplicateEmptyValues: if true, the value has to be copied even
         *                             if it's empty
         *     - overwriteValues: if true, the value has to be copied even if the
         *                        field is not empty
         *
         * @param  {object} settings
         * @param  {string} language    The language of element that receives the content
         * @return {Promise}
         */
        copyContent: function(settings, language) {
            var dfd = $.Deferred();
            // Duplicate empty values?
            if (settings.value !== '' || (settings.value === '' && settings.duplicateEmptyValues)) {
                // CKEditor
                if (typeof settings.ckeditorId !== 'undefined' && settings.ckeditorId !== false) {                    
                    // The ckeditorId of the original element is provided, so to find
                    // the id of the element's instance, we have to replace the original
                    // language with the provided one
                    var ckeditorId = settings.ckeditorId.replace(settings.language, language),
                        currentValue = CKEDITOR.instances[ckeditorId].getData();

                    // Overwrite values?
                    if (settings.overwriteValues || (!settings.overwriteValues && currentValue === '')) {
                        CKEDITOR.instances[ckeditorId].setData(settings.value);
                    }
                }
                // Bootstrap Switch
                else if(settings.bootstrapSwitch == true) {
                    // Overwrite values?
                    if (settings.overwriteValues) {
                        if (settings.value == true) {
                            $('[type="checkbox"][name="'+settings.model+'['+language+']['+settings.attribute+']"]').bootstrapSwitch('state', true);
                        }
                        else {
                            $('[type="checkbox"][name="'+settings.model+'['+language+']['+settings.attribute+']"]').bootstrapSwitch('state', true);
                        }

                    }
                }
                // Input field
                else {                    
                    var currentValue = $('[name="'+settings.model+'['+language+']['+settings.attribute+']"]').val();

                    // Overwrite values?
                    if (settings.overwriteValues || (!settings.overwriteValues && currentValue === '')) {
                        $('[name="'+settings.model+'['+language+']['+settings.attribute+']"]').val(settings.value);
                    }
                }
            }

            dfd.resolve();
            return dfd.promise();
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

    // Bind event to the duplication btn
    $('#duplicateable-modal #do-duplication').on('click', Plugin.prototype.duplicate);

})( jQuery, window, document );