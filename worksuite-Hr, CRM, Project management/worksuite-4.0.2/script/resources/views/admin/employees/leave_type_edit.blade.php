<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.leaves.leaveType')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table" id="leave-type-table">
                <thead>
                <tr>
                    <th>@lang('modules.leaves.leaveType')</th>
                    <th>@lang('modules.leaves.noOfLeaves')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($employeeLeavesQuota as $key=>$leaveType)
                    <tr id="type-{{ $leaveType->id }}">
                        <td>
                            <label class="label label-{{ $leaveType->leaveType->color }}">{{ ucwords($leaveType->leaveType->type_name) }}</label>
                        </td>
                        <td>
                            <input type="number" min="0" value="{{ $leaveType->no_of_leaves }}"
                                    class="form-control leave-count-{{ $leaveType->id }}">
                        </td>
                        <td>
                            <button type="button" data-type-id="{{ $leaveType->id }}"
                                class="btn btn-sm btn-success btn-outline update-category">
                                <i class="fa fa-check"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noLeaveTypeAdded')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

      
    </div>
</div>

<script>

$('.update-category').click(function () {
    var id = $(this).data('type-id');
    var leaves = $('.leave-count-'+id).val();
    var url = "{{ route('admin.employees.leaveTypeUpdate',':id') }}";
    url = url.replace(':id', id);

    var token = "{{ csrf_token() }}";

    $.easyAjax({
        type: 'POST',
        url: url,
        data: {'_token': token, 'leaves': leaves},
        success: function (response) {
            if (response.status == "success") {
                window.location.reload();   
            }
        }
    });
});
</script>