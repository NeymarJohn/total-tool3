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
                <div class="panel-heading">@lang('app.update') @lang('app.menu.projectSettings')</div>

                <div class="vtabs customvtab m-t-10">

                    @include('sections.admin_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">@lang('modules.accountSettings.sendReminder')
                                                <a class="mytooltip" href="javascript:void(0)">
                                                    <i class="fa fa-info-circle"></i>
                                                    <span class="tooltip-content5">
                                                                <span class="tooltip-text3">
                                                                    <span class="tooltip-inner2">
                                                                        @lang('modules.accountSettings.sendReminderInfo')
                                                                    </span>
                                                                </span>
                                                            </span>
                                                </a>
                                            </label>
                                            <div class="switchery-demo">
                                                <input type="checkbox" id="send_reminder" name="send_reminder"
                                                       @if($projectSetting->send_reminder == 'yes') checked
                                                       @endif class="js-switch " data-color="#00c292"
                                                       data-secondary-color="#f96262"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row @if($projectSetting->send_reminder == 'no') hide @endif" id="send_reminder_div">
                                    <div class="col-md-12">
                                        <label>@lang('modules.projectSettings.sendNotificationsTo')</label>
                                        <div class="form-group">
                                            <div id="remind_to">
                                                <div class="checkbox checkbox-info checkbox-inline m-r-10">
                                                    <input id="send_reminder_admin" name="remind_to[]" value="admins"
                                                           @if(in_array('admins', $projectSetting->remind_to) != false) checked @endif
                                                           type="checkbox">
                                                    <label for="send_reminder_admin">@lang('modules.messages.admins')</label>
                                                </div>
                                                <div class="checkbox checkbox-info checkbox-inline">
                                                    <input id="send_reminder_member" name="remind_to[]" value="members"
                                                           @if(in_array('members', $projectSetting->remind_to) != false) checked @endif
                                                           type="checkbox">
                                                    <label for="send_reminder_member">@lang('modules.messages.members')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-3">
                                        <div class="form-group">
                                            <label>@lang('modules.events.remindBefore')</label>
                                            <input type="number" min="1" value="{{ $projectSetting->remind_time }}" name="remind_time" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            {{--<select name="remind_type" id="" class="form-control">--}}
                                                {{--<option value="day">@lang('app.day')</option>--}}
                                                {{--<option value="hour">@lang('app.hour')</option>--}}
                                                {{--<option value="minute">@lang('app.minute')</option>--}}
                                            {{--</select>--}}
                                            <input type="text" readonly value="{{ $projectSetting->remind_type }}" name="remind_type" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                                    @lang('app.update')
                                </button>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>

    <script>
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());
        });

        var changeCheckbox = document.getElementById('send_reminder');

        changeCheckbox.onchange = function () {
            if (changeCheckbox.checked) {
                $('#send_reminder_div').removeClass('hide');
            } else {
                $('#send_reminder_div').addClass('hide');
            }
        };

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.project-settings.update', ['1'])}}',
                container: '#editSettings',
                type: "POST",
                redirect: true,
                data: $('#editSettings').serialize()
            })
        });

        $('.checkbox').change(function () {
            $(this).siblings('.help-block').remove();
            $(this).parents('.form-group').removeClass('has-error');
        });

        $('#reset').click(function () {
            $('#remind_time').val('{{ $projectSetting->remind_time }}').trigger('change');
        })
    </script>
@endpush
