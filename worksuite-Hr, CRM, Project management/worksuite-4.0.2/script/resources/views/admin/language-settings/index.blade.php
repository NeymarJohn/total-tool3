@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ url('/translations') }}" target="_blank" class="btn btn-sm btn-outline btn-warning"><i class="ti-settings"></i> Translate</a>
            <a href="{{ route('admin.language-settings.create') }}" class="btn btn-outline btn-success btn-sm">@lang('app.add') @lang('app.language')  <i class="fa fa-plus" aria-hidden="true"></i></a>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.language') @lang('app.menu.settings')</div>

                <div class="p-10 m-t-10">
                    @include('sections.admin_setting_menu')

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>@lang('app.language') @lang('app.name')</th>
                                <th>@lang('app.language') @lang('app.code')</th>
                                <th>@lang('app.status')</th>
                                <th class="text-nowrap">@lang('app.action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($languages as $language)
                                <tr id="languageRow{{ $language->id }}">
                                    <td>{{ ucwords($language->language_name) }}</td>
                                    <td>{{ strtoupper($language->language_code) }}</td>
                                    <td>
                                        <div class="switchery-demo">
                                            <input type="checkbox"
                                                   @if($language->status == 'enabled') checked
                                                   @endif class="js-switch change-language-setting"
                                                   data-color="#99d683"
                                                   data-setting-id="{{ $language->id }}"/>
                                        </div>
                                    </td>
                                    <td class="text-nowrap">

                                        <div class="btn-group dropdown m-r-10">
                                            <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                                            <ul role="menu" class="dropdown-menu pull-right">
                                                <li><a href="{{ route('admin.language-settings.edit', [$language->id]) }}"
                                                       data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> @lang('app.edit')</a></li>
                                                <li><a href="javascript:;"  data-language-id="{{ $language->id }}" class="delete-type sa-params"><i class="fa fa-times" aria-hidden="true"></i> @lang('app.delete') </a></li>

                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>    <!-- .row -->
        </div>
    </div>

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
    <script>
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());

        });

        $('.change-language-setting').change(function () {
            var id = $(this).data('setting-id');

            if ($(this).is(':checked'))
                var status = 'enabled';
            else
                var status = 'disabled';

            var url = '{{route('admin.language-settings.update', ':id')}}';
            url = url.replace(':id', id);
            $.easyAjax({
                url: url,
                type: "POST",
                data: {'id': id, 'status': status, '_method': 'PUT', '_token': '{{ csrf_token() }}'}
            })
        });
        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('language-id');
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.deleteLanguage')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.confirmCreate')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.language-settings.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                $('#languageRow'+id).fadeOut();
                            }
                        }
                    });
                }
            });
        });

    </script>
@endpush
