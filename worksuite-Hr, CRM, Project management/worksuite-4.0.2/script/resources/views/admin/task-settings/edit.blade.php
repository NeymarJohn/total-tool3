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
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/jquery-asColorPicker-master/css/asColorPicker.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang($pageTitle)</div>

                <div class="vtabs customvtab">
                    @include('sections.admin_setting_menu')

                    <div class="row">
                        <div class="white-box">
                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="col-sm-12 m-t-10">

                                <div class="form-group">
                                    <div class="checkbox checkbox-info  col-md-10">
                                        <input id="self_task" name="self_task" value="yes"
                                                @if($global->task_self == "yes") checked
                                                @endif
                                                type="checkbox">
                                        <label for="self_task">@lang('messages.employeeSelfTask')</label>
                                    </div>
                                </div>

                            </div>
                            
                            <div class="col-sm-12">
                                <h4>@lang('modules.tasks.reminder')</h4>
                            </div>

                            <div class="col-md-3">

                                <div class="form-group">
                                    <label class="control-label">@lang('modules.tasks.preDeadlineReminder')  (@lang('app.days'))</label>
                                    <input type="number" value="{{ $global->before_days }}" min="0" class="form-control" name="before_days"> 
                                </div>

                            </div>

                            <div class="col-md-3">

                                <div class="form-group">
                                    <label class="control-label">@lang('modules.tasks.onDeadlineReminder')</label>
                                    <div class="radio-list">
                                        <label class="radio-inline p-0">
                                            <div class="radio radio-info">
                                                <input type="radio" name="on_deadline" @if ($global->on_deadline == 'yes') checked  @endif id="on_deadline_yes" value="yes">
                                                <label for="on_deadline_yes">@lang('app.yes')</label>
                                            </div>
                                        </label>
                                        <label class="radio-inline">
                                            <div class="radio radio-info">
                                                <input type="radio" name="on_deadline" @if ($global->on_deadline == 'no') checked  @endif id="on_deadline_no" value="no">
                                                <label for="on_deadline_no">@lang('app.no')</label>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-3">

                                <div class="form-group">
                                    <label class="control-label">@lang('modules.tasks.postDeadlineReminder')  (@lang('app.days'))</label>
                                    <input type="number" value="{{ $global->after_days }}" min="0" class="form-control" name="after_days"> 
                                </div>

                            </div>

                            <div class="col-md-3">

                                <div class="form-group">
                                    <label class="control-label">@lang('modules.tasks.defaultTaskStatus')</label>
                                    <select name="default_task_status" class="form-control" id="default_task_status">
                                        @foreach ($taskboardColumns as $item)
                                            <option
                                            @if ($item->id == $global->default_task_status)
                                                selected
                                            @endif 
                                            value="{{ $item->id }}">{{ $item->column_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="col-sm-12">
                                <button class="btn btn-success" id="save-form" type="button">@lang('app.save')</button>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                    <!-- /.row -->

                            
                </div>

            </div>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')

    <script>
        // change task Setting For Setting
        $('#save-form').click(function () {

            $.easyAjax({
                url: '{{route('admin.task-settings.store')}}',
                container: '#editSettings',
                type: "POST",
                data: $('#editSettings').serialize()               
            })
            
        });

    </script>
@endpush

