<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.pinnedItem')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('app.project')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($pinnedItems as $key=>$pinnedItem)
                    <tr id="project-{{ $pinnedItem->id }}">
                        <td>{{ $key+1 }}</td>
                        <td><a href="{{ route('member.projects.show', $pinnedItem->id) }}">{{ ucwords($pinnedItem->project_name) }}</a></td>
                        <td><a href="{{ route('member.projects.show', $pinnedItem->id) }}" class="btn btn-info btn-circle" data-toggle="tooltip" data-original-title="@lang('app.view')"><i class="fa fa-search" aria-hidden="true"></i> </a></td>
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