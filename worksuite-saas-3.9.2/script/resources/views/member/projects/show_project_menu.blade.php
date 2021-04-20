<div class="white-box">
    <nav>
        <ul class="showProjectTabs">
            <li class="projects"><a href="{{ route('member.projects.show', $project->id) }}"><i class="icon-grid"></i> <span>@lang('modules.projects.overview')</span></a>
            </li>

            @if(in_array('employees',$modules))
            <li class="projectMembers"><a href="{{ route('member.project-members.show', $project->id) }}"><i class="icon-people"></i> <span>@lang('modules.projects.members')</span></a></li>
            @endif

            @if ($user->cans('view_projects'))
                <li class="projectMilestones">
                    <a href="{{ route('member.milestones.show', $project->id) }}"><i class="icon-flag"></i> <span>@lang('modules.projects.milestones')</span></a>
                </li>
            @endif

            @if(in_array('tasks',$modules))
            <li class="projectTasks"><a href="{{ route('member.tasks.show', $project->id) }}"><i class="ti-check-box"></i> <span>@lang('app.menu.tasks')</span></a></li>
            @endif

            <li class="projectFiles"><a href="{{ route('member.files.show', $project->id) }}"><i class="ti-files"></i> <span>@lang('modules.projects.files')</span></a></li>

            @if(in_array('timelogs',$modules))
            <li class="projectTimelogs"><a href="{{ route('member.time-log.show-log', $project->id) }}"><i class="ti-alarm-clock"></i> <span>@lang('app.menu.timeLogs')</span></a></li>
            @endif

            <li class="discussion">
                <a href="{{ route('member.projects.discussion', $project->id) }}"><i class="ti-comments"></i>
                    <span>@lang('modules.projects.discussion')</span></a>
            </li>
            
            <li class="gantt">
                <a href="{{ route('member.projects.gantt', [$project->id]) }}"><i class="fa fa-bar-chart"></i>
                    <span>@lang('modules.projects.viewGanttChart')</span></a>
            </li>
            @if($project->visible_rating_employee)
                <li class="projectRatings">
                    <a href="{{ route('member.project-ratings.show', $project->id) }}">
                        <i class="fa fa-star" aria-hidden="true"></i> <span>@lang('app.rating')</span>
                    </a>
                </li>
            @endif
            <li class="notes">
                <a href="{{ route('member.project-notes.show', $project->id) }}"><i class="fa fa-sticky-note-o"></i>
                    <span>@lang('modules.projects.notes')</span></a>
            </li> 
        </ul>
    </nav>
</div>