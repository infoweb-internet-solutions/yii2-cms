
/*
// --- Delete action (bootbox) ---
yii.confirm = function (message, ok, cancel) {

    bootbox.confirm({
        message: message,
        buttons: {
            confirm: {
                label: "OK"
            },
            cancel: {
                label: "Cancel"
            }
        },
        callback: function (confirmed) {

            if (confirmed) {
                //!ok || ok();

                // @todo Delete action ajax request
                /*
                var category = $(this).data('category'),
                    id = $(this).data('id'),
                    request = $.post('delete', {id:id});

                request.done(function(response) {
                    // Succes, reload PJAX grid
                    if (response == 1) {
                        $.pjax.reload({container: '#grid-pjax' + ((category) ? '-' + category : '')});
                    } else {
                        // @todo: catch error
                        return false;
                    }
                });


            } else {
                !cancel || cancel();
            }

            if (confirmed) {
                //$.pjax.reload({container: '#grid-pjax'});
            }
        }
    });

    // confirm will always return false on the first call
    // to cancel click handler
    return false;
}
*/

$(function() {

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

    // Init CMS module
    CMS.init();

    // Eliminates the 300ms delay between a physical tap and the firing of a click event on mobile browsers
    FastClick.attach(document.body);
});
