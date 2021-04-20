@forelse($project->issues as $issue)
    <li class="list-group-item">
        <div class="row">
            <div class="col-md-5 col-md-offset-7 text-right">
                <span class="btn btn-xs btn-info btn-rounded">{{ $issue->created_at->format('d M, y') }}</span>
                <i class="text-muted">by {{ ucwords($project->client->name) }}</i>
                                                        <span class="@if($issue->status == 'pending') text-danger @else text-success @endif m-l-10">
                                                            <i class="fa @if($issue->status == 'pending') fa-exclamation-circle @else fa-check-circle @endif"></i> {{ ucfirst($issue->status) }}
                                                        </span>
                @if($issue->status == 'pending')
                    <a href="javascript:;" class="btn btn-primary btn-xs btn-outline m-l-10 change-status" data-issue-id="{{ $issue->id }}" data-new-status="resolved">Mark Resolved</a>
                @else
                    <a href="javascript:;" class="btn btn-primary btn-xs btn-outline m-l-10 change-status" data-issue-id="{{ $issue->id }}" data-new-status="pending">Mark Pending</a>
                @endif

            </div>
        </div>

        <div class="row m-t-20">
            <div class="col-md-12">
                {{ nl2br($issue->description) }}
            </div>
        </div>
    </li>
@empty
    <li class="list-group-item">
        <div class="row">
            <div class="col-md-12">
                No issue found.
            </div>
        </div>
    </li>
@endforelse