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
                <div class="panel-heading">{{ __($pageTitle) }}</div>

                <div class="vtabs customvtab m-t-10">
                    @include('sections.super_admin_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-xs-12">

                        <div class="alert alert-info">
                            We recommend using <strong>SMTP</strong>. <strong>Mail</strong> settings might not work on every server which also
                            results in emails landing to SPAM
                        </div>
                                    {!! Form::open(['id'=>'updateSettings','class'=>'ajax-form']) !!}
                                    {!! Form::hidden('_token', csrf_token()) !!}
                                    @method('PUT')
                                    <div id="alert">
                                        @if($smtpSetting->mail_driver =='smtp')
                                            @if($smtpSetting->verified)
                                                <div class="alert alert-success">{{__('messages.smtpSuccess')}}</div>
                                            @else
                                                <div class="alert alert-danger">{{__('messages.smtpError')}}</div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <label>@lang("modules.emailSettings.mailDriver")</label>
                                                <div class="form-group">
                                                    <label class="radio-inline"><input type="radio" class="checkbox" onchange="getDriverValue(this);" value="mail" @if($smtpSetting->mail_driver == 'mail') checked @endif name="mail_driver">Mail</label>
                                                    <label class="radio-inline m-l-10"><input type="radio" onchange="getDriverValue(this);"  value="smtp" @if($smtpSetting->mail_driver == 'smtp') checked @endif name="mail_driver">SMTP</label>


                                                </div>
                                            </div>

                                            <!--/span-->
                                        </div>
                                        <div id="smtp_div">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang("modules.emailSettings.mailHost")</label>
                                                        <input type="text" name="mail_host" id="mail_host"
                                                               class="form-control"
                                                               value="{{ $smtpSetting->mail_host }}">
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang("modules.emailSettings.mailPort")</label>
                                                        <input type="text" name="mail_port" id="mail_port"
                                                               class="form-control"
                                                               value="{{ $smtpSetting->mail_port }}">
                                                    </div>
                                                </div>
                                                <!--/span-->

                                                <div class="col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang("modules.emailSettings.mailUsername")</label>
                                                        <input type="text" name="mail_username"
                                                               id="mail_username"
                                                               class="form-control"
                                                               value="{{ $smtpSetting->mail_username }}">
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <!--/row-->

                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">@lang("modules.emailSettings.mailPassword")</label>
                                                        <input type="password" name="mail_password"
                                                               id="mail_password"
                                                               class="form-control"
                                                               value="{{ $smtpSetting->mail_password }}">
                                                        <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">@lang("modules.emailSettings.mailEncryption")</label>
                                                        <select class="form-control" name="mail_encryption"
                                                                id="mail_encryption">
                                                            <option @if($smtpSetting->mail_encryption == 'tls') selected @endif>
                                                                tls
                                                            </option>
                                                            <option @if($smtpSetting->mail_encryption == 'ssl') selected @endif>
                                                                ssl
                                                            </option>

                                                            <option value="null" @if($smtpSetting->mail_encryption == null) selected @endif>
                                                                none
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>

                                    </div>

                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">@lang("modules.emailSettings.mailFrom")</label>
                                                        <input type="text" name="mail_from_name"
                                                               id="mail_from_name"
                                                               class="form-control"
                                                               value="{{ $smtpSetting->mail_from_name }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->

                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">@lang("modules.emailSettings.mailFromEmail")</label>
                                                        <input type="text" name="mail_from_email"
                                                               id="mail_from_email"
                                                               class="form-control"
                                                               value="{{ $smtpSetting->mail_from_email }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->


                                    <div class="form-actions">
                                        <button type="submit" id="save-form" class="btn btn-success"><i
                                                    class="fa fa-check"></i>
                                            @lang('app.update')
                                        </button>
                                        <button type="button" id="send-test-email"
                                                class="btn btn-primary">@lang('modules.emailSettings.sendTestEmail')</button>
                                    </div>
                                    {!! Form::close() !!}

                                </div>
                                <!-- .row -->

                                <div class="clearfix"></div>
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
                        <h4 class="modal-title">Test Email</h4>
                    </div>
                    <div class="modal-body">
                        {!! Form::open(['id'=>'testEmail','class'=>'ajax-form','method'=>'POST']) !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>Enter email address where test mail needs to be sent</label>
                                        <input type="text" name="test_email" id="test_email"
                                               class="form-control"
                                               value="{{ $user->email }}">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" id="send-test-email-submit">submit</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->.
            </div>

        </div> {{--Ajax Modal Ends--}}
    </div> {{--Ajax Modal Ends--}}

        @endsection

        @push('footer-script')
        <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
        <script>
            $('#save-form').click(function () {

                var url = '{{route('super-admin.email-settings.update', $smtpSetting->id)}}';

                $.easyAjax({
                    url: url,
                    type: "POST",
                    container: '#updateSettings',
                    data: $('#updateSettings').serialize(),
                    messagePosition: "inline",
                    success: function (response) {
                    if (response.status == 'error') {
                        $('#alert').prepend('<div class="alert alert-danger">{{__('messages.smtpError')}}</div>')
                    }else{
                        $('#alert').show();
                    }
                }
                })
            });

             $('#send-test-email').click(function () {
            $('#testMailModal').modal('show')
        });

        $('#send-test-email-submit').click(function () {
            $.easyAjax({
                url: '{{route('super-admin.email-settings.sendTestEmail')}}',
                type: "GET",
                messagePosition: "inline",
                container: "#testEmail",
                data: $('#testEmail').serialize()

            })
        });


        function getDriverValue(sel) {
            if (sel.value == 'mail') {
                $('#smtp_div').hide();
                $('#alert').hide();
            } else {
                $('#smtp_div').show();
                $('#alert').show();
            }
        }

        @if ($smtpSetting->mail_driver == 'mail')
        $('#smtp_div').hide();
        $('#alert').hide();
        @endif
        </script>
    @endpush
