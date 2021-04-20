<div class="row user-timelogs m-t-20">
    <div class="col-md-12 bg-white">
        <table class="table" >
            <thead>
                <tr>
                    <th>@lang('app.task')</th>
                    <th>@lang('app.time')</th>
                    <th>@lang('modules.timeLogs.totalHours')</th>
                    <th>@lang('app.earnings')</th>
                    <th>@lang('app.action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($timelogs as $item)
                    <tr>
                        <td>
                            @if (!is_null($item->project_id) && !is_null($item->task_id))
                                <span class="font-semi-bold">
                                    {{ $item->task->heading }}
                                </span><br>
                                <span class="text-muted">
                                    {{ $item->project->project_name }}
                                </span>
                            @elseif (!is_null($item->project_id))
                                <span class="font-semi-bold">
                                    {{ $item->project->project_name }}
                                </span>
                            @elseif (!is_null($item->task_id))
                                <span class="font-semi-bold">
                                    {{ $item->task->heading }}
                                </span>
                            @endif


                        </td>
                        <td>
                            {{ $item->start_time->timezone($global->timezone)->format($global->date_format . ' ' . $global->time_format) }}<br>
                            {{ $item->end_time ? $item->end_time->timezone($global->timezone)->format($global->date_format . ' ' . $global->time_format) : __('app.working') }}
                        </td>
                        <td>
                            {{ intdiv($item->total_minutes, 60)." hrs" }}
                        </td>
                        <td>
                            {{  currency_position($item->earnings, $global->currency->currency_symbol)  }}
                            @if ($item->approved)
                                <i data-toggle="tooltip" data-original-title="{{ __('app.approved') }}" class="fa fa-check-circle text-success"></i>
                            @else
                                <i data-toggle="tooltip" data-original-title="{{ __('app.pending') }}" class="fa fa-check-circle text-muted" ></i>
                            @endif

                        </td>
                        <td>
                            <a href="javascript:;" class="edit-time-log text-info btn"
                                data-toggle="tooltip" data-time-id="{{ $item->id }}"  data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                            <a href="javascript:;" class="sa-params text-danger btn"
                                data-toggle="tooltip" data-time-id="{{ $item->id }}" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i> </a>

                            @if (!$item->approved)
                                <a href="javascript:;" class="approve-timelog text-success btn"
                                data-toggle="tooltip" data-time-id="{{ $item->id }}"  data-original-title="@lang('app.approve')"><i class="fa fa-check" aria-hidden="true"></i></a>
                            @endif
                        </td>
                    </tr>
                @empty

                @endforelse
            </tbody>
        </table>
    </div>
</div>
