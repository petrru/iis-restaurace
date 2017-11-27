$(window).on('load', function () {
    $(".success_message .close").click(function () {
        $(this).parent().remove();
    });
    $(".confirm").click(function (e) {
        if (!confirm("Opravdu chcete " + $(this).html().toLowerCase() + "?")) {
            e.preventDefault();
        }
    });
});