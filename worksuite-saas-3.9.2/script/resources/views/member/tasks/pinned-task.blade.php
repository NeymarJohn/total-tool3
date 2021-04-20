<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.pinnedTask')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table" id="pinnedTaskTable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('app.task')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($pinnedItems as $key=>$pinnedItem)
                    <tr id="project-{{ $pinnedItem->id }}">
                        <td>{{ $key+1 }}</td>
                        <td><a href="javascript:;" data-task-id="{{ $pinnedItem->id }}" class="show-task-detail" >{{ ucwords($pinnedItem->heading) }}</a></td>
                        <td><a href="javascript:;" data-task-id="{{ $pinnedItem->id }}" class="show-task-detail btn btn-info btn-circle" data-toggle="tooltip" data-original-title="@lang('app.view')" ><i class="fa fa-search" aria-hidden="true"></i> </a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noPinnedItem')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $('#pinnedTaskTable').on('click', '.show-task-detail', function () {
        $('#editTimeLogModal').modal('hide');

        $(".right-sidebar").slideDown(50).addClass("shw-rside");

        var id = $(this).data('task-id');
        var url = "{{ route('member.all-tasks.show',':id') }}";
        url = url.replace(':id', id);

        $.easyAjax({
            type: 'GET',
            url: url,
            success: function (response) {
                if (response.status == "success") {
                    $('#right-sidebar-content').html(response.view);
                }
            }
        });
    })
</script>