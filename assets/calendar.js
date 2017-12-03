$(window).on('load', function () {
    $('#change-month').submit(function (e) {
        e.preventDefault();
        var year = $("#year").val();
        var month = $("#month").val();
        if (!(year > 1900 && year <= 9999)) {
            alert('Rok je potřeba zadat v čtyřmístném formátu.');
            return;
        }
        var url = get_location(year + '-' + month);
        location.href = url.replace(/\/reservations\/[^\/]+/, "/reservations");
    });
});