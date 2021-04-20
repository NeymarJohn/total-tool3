@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection
@push('head-script')
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/iconpicker/css/fontawesome-iconpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="panel">

                <div class="vtabs customvtab p-t-10">
                    @if($global->front_design == 1)
                        @include('sections.saas.feature_page_setting_menu')
                    @else
                        @include('sections.front_setting_menu')
                    @endif

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="white-box">
                                        @if($type !== 'icon')
                                            <h4>{{ucwords($type)}} @lang('app.section')</h4>
                                            <hr>
                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                <li class="nav-item active">
                                                    <a
                                                        class="nav-link active"
                                                        id="en-tab"
                                                        data-toggle="tab"
                                                        data-language-id="0"
                                                        href="#en"
                                                        role="tab"
                                                        aria-controls="en"
                                                        aria-selected="true"
                                                    >
                                                        <span class="flag-icon flag-icon-us"></span> English
                                                    </a>
                                                </li>
                                                @forelse ($activeLanguages as $language)
                                                    <li class="nav-item">
                                                        <a
                                                            class="nav-link"
                                                            id="{{$language->language_code}}-tab"
                                                            data-toggle="tab"
                                                            data-language-id="{{$language->id}}"
                                                            href="#{{$language->language_code}}"
                                                            role="tab"
                                                            aria-controls="{{$language->language_code}}"
                                                            aria-selected="true"
                                                        >
                                                            <span class="flag-icon flag-icon-{{ $language->language_code }}"></span> {{ ucfirst($language->language_name) }}
                                                        </a>
                                                    </li>
                                                @empty
                                                @endforelse
                                            </ul>
                                            <div class="tab-content" id="edit-form">
                                                @include('super-admin.feature-settings.edit-form')
                                            </div>
                                        @endif
                                        <br>
                                        <hr>
                                        <h3 class="box-title m-b-0">@lang('modules.feature.setting')</h3>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <a href="javascript:;" class="btn btn-outline btn-success btn-sm addFeature">@lang('modules.featureSetting.addFeature') <i class="fa fa-plus" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>@lang('app.title')</th>
                                                    @if($type !== 'apps')
                                                     <th>@lang('app.description')</th>
                                                    @endif
                                                    <th>@lang('app.language')</th>
                                                    <th>{{ucwords($type)}}</th>
                                                    <th class="text-nowrap">@lang('app.action')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($features as $feature)
                                                    <tr>
                                                        <td>{{ ucwords($feature->title) }}</td>
                                                        @if($type !== 'apps')
                                                         <td>{!! $feature->description  !!}</td>
                                                        @endif
                                                        <td>{{ $feature->language ? $feature->language->language_name : 'English' }}</td>
                                                        <td @if($feature->type != 'image' && $feature->type != 'apps') style="font-size: 27px" @endif>
                                                            @if($feature->type == 'image' || $feature->type == 'apps')
                                                                <img height="100" width="85" src="{{ $feature->image_url }}" alt=""/>
                                                            @else
                                                                <i class="{{ $feature->icon }}"></i>
                                                            @endif
                                                        </td>
                                                        <td class="text-nowrap">
                                                            <a href="javascript:;" data-feature-id="{{ $feature->id }}" class="btn btn-info btn-circle editFeature"
                                                               data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                            <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                                                               data-toggle="tooltip" data-feature-id="{{ $feature->id }}" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                        </td>
                                                    </tr>
                                                 @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">@lang('messages.noRecordFound')</td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>    <!-- .row -->

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
    <script>
        function changeForm(target) {
            $.easyAjax({
                url: "{{ route('super-admin.feature-settings.changeForm') }}",
                container: '#editSettings',
                data: {
                    language_settings_id: $(target).data('language-id'),
                    type: $('#editSettings').data('type')
                },
                type: 'GET',
                success: function (response) {
                    if (response.status === 'success') {
                        $('#edit-form').html(response.view);
                    }
                }
            })
        }

        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            changeForm(e.target);
        })

        $('.editFeature').click( function () {
            var id = $(this).data('feature-id');
            var url = "{{ route('super-admin.feature-settings.edit', ':id')}}?type={{$type}}";
            url = url.replace(':id', id);
            $('#modelHeading').html('Currency Convert Key');
            $.ajaxModal('#projectCategoryModal', url);
        })
        $('.addFeature').click( function () {
            var url = "{{ route('super-admin.feature-settings.create')}}?type={{$type}}";
            $('#modelHeading').html('Currency Convert Key');
            $.ajaxModal('#projectCategoryModal', url);
        })

        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('feature-id');
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.confirmation.deleteFeature')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.deleteConfirmation')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('super-admin.feature-settings.destroy',':id') }}?type={{$type}}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                                url: url,
                                data: {'_token': token, '_method': 'DELETE'},
                                success: function (response) {
                                    if (response.status == "success") {
                                        $.unblockUI();
                                        // swal("Deleted!", response.message, "success");
                                        window.location.reload();
                                    }
                                }
                    });
                }
            });
        });

        $('body').on('click', '#save-form', function () {
            $.easyAjax({
                url: "{{route('super-admin.feature-settings.title-update')}}",
                container: '#editSettings',
                type: "POST",
                file: true,
                data: {
                    language_settings_id: $('#editSettings').data('language-id')
                }
            })
        });

    </script>
@endpush
