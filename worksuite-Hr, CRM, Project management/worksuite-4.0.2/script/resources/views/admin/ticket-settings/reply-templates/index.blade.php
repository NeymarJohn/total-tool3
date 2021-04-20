@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.settings.index') }}">@lang('app.menu.settings')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection
@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
@endpush
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="vtabs customvtab m-t-10">

                @include('sections.ticket_setting_menu')

                <div class="row">

                    <div class="col-md-12">
                        <div class="white-box">
                            <h3>@lang('app.addNew') @lang('modules.tickets.template')</h3>

                            {!! Form::open(['id'=>'createTemplate','class'=>'ajax-form','method'=>'POST']) !!}

                            <div class="form-body">

                                <div class="form-group">
                                    <label class="required">@lang('modules.tickets.templateHeading')</label>
                                    <input type="text" name="reply_heading" id="reply_heading" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label class="required">@lang('modules.tickets.templateText')</label>
                                    <textarea name="reply_text" id="reply_text" class="form-control summernote" rows="5"></textarea>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" id="save-template" class="btn btn-success"><i
                                                class="fa fa-check"></i> @lang('app.save')
                                    </button>
                                </div>
                            </div>

                            {!! Form::close() !!}

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="white-box">
                            <h3>@lang('app.menu.replyTemplates')</h3>


                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('modules.tickets.templateHeading')</th>
                                        <th>@lang('app.action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($templates as $key=>$template)
                                        <tr>
                                            <td>{{ ($key+1) }}</td>
                                            <td>{{ ucwords($template->reply_heading) }}</td>
                                            <td>
                                                <div class="btn-group dropdown m-r-10">
                                                    <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                                                    <ul role="menu" class="dropdown-menu pull-right">
                                                        <li><a href="javascript:;" data-template-id="{{ $template->id }}" class="edit-template"><i
                                                                        class="fa fa-edit"></i> @lang('app.edit')</a></li>
                                                        <li><a href="javascript:;" data-template-id="{{ $template->id }}"
                                                                class="delete-template"><i
                                                                        class="fa fa-times"></i> @lang('app.remove')</a></li>
                                        
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>
                                                @lang('messages.noTemplateFound')
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                        
            </div>

        </div>


    </div>
    <!-- .row -->


    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="ticketTemplateModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
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
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script type="text/javascript">

    $('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen"]]
        ]
    });
    //    save project members
    $('#save-template').click(function () {
        $.easyAjax({
            url: '{{route('admin.replyTemplates.store')}}',
            container: '#createTemplate',
            type: "POST",
            data: $('#createTemplate').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    window.location.reload();
                }
            }
        })
    });


    $('body').on('click', '.delete-template', function () {
        var id = $(this).data('template-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.removeTemplateText')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.replyTemplates.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });


    $('.edit-template').click(function () {
        var typeId = $(this).data('template-id');
        var url = '{{ route("admin.replyTemplates.edit", ":id")}}';
        url = url.replace(':id', typeId);

        $('#modelHeading').html("{{  __('app.edit')." ".__('app.menu.replyTemplates') }}");
        $.ajaxModal('#ticketTemplateModal', url);
    })


</script>


@endpush

