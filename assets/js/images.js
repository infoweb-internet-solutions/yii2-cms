
var growlTemplate = '<div data-notify="container" class="col-xs-11 col-sm-4 alert alert-{0}" role="alert"><button type="button" aria-hidden="true" class="close" data-notify="dismiss">&times;</button><span data-notify="icon"></span> <span data-notify="title">{1}</span> <span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a></div>';

$(function() {

    // Fancybox
    $('.fancybox').fancybox();

    // Set eventhandlers
    $(document)
        .on('click', '.select-on-check-all', toggleCheckboxes)
        .on('click', '#gridview-container .kv-row-select input', toggleSelectAll)
        .on('filebatchuploadsuccess fileuploaded', '#file-upload', afterUpload);

    $(document).on('click', '#batch-delete', function (event) {
        event.preventDefault();

        //var ids = $('#gridview-container').yiiGridView('getSelectedRows');
        var ids = [],
            url = 'multiple-delete-confirm-message';

        $('#gridview-container').find("input[name='selection[]']:checked").each(function () {
            ids.push($(this).parent().closest('tr').data('key'));
        });

        if ($(this).attr('data-url'))
            url = $(this).attr('data-url');

        // @todo Remove first ajax request and translate in javascript (available in version 2.1)
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                'ids': ids.length
            },
            success: function (message) {

                bootbox.confirm(message, function (confirmed) {
                    if (confirmed) {

                        $.ajax({
                            url: 'multiple-delete',
                            type: 'POST',
                            data: {
                                'ids': ids
                            },
                            success: function (data) {

                                if (data.status == 1) {
                                    // Hide delete button
                                    $('#batch-delete').hide();

                                    // Success
                                    $.pjax.reload({container: '#grid-pjax'});

                                    $.notify({
                                        message: ' ' + data.message,
                                        icon: 'glyphicon glyphicon-ok-sign'
                                    }, {
                                        type: 'success',
                                        class: 'alert col-xs-10 col-sm-10 col-md-3',
                                        template: growlTemplate

                                    });

                                }
                            }
                        });
                    }
                });
            }
        });
    });

    $("#sortable").sortable({
        placeholder: "sortable-container col-xs-3 col-sm-2 ui-state-highlight",
        forcePlaceholderSize: true,
        delay: 150,
        distance: 5,
        //grid: [ 270, 270 ],
        //handle: ".handle",
        helper: "clone",
        items: '.handle',
        opacity: 0.5,
        sort: false,
        tolerance: "pointer",
        cursor: "move",
        containment: '#sortable',
        update: function (event, ui) {
            //var icon = '<img src="../../admin/images/icons/loading.gif" alt="Loading" title="Loading" />';
            //$('#icon').html(icon);

            var ids = $('#sortable').sortable('toArray');

            $.ajax({
                url: 'sort-pictures',
                type: 'POST',
                data: {ids: ids},
                dataType: 'json',
                success: function (data) {
                    if (data.status == 1) {

                        $.notify({
                            message: ' ' + data.message,
                            icon: 'glyphicon glyphicon-ok-sign'
                        }, {
                            type: 'success',
                            class: 'alert col-xs-10 col-sm-10 col-md-3',
                            template: growlTemplate

                        });

                        //var icon = '<img src="../../admin/images/icons/tick.png" alt="Saved" title="Saved" />';
                        //$('#icon').html(icon);
                    }
                }
            });
        }
    }).disableSelection();



});

function toggleCheckboxes(e) {
    // Check / uncheck all checkboxes
    $('#gridview-container .kv-row-select input').prop('checked', ($(this).is(':checked')) ? true : false);
    
    toggleDeleteBtn();    
}

function toggleSelectAll(e) {
    // If one checkbox is not checked, the "select-all" checkbox should also be no longer checked
    if (!$(this).is(':checked'))
        $('.select-on-check-all').prop('checked', false);
        
    toggleDeleteBtn();        
}

function toggleDeleteBtn() {

    // If at least one checkbox is checked the delete button has to be shown
    if ($('#gridview-container .kv-row-select input:checked').length || $('.select-on-check-all:checked').length) {
        $('#batch-delete').show();    
    } else {
        $('#batch-delete').hide();
    }     
}

function afterUpload (event, data, previewId, index) {
    var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;

    // Reload gridview
    $.pjax.reload({container: '#grid-pjax'});

    // Clear file input
    //$('#file-upload').fileinput('clear');

    // Show growl message
    $.notify({
        message: ' ' + response.message,
        icon: 'glyphicon glyphicon-ok-sign'
    }, {
        type: 'success',
        class: 'alert col-xs-10 col-sm-10 col-md-3',
        template: growlTemplate

    });
}