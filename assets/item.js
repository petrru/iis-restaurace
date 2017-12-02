$(window).on('load', function () {
    $('.ingredients').on('click', '.edit', function () {
        var $div = $(this).parent();
        var id = $div.data('id');
        var $name = $('#in-name');
        $name.val(id);
        $('#in-amount').val($div.find('.amount').html());
        $('#in-unit').html($name.find(':selected').data('unit'));
        $('#edit-ingredient').data('id', id).show();
    });
    $("#in-name").change(function () {
        $('#in-unit').html($(this).find(':selected').data('unit'))
    });
    $('#in-form').submit(function (e) {
        e.preventDefault();
        var data = {
            action: 'modify',
            old_id: $('#edit-ingredient').data('id'),
            new_id: $('#in-name').val(),
            amount: $('#in-amount').val()
        };
        $.post(get_location('save-ingredients'), data, function (resp) {
            console.log(resp);
        });
    });
});