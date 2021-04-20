<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.update') @lang('modules.lead.leadStatus')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'editLeadStatus','class'=>'ajax-form','method'=>'PUT']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>@lang('modules.lead.leadStatus')</label>
                        <input type="text" name="type" id="type" value="{{ $status->type }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required">@lang("modules.tasks.labelColor")</label><br>
                        <input type="text" class="colorpicker form-control"  name="label_color" value="{{ $status->label_color }}" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang("modules.tasks.position")</label><br>
                        <select class="form-control" name="priority" id="priority">
                            @for($i=1; $i<= $maxPriority; $i++)
                                <option @if($i == $status->priority) selected @endif>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="form-actions">
            <button type="button" id="save-group" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>
<script>

    $('#editLeadStatus').on('submit', function(e) {
        return false;
    })
    $(".colorpicker").asColorPicker();

    $('#save-group').click(function () {
        $.easyAjax({
            url: '{{route('admin.lead-status-settings.update', $status->id)}}',
            container: '#editLeadStatus',
            type: "PUT",
            data: $('#editLeadStatus').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>