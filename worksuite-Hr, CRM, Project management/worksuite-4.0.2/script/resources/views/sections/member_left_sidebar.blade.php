<div class="navbar-default sidebar" role="navigation">
    <div class="navbar-header">
        <!-- Toggle icon for mobile view -->
        <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse"
            data-target=".navbar-collapse"><i class="ti-menu"></i></a>

        <div class="top-left-part">
            <!-- Logo -->
            <a class="logo hidden-xs text-center" href="{{ route('admin.dashboard') }}">
                <span class="visible-md"><img src="{{ $global->logo_url }}" alt="home" class=" admin-logo"/></span>
                <span class="visible-sm"><img src="{{ $global->logo_url }}" alt="home" class=" admin-logo"/></span>
            </a>

        </div>
        <!-- /Logo -->

        <!-- This is the message dropdown -->
        <ul class="nav navbar-top-links navbar-right pull-right visible-xs">



            <!-- .Task dropdown -->
            <li class="dropdown" id="top-notification-dropdown">
                <a class="dropdown-toggle waves-effect waves-light show-user-notifications" data-toggle="dropdown" href="#">
                    <i class="icon-bell"></i>
                    @if($unreadNotificationCount > 0)
                        <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                    @endif
                </a>
                <ul class="dropdown-menu  dropdown-menu-right mailbox animated slideInDown">
                    <li>
                        <a href="javascript:;">...</a>
                    </li>
                    
                </ul>
            </li>
            <!-- /.Task dropdown -->


            <li class="dropdown">
                <a href="{{ route('logout') }}" title="Logout" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();"
                ><i class="fa fa-power-off"></i>
                </a>
            </li>



        </ul>

    </div>
    <!-- /.navbar-header -->

    <div class="top-left-part">
        <a class="logo hidden-xs hidden-sm text-center" href="{{ route('admin.dashboard') }}">
            <img src="{{ $global->logo_url }}" alt="home" class=" admin-logo"/>
        </a>
    </div>

    <div class="sidebar-nav navbar-collapse slimscrollsidebar">
        <!-- .User Profile -->
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

            <li class="user-pro  hidden-sm hidden-md hidden-lg">
                @if(is_null($user->image))
                    <a href="#" class="waves-effect"><img src="{{ asset('img/default-profile-3.png') }}" alt="user-img" class="img-circle"> <span class="hide-menu">{{ (strlen($user->name) > 24) ? substr(ucwords($user->name), 0, 20).'..' : ucwords($user->name) }}
                            <span class="fa arrow"></span></span>
                    </a>
                @else
                    <a href="#" class="waves-effect"><img src="{{ asset_url('avatar/'.$user->image) }}" alt="user-img" class="img-circle"> <span class="hide-menu">{{ ucwords($user->name) }}
                            <span class="fa arrow"></span></span>
                    </a>
                @endif
                <ul class="nav nav-second-level">
                    <li><a href="{{ route('member.profile.index') }}"><i class="ti-user"></i> @lang("app.menu.profileSettings")</a></li>
                    @if($user->hasRole('admin'))
                        <li>
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="fa fa-sign-in"></i>  @lang("app.loginAsAdmin")
                            </a>
                        </li>
                    @endif
                        <li role="separator" class="divider"></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                        ><i class="fa fa-power-off"></i> @lang('app.logout')</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </li>

            <li><a href="{{ route('member.dashboard') }}" class="waves-effect"><i class="icon-speedometer fa-fw"></i> <span class="hide-menu">@lang("app.menu.dashboard") </span></a> </li>

            @if(in_array('clients',$modules))
            @if($user->can('view_clients'))
            <li><a href="{{ route('member.clients.index') }}" class="waves-effect"><i class="icon-people fa-fw"></i> <span class="hide-menu">@lang('app.menu.clients') </span></a> </li>
            @endif
            @endif

            @if(in_array('employees',$modules))
            @if($user->can('view_employees'))
                <li><a href="{{ route('member.employees.index') }}" class="waves-effect"><i class="icon-user fa-fw"></i> <span class="hide-menu">@lang('app.menu.employees') </span></a> </li>
            @endif
            @endif

            @if(in_array('projects',$modules))
            <li><a href="{{ route('member.projects.index') }}" class="waves-effect"><i class="icon-layers fa-fw"></i> <span class="hide-menu">@lang("app.menu.projects") </span></a></li>
            @endif

            @if(in_array('contracts',$modules) && $user->can('view_contract'))
            <li><a href="{{ route('member.contracts.index') }}" class="waves-effect"><i class="icon-layers fa-fw"></i> <span class="hide-menu">@lang("app.menu.contract") </span> </a> </li>
            @endif

            @if(in_array('products',$modules) && $user->can('view_product'))
                <li><a href="{{ route('member.products.index') }}" class="waves-effect"><i class="icon-basket fa-fw"></i> <span class="hide-menu">@lang('app.menu.products') </span></a> </li>
            @endif

            @if(in_array('tasks',$modules))
            <li><a href="{{ route('member.task.index') }}" class="waves-effect"><i class="fa fa-tasks fa-fw"></i> <span class="hide-menu"> @lang('app.menu.tasks') <span class="fa arrow"></span> </span></a>
                <ul class="nav nav-second-level">
                    <li><a href="{{ route('member.all-tasks.index') }}">@lang('app.menu.tasks')</a></li>
                    <li><a href="{{ route('member.task-label.index') }}">@lang('app.menu.taskLabel')</a></li>
                    <li class="hidden-sm hidden-xs"><a href="{{ route('member.taskboard.index') }}">@lang('modules.tasks.taskBoard')</a></li>
                    <li><a href="{{ route('member.task-calendar.index') }}">@lang('app.menu.taskCalendar')</a></li>
                </ul>
            </li>
            @endif

            @if(in_array('leads',$modules))
                <li><a href="{{ route('member.leads.index') }}" class="waves-effect"><i class="icon-doc fa-fw"></i> <span class="hide-menu">@lang('app.menu.lead') </span></a> </li>
            @endif

            @if(in_array('timelogs',$modules))
                <li><a href="{{ route('member.all-time-logs.index') }}" class="waves-effect"><i class="icon-clock fa-fw"></i> <span class="hide-menu">@lang('app.menu.timeLogs') </span></a> </li>
            @endif

            @if(in_array('attendance',$modules))
                @if($user->can('view_attendance'))
                    <li><a href="{{ route('member.attendances.summary') }}" class="waves-effect"><i class="icon-clock fa-fw"></i> <span class="hide-menu">@lang("app.menu.attendance") </span></a> </li>
                @else
                    <li><a href="{{ route('member.attendances.index') }}" class="waves-effect"><i class="icon-clock fa-fw"></i> <span class="hide-menu">@lang("app.menu.attendance") </span></a> </li>
                @endif
            @endif

            @if(in_array('holidays',$modules))
            <li><a href="{{ route('member.holidays.index') }}" class="waves-effect"><i class="icon-calender fa-fw"></i> <span class="hide-menu">@lang("app.menu.holiday") </span></a> </li>
            @endif

            @if(in_array('tickets',$modules))
            <li><a href="{{ route('member.tickets.index') }}" class="waves-effect"><i class="ti-ticket fa-fw"></i> <span class="hide-menu">@lang("app.menu.tickets") </span></a> </li>
            @endif

            @if((in_array('estimates',$modules) && $user->can('view_estimates'))
            || (in_array('invoices',$modules)  && $user->can('view_invoices'))
            || (in_array('payments',$modules) && $user->can('view_payments'))
            || (in_array('expenses',$modules)))
            <li><a href="{{ route('member.finance.index') }}" class="waves-effect"><i class="fa fa-money fa-fw"></i> <span class="hide-menu"> @lang('app.menu.finance')<span class="fa arrow"></span> </span></a>
                <ul class="nav nav-second-level">
                    @if(in_array('estimates',$modules))
                    @if($user->can('view_estimates'))
                        <li><a href="{{ route('member.estimates.index') }}">@lang('app.menu.estimates')</a> </li>
                    @endif
                    @endif

                    @if(in_array('invoices',$modules))
                    @if($user->can('view_invoices'))
                        <li><a href="{{ route('member.all-invoices.index') }}">@lang('app.menu.invoices')</a> </li>
                    @endif
                    @endif

                    @if(in_array('payments',$modules))
                    @if($user->can('view_payments'))
                        <li><a href="{{ route('member.payments.index') }}">@lang('app.menu.payments')</a> </li>
                    @endif
                    @endif

                    @if(in_array('expenses',$modules))
                        <li><a href="{{ route('member.expenses.index') }}">@lang('app.menu.expenses')</a> </li>
                    @endif
                    @if(in_array('invoices',$modules))
                        @if($user->can('view_invoices'))
                            <li><a href="{{ route('member.all-credit-notes.index') }}">@lang('app.menu.credit-note') </a> </li>
                        @endif
                    @endif
                </ul>
            </li>
            @endif

            @if(in_array('messages',$modules))
            <li><a href="{{ route('member.user-chat.index') }}" class="waves-effect"><i class="icon-envelope fa-fw"></i> <span class="hide-menu">@lang("app.menu.messages") 
                    </span>
                </a>
            </li>
            @endif

            @if(in_array('events',$modules))
            <li><a href="{{ route('member.events.index') }}" class="waves-effect"><i class="icon-calender fa-fw"></i> <span class="hide-menu">@lang('app.menu.Events')</span></a> </li>
            @endif

            @if(in_array('leaves',$modules))
            <li><a href="{{ route('member.leaves.index') }}" class="waves-effect"><i class="icon-logout fa-fw"></i> <span class="hide-menu">@lang('app.menu.leaves')</span></a> </li>
            @endif

            @if(in_array('notices',$modules))
                <li><a href="{{ route('member.notices.index') }}" class="waves-effect"><i class="ti-layout-media-overlay fa-fw"></i> <span class="hide-menu">@lang("app.menu.noticeBoard") </span></a> </li>
            @endif

            @foreach ($worksuitePlugins as $item)
                @if(View::exists(strtolower($item).'::sections.member_left_sidebar'))
                    @include(strtolower($item).'::sections.member_left_sidebar')
                @endif
            @endforeach
        </ul>


    </div>

    <div class="menu-footer">
        <div class="menu-user row">
            <div class="col-lg-6 m-b-5">
                <div class="btn-group dropup user-dropdown">
                    @if(is_null($user->image))
                        <img  aria-expanded="false" data-toggle="dropdown" src="{{ asset('img/default-profile-3.png') }}" alt="user-img" class="img-circle dropdown-toggle h-30 w-30">

                    @else
                        <img aria-expanded="false" data-toggle="dropdown" src="{{ asset_url('avatar/'.$user->image) }}" alt="user-img" class="img-circle dropdown-toggle h-30 w-30">

                    @endif
                    <ul role="menu" class="dropdown-menu">
                        <li><a class="bg-inverse"><strong class="text-info">{{ ucwords($user->name) }}</strong></a></li>
                        @if($user->hasRole('admin'))
                            <li>
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="fa fa-sign-in"></i>  @lang("app.loginAsAdmin")
                                </a>
                            </li>
                        @endif
                        <li><a href="{{ route('member.profile.index') }}"><i class="ti-user"></i> @lang("app.menu.profileSettings")</a></li>                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();"
                            ><i class="fa fa-power-off"></i> @lang('app.logout')</a>

                        </li>

                    </ul>
                </div>
            </div>


            <div class="col-lg-6 text-center m-b-5">
                <div class="btn-group dropup notification-dropdown">
                    <a class="dropdown-toggle show-user-notifications" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>
                        @if($unreadNotificationCount > 0)

                            <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                        @endif
                    </a>
                    <ul class="dropdown-menu mailbox ">
                        <li>
                            <a href="javascript:;">...</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="menu-copy-right">
            <a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="ti-angle-double-right ti-angle-double-left"></i> <span class="collapse-sidebar-text">@lang('app.collapseSidebar')</span></a>
        </div>


    </div>
</div>
