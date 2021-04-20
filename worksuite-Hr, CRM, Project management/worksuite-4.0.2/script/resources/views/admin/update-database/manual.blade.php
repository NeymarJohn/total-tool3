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
            <li class="active">@lang($pageTitle)</li>
        </ol>
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
    @php($envatoUpdateCompanySetting = \Froiden\Envato\Functions\EnvatoUpdate::companySetting())
        @if(!is_null($envatoUpdateCompanySetting->supported_until))
            <div class="col-md-12" id="support-div">
                @if(\Carbon\Carbon::parse($envatoUpdateCompanySetting->supported_until)->isPast())
                    <div class="col-md-12 alert alert-danger ">
                        <div class="col-md-6">
                            Your support has been expired on <b><span
                                        id="support-date">{{\Carbon\Carbon::parse($envatoUpdateCompanySetting->supported_until)->format('d M, Y')}}</span></b>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ config('froiden_envato.envato_product_url') }}" target="_blank"
                            class="btn btn-inverse btn-small">Renew support <i class="fa fa-shopping-cart"></i></a>
                            <a href="javascript:;" onclick="getPurchaseData();" class="btn btn-inverse btn-small">Refresh
                                <i
                                        class="fa fa-refresh"></i></a>
                        </div>
                    </div>

                @else
                    <div class="col-md-12 alert alert-info">
                        Your support will expire on <b><span
                                    id="support-date">{{\Carbon\Carbon::parse($envatoUpdateCompanySetting->supported_until)->format('d M, Y')}}</span></b>
                    </div>
                @endif
            </div>
        @endif

        
    <div class="col-md-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">@lang($pageTitle)</div>

            <div class="vtabs customvtab m-t-10">

                @include('sections.admin_setting_menu')


                <div class="tab-content">

                    
                    
                    <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ route('admin.update-settings.index') }}" class="btn btn-warning btn-sm btn-outline "><i class="fa fa-arrow-left"></i> @lang('app.back')</a>
                                </div>
                            </div>

                            @if(!is_null($envatoUpdateCompanySetting->supported_until) && !\Carbon\Carbon::parse($envatoUpdateCompanySetting->supported_until)->isPast())

                                @if(isset($lastVersion))
                                <!--row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="box-title" id="structure">@lang('modules.update.updateManual')</h4>
                                    </div>

                                    <div class="col-md-12">
                                        <h4 class="box-title text-info">Step 1</h4>
                                        <a href="https://updates.froid.works/worksuite/download.php?{{ $encryptedDownloadLink }}" target="_blank" class="btn btn-success btn-sm btn-outline">@lang('modules.update.downloadUpdateFile') <i class="fa fa-download"></i></a>
                                    </div>

                                    <div class="col-md-12 m-t-20">
                                            <h4 class="box-title text-info">Step 2</h4>
                                            <form action="{{ route('admin.update-settings.store') }}" class="dropzone" id="file-upload-dropzone">
                                            {{ csrf_field() }}

                                            <div class="fallback">
                                                <input name="file" type="file" multiple />
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-12 m-t-20" id="install-process">
                                        
                                    </div>

                                    <div class="col-md-12 m-t-20">
                                        <h4 class="box-title text-info">Step 3</h4>
                                        <h4 class="box-title">@lang('modules.update.updateFiles')</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <ul class="list-group" id="files-list">
                                            @foreach (\Illuminate\Support\Facades\File::files($updateFilePath) as $key=>$filename)
                                            @if (\Illuminate\Support\Facades\File::basename($filename) != "modules_statuses.json")
                                                <li class="list-group-item" id="file-{{ $key+1 }}">
                                                        <div class="row">
                                                            <div class="col-md-9">
                                                                {{ \Illuminate\Support\Facades\File::basename($filename) }}
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <button type="button" class="btn btn-success btn-sm btn-outline install-files" data-file-no="{{ $key+1 }}" data-file-path="{{ $filename }}">@lang('modules.update.install') <i class="fa fa-refresh"></i></button>

                                                                <button type="button" class="btn btn-danger btn-sm btn-outline delete-files" data-file-no="{{ $key+1 }}" data-file-path="{{ $filename }}">@lang('app.delete') <i class="fa fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </li>
                                            @endif
                                            @endforeach
                                        </ul>
                                    </div>



                                </div>
                                <!--/row-->
                                @else
                                    <div class="row">
                                        <div class="col-md-12 m-t-20">
                                            <div class="alert alert-success ">
                                                    You have latest version of this app.
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                    </div>
                </div>


            </div>
        </div>


    </div>
    <!-- .row -->

    @endsection

    @push('footer-script')
    <script src="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.js') }}"></script>
    <script type="text/javascript">
        // "myAwesomeDropzone" is the camelized version of the HTML element's ID
        Dropzone.options.fileUploadDropzone = {
            paramName: "file", // The name that will be used to transfer the file
    //        maxFilesize: 2, // MB,
            dictDefaultMessage: "Upload or drop the downloaded file here",
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



        var updateAreaDiv = $('#update-area');
        var refreshPercent = 0;
        var checkInstall = true;

        function checkIfFileExtracted(){
            $.easyAjax({
                type: 'GET',
                url: '{!! route("admin.updateVersion.checkIfFileExtracted") !!}',
                success: function (response) {
                    checkInstall = false;
                    $('#download-progress').append("<br><i><span class='text-success'>Installed successfully. Reload page to see the changes.</span>.</i>");
                    document.getElementById('logout-form').submit();
                }
            });
        }

        $('.install-files').click(function(){
            $('#install-process').html('<div class="alert alert-info ">Installing...Please wait (This may take few minutes.)</div>');
            window.setInterval(function(){
                /// call your function here
                if(checkInstall == true){
                    checkIfFileExtracted();
                    console.log("install check");
                }
            }, 1500);
            console.log("install start");
            
            let filePath = $(this).data('file-path');
            $.easyAjax({
                type: 'GET',
                url: '{!! route("admin.update-settings.install") !!}',
                data: {filePath: filePath},
                success: function (response) {
                    $('#download-progress').append("<br><i><span class='text-success'>Installed successfully. Reload page to see the changes.</span>.</i>");
                    document.getElementById('logout-form').submit();
                }
            });
        });

        $('.delete-files').click(function(){
            let filePath = $(this).data('file-path');
            let fileNumber = $(this).data('file-no');

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
                   
                    $.easyAjax({
                        type: 'POST',
                        url: '{!! route("admin.update-settings.deleteFile") !!}',
                        data: {"_token": "{{ csrf_token() }}", filePath: filePath},
                        success: function (response) {
                            $('#file-'+fileNumber).remove();
                        }
                    });
                }
            });
        });
    </script>
    @endpush