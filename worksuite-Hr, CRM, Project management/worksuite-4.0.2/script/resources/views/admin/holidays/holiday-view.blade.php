<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="vtabs b-all">
        <ul class="nav tabs-vertical">
            @foreach($months as $month)
                <li class="tab nav-item @if($month == $currentMonth) active @endif">
                    <a data-toggle="tab" href="#{{ $month }}" class="nav-link " aria-expanded="@if($month == $currentMonth) true @else false @endif ">
                        <i class="fa fa-calendar"></i> @lang('app.months.'.$month)</a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content p-0">
            @foreach($months as $month)
                <div id="{{$month}}" class="tab-pane @if($month == $currentMonth) active @endif">
                    <div class="panel block4">
                        <div class="panel-heading p-l-5">
                            <div class="caption m-l-10">
                                <i class="fa fa-calendar"> </i> @lang('app.months.'.$month)
                            </div>

                        </div>
                        <div class="portlet-body">
                            <div class="table-scrollable">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th> # </th>
                                        <th> @lang('modules.holiday.date') </th>
                                        <th> @lang('modules.holiday.occasion') </th>
                                        <th> @lang('modules.holiday.day') </th>
                                        <th> @lang('modules.holiday.action') </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($holidaysArray[$month]))

                                        @for($i=0;$i<count($holidaysArray[$month]['date']);$i++)

                                            <tr id="row{{ $holidaysArray[$month]['id'][$i] }}">
                                                <td> {{($i+1)}} </td>
                                                <td> {{ $holidaysArray[$month]['date'][$i] }} </td>
                                                <td> {{ $holidaysArray[$month]['ocassion'][$i] }} </td>
                                                <td> {{ $holidaysArray[$month]['day'][$i] }} </td>
                                                <td>
                                                    <button type="button" onclick="del('{{ $holidaysArray[$month]['id'][$i] }}',' {{ $holidaysArray[$month]['date'][$i] }}')" href="#" class="btn btn-danger btn-circle">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endfor
                                    @else
                                        <td colspan="5" class="text-center">
                                            <div class="empty-space" style="height: 200px;">
                                                <div class="empty-space-inner">
                                                    <div class="icon" style="font-size:30px"><i
                                                                class="ti-calendar"></i>
                                                    </div>
                                                    <div class="title m-b-15">@lang('messages.noHolidayFound')
                                                    </div>
                                                    <div class="subtitle">
                                                        <a onclick="showAdd()"
                                                           class="btn btn-outline btn-success btn-sm">@lang('modules.holiday.addNewHoliday')
                                                            <i class="fa fa-plus" aria-hidden="true"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
