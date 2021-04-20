<div class="rpanel-title"> @lang('app.task') <span><i class="ti-close right-side-toggle"></i></span> </div>
<div class="r-panel-body p-t-0">

    <div class="row">
        <div class="col-xs-6 col-md-9 p-t-20 b-r h-scroll">

            <div class="col-xs-12">
                <h4>
                    {{ ucwords($task->heading) }}
                </h4>
                @if(!is_null($task->project_id))
                    <p><i class="icon-layers"></i> {{ ucfirst($task->project->project_name) }}</p>
                @endif

                <h5>
                    @if($task->task_category_id)
                        <label class="label label-default text-dark font-light">{{ ucwords($task->category->category_name) }}</label>
                    @endif

                    <label class="font-light label
                    @if($task->priority == 'high')
                            label-danger
                    @elseif($task->priority == 'medium') label-warning @else label-success @endif
                            ">
                        <span class="text-dark">@lang('modules.tasks.priority') ></span>  {{ ucfirst($task->priority) }}
                    </label>
                </h5>

            </div>

            <ul class="nav customtab nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#home1" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true">@lang('app.task')</a></li>
                <li role="presentation" class=""><a href="#profile1" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false">@lang('modules.tasks.subTask')({{ count($task->subtasks) }})</a></li>
                <li role="presentation" class=""><a href="#messages1" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false">@lang('app.file') ({{ sizeof($task->files) }})</a></li>
                <li role="presentation" class=""><a href="#settings1" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false">@lang('modules.tasks.comment') ({{ count($task->comments) }})</a></li>

                <li role="presentation" >  <a href="#history1" id="view-task-history" role="tab" data-toggle="tab" aria-expanded="false" data-task-id="{{ $task->id }}" > <span class="hidden-xs">@lang('modules.tasks.history')</span></a></li>
            </ul>

            <div class="tab-content" id="task-detail-section">
                <div role="tabpanel" class="tab-pane fade active in" id="home1">
                    <div class="row">
                        <div class="col-xs-12 col-md-12 m-t-10">
                            <label class="font-bold">@lang('app.description')</label><br>
                            <div class="task-description m-t-10">
                                {!! $task->description ?? __('messages.noDescriptionAdded') !!}
                            </div>
                        </div>

                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="profile1">
                    <div class="col-xs-12">
                        <h4><i class="ti-check-box"></i> @lang('modules.tasks.subTask')
                            @if (count($task->subtasks) > 0)
                                <span class="pull-right"><span class="donut" data-peity='{ "fill": ["#00c292", "#eeeeee"],    "innerRadius": 5, "radius": 8 }'>{{ count($task->completedSubtasks) }}/{{ count($task->subtasks) }}</span> <span class="text-muted font-12">{{ floor((count($task->completedSubtasks)/count($task->subtasks))*100) }}%</span></span>
                            @endif
                        </h4>
                        <ul class="list-group" id="sub-task-list">
                            @foreach($task->subtasks as $subtask)
                                <li class="list-group-item row">
                                    <div class="col-xs-12">
                                        <div>
                                            @if ($subtask->status != 'complete')
                                                {{ ucfirst($subtask->title) }}
                                            @else
                                                <span style="text-decoration: line-through;">{{ ucfirst($subtask->title) }}</span>
                                            @endif
                                        </div>
                                        @if($subtask->due_date)<span class="text-muted m-l-5 font-12"> - @lang('modules.invoices.due'): {{ $subtask->due_date->format($global->date_format) }}</span>@endif
                                    </div>


                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="messages1">
                    <div class="col-xs-12">
                        <ul class="list-group" id="files-list">
                            @forelse($task->files as $file)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-9">
                                        {{ $file->filename }}
                                    </div>
                                    <div class="col-md-3">
                                            <a target="_blank" href="{{ $file->file_url }}"
                                            data-toggle="tooltip" data-original-title="View"
                                            class="btn btn-info btn-circle"><i
                                                        class="fa fa-search"></i></a>
                                    
                                        <span class="clearfix m-l-10">{{ $file->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </li>
                            @empty
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-10">
                                            @lang('messages.noFileUploaded')
                                        </div>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="settings1">
                    <div class="col-xs-12 b-b">
                        <h4>@lang('modules.tasks.comment')</h4>
                    </div>

                    <div class="col-xs-12" id="comment-container">
                        <div id="comment-list">
                            @forelse($task->comments as $comment)
                                <div class="row b-b m-b-5 font-12">
                                    <div class="col-xs-12">
                                        <h5>{{ ucwords($comment->user->name) }} <span class="text-muted font-12">{{ ucfirst($comment->created_at->diffForHumans()) }}</span></h5>
                                    </div>
                                    <div class="col-xs-12">
                                        {!! ucfirst($comment->comment)  !!}
                                    </div>

                                </div>
                            @empty
                                <div class="col-xs-12">
                                    @lang('messages.noRecordFound')
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="history1">
                    <div class="col-xs-12">
                        <label class="font-bold">@lang('modules.tasks.history')</label>
                    </div>
                    <div class="col-xs-12" id="task-history-section">
                    </div>
                </div>

            </div>



            <div class="col-xs-12" id="task-history-section">
            </div>
        </div>

        <div class="col-xs-6 col-md-3">

            <div class="row">
                <div class="col-xs-12 p-10 p-t-20 ">
                    <label class="font-12" for="">@lang('app.status')</label><br>
                    <span id="columnStatusColor" style="width: 15px; height: 15px; background-color: {{ $task->board_column->label_color }}" class="btn btn-small btn-circle">&nbsp;</span> <span id="columnStatus">{{ $task->board_column->column_name }}</span>
                </div>

                <div class="col-xs-12">
                    <hr>

                    <label class="font-12" for="">@lang('modules.tasks.assignTo')</label><br>
                    @foreach ($task->users as $item)
                        <img src="{{ $item->image_url }}" data-toggle="tooltip"
                             data-original-title="{{ ucwords($item->name) }}" data-placement="right"
                             class="img-circle" width="35" height="35" alt="">
                    @endforeach
                    <hr>
                </div>
                @if($task->create_by)
                    <div class="col-xs-12">
                        <label class="font-12" for="">@lang('modules.tasks.assignBy')</label><br>
                        <img src="{{ $task->create_by->image_url }}" class="img-circle" width="35" height="35" alt="">

                        {{ ucwords($task->create_by->name) }}
                        <hr>
                    </div>
                @endif

                @if($task->start_date)
                    <div class="col-xs-12  ">
                        <label class="font-12" for="">@lang('app.startDate')</label><br>
                        <span class="text-success" >{{ $task->start_date->format($global->date_format) }}</span><br>
                        <hr>
                    </div>
                @endif

                <div class="col-xs-12 ">
                    <label class="font-12" for="">@lang('app.dueDate')</label><br>
                    <span @if($task->due_date->isPast()) class="text-danger" @endif>
                        {{ $task->due_date->format($global->date_format) }}
                    </span>
                    <hr>
                </div>

                @if(sizeof($task->label))
                    <div class="col-xs-12">
                        <label class="font-12" for="">@lang('app.label')</label><br>
                        <span>
                            @foreach($task->label as $key => $label)
                                <label class="badge text-capitalize font-semi-bold" style="background:{{ $label->label->label_color }}">{{ ucwords($label->label->label_name) }} </label>
                            @endforeach
                        </span>
                        <hr>
                    </div>
                @endif

            </div>


        </div>


    </div>

</div>


<script src="{{ asset('plugins/bower_components/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/peity/jquery.peity.init.js') }}"></script>
<script>
    $('#view-task-history').click(function () {
        var id = $(this).data('task-id');

        var url = '{{ route('front.task-history', ':id')}}';
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            type: "GET",
            success: function (response) {
                $('#task-history-section').html(response.view);
            }
        })

    })

    $('#uploadedFiles').click(function () {

        var url = '{{ route("front.task-files", ':id') }}';

        var id = {{ $task->id }};
        url = url.replace(':id', id);

        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    });


    $('.close-task-history').click(function () {
        $('#task-detail-section').show();
        $('#task-history-section').html('');
        $(this).hide();
        $('#view-task-history').show();
    })
</script>
