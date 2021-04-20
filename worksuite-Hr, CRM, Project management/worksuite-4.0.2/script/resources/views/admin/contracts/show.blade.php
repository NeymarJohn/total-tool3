<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <title>Admin Panel | @lang($pageTitle)</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css'>
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css'>

    <!-- This is Sidebar menu CSS -->
    <link href="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">

    <link href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}"   rel="stylesheet">
    <link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}"   rel="stylesheet">

    <!-- This is a Animation CSS -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">

<!-- This is a Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- color CSS you can use different color css from css/colors folder -->
    <!-- We have chosen the skin-blue (default.css) for this starter
       page. However, you can choose any other skin from folder css / colors .
       -->
    <link href="{{ asset('css/colors/default.css') }}" id="theme"  rel="stylesheet">
    <link href="{{ asset('plugins/froiden-helper/helper.css') }}"   rel="stylesheet">
    <link href="{{ asset('css/custom-new.css') }}"   rel="stylesheet">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
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

        .sidebar .notify  {
            margin: 0 !important;
        }
        .sidebar .notify .heartbit {
            top: -23px !important;
            right: -15px !important;
        }
        .sidebar .notify .point {
            top: -13px !important;
        }
        .wrapper {
            position: relative;
            width: 100%;
            height: 170px;
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .signature-pad {
            position: absolute;
            left: 0;
            top: 0;
            width:100%;
            height: 100%;
            background-color: white;
        }
        .tabs-style-line nav a {
             box-shadow: unset !important;
        }
        .discussion-action-button {
            display: none;
        }
        .discussion-action-button {
            display: none;
        }
        .sl-right:hover .discussion-action-button {
            display: block;
        }
        .admin-logo {
            max-height: 40px;
        }
    </style>
</head>
<body class="fix-sidebar">
<!-- Preloader -->
<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>
<div id="wrapper">

    <!-- Left navbar-header end -->
    <!-- Page Content -->
    <div id="page-wrapper" style="margin-left: 0px !important;">
        <div class="container">

            <!-- .row -->
            <div class="row">
                <div class="col-md-11 m-t-40 m-b-40">
                    <img src="{{ $global->logo_url }}" alt="home" class="admin-logo"/>
                </div>
                <div class="col-md-12" id="estimates">
                    <div class="row m-b-20">
                        <div class="col-md-12">
                            <div class="visible-xs">
                                <div class="clearfix"></div>
                            </div>
                            @if(!$contract->signature)
                            <button type="button" id="accept_action" class="btn btn-success pull-right m-r-10" onclick="sign();return false;"><i class="fa fa-check"></i> @lang('app.sign')</button>
                            @else
                                <button class="btn btn-default pull-right m-r-10 disabled"><i class="fa fa-check"></i> @lang('app.signed')</button>
                            @endif

                            <a href="{{ route("admin.contracts.download", $contract->id) }}" class="btn btn-default pull-right m-r-10"><i class="fa fa-file-pdf-o"></i> @lang('app.download')</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="white-box printableArea" style="background: #ffffff !important;">
                                <div class="sttabs tabs-style-line" id="invoice_container">
                                    <nav>
                                        <ul class="customtab" role="tablist" id="myTab">
                                            <li class="nav-item active"><a class="nav-link" href="#summery" data-toggle="tab" role="tab"><span><i class="glyphicon glyphicon-file"></i> @lang('modules.contracts.summery')</span></a>
                                            </li>

                                            <li class="nav-item"><a class="nav-link" href="#discussion" data-toggle="tab" role="tab"><span><i class="glyphicon glyphicon-comment"></i> @lang('modules.contracts.discussion')</span></a></li>

                                            <li class="nav-item active"><a class="nav-link" href="#files" data-toggle="tab" role="tab"><span><i class="glyphicon glyphicon-image"></i> @lang('app.files')</span></a></li>

                                        </ul>
                                    </nav>

                                    <div class="tab-content tabcontent-border">
                                        <div class="tab-pane active" id="summery" role="tabpanel">
                                            <div class="row p-20">
                                                <div class="col-md-8">
                                                    {!! $contract->contract_detail !!}
                                                </div>
                                                <div class="col-md-4 p-l-30">
                                                    <div class="card p-20">
                                                        <div class="card-body">
                                                            <address>
                                                                <h3><b class="text-danger">{{ ucwords($global->company_name) }}</b></h3>
                                                                <p class="text-muted">{!! nl2br($global->address) !!}</p>
                                                            </address>
                                                            @if($contract->amount != 0)
                                                              <h3>@lang('modules.contracts.contractValue'): {{ $global->currency->currency_symbol }} {{ $contract->amount }}</h3>
                                                            @endif
                                                            <table>
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
                                        <div class="tab-pane  p-20" id="discussion" role="tabpanel">
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
                                                    <div class="card p-20">
                                                        <div class="card-body">
                                                            <address>
                                                                <h3><b class="text-danger">{{ ucwords($global->company_name) }}</b></h3>
                                                                <p class="text-muted">{!! nl2br($global->address) !!}</p>
                                                            </address>
                                                            <h3>@lang('modules.contracts.contractValue'): {{ $global->currency->currency_symbol }} {{ $contract->amount }}</h3>

                                                            <table>
                                                                <tr>
                                                                    <td># @lang('modules.contracts.contractNumber')</td>
                                                                    <td>{{ $contract->id }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('modules.projects.startDate')</td>
                                                                    <td>{{ $contract->start_date->format($global->date_format) }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('modules.contracts.endDate')</td>
                                                                    <td>{{ $contract->end_date == null ? $contract->end_date : $contract->end_date->format($global->date_format) }}</td>
                                                                </tr>
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
                                                        <form action="{{ route('admin.files.store') }}" class="dropzone"
                                                              id="file-upload-dropzone">
                                                            {{ csrf_field() }}

                                                            {!! Form::hidden('project_id', $project->id) !!}

                                                            <input name="view" type="hidden" id="view" value="list">

                                                            <div class="fallback">
                                                                <input name="file" type="file" multiple/>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="row m-b-20 hide" id="file-link">
                                                    {!! Form::open(['id'=>'file-external-link','class'=>'ajax-form','method'=>'POST']) !!}


                                                    {!! Form::hidden('project_id', $project->id) !!}

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
                                                                                        class="fa fa-search"></i></a>

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
                </div>


            </div>

        </div>
        <!-- /.container-fluid -->
        <footer class="text-center m-t-20"> {{ \Carbon\Carbon::now()->year }} &copy; {{ $global->company_name }} </footer>

    </div>
    <!-- /#page-wrapper -->


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
</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<!-- Sidebar menu plugin JavaScript -->
<script src="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>
<!--Slimscroll JavaScript For custom scroll-->
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('js/waves.js') }}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{ asset('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
<script src="{{ asset('plugins/froiden-helper/helper.js') }}"></script>
<script src="{{ asset('plugins/bower_components/toast-master/js/jquery.toast.js') }}"></script>

{{--sticky note script--}}
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bower_components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/icheck/icheck.init.js') }}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
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
    function sign() {
        var url = '{{ route('admin.contracts.sign-modal', $contract->id) }}';
        $.ajaxModal('#estimateAccept', url);
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
        var projectID = "{{ $project->id }}";
        $.easyAjax({
            type: 'GET',
            url: "{{ route('admin.contract-files.thumbnail') }}",
            data: {
                id: projectID
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

</body>
</html>
