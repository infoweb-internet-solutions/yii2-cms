$(function() {

    moxman.browse({
        insert: false,
        close: false,
        remember_last_path: true,
        view: 'thumbs',
        fullscreen: true
        //path: '/uploads/img'
    });

    /*
    // Disable esc key
    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            e.preventDefault();
            e.stopPropagation();
            console.log('test');
            return false;
        }
    });
    */
});