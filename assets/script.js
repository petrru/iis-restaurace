$(window).on('load', function () {
    $(".success_message .close").click(function () {
        $(this).parent().remove();
    });
    $(".confirm").click(function (e) {
        var text = $(this).data('confirm-msg') ? $(this).data('confirm-msg')
                 : $(this).html().toLowerCase();
        if (!confirm("Opravdu chcete " + text + "?")) {
            e.preventDefault();
        }
    });
});