<div class="white-box">
    <div class="row">
        <div class="col-md-11 p-r-0">
            <nav>
                <ul class="showProjectTabs">
                    <li class="projects">
                        <a href="{{ route('admin.projects.show', $project->id) }}"><i class="icon-grid"></i>
                            <span>@lang('modules.projects.overview')</span></a>
                    </li>
                    @if(in_array('employees',$modules))
                    <li class="projectMembers">
                        <a href="{{ route('admin.project-members.show', $project->id) }}"><i class="icon-people"></i>
                            <span>@lang('modules.projects.members')</span></a>
                    </li>
                    @endif
                    <li class="projectMilestones">
                        <a href="{{ route('admin.milestones.show', $project->id) }}"><i class="icon-flag"></i>
                            <span>@lang('modules.projects.milestones')</span></a>
                    </li>
                    @if(in_array('tasks',$modules))
                    <li class="projectTasks">
                        <a href="{{ route('admin.tasks.show', $project->id) }}"><i class="ti-check-box"></i>
                            <span>@lang('app.menu.tasks')</span></a>
                    </li>
                    <li class="projectTaskBoard">
                        <a href="{{ route('admin.tasks.kanbanboard', $project->id) }}"><i class="ti-layout-column3"></i>
                            <span>@lang('modules.tasks.taskBoard')</span></a>
                    </li>
                    @endif
                    <li class="projectFiles">
                        <a href="{{ route('admin.files.show', $project->id) }}"><i class="ti-files"></i>
                            <span>@lang('modules.projects.files')</span></a>
                    </li>
                    @if(in_array('invoices',$modules))
                    <li class="projectInvoices">
                        <a href="{{ route('admin.invoices.show', $project->id) }}"><i class="ti-file"></i>
                            <span>@lang('app.menu.invoices')</span></a>
                    </li>
                    @endif @if(in_array('timelogs',$modules))
                    <li class="projectTimelogs">
                        <a href="{{ route('admin.time-logs.show', $project->id) }}"><i class="ti-alarm-clock"></i>
                            <span>@lang('app.menu.timeLogs')</span></a>
                    </li>
                    @endif

                    <li class="discussion">
                        <a href="{{ route('admin.projects.discussion', $project->id) }}"><i class="ti-comments"></i>
                            <span>@lang('modules.projects.discussion')</span></a>
                    </li>

                </ul>
            </nav>
        </div>
        <div class="col-md-1 text-center tabs-more">
            <div class="btn-group dropdown m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                <ul role="menu" class="dropdown-menu pull-right">
                <li><a href="{{ route('admin.projects.burndown-chart', $project->id) }}"><i class="icon-graph" aria-hidden="true"></i> @lang('modules.projects.burndownChart')</a>
                </li>
                @if(in_array('expenses',$modules))
                  <li><a href="{{ route('admin.project-expenses.show', $project->id) }}"><i class="ti-shopping-cart" aria-hidden="true"></i> @lang('app.menu.expenses')</a></li>
                @endif

                @if(in_array('payments',$modules))
                    <li><a href="{{ route('admin.project-payments.show', $project->id) }}"><i class="fa fa-money" aria-hidden="true"></i> @lang('app.menu.payments')</a></li>
                @endif

                <li class="gantt">
                    <a href="{{ route('admin.projects.gantt', $project->id) }}"><i class="fa fa-bar-chart"></i>
                        <span>@lang('modules.projects.viewGanttChart')</span></a>
                </li>
                    <li class="projectRatings">
                        <a href="{{ route('admin.project-ratings.show', $project->id) }}">
                            <i class="fa fa-star" aria-hidden="true"></i> <span>@lang('app.rating')</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
