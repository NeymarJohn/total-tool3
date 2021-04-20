<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="icon-pencil"></i> @lang('app.edit') @lang('app.menu.leaves')</h4>
</div>
<div class="modal-body">
    {!! Form::open(['id'=>'createLeave','class'=>'ajax-form','method'=>'PUT']) !!}
    <div class="form-body">
        <div class="row">
            <div class="col-md-12 ">
                <div class="form-group">
                    <label>@lang('modules.messages.chooseMember')</label>
                    <select class="select2 form-control" data-placeholder="@lang('modules.messages.chooseMember')" name="user_id">
                        @foreach($employees as $employee)
                            <option
                                    @if($leave->user_id == $employee->id) selected @endif
                            value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!--/span-->
        </div>
        <div class="row">

            <div class="col-md-12 ">
                <div class="form-group">
                    <label class="control-label">@lang('modules.leaves.leaveType')</label>
                    <select class="form-control" name="leave_type_id" id="leave_type_id"
                            data-style="form-control">
                        @forelse($leaveTypes as $leaveType)
                            <option
                                    @if($leave->leave_type_id == $leaveType->id) selected @endif
                            value="{{ $leaveType->id }}">{{ ucwords($leaveType->type_name) }}</option>
                        @empty
                            <option value="">@lang('messages.noLeaveTypeAdded')</option>
                        @endforelse
                    </select>
                </div>
            </div>

        </div>
        <!--/row-->

        <div class="row">
            <div class="col-md-6">
                <label>@lang('app.date')</label>
                <div class="form-group">
                    <input type="text" class="form-control" name="leave_date" id="single_date" value="{{ $leave->leave_date->format($global->date_format) }}">
                </div>
            </div>

        </div>
        <!--/span-->

        <div class="row">
            <div class="col-md-12">
                <label>@lang('modules.leaves.reason')</label>
                <div class="form-group">
                    <textarea name="reason" id="reason" class="form-control" cols="30" rows="5">{!! $leave->reason !!}</textarea>
                </div>
            </div>

            <div class="col-md-12">
                <label>@lang('app.status')</label>
                <div class="form-group">
                    <select class="form-control" data-style="form-control" name="status" id="status" >
                        <option
                                @if($leave->status == 'approved') selected @endif
                        value="approved">@lang('app.approved')</option>
                        <option
                                @if($leave->status == 'pending') selected @endif
                        value="pending">@lang('app.pending')</option>
                        <option
                                @if($leave->status == 'rejected') selected @endif
                        value="rejected">@lang('app.reject')</option>
                    </select>
                </div>
            </div>

        </div>


    </div>
    {!! Form::close() !!}

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-success save-leave waves-effect waves-light">@lang('app.update')</button>
</div>

<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    jQuery('#single_date').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true
    });

    

    $('.save-leave').click(function () {
        $.easyAjax({
            url: '{{route('member.leaves-dashboard.update', $leave->id)}}',
            container: '#createLeave',
            type: "POST",
            redirect: true,
            data: $('#createLeave').serialize()
        })
    });
</script>