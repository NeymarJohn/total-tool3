<div class="white-box">
    <div class="row">
    <div class="col-md-12">
        <nav>
            <ul class="showProjectTabs">
                <li class="projects"><a href="{{ route('client.projects.show', $project->id) }}"><i class="icon-grid"></i> <span>@lang('modules.projects.overview')</span></a></li>

                @if(in_array('employees',$modules))
                <li><a href="{{ route('client.project-members.show', $project->id) }}"><span>@lang('modules.projects.members')</span></a></li>
                @endif

                <li class="projectMilestones">
                    <a href="{{ route('client.milestones.show', $project->id) }}"><i class="icon-flag"></i>
                        <span>@lang('modules.projects.milestones')</span></a>
                </li>

                @if($project->client_view_task == 'enable' && in_array('tasks',$modules))
                    <li class="projectTasks"><a href="{{ route('client.tasks.edit', $project->id) }}"><i class="ti-check-box"></i> <span>@lang('app.menu.tasks')</span></a></li>
                @endif

                <li class="projectFiles"><a href="{{ route('client.files.show', $project->id) }}"><i class="ti-files"></i> <span>@lang('modules.projects.files')</span></a></li>

                @if(in_array('timelogs',$modules))
                <li class="projectTimelogs"><a href="{{ route('client.time-log.show', $project->id) }}"><i class="ti-alarm-clock"></i> <span>@lang('app.menu.timeLogs')</span></a></li>
                @endif

                @if(in_array('invoices',$modules))
                <li class="projectInvoices"><a href="{{ route('client.project-invoice.show', $project->id) }}"><i class="ti-file"></i> <span>@lang('app.menu.invoices')</span></a></li>
                @endif

                @if(in_array('expenses',$modules))
                  <li class="projectExpenses"><a href="{{ route('client.project-expenses.show', $project->id) }}"><i class="ti-shopping-cart" aria-hidden="true"></i> <span>@lang('app.menu.expenses')</span></a></li>
                @endif

                @if(in_array('payments',$modules))
                    <li class="projectPayments"><a href="{{ route('client.project-payments.show', $project->id) }}"><i class="fa fa-money" aria-hidden="true"></i> <span>@lang('app.menu.payments')</span></a></li>
                @endif
                @if($project->status == 'finished')
                    <li class="projectRatings"><a href="{{ route('client.project-ratings.show', $project->id) }}"><i class="fa fa-star" aria-hidden="true"></i> <span>@lang('app.rating')</span></a></li>
                @endif

            </ul>
        </nav>
    </div>
    </div>
</div>
