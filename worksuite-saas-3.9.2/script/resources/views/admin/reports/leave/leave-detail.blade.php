<style>
    .border-right-none{
        border-right:none !important;
    }
    .border-left-none{
        border-left:none !important;
    }
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title" id="myLargeModalLabel">@if($modalHeader == 'approved') @lang('app.approved') @elseif($modalHeader == 'pending') @lang('app.pending') @else @lang('app.upcoming') @endif @lang('app.menu.leaves') @lang('app.details')</h4>
</div>
<div class="modal-body">
    <div class="row dashboard-stats">
        <div class="col-md-12 m-b-20">
            <div class="white-box">
                @foreach($leave_types as $leave_type)
                    <div class="col-md-4 text-center">
                        <h4><span class="text-{{ $leave_type->color }}">{{ $leave_type->leaves->count() }}</span> <span class="font-12 text-muted m-l-5"> {{ $leave_type->type_name }}</span></h4>
                    </div>
                @endforeach
            </div>
        </div>

    </div>


    <div class="row">
        <div class="table-responsive">
            <table class="table" id="leave-detail-table" style="">
                <thead>
                <tr>
                    <th>@lang('modules.leaves.leaveType')</th>
                    <th>@lang('app.date')</th>
                    <th>@lang('modules.leaves.reason')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($leaves as $key=>$leave)
                    <tr>
                        <td>
                            <label>{{ ucwords(str_replace('_', '-', $leave->type_name)) }}</label>
                            {!! ($leave->duration == 'half day') ? '<label class="label label-inverse">'.__('modules.leaves.halfDay').'</label>' : "" !!}
                        </td>
                        <td>
                            {{ $leave->leave_date->format($global->date_format) }}
                        </td>
                        <td>
                            {{ $leave->reason }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noRecordFound')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
</div>