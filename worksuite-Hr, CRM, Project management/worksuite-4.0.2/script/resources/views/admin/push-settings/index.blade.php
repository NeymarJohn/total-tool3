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
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.pushSettings.updateTitle')</div>

                <div class="vtabs customvtab m-t-10">

                    @include('sections.notification_settings_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-md-6">

                                    <h3 class="box-title m-b-0">@lang("modules.slackSettings.notificationTitle")</h3>

                                    <p class="text-muted m-b-10 font-13">
                                        @lang("modules.slackSettings.notificationSubtitle")
                                    </p>

                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12 b-t p-t-20">
                                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form form-horizontal','method'=>'PUT','autocomplete'=>"off"]) !!}
                                            @forelse($emailSettings as $emailSetting)
                                                <div class="form-group">
                                                    <label class="control-label col-sm-8">@lang('modules.emailNotification.'.str_slug($emailSetting->setting_name))</label>

                                                    <div class="col-sm-4">
                                                        <div class="switchery-demo">
                                                            <input type="checkbox"
                                                                   @if($emailSetting->send_push == 'yes') checked
                                                                   @endif class="js-switch change-email-setting"
                                                                   data-color="#99d683"
                                                                   data-setting-id="{{ $emailSetting->id }}"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                            @endforelse
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    {!! Form::open(['id'=>'editSlackSettings','class'=>'ajax-form','method'=>'PUT']) !!}


                                    <h5>
                                        Signup on <a href="https://onesignal.com/" target="_blank">onesignal.com</a>
                                    </h5>
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="company_name">@lang('modules.pushSettings.oneSignalAppId')</label>

                                            <input type="text" class="form-control" id="onesignal_app_id"
                                                   name="onesignal_app_id" value="{{ $pushSettings->onesignal_app_id }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="company_name">@lang('modules.pushSettings.oneSignalRestApiKey')</label>


                                            <input type="password" readonly="readonly" onfocus="this.removeAttribute('readonly');" class="form-control auto-complete-off" id="onesignal_rest_api_key"
                                                   name="onesignal_rest_api_key" value="{{ $pushSettings->onesignal_rest_api_key }}">
                                            <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        </div>

                                        <div class="form-group">
                                            <label for="company_name">@lang('app.status')</label>
                                            <select name="status" class="form-control" id="">
                                                <option
                                                        @if($pushSettings->status == 'inactive') selected @endif
                                                        value="inactive">@lang('app.inactive')</option>
                                                <option
                                                        @if($pushSettings->status == 'active') selected @endif
                                                        value="active">@lang('app.active')</option>
                                            </select>
                                        </div>


                                    </div>


                                    <div class="form-actions m-t-20">
                                        <button type="submit" id="save-form"
                                                class="btn btn-success waves-effect waves-light m-r-10">
                                            @lang('app.update')
                                        </button>
                                        <button type="button" id="send-test-notification"
                                                class="btn btn-primary waves-effect waves-light">@lang('modules.slackSettings.sendTestNotification')</button>
                                        <button type="reset"
                                                class="btn btn-inverse waves-effect waves-light">@lang('app.reset')</button>
                                    </div>

                                    {!! Form::close() !!}
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>

    <script>

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.push-notification-settings.update', ['1'])}}',
                container: '#editSlackSettings',
                type: "POST",
                redirect: true,
                file: true
            })
        });
        $('#removeImageButton').change(function () {
            var removeButton;
            if ($(this).is(':checked'))
                removeButton = 'yes';
            else
                removeButton = 'no';

            var img;
            if(removeButton == 'yes'){
                img = '<img src="https://placeholdit.imgix.net/~text?txtsize=25&txt=@lang('modules.slackSettings.uploadSlackLogo')&w=200&h=150" alt=""/>';
            }
            else{
                img = '<img src="{{ asset_url('notification-logo/'.$pushSettings->notification_logo) }}" alt=""/>'
            }
            $('.thumbnail').html(img);

        });
    </script>
    <script>

        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());

        });

        $('.change-email-setting').change(function () {
            var id = $(this).data('setting-id');

            if ($(this).is(':checked'))
                var sendSlack = 'yes';
            else
                var sendSlack = 'no';

            var url = '{{route('admin.push-notification-settings.updatePushNotification', ':id')}}';
            url = url.replace(':id', id);
            $.easyAjax({
                url: url,
                type: "POST",
                data: {'id': id, 'send_push': sendSlack, '_method': 'POST', '_token': '{{ csrf_token() }}'}
            })
        });

        $('#send-test-notification').click(function () {

            var url = '{{route('admin.push-notification-settings.sendTestNotification')}}';

            $.easyAjax({
                url: url,
                type: "GET",
                success: function (response) {

                }
            })
        });



    </script>
@endpush

