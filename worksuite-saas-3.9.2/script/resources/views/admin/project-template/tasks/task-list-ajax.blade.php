@foreach($project->tasks as $task)
    <li class="list-group-item">
        <div class="row">
            <div class="checkbox checkbox-success checkbox-circle task-checkbox col-md-10">
                <a href="javascript:;" class="text-muted edit-task"
                   data-task-id="{{ $task->id }}">{{ ucfirst($task->heading) }}</a>
            </div>
            <div class="col-md-2 text-right">
                {!! '<img data-toggle="tooltip" data-original-title="' . ucwords($task->user->name) . '" src="' . $task->user->image_url . '"
                        alt="user" class="img-circle" height="35"> ' !!}
            </div>
        </div>
    </li>
@endforeach
