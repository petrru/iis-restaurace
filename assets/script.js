$(window).on('load', function () {
    $(".success_message .close, .error_message .close").click(function () {
        $(this).parent().remove();
    });
    $(".confirm").click(function (e) {
        var text = $(this).data('confirm-msg') ? $(this).data('confirm-msg')
                 : $(this).html().toLowerCase();
        if (!confirm("Opravdu chcete " + text + "?")) {
            e.preventDefault();
        }
    });
    $(".modal .close").click(function () {
        $(this).parents(".modal").hide();
    });
});

function get_location(append) {
    var url = location.href;
    if (url.slice(-1) === '/')
        return url + append;
    return url + '/' + append;
}