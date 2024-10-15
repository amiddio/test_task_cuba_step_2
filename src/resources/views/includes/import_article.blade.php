<form class="row form-horizontal" id="import-form">
    <div class="col-sm-8">
        <input type="text" class="form-control" id="title" name="title" placeholder="{{ __('Укажите название статьи') }}" value="" required>
    </div>
    <div class="col-sm-2">
        <select class="form-select" name="language">
            @foreach(\App\Enums\Languages::cases() as $lang)
                <option value="{{ $lang }}">{{ $lang }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2">
        <button type="button" id="import-button" class="btn btn-primary mb-3">{{ __('Импортировать') }}</button>
    </div>
</form>

<div id="import-validation-error" class="invalid-feedback border border-danger rounded-2 p-4"></div>

<div id="status-section" class="border border-1 rounded-2 p-4" style="display: none;">
    <p>{{ __('Найдена статья по адресу:') }} <span id="status-section-url"></span></p>
    <p>{{ __('Время обработки:') }} <span id="status-section-time"></span></p>
    <p>{{ __('Размер статьи:') }} <span id="status-section-size"></span></p>
    <p>{{ __('Количество слов:') }} <span id="status-section-totalwords"></span></p>
</div>

<hr/>

<table id="imported-articles-table" class="table" @if(0 == count($articles)) style="display: none;" @endif>
    <thead>
    <tr>
        <th scope="col">{{ __('Название статьи') }}</th>
        <th scope="col">{{ __('Ссылка') }}</th>
        <th scope="col">{{ __('Размер статьи') }}</th>
        <th scope="col">{{ __('Количество слов') }}</th>
    </tr>
    </thead>
    <tbody id="imported-articles-table-rows">
    @foreach($articles as $article)
        <tr>
            <td>{{ $article->title }}</td>
            <td><a href="{{ $article->url }}" target="_blank">{{ $article->url }}</a></td>
            <td>{{ formatSize($article->size) }}</td>
            <td>{{ $article->total_words }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
