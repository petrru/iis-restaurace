$(window).on('load', function () {
    var $in_edited;
    $('.ingredients').on('click', '.edit', function () {
        $in_edited = $(this).parent();
        var id = $in_edited.data('id');
        var $name = $('#in-name');
        $name.val(id);
        $('#edit-ingredient h3').html('Změna ingredience');
        $('#in-amount').val($in_edited.find('.amount').html());
        $('#in-unit').html($name.find(':selected').data('unit'));
        $('#edit-ingredient').data('id', id).show();
        $('#in-error').html('');
        $('#in-delete').show();
    });
    $('#in-new').click(function (e) {
        e.preventDefault();
        var $name = $('#in-name');
        $('#edit-ingredient h3').html('Nová ingredience');
        $('#in-amount').val('');
        $('#in-unit').html($name.find(':selected').data('unit'));
        $('#edit-ingredient').data('id', 0).show();
        $('#in-error').html('');
        $('#in-delete').hide();
    });
    $("#in-name").change(function () {
        $('#in-unit').html($(this).find(':selected').data('unit'))
    });
    $('#in-form').submit(function (e) {
        e.preventDefault();
        var old_id = $('#edit-ingredient').data('id');
        var data;
        if (old_id) {
            data = {
                action: 'modify',
                old_id: old_id,
                new_id: $('#in-name').val(),
                amount: $('#in-amount').val()
            };
        }
        else {
            data = {
                action: 'add',
                new_id: $('#in-name').val(),
                amount: $('#in-amount').val()
            };
        }
        $.post(get_location('save-ingredients'), data, function (resp) {
            if (resp.e === undefined) {
                $('#in-error').html('Chyba při ukládání záznamu');
            }
            else if (resp.e !== 'ok') {
                $('#in-error').html('<b>Chyba:</b> ' + resp.e);
            }
            else if(old_id) {
                // Editace
                var new_name = $('#in-name').find(':selected').html();
                $in_edited.data('id', data.new_id);
                $in_edited.find(".amount").html(data.amount);
                $in_edited.find(".unit").html($('#in-unit').html());
                $in_edited.find(".name").html(new_name);
                $('#edit-ingredient').hide();
            }
            else {
                // Nový záznam
                var new_name = $('#in-name').find(':selected').html();
                var msg = "<div data-id='" + data.new_id + "'>"
                    + "<span class='amount'>" + data.amount + "</span> "
                    + "<span class='unit'>" + $('#in-unit').html() + "</span> "
                    + "<span class='name'>" + new_name +"</span>"
                    + "<i class='material-icons edit' title='Editovat'>"
                    + "mode_edit</i>"
                    + "</div>";
                $(".ingredients .no-items").before(msg);
                $('#edit-ingredient').hide();
            }
        });
    });
    $('#in-delete').click(function () {
        if (!confirm("Opravdu chcete odebrat tuto ingredienci?"))
            return;
        var id = $in_edited.data('id');
        var data = {
            action: 'delete',
            id: id
        };
        $.post(get_location('save-ingredients'), data, function (resp) {
            if (resp.e === undefined) {
                $('#in-error').html('Chyba při odstranění záznamu');
            }
            else if (resp.e !== 'ok') {
                $('#in-error').html('<b>Chyba:</b> ' + resp.e);
            }
            else {
                $in_edited.remove();
                $('#edit-ingredient').hide();
            }
        });
    });
});