<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.designation')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'createDepartment','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>@lang('app.name')</label>
                        <input type="text" name="designation_name" id="designation_name" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-department" onclick="saveDesignation()" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>

    function saveDesignation() {
        var designationName = $('#designation_name').val();
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: '{{route('admin.designations.quick-store')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: { 'designation_name':designationName, '_token':token},
            success: function (response) {
                if(response.status == 'success'){
                    $('#designation').html(response.designationData);
                    $("#designation").select2();
                    $('#departmentModel').modal('hide');
                }
            }
        })
        return false;
    }
</script>