<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.client.createTitle')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'ajxCreateEmployee','class'=>'ajax-form','method'=>'POST']) !!}
        <input type="hidden" name="ajax_create" value="1">
        <div class="form-body">
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <div class="form-group">
                        <label class="required">@lang('modules.client.clientName')</label>
                        <input type="text" name="name" id="name"  value="{{ $leadDetail->client_name ?? '' }}"   class="form-control">
                    </div>
                </div>

                <div class="col-md-4 col-xs-12">
                    <div class="form-group">
                        <label class="required">@lang('modules.client.clientEmail')</label>
                        <input style="opacity: 0;position: absolute;">
                        <input type="email" name="email" id="email"  class="form-control auto-complete-off">
                        <span class="help-block">@lang('modules.client.emailNote')</span>
                    </div>
                </div>
                <!--/span-->


                <div class="col-md-4 col-xs-12">
                    <div class="form-group">
                        <label class="required">@lang('modules.client.password')</label>
                        <input type="password" style="opacity: 0;position: absolute;">
                        <input type="password" name="password" id="password" autocomplete="off" class="form-control">
                        <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        <span class="help-block"> @lang('modules.client.passwordNote')</span>
                    </div>
                    <div class="form-group">
                        <div class="checkbox checkbox-info">
                            <input id="random_password_ajax" name="random_password" value="true"
                                    type="checkbox">
                            <label for="random_password_ajax" class="text-info">@lang('modules.client.generateRandomPassword')</label>
                        </div>
                    </div>
                </div>
                <!--/span-->

                
                <!--/span-->
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

    $('#ajax-save-employee').click(function () {
        $.easyAjax({
            url: '{{route('admin.clients.store')}}',
            container: '#ajxCreateEmployee',
            type: "POST",
            data: $('#ajxCreateEmployee').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    if ($('#client_id').length !== 0) {
                        $('#client_id').html(response.teamData);
                        $('#client_id').select2();
                        $('#projectTimerModal').modal('hide');                        
                    } else {
                        window.location.reload();
                    }
                }
            }
        })
    });


</script>