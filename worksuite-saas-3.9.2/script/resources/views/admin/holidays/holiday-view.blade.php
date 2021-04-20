<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="vtabs">
        <ul class="nav tabs-vertical">
            @foreach($months as $month)
                <li class="tab nav-item @if($month == $currentMonth) active @endif">
                    <a data-toggle="tab" href="#{{ $month }}" class="nav-link " aria-expanded="@if($month == $currentMonth) true @else false @endif ">
                        <i class="fa fa-calendar"></i> @lang('app.'.strtolower($month))</a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content p-0" >
            @foreach($months as $month)
                <div id="{{$month}}" class="tab-pane @if($month == $currentMonth) active @endif">
                    <div class="panel panel-info block4">
                        <div class="panel-heading">
                            <div class="caption">
                                <i class="fa fa-calendar"> </i> @lang('app.'.strtolower($month))
                            </div>

                        </div>
                        <div class="portlet-body">
                            <div class="table-scrollable">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th> # </th>
                                        <th> @lang('app.date') </th>
                                        <th> @lang('modules.holiday.occasion') </th>
                                        <th> @lang('app.day') </th>
                                        <th> @lang('app.action') </th>
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
                                                    <button type="button" onclick="del('{{ $holidaysArray[$month]['id'][$i] }}',' {{ $holidaysArray[$month]['date'][$i] }}')" href="#" class="btn btn-xs btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endfor
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