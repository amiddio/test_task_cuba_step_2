$(function () {
    $('#search-validation-error').hide();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * Функция заменяет перевод строки на соответствующий html тег
     *
     * @param varTest string
     * @returns string
     */
    $.nl2br = function(varTest){
        return varTest.replace(/(\r\n|\n\r|\r|\n)/g, "<br>");
    };

    /**
     * Обработчик поиска статей
     */
    $('#search-button').click(function (e) {
        e.preventDefault();

        $('#article-section').hide();

        $.ajax({
            data: $('#search-form').serialize(),
            url: '/api/v1/search',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                $('#search-validation-error').hide();
                $('#search-result').show();
                var articles = '';
                $.each(data.data.articles, function(index, article) {
                    articles += '<li class="list-group-item">' +
                        '<a href="#" class="articles-list" data-id="' + article.pivot.article_id + '"><b>' + article.title + '</b></a> ' +
                        '(' + article.pivot.cnt + ' вхождений)</li>';
                });
                $('#found-articles-list').html(articles);
            },
            error: function (data) {
                $('#search-validation-error').html(data.responseJSON.message).show();
                $('#search-result').hide();
                $('#found-articles-list').html('');
                $('#article-section').hide();
            }
        });
    });

    /**
     * Обработчик просмотра статей
     */
    $(document).on('click', '.articles-list', function(e) {
        e.preventDefault();

        $.ajax({
            url: '/api/v1/article/' + $(this).attr('data-id'),
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#search-validation-error').hide();
                $('#article-section').show();
                $('#article-section-title').html(data.data.title);
                $('#article-section-url').html(
                    '<a href="' + data.data.url + '" target="_blank">' + data.data.url + '</a>'
                );
                $('#article-section-content').html($.nl2br(data.data.content));
            },
            error: function (data) {
                $('#search-validation-error').html(data.responseJSON.message).show();
                $('#article-section').hide();
            }
        });
    });

});
