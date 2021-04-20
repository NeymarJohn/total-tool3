<h4 class="dashboard-stats"><span class="text-info" id="totalDays">{{ $totalDays }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.attendance.totalWorkingDays')</span></h4>

<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
                <th>#</th>
                <th>@lang('app.employee')</th>
                <th>@lang('modules.attendance.present')</th>
                <th>@lang('modules.attendance.absent')</th>
                <th>@lang('modules.attendance.hoursClocked')</th>
                <th>@lang('app.days') @lang('modules.attendance.late')</th>
                <th>@lang('modules.attendance.halfDay')</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($summaryData as $key=>$item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ ucwords($item['name']) }}</td>
                    <td>{{ $item['present_days'] }}</td>
                    <td>{{ $item['absent_days'] }}</td>
                    <td>{{ $item['hours_clocked'] }}</td>
                    <td>{{ $item['late_day_count'] }}</td>
                    <td>{{ $item['half_day_count'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>