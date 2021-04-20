@if(isset($project))
    @foreach($project->tasks as $task)
        <li class="list-group-item @if($task->board_column->slug == 'completed') task-completed @endif">
            <div class="row">
                <div class="checkbox checkbox-success checkbox-circle task-checkbox col-md-10">
                    <input class="task-check" data-task-id="{{ $task->id }}" id="checkbox{{ $task->id }}" type="checkbox"
                           @if($task->board_column->slug == 'completed') checked @endif>
                    <label for="checkbox{{ $task->id }}">&nbsp;</label>
                    <a href="javascript:;" class="text-muted edit-task"
                       data-task-id="{{ $task->id }}">{{ ucfirst($task->heading) }}</a>
                </div>
                <div class="col-md-2 text-right">
                    <span class="@if($task->due_date->isPast()) text-danger @else text-success @endif m-r-10">{{ $task->due_date->format('d M') }}</span>
                     @foreach ($task->users as $item)
                        <img src="{{ $item->image_url }}" data-toggle="tooltip" data-original-title="{{ ucwords($item->name) }}" data-placement="right" class="img-circle" width="35" height="35" alt="">
                    @endforeach
                </div>
            </div>
        </li>
    @endforeach
@else
    <li class="list-group-item @if($task->board_column->slug == 'completed') task-completed @endif">
        <div class="row">
            <div class="checkbox checkbox-success checkbox-circle task-checkbox col-md-10">
                <input class="task-check" data-task-id="{{ $task->id }}" id="checkbox{{ $task->id }}" type="checkbox"
                       @if($task->board_column->slug == 'completed') checked @endif>
                <label for="checkbox{{ $task->id }}">&nbsp;</label>
                <a href="javascript:;" class="text-muted edit-task"
                   data-task-id="{{ $task->id }}">{{ ucfirst($task->heading) }}</a>
            </div>
            <div class="col-md-2 text-right">
                <span class="@if($task->due_date->isPast()) text-danger @else text-success @endif m-r-10">{{ $task->due_date->format('d M') }}</span>
                 @foreach ($task->users as $item)
                    <img src="{{ $item->image_url }}" data-toggle="tooltip" data-original-title="{{ ucwords($item->name) }}" data-placement="right" class="img-circle" width="35" height="35" alt="">
                @endforeach
            </div>
        </div>
    </li>
@endif
