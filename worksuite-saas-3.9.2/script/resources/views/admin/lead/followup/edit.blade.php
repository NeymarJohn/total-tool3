<div class="panel panel-default">
    <div class="panel-heading "><i class="ti-pencil"></i> @lang('modules.followup.updateFollow')
        <div class="panel-action">
            <a href="javascript:;" class="close" id="hide-edit-follow-panel" data-dismiss="modal"><i class="ti-close"></i></a>
        </div>
    </div>
    <div class="panel-wrapper collapse in">
        <div class="panel-body">
            {!! Form::open(['id'=>'updateFollow','class'=>'ajax-form']) !!}
            {!! Form::hidden('lead_id', $follow->lead_id) !!}
            {!! Form::hidden('id', $follow->id) !!}

            <div class="form-body">
                <div class="row">
                    <!--/span-->
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label">@lang('app.next_follow_up')</label>
                            <input type="text" autocomplete="off" name="next_follow_up_date" id="next_follow_up_date2" class="form-control" value="{{ $follow->next_follow_up_date->format('d/m/Y H:i') }}">
                            <input type="hidden"  name="type" class="form-control datepicker" value="datetime">

                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label">@lang('app.remark')</label>
                            <textarea id="remark" name="remark" class="form-control">{{ $follow->remark }}</textarea>
                        </div>
                    </div>
                </div>
                <!--/row-->

            </div>
            <div class="form-actions">
                <button type="button" id="update-follow" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script>
    //    update task
    $('#update-follow').click(function () {
        $.easyAjax({
            url: '{{route('admin.leads.follow-up-update')}}',
            container: '#updateFollow',
            type: "POST",
            data: $('#updateFollow').serialize(),
            success: function (data) {
                $('#follow-list-panel .list-group').html(data.html);
            }
        })
    });

    jQuery('#next_follow_up_date2').datetimepicker({
        format: 'DD/MM/Y HH:mm',
    });
</script>
