<div class="white-box">
    <div class="table-responsive tableFixHead">
        <table class="table table-nowrap mb-0">
            <thead >
                <tr>
                    <th>@lang('app.employee')</th>
                    @for($i =1; $i <= $daysInMonth; $i++)
                        <th>{{ $i }}</th>
                    @endfor
                    <th>@lang('app.total')</th>
                </tr>
            </thead>
            <tbody>
            @foreach($employeeAttendence as $key => $attendance)
                @php
                    $totalPresent = 0;
                    $userId = explode("#", $key);
                    $userId = $userId[0];
                @endphp
                <tr>
                    {{-- <td> {{ substr($key, strripos($key,'#')+strlen('#')) }} </td> --}}
                    <td> {!! end($attendance) !!} </td>
                    @foreach($attendance as $key2=>$day)
                        @if ($key2+1 <= count($attendance))
                            <td>
                                @if($day == 'Absent')
                                    <a href="javascript:;" class="edit-attendance" data-user-id="{{ $userId }}" data-attendance-date="{{ $key2 }}"><i class="fa fa-times text-danger"></i></a>
                                @elseif($day == 'Holiday')
                                    <a href="javascript:;" title="@lang("app.menu.holiday")" class="edit-attendance" data-user-id="{{ $userId }}" data-attendance-date="{{ $key2 }}"><i class="fa fa-star text-warning"></i></a>

                                @else
                                    @if($day != '-')
                                        @php
                                            $totalPresent = $totalPresent + 1;
                                        @endphp
                                    @endif
                                    
                                    {!! $day !!}
                                @endif
                            </td>
                        @endif
                    @endforeach
                    <td class="text-success">{{ $totalPresent .' / '.(count($attendance)-1) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>