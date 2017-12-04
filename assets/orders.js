$(window).on('load', function () {
    $('.room-box:not(.small)').click(function () {
        var order = $(this).data('order');
        var url;
        if (order)
            url = order;
        else
            url = 'new/' + $(this).html() + '/' + $('#reservation').val();
        location.href = get_location(url);
    });
});