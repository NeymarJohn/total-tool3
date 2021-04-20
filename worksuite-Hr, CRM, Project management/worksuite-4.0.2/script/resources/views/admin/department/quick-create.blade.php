<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.department')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'createDepartment','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="required">@lang('app.name')</label>
                        <input type="text" name="department_name" id="department_name" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-department" onclick="saveDepartment(); return false;" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
    function saveDepartment() {
        var departmentName = $('#department_name').val();
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: '{{route('admin.department.quick-store')}}',
            container: '#createDepartment',
            type: "POST",
            data: { 'department_name':departmentName, '_token':token},
            success: function (response) {
                if(response.status == 'success'){
                    if ($('#department').length !== 0) {
                        $('#department').html(response.teamData);
                        $("#department").select2();
                        $('#departmentModel').modal('hide');                        
                    } else {
                        window.location.reload();
                    }
                }
            }
        })
        return false;
    }
</script>