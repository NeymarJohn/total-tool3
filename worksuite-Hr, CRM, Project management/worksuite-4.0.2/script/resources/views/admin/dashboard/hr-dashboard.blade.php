<link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}">

<div class="row dashboard-stats front-dashboard">
    @if(in_array('leaves',$modules) && in_array('total_leaves_approved',$activeWidgets))
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.leaves.index') }}">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div><span class="bg-success-gradient"><i class="fa fa-sign-out"></i></span></div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.totalLeavesApproved')</span><br>
                            <span class="counter">{{ $totalLeavesApproved }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
    @if(in_array('employees',$modules) && in_array('total_new_employee',$activeWidgets))
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.employees.index') }}">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div><span class="bg-warning-gradient"><i class="icon-user"></i></span></div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.totalNewEmployee')</span><br>
                            <span class="counter">{{ $totalNewEmployee }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
    @if(in_array('employees',$modules) && in_array('total_employee_exits',$activeWidgets))
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.employees.index') }}">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div><span class="bg-danger-gradient"><i class="icon-user"></i></span></div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.totalEmployeeExits')</span><br>
                            <span class="counter">{{ $totalEmployeeExits }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
    @if(in_array('attendance',$modules) && in_array('average_attendance',$activeWidgets))
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.attendances.index') }}">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div><span class="bg-inverse-gradient"><i class="icon-user"></i></span></div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.averageAttendance')</span><br>
                            <span class="counter">{{ $averageAttendance }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
</div>
<div class="row">
    @if(in_array('employees',$modules) && in_array('department_wise_employee',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.departmentWiseEmployee')
                    <a href="javascript:;" data-chart-id="department_wise_employees" class="text-dark pull-right download-chart">
                        <i class="fa fa-download"></i> @lang('app.download')
                    </a>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
                                @if(!empty(json_decode($departmentWiseEmployee)))
                                    <div>
                                        <canvas id="department_wise_employees"></canvas>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px"><i class="icon-user"></i></div>
                                                <div class="title m-b-15">@lang('messages.noDepartmentWiseEmployee')</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    @endif
    @if(in_array('employees',$modules) && in_array('designation_wise_employee',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.designationWiseEmployee')
                    <a href="javascript:;" data-chart-id="designation_wise_employees" class="text-dark pull-right download-chart">
                        <i class="fa fa-download"></i> @lang('app.download')
                    </a>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
           
                                @if(!empty(json_decode($designationWiseEmployee)))
                                    <div>
                                        <canvas id="designation_wise_employees"></canvas>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px"><i class="icon-user"></i></div>
                                                <div class="title m-b-15">@lang('messages.noDesignationWiseEmployee')</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    @endif

    @if(in_array('employees',$modules) && in_array('gender_wise_employee',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.genderWiseEmployee')
                    <a href="javascript:;" data-chart-id="gender_wise_employees" class="text-dark pull-right download-chart">
                        <i class="fa fa-download"></i> @lang('app.download')
                    </a>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
                                @if(!empty(json_decode($genderWiseEmployee)))
                                    <div>
                                        <canvas id="gender_wise_employees" ></canvas>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px"><i class="icon-user"></i></div>
                                                <div class="title m-b-15">@lang('messages.noGenderWiseEmployee')</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    @endif
    @if(in_array('employees',$modules) && in_array('role_wise_employee',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.roleWiseEmployee')
                    <a href="javascript:;" data-chart-id="role_wise_employees" class="text-dark pull-right download-chart">
                        <i class="fa fa-download"></i> @lang('app.download')
                    </a>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
                                @if(!empty(json_decode($roleWiseEmployee)))
                                    <div>
                                        <canvas id="role_wise_employees"></canvas>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px"><i class="icon-user"></i></div>
                                                <div class="title m-b-15">@lang('messages.noRoleWiseEmployee')</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    @endif

    @if(in_array('leaves',$modules) && in_array('leaves_taken',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.leavesTaken')</div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('app.employee')</th>
                                    <th>@lang('modules.dashboard.leavesTaken')</th>   
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leavesTakens as $leavesTaken)
                                @php
                                    $image = ($leavesTaken->image) ? '<img src="' . asset_url('avatar/' . $leavesTaken->image) . '"
                                                            alt="user" class="img-circle" width="30" height="30"> ' : '<img src="' . asset('img/default-profile-3.png') . '"
                                                            alt="user" class="img-circle" width="30" height="30"> ';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="row truncate"><div class="col-sm-3 col-xs-4">{!! $image !!}</div><div class="col-sm-9 col-xs-8"><a href="{{ route('admin.employees.show', $leavesTaken->id) }}">{{ ucwords($leavesTaken->name) }}</a></div></div>
                                        
                                    </td>
                                    <td>
                                        <label class="label label-success">{{ $leavesTaken->employeeLeaveCount }}</label>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2">
                                        @lang("messages.noEmployeeTakeLeaves")
                                    </td>
                                    
                                </tr>
                               
                            @endforelse
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(in_array('attendance',$modules) && in_array('late_attendance_mark',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.lateAttendanceMark')</div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('app.employee')</th>
                                    <th>@lang('modules.dashboard.lateMark')</th>   
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lateAttendanceMarks as $lateAttendanceMark)
                                @php
                                    $image = ($lateAttendanceMark->image) ? '<img src="' . asset_url('avatar/' . $lateAttendanceMark->image) . '"
                                                            alt="user" class="img-circle" width="30" height="30"> ' : '<img src="' . asset('img/default-profile-3.png') . '"
                                                            alt="user" class="img-circle" width="30" height="30"> ';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="row truncate"><div class="col-sm-3 col-xs-4">{!! $image !!}</div><div class="col-sm-9 col-xs-8"><a href="{{ route('admin.employees.show', $lateAttendanceMark->id) }}">{{ ucwords($lateAttendanceMark->name) }}</a></div></div>
                                        
                                    </td>
                                    <td>
                                        <label class="label label-success">{{ $lateAttendanceMark->employeeLateCount }}</label>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2">
                                        @lang("messages.noLateAttendanceMark")
                                    </td>
                                    
                                </tr>
                               
                            @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="{{ asset('js/Chart.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>
<script src="{{ asset('plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
<script src="{{ asset('plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/jquery-ui.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/jquery.fullcalendar.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/locale-all.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script>
    $(document).ready(function () {
        @if(!empty(json_decode($departmentWiseEmployee)))
            function departmentWiseEmployeePieChart(departmentWiseEmployee) {
                var ctx2 = document.getElementById("department_wise_employees");
                var data = new Array();
                var color = new Array();
                var labels = new Array();
                var total = 0;

                $.each(departmentWiseEmployee, function(key,val){
                    labels.push(val.team_name);
                    data.push(parseInt(val.totalEmployee));
                    total = total+parseInt(val.totalEmployee);
                    color.push(getRandomColor());
                });

                // labels.push('Total '+total);
                var chart = new Chart(ctx2,{
                    "type":"doughnut",
                    "data":{
                        "labels":labels,
                        "datasets":[{
                            "data":data,
                            "backgroundColor":color
                        }]
                    }
                });
                chart.canvas.parentNode.style.height = '470px';
            }
            departmentWiseEmployeePieChart(jQuery.parseJSON('{!! $departmentWiseEmployee !!}'));

        @endif
        @if(!empty(json_decode($designationWiseEmployee)))
            function designationWiseEmployeePieChart(designationWiseEmployee) {
                var ctx2 = document.getElementById("designation_wise_employees");
                var data = new Array();
                var color = new Array();
                var labels = new Array();
                var total = 0;

                $.each(designationWiseEmployee, function(key,val){
                    labels.push(val.name);
                    data.push(parseInt(val.totalEmployee));
                    total = total+parseInt(val.totalEmployee);
                    color.push(getRandomColor());
                });

                // labels.push('Total '+total);
                var chart = new Chart(ctx2,{
                    "type":"doughnut",
                    "data":{
                        "labels":labels,
                        "datasets":[{
                            "data":data,
                            "backgroundColor":color
                        }]
                    }
                });
                chart.canvas.parentNode.style.height = '470px';
            }
            designationWiseEmployeePieChart(jQuery.parseJSON('{!! $designationWiseEmployee !!}'));

        @endif

        @if(!empty(json_decode($genderWiseEmployee)))
            function genderWiseEmployeePieChart(genderWiseEmployee) {
                var ctx2 = document.getElementById("gender_wise_employees");
                var data = new Array();
                var color = new Array();
                var labels = new Array();
                var total = 0;

                $.each(genderWiseEmployee, function(key,val){
                    labels.push(val.gender.toUpperCase());
                    data.push(parseInt(val.totalEmployee));
                    total = total+parseInt(val.totalEmployee);
                    color.push(getRandomColor());
                });

                // labels.push('Total '+total);
                var chart = new Chart(ctx2,{
                    "type":"doughnut",
                    "data":{
                        "labels":labels,
                        "datasets":[{
                            "data":data,
                            "backgroundColor":color
                        }]
                    }
                });
                chart.canvas.parentNode.style.height = '470px';
            }
            genderWiseEmployeePieChart(jQuery.parseJSON('{!! $genderWiseEmployee !!}'));

        @endif
        @if(!empty(json_decode($roleWiseEmployee)))
            function roleWiseEmployeePieChart(roleWiseEmployee) {
                var ctx2 = document.getElementById("role_wise_employees");
                var data = new Array();
                var color = new Array();
                var labels = new Array();
                var total = 0;

                $.each(roleWiseEmployee, function(key,val){
                    labels.push(val.name.toUpperCase());
                    data.push(parseInt(val.totalEmployee));
                    total = total+parseInt(val.totalEmployee);
                    color.push(getRandomColor());
                });

                // labels.push('Total '+total);
                var chart = new Chart(ctx2,{
                    "type":"doughnut",
                    "data":{
                        "labels":labels,
                        "datasets":[{
                            "data":data,
                            "backgroundColor":color
                        }]
                    }
                });
                chart.canvas.parentNode.style.height = '470px';
            }
            roleWiseEmployeePieChart(jQuery.parseJSON('{!! $roleWiseEmployee !!}'));

        @endif

        function getRandomColor() {
            var letters = '0123456789ABCDEF'.split('');
            var color = '#';
            for (var i = 0; i < 6; i++ ) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        $('.download-chart').click(function() {
            var id = $(this).data('chart-id');
            this.href = $('#'+id)[0].toDataURL();// Change here
            this.download = id+'.png';
        });

    });

    
    
</script>