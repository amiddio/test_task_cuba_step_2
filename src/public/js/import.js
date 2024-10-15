$(function () {
    $('#import-validation-error').hide();
    $('#status-section').hide();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * Обработчик импорта статей
     */
    $('#import-button').click(function (e) {
        e.preventDefault();

        $('#import-button').prop('disabled', true);

        $.ajax({
            data: $('#import-form').serialize(),
            url: '/api/v1/import',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                $('#import-validation-error').hide();
                $('#title').val('');
                $('#import-button').prop('disabled', false);
                $('#status-section').show();
                $('#imported-articles-table').show();
                $('#status-section-url').html(data.data.url);
                $('#status-section-time').html(data.data.time_execution);
                $('#status-section-size').html(data.data.size);
                $('#status-section-totalwords').html(data.data.total_words);

                var tr = '<tr>' +
                    '<td>' + data.data.title + '</td>' +
                    '<td><a href="' + data.data.url + '" target="_blank">' + data.data.url + '</a></td>' +
                    '<td>' + data.data.size + '</td>' +
                    '<td>' + data.data.total_words + '</td>' +
                    '</tr>';
                $('#imported-articles-table-rows').append(tr);
            },
            error: function (data) {
                $('#status-section').hide();
                $('#import-button').prop('disabled', false);
                $('#import-validation-error').html(data.responseJSON.message).show();
            }
        });
    });

});
