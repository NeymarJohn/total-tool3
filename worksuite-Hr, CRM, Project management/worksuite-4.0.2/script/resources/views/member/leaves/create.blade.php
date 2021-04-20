@extends('layouts.member-app')

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
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.leaves.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.leaves.assignLeave')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createLeave','class'=>'ajax-form','method'=>'POST']) !!}
                        <div class="form-body">
                            {!! Form::hidden('user_id', $user->id) !!}
                            <div class="row">

                                <div class="col-md-12 ">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('modules.leaves.leaveType')</label>
                                        <select class="selectpicker form-control" name="leave_type_id" id="leave_type_id"
                                                data-style="form-control">
                                            @forelse($leaveTypes as $leaveType)
                                                <option value="{{ $leaveType->leaveType->id }}">{{ ucwords($leaveType->leaveType->type_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noLeaveTypeAdded')</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('modules.leaves.selectDuration')</label>
                                        <div class="radio-list">
                                            <label class="radio-inline p-0">
                                                <div class="radio radio-info">
                                                    <input type="radio" name="duration" id="duration_single" checked value="single">
                                                    <label for="duration_single">@lang('modules.leaves.single')</label>
                                                </div>
                                            </label>
                                            <label class="radio-inline">
                                                <div class="radio radio-info">
                                                    <input type="radio" name="duration" id="duration_multiple" value="multiple">
                                                    <label for="duration_multiple">@lang('modules.leaves.multiple')</label>
                                                </div>
                                            </label>
                                            <label class="radio-inline">
                                                <div class="radio radio-info">
                                                    <input type="radio" name="duration" id="duration_half_day" value="half day">
                                                    <label for="duration_half_day">@lang('modules.leaves.halfDay')</label>
                                                </div>
                                            </label>

                                        </div>

                                    </div>
                                </div>

                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-6" id="single-date">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('app.date')</label>
                                        <input type="text" class="form-control" name="leave_date" id="single_date" >
                                    </div>
                                </div>

                                <div class="col-md-6" id="multi-date" style="display: none">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('modules.leaves.selectDates') (@lang('messages.selectMultipleDates'))</label>
                                        <input type="text" class="form-control" name="multi_date" id="multi_date">
                                    </div>
                                </div>

                            </div>
                            <!--/span-->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.leaves.reason')</label>
                                        <textarea name="reason" id="reason" class="form-control" cols="30" rows="5"></textarea>
                                    </div>
                                </div>

                                {!! Form::hidden('status', 'pending') !!}

                            </div>


                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form-2" class="btn btn-success"><i class="fa fa-check"></i>
                                @lang('app.save')
                            </button>
                            <button type="reset" class="btn btn-default">@lang('app.reset')</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel"
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
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>


    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    var disabledDates = [
        @foreach($leaves as $leave)
            {!! '"'.$leave->leave_date->format($global->date_format).'",' !!}
        @endforeach
    ];
    jQuery('#multi_date').datepicker({
        format: '{{ $global->date_picker_format }}',
        multidate: true,
        todayHighlight: true,
        datesDisabled: disabledDates
    });

    jQuery('#single_date').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true,
        datesDisabled: disabledDates
    });

    $("input[name=duration]").click(function () {
        if($(this).val() == 'multiple'){
            $('#multi-date').show();
            $('#single-date').hide();
        }
        else{
            $('#multi-date').hide();
            $('#single-date').show();
        }
    })


    $('#save-form-2').click(function () {
        $.easyAjax({
            url: '{{route('member.leaves.store')}}',
            container: '#createLeave',
            type: "POST",
            redirect: true,
            data: $('#createLeave').serialize()
        })
    });
</script>
@endpush