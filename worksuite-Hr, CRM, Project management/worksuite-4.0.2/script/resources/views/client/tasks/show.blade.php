<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">

<div class="rpanel-title"> @lang('app.task') <span><i class="ti-close right-side-toggle"></i></span> </div>
<div class="r-panel-body">

    <div class="row">
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
        <div class="col-xs-6">
            <label for="">@lang('modules.tasks.assignTo')</label><br>
            @foreach ($task->users as $item)
                <img src="{{ $item->image_url }}" data-toggle="tooltip" title="{{ ucwords($item->name) }}" data-original-title="{{ ucwords($item->name) }}" data-placement="right" class="img-circle" width="25" height="25" alt="">
            @endforeach
        </div>
        <div class="col-xs-6">
            <label for="">@lang('app.dueDate')</label><br>
            <span @if($task->due_date->isPast()) class="text-danger" @endif>{{ $task->due_date->format($global->date_format) }}</span>
        </div>
        <div class="col-xs-12 task-description">
            {!! ucfirst($task->description) !!}
        </div>


        <div class="col-xs-12 m-t-20 m-b-10">
            <ul class="list-group" id="sub-task-list">
                @foreach($task->subtasks as $subtask)

                    <li class="list-group-item row">
                        <div class="col-xs-9">
                            <span>{{ ucfirst($subtask->title) }}</span>
                        </div>

                        <div class="col-xs-3 text-right">
                            @if($subtask->due_date)<span class="text-muted m-l-5"> - @lang('modules.invoices.due'): {{ $subtask->due_date->format($global->date_format) }}</span>@endif
                        </div>
                    </li>
                @endforeach

            </ul>

            <div class="row b-all m-t-10 p-10"  id="new-sub-task" style="display: none">
                <div class="col-xs-11 ">
                    <a href="javascript:;" id="create-sub-task" data-name="title"  data-url="{{ route('admin.sub-task.store') }}" class="text-muted" data-type="text"></a>
                </div>

                <div class="col-xs-1 text-right">
                    <a href="javascript:;" id="cancel-sub-task" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></a>
                </div>
            </div>

        </div>

        <div class="col-xs-12 m-t-15">
            <h5>@lang('modules.tasks.comment')</h5>
        </div>

        <div class="col-xs-12" id="comment-container">
            <div id="comment-list">
                @forelse($task->comments as $comment)
                    <div class="row b-b m-b-5 font-12">
                        <div class="col-xs-12">
                            <h5>{{ ucwords($comment->user->name) }} <span class="text-muted font-12">{{ ucfirst($comment->created_at->diffForHumans()) }}</span></h5>
                        </div>
                        <div class="col-xs-10">
                            {!! ucfirst($comment->comment)  !!}
                        </div>
                        @if($comment->user_id == $user->id)
                            <div class="col-xs-2 text-right">
                                <a href="javascript:;" data-comment-id="{{ $comment->id }}" class="text-danger" onclick="deleteComment('{{ $comment->id }}');return false;">@lang('app.delete')</a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-xs-12">
                        @lang('messages.noRecordFound')
                    </div>
                @endforelse
            </div>
        </div>

        <div class="form-group" id="comment-box">
            <div class="col-xs-12">
                <textarea name="comment" id="task-comment" class="summernote" placeholder="@lang('modules.tasks.comment')"></textarea>
            </div>
            <div class="col-xs-3">
                <a href="javascript:;" id="submit-comment" class="btn btn-success"><i class="fa fa-send"></i> @lang('app.submit')</a>
            </div>
        </div>

    </div>

</div>

<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script>
    $('.summernote').summernote({
        height: 100,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen"]]
        ]
    });

    //    change sub task status

    $('#submit-comment').click(function () {
        var comment = $('#task-comment').val();
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            url: '{{ route("client.task-comment.store") }}',
            type: "POST",
            data: {'_token': token, comment: comment, taskId: '{{ $task->id }}'},
            success: function (response) {
                if (response.status == "success") {
                    $('#comment-list').html(response.view);
                    $('.summernote').summernote("reset");
                    $('.note-editable').html('');
                    $('#task-comment').val('');
                }
            }
        })
    })

    function deleteComment (id) {

        var url = '{{ route("client.task-comment.destroy", ':id') }}';
        url = url.replace(':id', id);

        $.easyAjax({
            url: url,
            type: "POST",
            data: {'_token': '{{ csrf_token() }}', '_method': 'DELETE'},
            success: function (response) {
                if (response.status == "success") {
                    $('#comment-list').html(response.view);
                }
            }
        })
    }


</script>
