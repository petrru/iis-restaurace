$(window).on('load', function () {
    $('.small-input').keyup(function (e) {
        calc_total();
    });
    calc_total();
});
function calc_total() {
    var sum = 0;
    $('.small-input').each(function () {
        sum += parseInt($(this).val()) || 0;
    });
    $("#total-seats").html(sum);
}