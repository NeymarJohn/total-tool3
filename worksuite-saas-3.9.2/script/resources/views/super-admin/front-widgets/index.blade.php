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
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/iconpicker/css/fontawesome-iconpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="panel">

                <div class="vtabs customvtab p-t-10">
                    @include('sections.front_setting_new_theme_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}

                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>@lang('app.add')</h4>
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.frontCms.widgetName')</label>
                                        <input type="text" name="name" class="form-control" >
                                    </div>
                                
                                    
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.frontCms.widgetCode')</label>
                                        <textarea name="widget_code" class="form-control" rows="6"></textarea>
                                    </div>
                                </div>
                            
                                <div class="col-sm-12">
                                    <button class="btn btn-success" type="button" id="save-form"><i class="fa fa-check"></i> @lang('app.save')</button>
                                </div>

                                <div class="col-md-12 m-t-30">

                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>@lang('app.name')</th>
                                                <th>@lang('app.action')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($frontWidgets as $key=>$widget)
                                                <tr>
                                                    <td>{{ ($key+1) }}</td>
                                                    <td>{{ ucwords($widget->name) }}</td>
                                                    <td>
                                                        <a href="javascript:;" data-type-id="{{ $widget->id }}"
                                                            class="btn btn-sm btn-info btn-rounded btn-outline edit-type"><i
                                                                    class="fa fa-edit"></i> @lang('app.edit')</a>
                                                        <a href="javascript:;" data-type-id="{{ $widget->id }}"
                                                            class="btn btn-sm btn-danger btn-rounded btn-outline delete-type"><i
                                                                    class="fa fa-times"></i> @lang('app.remove')</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">
                                                        @lang('messages.noRecordFound')
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

                            {!! Form::close() !!}

                            

                            <div class="clearfix"></div>


                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-lg in" id="ticketTypeModal" role="dialog" aria-labelledby="myModalLabel"
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
    
    $('#save-form').click(function () {
        $.easyAjax({
            url: "{{route('super-admin.front-widgets.store')}}",
            container: '#editSettings',
            type: "POST",
            data: $('#editSettings').serialize()
        })
    });


    $('body').on('click', '.delete-type', function () {
        var id = $(this).data('type-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.confirmation.removeWidget')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('app.yes')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('super-admin.front-widgets.destroy',':id') }}";
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


    $('.edit-type').click(function () {
        var typeId = $(this).data('type-id');
        var url = '{{ route("super-admin.front-widgets.edit", ":id")}}';
        url = url.replace(':id', typeId);

        $('#modelHeading').html("{{  __('app.edit')." ".__('modules.tickets.ticketType') }}");
        $.ajaxModal('#ticketTypeModal', url);
    })

  </script>
@endpush
