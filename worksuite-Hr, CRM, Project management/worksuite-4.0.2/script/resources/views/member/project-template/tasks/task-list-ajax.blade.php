@foreach($project->tasks as $task)
    <li class="list-group-item">
        <div class="row">
            <div class="checkbox checkbox-success checkbox-circle task-checkbox col-md-10">
                <a href="javascript:;" class="text-muted edit-task"
                   data-task-id="{{ $task->id }}">{{ ucfirst($task->heading) }}</a>
            </div>
            <div class="col-md-2 text-right">
                {!! ($task->user->image) ? '<img data-toggle="tooltip" data-original-title="' . ucwords($task->user->name) . '" src="' . asset_url('avatar/' . $task->user->image) . '"
                        alt="user" class="img-circle" height="35"> ' : '<img data-toggle="tooltip" data-original-title="' . ucwords($task->user->name) . '" src="' . asset('img/default-profile-3.png') . '"
                        alt="user" class="img-circle" height="35"> ' !!}
            </div>
        </div>
    </li>
@endforeach