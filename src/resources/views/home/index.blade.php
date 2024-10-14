<x-app-layout>

    <x-slot name="content">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="simple-tab-1" data-bs-toggle="tab" href="#simple-tabpanel-1" role="tab" aria-controls="simple-tabpanel-1" aria-selected="true">{{ __('Импорт статей') }}</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="simple-tab-2" data-bs-toggle="tab" href="#simple-tabpanel-2" role="tab" aria-controls="simple-tabpanel-2" aria-selected="false">{{ __('Поиск') }}</a>
            </li>
        </ul>
        <div class="tab-content pt-5" id="tab-content">
            <div class="tab-pane active" id="simple-tabpanel-1" role="tabpanel" aria-labelledby="simple-tab-1">

                <form class="row form-horizontal" id="search-form">
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter Article Title" value="" required>
                    </div>
                    <div class="col-sm-2">
                        <select class="form-select" name="language">
                            @foreach(\App\Enums\Languages::cases() as $lang)
                                <option value="{{ $lang }}">{{ $lang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="button" id="search-button" class="btn btn-primary mb-3">Search</button>
                    </div>
                </form>
                <div id="validation-error" class="invalid-feedback"></div>

                <script type="text/javascript">
                    $(function () {
                        $('#validation-error').hide();

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $('#search-button').click(function (e) {
                            e.preventDefault();

                            $.ajax({
                                data: $('#search-form').serialize(),
                                url: "{{ route('api.v1.search_article') }}",
                                type: "POST",
                                dataType: 'json',
                                success: function (data) {
                                    $('#validation-error').hide();
                                    //alert(data);
                                },
                                error: function (data) {
                                    $('#validation-error').html(data.responseJSON.message).show();
                                    // $('#savedata').html('Save Changes');
                                }
                            });
                        });



                    });
                </script>
            </div>
            <div class="tab-pane" id="simple-tabpanel-2" role="tabpanel" aria-labelledby="simple-tab-2">Tab 2 selected</div>
        </div>
    </x-slot>

</x-app-layout>
