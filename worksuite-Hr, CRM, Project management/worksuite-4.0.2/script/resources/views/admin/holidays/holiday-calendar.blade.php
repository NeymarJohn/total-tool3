@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <a onclick="showAdd()" class="btn btn-outline btn-success btn-sm m-l-5">@lang('modules.holiday.addNewHoliday') <i class="fa fa-plus" aria-hidden="true"></i></a>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.holidays.index') }}">@lang('app.menu.holiday')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('css/full-calendar/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">

@endpush

@section('content')

    @section('other-section')
    <div class="row">
        <div class="col-md-12 show" id="new-follow-panel" style="">
            <h4 id="currentMonthName"></h4>
            
            <table class="table table-hover">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>@lang('app.date')</th>
                    <th>@lang('modules.holiday.occasion')</th>
                </tr>
                </thead>
                <tbody id="monthDetailData">

                </tbody>
            </table>
        </div>
    </div>
    @endsection

    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group col-md-2 pull-right">
                            <label class="control-label">@lang('app.select') @lang('app.year')</label>
                            <select onchange="getYearData()" class="select2 form-control" data-placeholder="@lang('app.menu.projects') @lang('app.status')" id="year">
                                @forelse($years as $yr)
                                    <option @if($yr == $year) selected @endif value="{{ $yr }}">{{ $yr }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>


                <div id="calendar"></div>
            </div>
        </div>

    </div>
    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="eventDetailModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')

<script>

    var taskEvents = [
        @foreach($holidays as $holiday)
        {
            id: '{{ ucfirst($holiday->id) }}',
            title: '{{ ucfirst($holiday->occassion) }}',
            start: '{{ $holiday->date->format("Y-m-d") }}',
            className:function(){
                var occassion = '{{ $holiday->occassion }}';
                if(occassion == 'Sunday' || occassion == 'Saturday'){
                    return 'bg-info';
                }else{
                    return 'bg-danger';
                }
            }
        },
        @endforeach
    ];

    var getEventDetail = function (id) {
        var url = '{{ route('admin.holidays.show', ':id')}}';
        url = url.replace(':id', id);

        $('#modelHeading').html('Event');
        $.ajaxModal('#eventDetailModal', url);
    }
    var calendarLocale = '{{ $global->locale }}';
    var date = new Date();
    var y = date.getFullYear();
    var d = date.getDate();
    var m = date.getMonth();

    var year = "{{ $year }}";

     year =  parseInt(year, 10);
    var defaultDate;

    if(y != year){
         defaultDate = new Date(year, m, d);
         console.log(defaultDate, 'hello');
    }
    else{
         defaultDate = new Date(y, m, d);
    }


</script>

<script src="{{ asset('plugins/bower_components/calendar/jquery-ui.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('js/full-calendar/main.min.js') }}"></script>
<script src="{{ asset('js/full-calendar/locales-all.min.js') }}"></script>

<script>
    var initialLocaleCode = '{{ $global->locale }}';
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
  
      var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: initialLocaleCode,
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        // initialDate: '2020-09-12',
        navLinks: true, // can click day/week names to navigate views
        selectable: false,
        selectMirror: true,
        select: function(arg) {
          var title = prompt('Event Title:');
          if (title) {
            calendar.addEvent({
              title: title,
              start: arg.start,
              end: arg.end,
              allDay: arg.allDay
            })
          }
          calendar.unselect()
        },
        eventClick: function(arg) {
            getEventDetail(arg.event.id);
        },
        editable: false,
        dayMaxEvents: true, // allow "more" link when too many events
        events: taskEvents,
        customButtons: {
            prev: {
                text: 'Prev',
                click: function() {
                    calendar.prev();
                    setMonthData(calendar.getDate());
                }
            },
        next: {
                text: 'Next',
                click: function() {
                    calendar.next();
                    setMonthData(calendar.getDate());
                }
            },
        }
      });
  
      calendar.render();
    });
  
</script>
<script>

    // Show Create Holiday Modal
    function showAdd() {
        var url = "{{ route('admin.holidays.create') }}";
        $.ajaxModal('#eventDetailModal', url);
    }

    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    var currentMonth = new Date();
    $('#currentMonthName').html(monthNames[currentMonth.getMonth()]);
    var currentMonthData = '';

    function setMonthData(d){

        var month_int = d.getMonth();
        var year_int = d.getFullYear();
        var firstDay = new Date(year_int, month_int, 1);

        firstDay = moment(firstDay).format("YYYY-MM-DD");

        $('#currentMonthName').html(monthNames[d.getMonth()]);
        var year = "{{ $year }}";
        var url = "{{ route('admin.holidays.calendar-month') }}?startDate="+encodeURIComponent(firstDay)+"&year="+year;

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'GET',
            url: url,
            success: function (response) {
                $('#monthDetailData').html(response.data);
            }
        });
    }

    function getYearData(){
        var year = $('#year').val();
        var url = "{{ route('admin.holidays.calendar', ':year') }}";
        url = url.replace(':year', year);
        window.location.href = url;
    }
    setMonthData(currentMonth);
</script>


@endpush
