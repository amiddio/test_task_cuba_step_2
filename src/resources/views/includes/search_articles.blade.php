<form class="row form-horizontal" id="search-form">
    <div class="col-sm-10">
        <input type="text" class="form-control" id="kw" name="kw" placeholder="{{ __('Укажите поисковое слово') }}" value="" required>
    </div>
    <div class="col-sm-2">
        <button type="button" id="search-button" class="btn btn-primary mb-3">{{ __('Найти') }}</button>
    </div>
</form>

<div id="search-validation-error" class="invalid-feedback border border-danger rounded-2 p-4"></div>

<hr/>

<div id="search-result" class="row" style="display: none;">
    <div class="col-sm-4">
        <ul id="found-articles-list" class="list-group"></ul>
    </div>
    <div id="article-section" class="col-sm-8 border border-1 rounded-2 p-4" style="display: none;">
        <h3 id="article-section-title"></h3>
        <p id="article-section-url"></p>
        <div id="article-section-content"></div>
    </div>
</div>
