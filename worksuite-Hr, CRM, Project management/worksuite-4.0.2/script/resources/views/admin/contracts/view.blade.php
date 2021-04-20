@extends('layouts.app')

@section('page-title')
<div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <a class="btn btn-info btn-outline btn-sm"
                href="{{ route("admin.contracts.edit", $contract->id) }}"> <span><i class="fa fa-edit"></i> @lang('app.edit')</span> </a>
            <a class="btn btn-default btn-outline btn-sm"
                href="{{ route("admin.contracts.download", $contract->id) }}"> <span><i class="fa fa-file-pdf-o"></i> @lang('modules.invoices.downloadPdf')</span> </a>
        </div>
        <!-- /.breadcrumb -->
    </div>
    @endsection
@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">
<style>
    .file-bg {
        height: 150px;
        overflow: hidden;
        position: relative;
    }
    .file-bg .overlay-file-box {
        opacity: .9;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        text-align: center;
    }
</style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12" id="estimates">
            <div class="white-box printableArea" style="background: #ffffff !important;">
                <div class="sttabs tabs-style-line" id="invoice_container">
                    <nav>
                        <ul class="customtab" role="tablist" id="myTab">
                            <li class="nav-item active"><a class="nav-link" href="#summery" data-toggle="tab" role="tab"><i class="icon-grid"></i> <span>@lang('modules.contracts.summery')</span></a>
                            </li>

                            <li class="nav-item"><a class="nav-link" href="#discussion" data-toggle="tab" role="tab"><i class="ti-comments"></i> <span>@lang('modules.contracts.discussion')</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="#files" data-toggle="tab" role="tab"><span><i class="glyphicon glyphicon-file"></i> @lang('app.contractFiles')</span></a></li>
                        </ul>
                    </nav>

                    <div class="tab-content tabcontent-border">
                        <div class="tab-pane active" id="summery" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    {!! $contract->contract_detail !!}
                                                </div>
                                                <div class="col-md-4 p-l-30">
                                                    <div class="card p-20 p-t-0">
                                                        <div class="card-body">
                                                            <address>
                                                                <h3><b>{{ ucwords($global->company_name) }}</b></h3>
                                                                <p class="text-muted">{!! nl2br($global->address) !!}</p>
                                                            </address>
                                                            @if($contract->amount!=0)
                                                            <h3>@lang('modules.contracts.contractValue'): {{ $global->currency->currency_symbol }}{{ $contract->amount }}</h3>
                                                            @endif
                                                            <table class="table">
                                                                <tr>
                                                                    <td># @lang('modules.contracts.contractNumber')</td>
                                                                    <td>{{ $contract->id }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('modules.projects.startDate')</td>
                                                                    <td>{{ $contract->start_date->format($global->date_format) }}</td>
                                                                </tr>
                                                                @if($contract->end_date != null)
                                                                <tr>
                                                                    <td>@lang('modules.contracts.endDate')</td>
                                                                    <td>{{ $contract->end_date->format($global->date_format) }}</td>
                                                                </tr>
                                                                @endif
                                                                <tr>
                                                                    <td>@lang('modules.contracts.contractType')</td>
                                                                    <td>{{ $contract->contract_type->name }}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="discussion" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8">
                                    {!! Form::open(['id'=>'addDiscussion','class'=>'ajax-form','method'=>'POST']) !!}
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <div class="form-group">
                                                    <textarea name="message"  id="message"  rows="5" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-action pull-right">
                                        <button type="submit" class="btn btn-info" onclick="addDiscussion();return false;">@lang('modules.contracts.addComment')</button>
                                    </div>
                                    {!! Form::close() !!}

                                    <div class="clearfix"></div>
                                    @foreach($contract->discussion as $discussion)
                                        <div class="row m-t-20" id="discussion-row-{{$discussion->id}}">
                                            <div class="sl-item">
                                                <div class="sl-right">
                                                    <img src="{{ $discussion->user->image_url }}" class="img-circle" style="height:30px;width:30px">
                                                    <h5 style="display: inline-block;">
                                                        {{ $discussion->user->name }} -
                                                        <span class="text-muted m-l-5 d-none">
                                                            {{ $discussion->created_at->diffForHumans(\Carbon\Carbon::now()) }}
                                                        </span>

                                                    </h5>
                                                    @if($discussion->from == $user->id)
                                                        <div class="pull-right m-t-10 discussion-action-button">
                                                            <button class="btn btn-circle btn-sm btn-success" onclick="edit('{{ $discussion->id }}')"><i class="icon-pencil"></i></button>
                                                            <button class="btn btn-circle btn-sm btn-danger remove-discussion" data-discussion-id="{{ $discussion->id }}"><i class="icon-trash"></i></button>
                                                        </div>
                                                    @endif
                                                    <div class="m-l-30" id="discussion-{{ $discussion->id }}">
                                                        {{ $discussion->message }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-4 p-l-30">
                                    <div class="card p-20 p-t-0">
                                        <div class="card-body">
                                            <address>
                                                <h3><b>{{ ucwords($global->company_name) }}</b></h3>
                                                <p class="text-muted">{!! nl2br($global->address) !!}</p>
                                            </address>
                                            @if($contract->amount!=0)
                                            <h3>@lang('modules.contracts.contractValue'): {{ $global->currency->currency_symbol }}{{ $contract->amount }}</h3>
                                            @endif

                                            <table class="table">
                                                <tr>
                                                    <td># @lang('modules.contracts.contractNumber') </td>
                                                    <td>{{ $contract->id }}</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('modules.projects.startDate')</td>
                                                    <td>{{ $contract->start_date->format($global->date_format) }}</td>
                                                </tr>
                                                @if($contract->end_date != null)
                                                <tr>
                                                    <td>@lang('modules.contracts.endDate')</td>
                                                    <td>{{ $contract->end_date->format($global->date_format)}}</td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td>@lang('modules.contracts.contractType')</td>
                                                    <td>{{ $contract->contract_type->name }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane  p-20" id="files" role="tabpanel">

                            <div class="row m-b-10">
                                <div class="col-md-2">
                                    <a href="javascript:;" id="show-dropzone"
                                       class="btn btn-success btn-sm btn-outline"><i class="ti-upload"></i> @lang('modules.projects.uploadFile')</a>
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:;" id="show-link-form"
                                       class="btn btn-success btn-sm btn-outline"><i class="ti-link"></i> @lang('modules.projects.addFileLink')</a>
                                </div>
                            </div>

                            <div class="row m-b-20 hide" id="file-dropzone">
                                <div class="col-md-12">
                                    <form action="{{ route('admin.contract-files.store') }}" class="dropzone"
                                          id="file-upload-dropzone">
                                        {{ csrf_field() }}

                                        {!! Form::hidden('contract_id', $contract->id) !!}

                                        <input name="view" type="hidden" id="view" value="list">

                                        <div class="fallback">
                                            <input name="file" type="file" multiple/>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row m-b-20 hide" id="file-link">
                                {!! Form::open(['id'=>'file-external-link','class'=>'ajax-form','method'=>'POST']) !!}


                                {!! Form::hidden('contract_id', $contract->id) !!}

                                <input name="view" type="hidden" id="view" value="list">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="">@lang('app.name')</label>
                                        <input type="text" name="filename" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="">@lang('modules.projects.addFileLink')</label>
                                        <input type="text" name="external_link" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button class="btn btn-success" id="save-link">@lang('app.submit')</button>
                                    </div>
                                </div>

                                {!! Form::close() !!}
                            </div>

                            <ul class="nav nav-tabs" role="tablist" id="list-tabs">
                                <li role="presentation" class="active nav-item" data-pk="list"><a href="#list" class="nav-link" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> @lang('app.list')</span></a></li>
                                <li role="presentation" class="nav-item" data-pk="thumbnail"><a href="#thumbnail" class="nav-link thumbnail" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">@lang('app.thumbnail')</span></a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="list">
                                    <ul class="list-group" id="files-list">
                                        @forelse($contract->files as $file)
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        {{ $file->filename }}
                                                    </div>
                                                    <div class="col-md-3">

                                                        <a target="_blank" href="{{ $file->file_url }}"
                                                           data-toggle="tooltip" data-original-title="View"
                                                           class="btn btn-info btn-circle"><i
                                                                    class="fa fa-search"></i> </a>

                                                        @if(is_null($file->external_link))
                                                            &nbsp;&nbsp;
                                                            <a href="{{ route('admin.files.download', $file->id) }}"
                                                               data-toggle="tooltip" data-original-title="Download"
                                                               class="btn btn-inverse btn-circle"><i
                                                                        class="fa fa-download"></i></a>
                                                        @endif
                                                        &nbsp;&nbsp;
                                                        <a href="javascript:;" data-toggle="tooltip"
                                                           data-original-title="Delete"
                                                           data-file-id="{{ $file->id }}"
                                                           class="btn btn-danger btn-circle sa-params" data-pk="list"><i
                                                                    class="fa fa-times"></i></a>

                                                        <span class="m-l-10">{{ $file->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        @lang('messages.noFileUploaded')
                                                    </div>
                                                </div>
                                            </li>
                                        @endforelse

                                    </ul>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="thumbnail">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Timer Modal--}}
    <div class="modal fade bs-modal-md in" id="estimateAccept" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                    <button type="button" class="btn blue">Accept</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Timer Modal Ends--}}

@endsection
@push('footer-script')
<script src="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.js') }}"></script>

<script>
    $(document).ready(() => {
        let url = location.href.replace(/\/$/, "");

        if (location.hash) {
            const hash = url.split("#");
            $('#myTab a[href="#'+hash[1]+'"]').tab("show");
            url = location.href.replace(/\/#/, "#");
            history.replaceState(null, null, url);
            setTimeout(() => {
                $(window).scrollTop(0);
            }, 400);
        }

        $('a[data-toggle="tab"]').on("click", function() {
            let newUrl;
            const hash = $(this).attr("href");
            if(hash == "#summery") {
                newUrl = url.split("#")[0];
            } else {
                newUrl = url.split("#")[0] + hash;
            }
            // newUrl += "/";
            history.replaceState(null, null, newUrl);
        });
    });

    //Decline estimate
    function addDiscussion() {
        $.easyAjax({
            type:'POST',
            url:'{{route('admin.contracts.add-discussion', $contract->id)}}',
            container:'#estimates',
            data: $('#addDiscussion').serialize(),
            success: function(response){
                if(response.status == 'success') {
                    window.location.reload();
                }
            }
        })
    }
    //Accept estimate
    function edit(id) {
        var url = '{{ route('admin.contracts.edit-discussion', ':id') }}';
        url = url.replace(':id', id);
        $.ajaxModal('#estimateAccept', url);
    }

    $('body').on('click', '.remove-discussion', function(){
        var id = $(this).data('discussion-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.deleteDiscussionText')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.contracts.remove-discussion',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token},
                    success: function (response) {
                        $('#discussion-row-'+id).remove();
                    }
                });
            }
        });
    });

    $('#show-dropzone').click(function () {
        $('#file-dropzone').toggleClass('hide show');
    });

    $('#show-link-form').click(function () {
        $('#file-link').toggleClass('hide show');
    });

    $("body").tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    // "myAwesomeDropzone" is the camelized version of the HTML element's ID
    Dropzone.options.fileUploadDropzone = {
        paramName: "file", // The name that will be used to transfer the file
//        maxFilesize: 2, // MB,
        dictDefaultMessage: "@lang('modules.projects.dropFile')",
        accept: function (file, done) {
            done();
        },
        init: function () {
            this.on("success", function (file, response) {
                var viewName = $('#view').val();
                if(viewName == 'list') {
                    $('#files-list-panel ul.list-group').html(response.html);
                } else {
                    $('#thumbnail').empty();
                    $(response.html).hide().appendTo("#thumbnail").fadeIn(500);
                }
            })
        }
    };

    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('file-id');
        var deleteView = $(this).data('pk');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.removeFileText')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.contract-files.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE', 'view': deleteView},
                    success: function (response) {
                        console.log(response);
                        if (response.status == "success") {
                            $.unblockUI();
                            if(deleteView == 'list') {
                                $('#files-list-panel ul.list-group').html(response.html);
                            } else {
                                $('#thumbnail').empty();
                                $(response.html).hide().appendTo("#thumbnail").fadeIn(500);
                            }
                        }
                    }
                });
            }
        });
    });

    $('.thumbnail').on('click', function(event) {
        event.preventDefault();
        $('#thumbnail').empty();
        var contractID = "{{ $contract->id }}";
        $.easyAjax({
            type: 'GET',
            url: "{{ route('admin.contract-files.thumbnail') }}",
            data: {
                id: contractID
            },
            success: function (response) {
                $(response.view).hide().appendTo("#thumbnail").fadeIn(500);
            }
        });
    });

    $('#save-link').click(function () {
        $.easyAjax({
            url: '{{route('admin.contract-files.storeLink')}}',
            container: '#file-external-link',
            type: "POST",
            redirect: true,
            data: $('#file-external-link').serialize(),
            success: function () {
                window.location.reload();
            }
        })
    });


    $('#list-tabs').on("shown.bs.tab",function(event){
        var tabSwitch = $('#list').hasClass('active');
        if(tabSwitch == true) {
            $('#view').val('list');
        } else {
            $('#view').val('thumbnail');
        }
    });
    $('ul.showProjectTabs .projectFiles').addClass('tab-current');
</script>
@endpush


