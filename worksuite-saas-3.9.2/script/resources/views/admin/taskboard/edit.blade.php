<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.update') @lang('app.task') @lang('app.status') </h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'updateColumn','class'=>'ajax-form','method'=>'PUT']) !!}        <div class="form-body">
            <div class="row">
                <div class="col-xs-12">
                    <hr>
                    <div class="form-group">
                        <label class="control-label">@lang("modules.tasks.columnName")</label>
                        <input type="text" name="column_name" class="form-control" value="{{ $boardColumn->column_name }}">
                    </div>
                </div>
                <!--/span-->

                <div class="col-md-4 col-xs-12">
                    <div class="form-group">
                        <label>@lang("modules.tasks.labelColor")</label><br>
                        <input type="text" class="colorpicker form-control"  name="label_color" value="{{ $boardColumn->label_color }}" />
                    </div>
                </div>


                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label>@lang("modules.tasks.position")</label><br>
                        <select class="form-control" name="priority" id="priority">
                            @for($i=1; $i<= $maxPriority; $i++)
                                <option @if($i == $boardColumn->priority) selected @endif>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-actions">
            <button class="btn btn-success" id="update-form" type="button"><i class="fa fa-check"></i> @lang('app.save')</button>

            <button class="btn btn-danger" id="close-form" type="button"><i class="fa fa-times"></i> @lang('app.close')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>
<script>
//    $('#close-form').click(function () {
//        $('#edit-column-form').hide();
//    })

    $('#editLeadStatus').on('submit', function(e) {
        return false;
    })
    $(".colorpicker").asColorPicker();

    $('#update-form').click(function () {
        var url = '{{route('admin.taskboard.update',  [$boardColumn->id])}}';
        console.log(url);
        $.easyAjax({
            url: url,
            container: '#updateColumn',
            type: "POST",
            data: $('#updateColumn').serialize(),
            success: function (response) {
                window.location.reload();
            }

        });
    });
</script>