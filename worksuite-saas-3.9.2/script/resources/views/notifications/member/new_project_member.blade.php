<li class="top-notifications">
    <div class="message-center">
        @php
            if (!isset($notification->data['project'])) {
                $project = \App\ProjectMember::with('project')->find($notification->data['id']);
                $projectId = $project->project_id;
                $project = $project->project ? $project->project->project_name : '';
            } else {
                $projectId = $notification->data['project_id'];
                $project = $notification->data['project'];
            }
            $route = route('member.projects.show', $projectId);
        @endphp
        <a href="{{ $route }}" >
            <div class="user-img">
                <span class="btn btn-circle btn-success"><i class="icon-layers"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">@lang('email.newProjectMember.subject')</span>
                <small>{{ ucwords($project) }}</small>
                <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>
