
@foreach ($dateWiseData as $key => $dateData)
    @php
        $currentDate = \Carbon\Carbon::parse($key);
    @endphp
    @if($dateData['attendance'])

        <tr>
            <td>
                {{ $currentDate->format($global->date_format) }}
                <br>
                <label class="label label-success">{{ $currentDate->format('l') }}</label>
            </td>
            <td><label class="label label-success">@lang('modules.attendance.present')</label></td>
            <td colspan="3">
                <table width="100%" >
                    @foreach($dateData['attendance'] as $attendance)
                        <tr>
                            <td width="25%" class="al-center bt-border">
                                {{ $attendance->clock_in_time->timezone($global->timezone)->format($global->time_format) }}
                            </td>
                            <td width="25%" class="al-center bt-border text-center">
                                @if(!is_null($attendance->clock_out_time)) {{ $attendance->clock_out_time->timezone($global->timezone)->format($global->time_format) }} @else - @endif
                            </td>
                            <td class="bt-border text-center" style="padding-bottom: 5px;">
                                <button type="button"  class="btn btn-info btn-xs btn-rounded view-attendance" data-attendance-id="{{$attendance->aId}}">
                                    <i class="fa fa-search"></i> @lang('app.details')
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </td>

        </tr>
    @else
        <tr>
            <td>
                {{ $currentDate->format($global->date_format) }}
                <br>
                <label class="label label-success">{{ $currentDate->format('l') }}</label>
            </td>
            <td>
                @if(!$dateData['holiday'] && !$dateData['leave'])
                    <label class="label label-danger">@lang('modules.attendance.absent')</label>
                @elseif($dateData['leave'])
                    @if ($dateData['leave']['duration'] == "half day")
                    <label class="label label-primary">@lang('modules.attendance.leave')</label><br><br>
                    <label class="label label-warning">@lang('modules.attendance.halfDay')</label>
                    @else
                        <label class="label label-primary">@lang('modules.attendance.leave')</label>
                    @endif
                @else
                    <label class="label label-megna">@lang('modules.attendance.holiday')</label>
                @endif
            </td>
            <td colspan="3">
                <table width="100%">
                    <tr>
                        <td width="25%" class="al-center">-</td>
                        <td width="25%" class="al-center text-center">-</td>
                        <td class="text-center" style="padding-bottom: 5px;">
                            @if($dateData['holiday']  && !$dateData['leave'])
                                @lang('modules.attendance.holidayfor') {{ ucwords($dateData['holiday']->occassion) }}
                            @elseif($dateData['leave'])
                                @lang('modules.attendance.leaveFor') {{ ucwords($dateData['leave']['reason']) }}
                            @else
                                -
                            @endif

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif

@endforeach

