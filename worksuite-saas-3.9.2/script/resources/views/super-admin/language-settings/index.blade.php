@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
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
        <div class="col-xs-12">

            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.language') @lang('app.menu.settings')</div>

                <div class="p-10 m-t-10">
                    @include('sections.super_admin_setting_menu')

                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <a href="{{ route('super-admin.language-settings.create') }}" class="btn btn-outline btn-success btn-sm m-b-30">@lang('app.add') @lang('app.language')  <i class="fa fa-plus" aria-hidden="true"></i></a>
                            <a href="{{ url('/translations') }}" target="_blank" class="btn btn-sm m-b-30 btn-warning"><i class="ti-settings"></i> Translate</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>@lang('app.language') @lang('app.name')</th>
                                <th>@lang('app.language_code')</th>
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
                                        <a href="{{ route('super-admin.language-settings.edit', [$language->id]) }}" class="btn btn-info btn-circle"
                                           data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                                        <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                                           data-toggle="tooltip" data-language-id="{{ $language->id }}" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>
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

            var url = '{{route('super-admin.language-settings.update', ':id')}}';
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
                text: "@lang('messages.confirmation.recoverDeleteLanguage')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.deleteConfirmation')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('super-admin.language-settings.destroy',':id') }}";
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
