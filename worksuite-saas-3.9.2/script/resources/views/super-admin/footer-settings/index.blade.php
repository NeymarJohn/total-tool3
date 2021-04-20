@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
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
                        @include('sections.saas.footer_page_setting_menu')
                    @else
                        @include('sections.front_setting_menu')
                    @endif

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="white-box">
                                        <h3 class="box-title m-b-0">@lang('modules.footer.setting')</h3>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <a href="{{ route('super-admin.footer-settings.create') }}" class="btn btn-outline btn-success btn-sm addFeature">@lang('modules.footer.addFooter') <i class="fa fa-plus" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>@lang('app.title')</th>
                                                    <th>@lang('app.description')</th>
                                                    <th>@lang('app.language')</th>
                                                    <th>@lang('app.status')</th>
                                                    <th>@lang('app.type')</th>
                                                    <th class="text-nowrap">@lang('app.action')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($footer as $footerMenu)
                                                    <tr>
                                                        <td>{{ ucwords($footerMenu->name) }}</td>
                                                        <td>
                                                            @if(!is_null($footerMenu->description))
                                                                {!! $footerMenu->description  !!}
                                                            @else
                                                                <a target="_blank" href="{{ $footerMenu->external_link }}">{{ $footerMenu->external_link }}</a>
                                                            @endif
                                                        </td>
                                                        <td>{{ $footerMenu->language ? $footerMenu->language->language_name : 'English' }}</td>
                                                        <td>
                                                            @if ($footerMenu->status == 'active')
                                                                <label class="label label-info">@lang('app.active')</label>
                                                            @else
                                                                <label class="label label-danger">@lang('app.inactive')</label>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($footerMenu->type == 'footer')
                                                                @lang('app.footer')
                                                            @elseif ($footerMenu->type == 'header')
                                                                @lang('app.header')
                                                            @else
                                                                @lang('app.both')
                                                            @endif
                                                        </td>

                                                        <td class="text-nowrap">
                                                            <a href="{{ route('super-admin.footer-settings.edit', $footerMenu->id) }}" class="btn btn-info btn-circle"
                                                               data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                            <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                                                               data-toggle="tooltip" data-feature-id="{{ $footerMenu->id }}" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>
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
    $('.editFeature').click( function () {
        var id = $(this).data('feature-id');
        var url = '{{ route('super-admin.footer-settings.edit', ':id')}}';
        url = url.replace(':id', id);
        $('#modelHeading').html('edit Feature');
        $.ajaxModal('#projectCategoryModal', url);
    })

    $('body').on('click', '.sa-params', function(){
        var id = $(this).data('feature-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.confirmation.recoverFooterMenu')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.deleteConfirmation')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('super-admin.footer-settings.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });

</script>
@endpush
