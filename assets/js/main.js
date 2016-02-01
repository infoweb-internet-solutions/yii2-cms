/**
 * Overwrite default delete confirm dialog
 *
 * @param message
 * @param ok
 * @param cancel
 * @returns {boolean}
 */
yii.confirm = function (message, ok, cancel) {
    bootbox.confirm(message, function (confirmed) {
        if (confirmed) {
            !ok || ok();
        } else {
            !cancel || cancel();
        }
    });
    return false;
};

$(function() {
    // Init CMS module
    CMS.init();
});
