<x-app-layout>

    <x-slot name="content">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="simple-tab-1" data-bs-toggle="tab" href="#simple-tabpanel-1" role="tab" aria-controls="simple-tabpanel-1" aria-selected="true">{{ __('Импорт статей') }}</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="simple-tab-2" data-bs-toggle="tab" href="#simple-tabpanel-2" role="tab" aria-controls="simple-tabpanel-2" aria-selected="false">{{ __('Поиск') }}</a>
            </li>
        </ul>
        <div class="tab-content pt-5 p-4" id="tab-content" style="">
            <div class="tab-pane active" id="simple-tabpanel-1" role="tabpanel" aria-labelledby="simple-tab-1">
                @include('includes/import_article')
            </div>
            <div class="tab-pane" id="simple-tabpanel-2" role="tabpanel" aria-labelledby="simple-tab-2">
                @include('includes/search_articles')
            </div>
        </div>
    </x-slot>

</x-app-layout>
