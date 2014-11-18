/*
yii.allowAction = function ($e) {
    var message = $e.data('confirm');
    return message === undefined || yii.confirm(message, $e);
};
yii.confirm = function (message, $e) {
    bootbox.confirm(message, function (confirmed) {
        if (confirmed) {
            yii.handleAction($e);
        }
    });
    // confirm will always return false on the first call
    // to cancel click handler
    return false;
}
 */
$(function() {


    // Toggle bootstrap tooltip
    $("[data-toggle='tooltip']").tooltip({
        //selector: "[data-toggle=tooltip]",
        container: 'body'
    });


    // Toggle bootstrap popover
    //$("[data-toggle='popover']").popover();


    /*
    // Minimalize menu when screen is less than 768px
    $(function() {
        $(window).bind("load resize", function() {
            if ($(this).width() < 769) {
                $('body').addClass('body-small')
            } else {
                $('body').removeClass('body-small')
            }
        })
    });
    */


    /*
    $(document).on('click', '#cke_212_uiElement, #cke_211_label', function(e) {

        e.preventDefault();

        moxman.browse({
            view: "thumbs",
            path: '/uploads/img/store',
            rootpath: '/uploads/img/store',
            //exclude_directory_pattern: '/^cache$/i',
            title: 'Image manager',
            remember_last_path: true,
            oninsert: function (data) {
                $('#cke_208_textInput').val(data.focusedFile.url);
                $('#cke_215_textInput').val(data.focusedFile.nameWithoutExtension);

                $('#cke_218_textInput').val(data.focusedFile.meta.width);
                $('#cke_221_textInput').val(data.focusedFile.meta.height);

                $('#cke_202_previewLink').html('<img src="' + data.focusedFile.url + '" width="' + data.focusedFile.meta.width + '" height="' + data.focusedFile.meta.height + '" />');
            }
        });
    });

    $(document).on('click', '#cke_214_uiElement, #cke_213_label', function(e) {

        e.preventDefault();

        moxman.browse({
            path: '/uploads/file',
            rootpath: '/uploads/files',
            title: 'File manager',
            oninsert: function (data) {
                // Do stuff
            }
        });
    });
    */


    $(document).on('click', '#media', function(e) {

        e.preventDefault();

        moxman.browse({
            view : 'thumbs',
            fullscreen : true,
            insert : false
        });
    });


    // Init CMS module
    CMS.init();

    // Eliminates the 300ms delay between a physical tap and the firing of a click event on mobile browsers
    FastClick.attach(document.body);
});
