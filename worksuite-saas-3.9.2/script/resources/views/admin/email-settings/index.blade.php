@extends('layouts.app')

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
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
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
                    @include('sections.notification_settings_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-xs-12">

                                    <h3 class="box-title m-b-0">@lang("modules.emailSettings.notificationTitle")</h3>

                                    <p class="text-muted m-b-10 font-13">
                                        @lang("modules.emailSettings.notificationSubtitle")
                                    </p>

                                    <div class="row">
                                        <div class="col-sm-6 col-xs-6 b-t p-t-20">
                                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form form-horizontal','method'=>'PUT']) !!}
                                               @forelse($emailSettings as $emailSetting)
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-8">@lang('modules.emailNotification.'.str_slug($emailSetting->setting_name))</label>

                                                        <div class="col-sm-4">
                                                            <div class="switchery-demo">
                                                                <input type="checkbox"
                                                                       @if($emailSetting->send_email == 'yes') checked
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

            // Switchery
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            $('.js-switch').each(function () {
                new Switchery($(this)[0], $(this).data());

            });

            $('.change-email-setting').change(function () {
                var id = $(this).data('setting-id');

                if ($(this).is(':checked'))
                    var sendEmail = 'yes';
                else
                    var sendEmail = 'no';

                var url = '{{route('admin.email-settings.update', ':id')}}';
                url = url.replace(':id', id);
                $.easyAjax({
                    url: url,
                    type: "POST",
                    data: {'id': id, 'send_email': sendEmail, '_method': 'PUT', '_token': '{{ csrf_token() }}'}
                })
            });

            $('#save-form').click(function () {

                var url = '{{route('admin.email-settings.updateMailConfig')}}';

                $.easyAjax({
                    url: url,
                    type: "POST",
                    container: '#updateSettings',
                    data: $('#updateSettings').serialize()
                })
            });

            $('#send-test-email').click(function () {

                var url = '{{route('admin.email-settings.sendTestEmail')}}';

                $.easyAjax({
                    url: url,
                    type: "GET",
                    success: function (response) {

                    }
                })
            });
        </script>
    @endpush
