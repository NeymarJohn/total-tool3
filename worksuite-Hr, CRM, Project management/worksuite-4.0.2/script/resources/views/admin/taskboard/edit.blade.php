<div class="col-md-12">
    <hr>
    <div class="form-group">
        <label class="control-label">@lang("modules.tasks.columnName")</label>
        <input type="text" name="column_name" class="form-control" value="{{ $boardColumn->column_name }}">
    </div>
</div>
<!--/span-->

<div class="col-md-4">
    <div class="form-group">
        <label>@lang("modules.tasks.labelColor")</label><br>
        <input type="text" class="colorpicker form-control"  name="label_color" value="{{ $boardColumn->label_color }}" />
    </div>
</div>


<div class="col-md-3">
    <div class="form-group">
        <label>@lang("modules.tasks.position")</label><br>
        <select class="form-control" name="priority" id="priority">
            @for($i=1; $i<= $maxPriority; $i++)
                <option @if($i == $boardColumn->priority) selected @endif>{{ $i }}</option>
            @endfor
        </select>
    </div>
</div>



<div class="col-md-12">
    <div class="form-group">
        <button class="btn btn-success" id="update-form" data-column-id="{{  $boardColumn->id }}" type="submit"><i class="fa fa-check"></i> @lang('app.save')</button>

        <button class="btn btn-danger" id="close-form" type="button"><i class="fa fa-times"></i> @lang('app.close')</button>
    </div>
</div>
<!--/span-->

<script>
    $('#close-form').click(function () {
        $('#edit-column-form').hide();
    })
</script>