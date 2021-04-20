<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.tasks.subTask')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'updateSubTask','class'=>'ajax-form','method'=>'PUT']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('app.name')</label>
                        <input type="text" name="name" id="name" value="{{ $subTask->title }}" class="form-control">
                        <input type="hidden" name="taskID" id="taskID" value="{{ $subTask->project_template_task_id  }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" onclick="saveSubTask()" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script>

    function saveSubTask(){
        $('#nameBox').removeClass("has-error");
        $('#errorName').html('');
        $('.help-block').remove();

        var url = "{{ route('admin.project-template-sub-task.update',[$subTask->id]) }}";
            var id = {{  $subTask->id }}
        $.easyAjax({
            type: 'POST',
            url: url,
            container: '#updateSubTask',
            data: $('#updateSubTask').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    
                    $('#subTaskModal').modal('hide');
                    $('#taskData'+id).html($('#name').val())

                }
            }
        });
    }

</script>
