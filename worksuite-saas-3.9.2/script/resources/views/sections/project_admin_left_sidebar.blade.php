<!-- .User Profile -->
<div class="user-profile">
    <div class="dropdown user-pro-body">
        @if(is_null($user->image))
            <div><img src="{{ asset('img/default-profile-3.png') }}" alt="user-img" class="img-circle"></div>
        @else
            <div><img src="{{ asset_url('avatar/'.$user->image) }}" alt="user-img" class="img-circle"></div>
        @endif
        <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ ucwords($user->name) }} <span class="caret"></span></a>
        <ul class="dropdown-menu animated flipInY">
            <li><a href="{{ route('admin.settings.index') }}"><i class="ti-settings"></i> @lang('app.menu.accountSettings')</a></li>
            <li>
                <a href="{{ route('member.dashboard') }}">
                    <i class="fa fa-sign-in"></i> @lang('app.loginAsEmployee')
                </a>
            </li>
            <li role="separator" class="divider"></li>
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                ><i class="fa fa-power-off"></i> @lang('app.logout')</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </div>
</div>
<!-- .User Profile -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">

        <ul class="nav" id="side-menu">
            {{--<li class="sidebar-search hidden-sm hidden-md hidden-lg">--}}
                {{--<!-- / Search input-group this is only view in mobile-->--}}
                {{--<div class="input-group custom-search-form">--}}
                    {{--<input type="text" class="form-control" placeholder="Search...">--}}
                        {{--<span class="input-group-btn">--}}
                        {{--<button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>--}}
                        {{--</span>--}}
                {{--</div>--}}
                {{--<!-- /input-group -->--}}
            {{--</li>--}}
            <li><a href="{{ route('project-admin.dashboard') }}" class="waves-effect"><i class="icon-speedometer fa-fw"></i> <span class="hide-menu">@lang('app.menu.dashboard') </span></a> </li>
            <li><a href="{{ route('project-admin.projects.index') }}" class="waves-effect"><i class="icon-layers fa-fw"></i> <span class="hide-menu">@lang('app.menu.projects') </span></a> </li>
            <li><a href="{{ route('project-admin.task-calendar.index') }}" class="waves-effect"><i class="icon-calender fa-fw"></i> <span class="hide-menu">@lang('app.menu.taskCalendar') </span></a> </li>
            {{--<li><a href="{{ route('project-admin.all-issues.index') }}" class="waves-effect"><i class="ti-alert"></i> <span class="hide-menu">@lang('app.menu.issues') </span></a> </li>--}}
            <li><a href="{{ route('project-admin.all-time-logs.index') }}" class="waves-effect"><i class="icon-clock fa-fw"></i> <span class="hide-menu">@lang('app.menu.timeLogs') </span></a> </li>
            <li><a href="{{ route('project-admin.all-tasks.index') }}" class="waves-effect"><i class="ti-layout-list-thumb fa-fw"></i> <span class="hide-menu">@lang('app.menu.tasks')</span></a> </li>
            <li><a href="{{ route('project-admin.reports.index') }}" class="waves-effect"><i class="ti-pie-chart fa-fw"></i> <span class="hide-menu"> @lang('app.menu.reports') <span class="fa arrow"></span> </span></a>
                <ul class="nav nav-second-level">
                    <li><a href="{{ route('project-admin.task-report.index') }}">@lang('app.menu.taskReport')</a></li>
                    <li><a href="{{ route('project-admin.time-log-report.index') }}">@lang('app.menu.timeLogReport')</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
