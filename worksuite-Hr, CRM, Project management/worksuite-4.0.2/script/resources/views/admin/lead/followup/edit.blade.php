<link rel="stylesheet" href="{{ asset('plugins/datetime-picker/datetimepicker.css') }}">

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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">@lang('app.next_follow_up')</label>
                            <input type="text" autocomplete="off" name="next_follow_up_date" id="next_follow_up_date2" class="form-control" value="{{ $follow->next_follow_up_date->format('d/m/Y H:i a') }}">
                            <input type="hidden"  name="type" class="form-control" value="datetime">

                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-12">
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
                <button type="button" id="delete-follow" data-follow-id="{{ $follow->id }}" class="btn btn-danger"><i class="fa fa-times"></i> @lang('app.delete')</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/datetime-picker/datetimepicker.js') }}"></script>
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

    $('body').on('click', '#delete-follow', function () {
        var id = $(this).data('follow-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.deleteFollowup')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.leads.follow-up-delete',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token},
                    success: function (response) {
                        if (response.status == "success") {
                            // $.easyBlockUI('#leads-table');
                            // window.LaravelDataTables["leads-table"].draw();
                            window.location.reload();
                            // $.easyUnblockUI('#leads-table');
                        }
                    }
                });
            }
        });
    });

    jQuery('#next_follow_up_date2').datetimepicker({
        format: 'DD/MM/Y HH:mm',
    });
</script>
