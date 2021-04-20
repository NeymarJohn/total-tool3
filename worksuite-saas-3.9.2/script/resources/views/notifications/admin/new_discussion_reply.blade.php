<li class="top-notifications">
    <div class="message-center">
        @php
            if (!isset($notification->data['discussion_id'])) {
                $discussionReply = \App\DiscussionReply::with('discussion')->find($notification->data['id']);
                $projectId = $discussionReply->discussion->project_id;
                $discussionId = $discussionReply->discussion_id;
            } else {
                $projectId = $notification->data['project_id'];
                $discussionId = $notification->data['discussion_id'];
            }
           $route = route('admin.projects.discussionReplies', [$projectId, $discussionId])
        @endphp

        <a href="{{ $route }}">
            <div class="user-img">
                <span class="btn btn-circle btn-success"><i class="ti-comments"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">{{ ucwords($notification->data['user']) . ' '. __('email.discussionReply.subject') . $notification->data['title'] }}</span> 
                <small>{{ ucfirst($notification->data['title']) }}</small>
                <span class="time">@if($notification->created_at){{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}@endif</span>
            </div>
        </a>
    </div>
</li>
