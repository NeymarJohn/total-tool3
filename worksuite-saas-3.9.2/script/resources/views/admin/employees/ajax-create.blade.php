<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.employees.createTitle')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'ajxCreateEmployee','class'=>'ajax-form','method'=>'POST']) !!}
        <input type="hidden" name="ajax_create" value="1">
        <div class="form-body">
            <div class="row">
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label class="required">@lang('modules.employees.employeeId')</label>
                        <a class="mytooltip" href="javascript:void(0)">
                            <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span
                                            class="tooltip-inner2">@lang('modules.employees.employeeIdInfo')</span></span></span></a>
                        <input type="text" name="employee_id" id="employee_id" class="form-control" autocomplete="nope">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required">@lang('modules.employees.employeeName')</label>
                        <input type="text" name="name" id="name" class="form-control"
                               autocomplete="nope">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required">@lang('modules.employees.employeeEmail')</label>
                        <input type="email" name="email" id="email" class="form-control"
                               autocomplete="nope">
                        <span class="help-block">@lang('modules.employees.emailNote')</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required">@lang('modules.employees.employeePassword')</label>
                        <input type="password" style="display: none">
                        <input type="password" name="password" id="password" readonly="readonly" onfocus="this.removeAttribute('readonly');" class="form-control auto-complete-off"
                               autocomplete="nope">
                        <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        <span class="help-block"> @lang('modules.employees.passwordNote') </span>
                        <div class="checkbox checkbox-info">
                            <input id="random_password_ajax" name="random_password" value="true" type="checkbox">
                            <label for="random_password_ajax">@lang('modules.client.generateRandomPassword')</label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label class="required">@lang('app.designation')</label>
                        <select name="designation" id="designation_ajax" class="form-control select2">
                            <option value="">--</option>
                            @forelse($designations as $designation)
                                <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                            @empty
                                <option value="">@lang('messages.noRecordFound')</option>
                            @endforelse()
                        </select>
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label class="required">@lang('app.department')</label>
                        <select name="department" id="department_ajax" class="form-control select2">
                            <option value="">--</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required">@lang('modules.employees.joiningDate')</label>
                        <input type="text" autocomplete="off" name="joining_date" id="joining_date_ajax"
                               class="form-control" value="">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('modules.employees.gender')</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="male">@lang('app.male')</option>
                            <option value="female">@lang('app.female')</option>
                            <option value="others">@lang('app.others')</option>
                        </select>
                    </div>
                </div>

            </div>

        </div>
        <div class="form-actions">
            <button type="button" id="ajax-save-employee" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script>
    $('#random_password_ajax').change(function () {
        var randPassword = $(this).is(":checked");

        if (randPassword) {
            $('#password').val('{{ str_random(8) }}');
            $('#password').attr('readonly', 'readonly');
        } else {
            $('#password').val('');
            $('#password').removeAttr('readonly');
        }
    });

    $("#joining_date_ajax").datepicker({
        format: '{{ $global->date_picker_format }}',
        todayHighlight: true,
        autoclose: true
    });
    $("#joining_date_ajax").datepicker("setDate", new Date());
    
    $("#department_ajax").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    $("#designation_ajax").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('#ajax-save-employee').click(function () {
        $.easyAjax({
            url: '{{route('admin.employees.store')}}',
            container: '#ajxCreateEmployee',
            type: "POST",
            data: $('#ajxCreateEmployee').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    if ($('#selectEmployee').length !== 0) {
                        $('#selectEmployee').html(response.teamData);
                        $('#selectEmployee').select2();
                        $('#projectTimerModal').modal('hide');                        
                    } else {
                        window.location.reload();
                    }
                }
            }
        })
    });


</script>