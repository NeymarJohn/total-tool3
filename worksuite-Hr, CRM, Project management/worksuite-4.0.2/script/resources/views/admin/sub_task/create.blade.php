<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.tasks.subTask')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'createSubTask','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('app.name')</label>
                        <input type="text" name="name" id="name" class="form-control">
                        <input type="hidden" name="taskID" id="taskID" value="{{ $taskID }}">
                    </div>
                </div>
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('app.dueDate')</label>
                        <input type="text" name="due_date" autocomplete="off" id="due_date3" class="form-control datepicker">
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
<script>
    jQuery('#due_date3').datepicker({
        autoclose: true,
        todayHighlight: true
    });

</script>
