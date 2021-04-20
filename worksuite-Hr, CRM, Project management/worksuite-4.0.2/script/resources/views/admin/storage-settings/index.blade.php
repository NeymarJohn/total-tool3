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
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
@endpush

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">@lang('app.menu.storageSettings')</div>

            <div class="vtabs customvtab m-t-10">
                @include('sections.admin_setting_menu')

                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">

                            <div class="row">
                                <div class="col-sm-12 col-xs-12 ">
                                    {!! Form::open(['id'=>'updateSettings','class'=>'ajax-form','method'=>'POST']) !!}
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3 class="box-title text-success">File Storage</h3>
                                                <hr class="m-t-0 m-b-20">
                                            </div>


                                            <div class="col-md-12">

                                                <div class="form-group m-t-10">
                                                    <label class="control-label">Select Storage</label>
                                                    <select class="select2 form-control" id="storage" name="storage">
                                                        <option value="local" @if(isset($localCredentials) &&
                                                            $localCredentials->status == 'enabled') selected
                                                            @endif>Local (Default)</option>
                                                        <option value="aws" @if(isset($awsCredentials) &&
                                                            $awsCredentials->status == 'enabled') selected @endif>AWS
                                                            Storage (Amazon Web Services)</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12 aws-form">
                                                <div class="form-group">
                                                    <label>AWS Key</label>
                                                    <input type="text" class="form-control" name="aws_key"
                                                        @if(isset($awsCredentials) && isset($awsCredentials->key))
                                                    value="{{ $awsCredentials->key }}" @endif>
                                                </div>
                                                <div class="form-group">
                                                    <label>AWS Secret</label>
                                                    <input type="password" readonly="readonly"
                                                        onfocus="this.removeAttribute('readonly');"
                                                        class="form-control auto-complete-off" name="aws_secret"
                                                        @if(isset($awsCredentials) && isset($awsCredentials->secret))
                                                    value="{{ $awsCredentials->secret }}" @endif>
                                                    <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label>AWS Region</label>
                                                    <select class="select2 form-control" name="aws_region">
                                                        @foreach (\App\StorageSetting::$awsRegions as $key =>
                                                        $region)

                                                        <option @if(isset($awsCredentials) && $awsCredentials->
                                                            region == $key) selected @endif
                                                            value="{{$key}}">{{  $region }}</option>

                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>AWS Bucket</label>
                                                    <input type="text" class="form-control" id="company_name"
                                                        name="aws_bucket" @if(isset($awsCredentials) &&
                                                        isset($awsCredentials->bucket))
                                                    value="{{ $awsCredentials->bucket }}" @endif>
                                                </div>
                                            </div>

                                        </div>

                                        <!--/row-->

                                    </div>
                                    <div class="form-actions m-t-20">
                                        <button type="submit" id="save-form-2" class="btn btn-success"><i
                                                class="fa fa-check"></i>
                                            @lang('app.save')
                                        </button>
                                        <button type="button" id="test-aws" class="aws-form btn btn-primary m-t-10">
                                            Test AWS
                                        </button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>


</div>
<!-- .row -->


{{--Ajax Modal--}}
<div class="modal fade bs-modal-md in" id="testMailModal" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" id="modal-data-application">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Test AWS Setting</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['id'=>'testEmail','class'=>'ajax-form','method'=>'POST']) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label>Upload file to test if its getting uploaded to aws bucket</label>
                                <input type="file" name="file" id="file">
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                </div>
                <div class="form-actions">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="test-aws-submit">submit</button>
                    <a target="_blank" class="btn btn-info" href="" id="show-file">View File</a>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->.
    </div>

</div> {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script>
    $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        $('#test-aws').click(function () {
            $('#testMailModal').modal('show');
            $('#show-file').hide();

    });
        $(function () {
           var type = $('#storage').val();
            if (type == 'aws') {
                $('.aws-form').css('display', 'block');
            } else if(type == 'local') {
                $('.aws-form').css('display', 'none');
            }
        });

        $('#storage').on('change', function(event) {
            event.preventDefault();
            var type = $(this).val();
            if (type == 'aws') {
                $('.aws-form').css('display', 'block');
            } else if(type == 'local') {
                $('.aws-form').css('display', 'none');
            }
        });

        $('#save-form-2').click(function () {
            $.easyAjax({
                url: '{{ route('admin.storage-settings.store')}}',
                container: '#updateSettings',
                type: "POST",
                redirect: true,
                data: $('#updateSettings').serialize()
            })
        });
        
        $('#test-aws-submit').click(function () {
            $.easyAjax({
                url: '{{route('admin.storage-settings.awstest')}}',
                type: "POST",
                file:true,
                messagePosition: "inline",
                container: "#testEmail",
                data: $('#testEmail').serialize(),
                success: function(response){
                    if(response.status == 'success') {
                        $('#show-file').show();
                        $("#show-file").attr("href", response.fileurl);
                    }
                 }

            })
        });
</script>
@endpush