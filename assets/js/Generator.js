$(function() {

    $(document).on('focusout', '#generator-modelclass', function() {

        //var category = $(this).data('category');

        $.pjax.reload({container: '#test'});

        /*
        var action = $(this).data('url'),
            request = $.post(action);

        console.log(action);


        request.done(function(response) {
            // Succes, reload PJAX grid
            if (response == 1) {
                $.pjax.reload({container: '#grid-pjax' + ((category) ? '-' + category : '')});
            } else {
                // @todo: catch error
                return false;
            }
        });
        */

    });
});