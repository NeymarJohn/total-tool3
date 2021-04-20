<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">
<style>
    .dropzone .dz-preview .dz-remove {
        display: none;
    }
</style>

<div class="rpanel-title"> @lang('app.task') #{{ $task->id }} <span><i class="ti-close right-side-toggle"></i></span> </div>
<div class="r-panel-body p-t-0">

    <div class="row">
        <div class="col-xs-12 col-md-9 p-t-20 b-r h-scroll">

            <div class="col-xs-12">
                <a href="{{route('front.task-share',[md5($task->id)])}}" target="_blank" data-toggle="tooltip" data-placement="bottom"
                    data-original-title="@lang('app.share')" class="btn btn-default btn-sm m-b-10 btn-rounded btn-outline pull-right m-l-5"> <i class="fa fa-share-alt"></i></a>

                @if ($user->can('edit_tasks') || $user->id == $task->created_by)
                    <a href="{{route('member.all-tasks.edit',$task->id)}}" class="btn btn-default btn-sm m-b-10 btn-rounded btn-outline pull-right m-l-5"> <i class="fa fa-edit"></i> @lang('app.edit')</a>                   
                @endif

                <a href="javascript:;" id="completedButton" class="btn btn-success btn-sm m-b-10 btn-rounded @if($task->board_column->slug == 'completed') hidden @endif "  onclick="markComplete('completed')" ><i class="fa fa-check"></i> @lang('modules.tasks.markComplete')</a>
                <a href="javascript:;" id="inCompletedButton" class="btn btn-default btn-outline btn-sm m-b-10 btn-rounded @if($task->board_column->slug != 'completed') hidden @endif"  onclick="markComplete('incomplete')"><i class="fa fa-times"></i> @lang('modules.tasks.markIncomplete')</a>
                @if($task->board_column->slug != 'completed' && ($user->can('edit_tasks') || $task->created_by == $user->id))
                    <a href="javascript:;" id="reminderButton" class="btn btn-default btn-sm m-b-10 m-l-5 btn-rounded btn-outline pull-right" title="@lang('messages.remindToAssignedEmployee')"><i class="fa fa-bell"></i> @lang('modules.tasks.reminder')</a>
                @endif

                @php $pin = $task->pinned() @endphp
                <a href="javascript:;" class="btn btn-sm btn-info @if(!$pin) btn-outline @endif pull-right m-l-5"   data-placement="bottom"  data-toggle="tooltip" data-original-title="@if($pin) @lang('app.unpin') @else @lang('app.pin') @endif"  data-pinned="@if($pin) pinned @else unpinned @endif" id="pinnedItem" >
                    <i class="icon-pin icon-2 pin-icon  @if($pin) pinned @else unpinned @endif" ></i>
                </a>
                @if ($task->board_column->slug != 'completed')

                    @if (is_null($task->activeTimer) && !is_null($task->is_task_user))
                        <a href="javascript:;" id="start-task-timer"  class="btn btn-info btn-sm m-b-10 btn-rounded btn-outline pull-right m-l-5"> <i class="fa fa-clock-o"></i> @lang('modules.timeLogs.startTimer')</a>
                    @elseif (!is_null($task->activeTimer) && !is_null($task->is_task_user))
                        <a href="javascript:;" data-toggle="tooltip" id="stop-task-timer" data-placement="bottom"
                        data-original-title="@lang('app.stop')" data-time-id="{{ $task->activeTimer->id }}" class="btn btn-inverse btn-sm m-b-10 btn-rounded btn-outline pull-right m-l-5 w-100"> <span id="active-task-timer" class="b-r m-r-5 p-r-5">{{ $task->activeTimer->timer }}</span> <i class="fa fa-stop text-danger"></i></a>
                    @endif
                @endif

            </div>
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
                <li role="presentation" class=""><a href="#messages1" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false">@lang('app.file') (<span id="totalUploadedFiles">{{ sizeof($task->files) }}</span>) </a></li>
                <li role="presentation" class=""><a href="#timelogs1" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false">@lang('app.menu.timeLogs') </a></li>
                <li role="presentation" class=""><a href="#settings1" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false">@lang('modules.tasks.comment') ({{ count($task->comments) }})</a></li>

                <li role="presentation" class=""><a href="#notes1" aria-controls="note" role="tab" data-toggle="tab" aria-expanded="false">@lang('app.notes') ({{ count($task->notes) }})</a></li>

                <li role="presentation" >  <a href="#history1" id="view-task-history" role="tab" data-toggle="tab" aria-expanded="false" data-task-id="{{ $task->id }}" > <span class="hidden-xs">@lang('modules.tasks.history')</span></a></li>

            </ul>

            <div class="tab-content" id="task-detail-section">
                <div role="tabpanel" class="tab-pane fade active in" id="home1">
                    <div class="col-xs-12" >
                        
                        <div class="row visible-xs visible-sm">
                            <div class="col-xs-6 col-md-3 font-12">
                                <label class="font-12" for="">@lang('modules.tasks.assignTo')</label><br>
                                @foreach ($task->users as $item)
                                    <img src="{{ $item->image_url }}" data-toggle="tooltip"
                                         data-original-title="{{ ucwords($item->name) }}" data-placement="right"
                                         class="img-circle" width="25" height="25" alt="">
                                @endforeach
                            </div>
                            @if($task->create_by)
                                <div class="col-xs-6 col-md-3 font-12">
                                    <label class="font-12" for="">@lang('modules.tasks.assignBy')</label><br>
                                    <img src="{{ $task->create_by->image_url }}" class="img-circle" width="25" height="25" alt="">
    
                                    {{ ucwords($task->create_by->name) }}
                                </div>
                            @endif
    
                            @if($task->start_date)
                                <div class="col-xs-6 col-md-3 font-12">
                                    <label class="font-12" for="">@lang('app.startDate')</label><br>
                                    <span class="text-success" >{{ $task->start_date->format($global->date_format) }}</span><br>
                                </div>
                            @endif
                            <div class="col-xs-6 col-md-3 font-12">
                                <label class="font-12" for="">@lang('app.dueDate')</label><br>
                                <span @if($task->due_date->isPast()) class="text-danger" @endif>
                                    {{ $task->due_date->format($global->date_format) }}
                                </span>
                                <span style="color: {{ $task->board_column->label_color }}" id="columnStatus"> {{ $task->board_column->column_name }}</span>
    
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-12 col-md-12 m-t-10">
                                <label class="font-bold">@lang('app.description')</label><br>
                                <div class="task-description m-t-10">
                                    {!! $task->description ?? __('messages.noDescriptionAdded') !!}
                                </div>
                            </div>
    
                        </div>

                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="profile1">
                    <div class="col-xs-12 m-b-10">
                        <a href="javascript:;"  data-task-id="{{ $task->id }}" class="add-sub-task"><i class="icon-plus"></i> @lang('app.add') @lang('modules.tasks.subTask')</a>
                    </div>
                    <div class="col-xs-12 m-t-5">
                        <h5><i class="ti-check-box"></i> @lang('modules.tasks.subTask')
                            @if (count($task->subtasks) > 0)
                                <span class="pull-right"><span class="donut" data-peity='{ "fill": ["#00c292", "#eeeeee"],    "innerRadius": 5, "radius": 8 }'>{{ count($task->completedSubtasks) }}/{{ count($task->subtasks) }}</span> <span class="text-muted font-12">{{ floor((count($task->completedSubtasks)/count($task->subtasks))*100) }}%</span></span>
                            @endif

                        </h5>

                        <ul class="list-group b-t" id="sub-task-list">
                            @foreach($task->subtasks as $subtask)
                                <li class="list-group-item row">
                                    <div class="col-xs-9">
                                        <div class="checkbox checkbox-success checkbox-circle task-checkbox">
                                            <input class="task-check" data-sub-task-id="{{ $subtask->id }}" id="checkbox{{ $subtask->id }}" type="checkbox"
                                                @if($subtask->status == 'complete') checked @endif>
                                            <label for="checkbox{{ $subtask->id }}">&nbsp;</label>
                                            <span>{{ ucfirst($subtask->title) }}</span>
                                        </div>
                                        @if($subtask->due_date)<span class="text-muted m-l-5"> - @lang('modules.invoices.due'): {{ $subtask->due_date->format($global->date_format) }}</span>@endif
                                    </div>

                                    <div class="col-xs-3 text-right">
                                        <a href="javascript:;" data-sub-task-id="{{ $subtask->id }}" class="edit-sub-task"><i class="fa fa-pencil"></i></a>&nbsp;
                                        <a href="javascript:;" data-sub-task-id="{{ $subtask->id }}" class="delete-sub-task"><i class="fa fa-trash"></i></a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="messages1">
                    <div class="col-xs-12">
                        <button href="javascript:;" id="show-dropzone"
                                class="btn btn-success btn-sm btn-outline  m-b-20"><i class="ti-upload"></i> @lang('modules.projects.uploadFile')</button>

                        <div class="row m-b-20 hide" id="file-dropzone">
                            <div class="col-xs-12">
                                <form action="{{ route('admin.task-files.store') }}" class="dropzone"
                                      id="file-upload-dropzone">
                                    {{ csrf_field() }}

                                    {!! Form::hidden('task_id', $task->id) !!}

                                    <input name="view" type="hidden" id="view" value="list">

                                    <div class="fallback">
                                        <input name="file" type="file" multiple/>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <ul class="list-group" id="files-list">
                            @forelse($task->files as $file)
                            <li class="list-group-item" id="task-file-{{ $file->id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        {{ $file->filename }}
                                    </div>
                                    <div class="col-md-3">
                                        <span>{{ $file->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="col-md-3">
                                            <a target="_blank" href="{{ $file->file_url }}"
                                               data-toggle="tooltip" data-original-title="View"
                                               class="btn btn-info btn-circle"><i
                                                        class="fa fa-search"></i></a>
                                        @if(is_null($file->external_link))
                                        <a href="{{ route('member.task-files.download', $file->id) }}"
                                           data-toggle="tooltip" data-original-title="Download"
                                           class="btn btn-inverse btn-circle"><i
                                                    class="fa fa-download"></i></a>
                                        @endif
            
                                        <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{ $file->id }}"
                                           data-pk="list" class="btn btn-danger btn-circle file-delete"><i class="fa fa-times"></i></a>
                                        
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

                <div role="tabpanel" class="tab-pane" id="timelogs1">
    
                    <div class="col-xs-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('app.employee')</th>
                                    <th>@lang('modules.employees.hoursLogged')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $item)
                                <tr>
                                    <td>
                                        <img src="{{ $item->image_url }}" width="35" height="35" class="img-circle">
                                        <span class="font-semi-bold">{{ ucwords($item->name) }}</span><br>
                                    </td>
                                    <td>
                                        @php
                                            $timeLog = intdiv($item->total_minutes, 60) . ' ' . __('app.hrs') . ' ';

                                            if (($item->total_minutes % 60) > 0) {
                                                $timeLog .= ($item->total_minutes % 60) . ' ' . __('app.mins');
                                            }
                                        @endphp 
                                        {{ $timeLog }} 
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2">
                                        @lang('messages.noRecordFound')
                                    </td>
                                    
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

    
                </div>

                <div role="tabpanel" class="tab-pane" id="settings1">


                    <div class="col-xs-12">
                        <h4>@lang('modules.tasks.comment')</h4>
                    </div>

                    <div class="col-xs-12" id="comment-container">
                        <div id="comment-list">
                            @forelse($task->comments as $comment)
                                <div class="row b-b m-b-5 font-12">
                                    <div class="col-xs-12 m-b-5">
                                        <span class="font-semi-bold">{{ ucwords($comment->user->name) }}</span> <span class="text-muted font-12">{{ ucfirst($comment->created_at->diffForHumans()) }}</span>
                                    </div>
                                    <div class="col-xs-10">
                                        {!! ucfirst($comment->comment)  !!}
                                    </div>
                                    @if($comment->user->id == $user->id || $user->can('delete_tasks'))
                                        <div class="col-xs-2 text-right">
                                            <a href="javascript:;" data-comment-id="{{ $comment->id }}" class="btn btn-xs  btn-outline btn-default" onclick="deleteComment('{{ $comment->id }}');return false;"><i class="fa fa-trash"></i> @lang('app.delete')</a>
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
                        <div class="col-xs-12">
                            <a href="javascript:;" id="submit-comment" class="btn btn-info btn-sm"><i class="fa fa-send"></i> @lang('app.submit')</a>
                        </div>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="notes1">
                    <div class="col-xs-12">
                        <h4>@lang('app.notes')</h4>
                    </div>
    
                    <div class="col-xs-12" id="note-container">
                        <div id="note-list">
                            @forelse($task->notes as $note)
                                <div class="row b-b m-b-5 font-12">
                                    <div class="col-xs-12 m-b-5">
                                        <span class="font-semi-bold">{{ ucwords($note->user->name) }}</span> <span class="text-muted font-12">{{ ucfirst($note->created_at->diffForHumans()) }}</span>
                                    </div>
                                    <div class="col-xs-10">
                                        {!! ucfirst($note->note)  !!}
                                    </div>

                                    @if ($note->user_id == $user->id)
                                    <div class="col-xs-2 text-right">
                                        <a href="javascript:;" data-comment-id="{{ $note->id }}" class="btn btn-xs  btn-outline btn-default" onclick="deleteNote('{{ $note->id }}');return false;"><i class="fa fa-trash"></i> @lang('app.delete')</a>
                                    </div>
                                    @endif
                                </div>
                            @empty
                                <div class="col-xs-12">
                                    @lang('messages.noNoteFound')
                                </div>
                            @endforelse
                        </div>
                    </div>
    
                    <div class="form-group" id="note-box">
                        <div class="col-xs-12 m-t-10">
                            <textarea name="note" id="task-note" class="summernote" placeholder="@lang('app.notes')"></textarea>
                        </div>
                        <div class="col-xs-12">
                            <a href="javascript:;" id="submit-note" class="btn btn-info btn-sm"><i class="fa fa-send"></i> @lang('app.submit')</a>
                        </div>
                    </div>
    
                </div>


                <div role="tabpanel" class="tab-pane" id="history1">
                    <div class="col-xs-12" id="task-history-section">
                    </div>
                </div>

            </div>



        </div>

        <div class="col-xs-6 col-md-3 hidden-xs hidden-sm">

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

                @if ($task->estimate_hours > 0 || $task->estimate_minutes > 0)
                    <div class="col-xs-12 ">
                        <label class="font-12" for="">@lang('app.estimate')</label><br>
                       
                        <span>
                            {{ $task->estimate_hours }} @lang('app.hrs') 
                            {{ $task->estimate_minutes }} @lang('app.mins')
                        </span>
                        <hr>
                    </div>

                    <div class="col-xs-12 ">
                        <label class="font-12" for="">@lang('modules.employees.hoursLogged')</label><br>
                        <span>
                            @php
                                $timeLog = intdiv($task->timeLogged->sum('total_minutes'), 60) . ' ' . __('app.hrs') . ' ';

                                if (($task->timeLogged->sum('total_minutes') % 60) > 0) {
                                    $timeLog .= ($task->timeLogged->sum('total_minutes') % 60) . ' ' . __('app.mins');
                                }
                            @endphp 
                            <span @if ($task->total_estimated_minutes < $task->timeLogged->sum('total_minutes')) class="text-danger font-semi-bold" @endif>
                                {{ $timeLog }}
                            </span>
                        </span>
                        <hr>
                    </div>
                @else
                    <div class="col-xs-12 ">
                        <label class="font-12" for="">@lang('modules.employees.hoursLogged')</label><br>
                        <span>
                            @php
                                $timeLog = intdiv($task->timeLogged->sum('total_minutes'), 60) . ' ' . __('app.hrs') . ' ';

                                if (($task->timeLogged->sum('total_minutes') % 60) > 0) {
                                    $timeLog .= ($task->timeLogged->sum('total_minutes') % 60) . ' ' . __('app.mins');
                                }
                            @endphp 
                            <span>
                                {{ $timeLog }}
                            </span>
                        </span>
                        <hr>
                    </div>
                @endif

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



<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/peity/jquery.peity.init.js') }}"></script>
<script src="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.js') }}"></script>

<script>
    var myDropzone;
    $('body').on('click', '.edit-sub-task', function () {
        var id = $(this).data('sub-task-id');
        var url = '{{ route('member.sub-task.edit', ':id')}}';
        url = url.replace(':id', id);

        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    });

    $('body').on('click', '.add-sub-task', function () {
        console.log('add-sub-task');
        var id = $(this).data('task-id');
        var url = '{{ route('member.sub-task.create')}}?task_id='+id;

        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    });

    $(function() {
        Dropzone.autoDiscover = false;
        //Dropzone class
        myDropzone = new Dropzone("#file-upload-dropzone", {
            url: "{{ route('member.task-files.store') }}",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            paramName: "file",
            maxFilesize: 10,
            maxFiles: 10,
            acceptedFiles: "image/*,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/docx,application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            uploadMultiple: true,
            addRemoveLinks:true,
            parallelUploads:10,
            init: function () {
                this.on("success", function (file, response) {

                    if(response.status == 'fail') {
                        $.showToastr(response.message, 'error');
                        return;
                    }

                    $('#totalUploadedFiles').html(response.totalFiles);
                    $('#files-list').html(response.html);

                })
            }
        });
    });
    $('#show-dropzone').click(function () {
        $('#file-dropzone').toggleClass('hide show');
        myDropzone.removeAllFiles();

    });
    $('#reminderButton').click(function () {
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.sendReminder')",
            dangerMode: true,
            icon: 'warning',
            buttons: {
                cancel: "@lang('messages.confirmNoArchive')",
                confirm: {
                    text: "@lang('messages.confirmSend')",
                    value: true,
                    visible: true,
                    className: "danger",
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {

                var url = '{{ route('member.all-tasks.reminder', $task->id)}}';

                $.easyAjax({
                    type: 'GET',
                    url: url,
                    success: function (response) {
                        //
                    }
                });
            }
        });
    })

    function saveSubTask() {
        $.easyAjax({
            url: '{{route('member.sub-task.store')}}',
            container: '#createSubTask',
            type: "POST",
            data: $('#createSubTask').serialize(),
            success: function (response) {
                $('#subTaskModal').modal('hide');
                $('#sub-task-list').html(response.view)
            }
        })
    }

    function updateSubTask(id) {
        var url = '{{ route('member.sub-task.update', ':id')}}';
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            container: '#createSubTask',
            type: "POST",
            data: $('#createSubTask').serialize(),
            success: function (response) {
                $('#subTaskModal').modal('hide');
                $('#sub-task-list').html(response.view)
            }
        })
    }

    $('#view-task-history').click(function () {
        var id = $(this).data('task-id');

        var url = '{{ route('member.all-tasks.history', ':id')}}';
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            type: "GET",
            success: function (response) {
                // $('#task-detail-section').hide();
                $('#task-history-section').html(response.view);
                // $('#view-task-history').hide();
                // $('.close-task-history').show();
            }
        })

    })

    $('.close-task-history').click(function () {
        $('#task-detail-section').show();
        $('#task-history-section').html('');
        $(this).hide();
        $('#view-task-history').show();

    })

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


    $('body').on('click', '.delete-sub-task', function () {
        var id = $(this).data('sub-task-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.deleteSubtaskText')",
            dangerMode: true,
            icon: 'warning',
            buttons: {
                cancel: "@lang('messages.confirmNoArchive')",
                confirm: {
                    text: "@lang('messages.completeIt')",
                    value: true,
                    visible: true,
                    className: "danger",
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('member.sub-task.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $('#sub-task-list').html(response.view);
                        }
                    }
                });
            }
        });
    });

    //    change sub task status
    $('#sub-task-list').on('click', '.task-check', function () {
        if ($(this).is(':checked')) {
            var status = 'complete';
        }else{
            var status = 'incomplete';
        }

        var id = $(this).data('sub-task-id');
        var url = "{{route('member.sub-task.changeStatus')}}";
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            url: url,
            type: "POST",
            data: {'_token': token, subTaskId: id, status: status},
            success: function (response) {
                if (response.status == "success") {
                    $('#sub-task-list').html(response.view);
                }
            }
        })
    });

    $('#uploadedFiles').click(function () {

        var url = '{{ route("member.all-tasks.show-files", ':id') }}';

        var id = {{ $task->id }};
        url = url.replace(':id', id);

        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    });

    $('#submit-comment').click(function () {
        var comment = $('#task-comment').val();
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            url: '{{ route("member.task-comment.store") }}',
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
    });


    function deleteComment(id) {

        var commentId = id;
        var token = '{{ csrf_token() }}';

        var url = '{{ route("member.task-comment.destroy", ':id') }}';
        url = url.replace(':id', commentId);

        $.easyAjax({
            url: url,
            type: "POST",
            data: {'_token': token, '_method': 'DELETE', commentId: commentId},
            success: function (response) {
                if (response.status == "success") {
                    $('#comment-list').html(response.view);
                }
            }
        })
    }

    $('#submit-note').click(function () {
        var note = $('#task-note').val();
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            url: '{{ route("member.task-note.store") }}',
            type: "POST",
            data: {'_token': token, note: note, taskId: '{{ $task->id }}'},
            success: function (response) {
                if (response.status == "success") {
                    $('#note-list').html(response.view);
                    $('.summernote').summernote("reset");
                    $('.note-editable').html('');
                    $('#task-note').val('');
                }
            }
        })
    })

    function deleteNote (id) {

        var url = '{{ route("member.task-note.destroy", ':id') }}';
        url = url.replace(':id', id);

        $.easyAjax({
            url: url,
            type: "POST",
            data: {'_token': '{{ csrf_token() }}', '_method': 'DELETE'},
            success: function (response) {
                if (response.status == "success") {
                    $('#note-list').html(response.view);
                }
            }
        })
    }

    //    change task status
    function markComplete(status) {

        var id = {{ $task->id }};

        if(status == 'completed'){
            var checkUrl = '{{route('member.tasks.checkTask', ':id')}}';
            checkUrl = checkUrl.replace(':id', id);
            $.easyAjax({
                url: checkUrl,
                type: "GET",
                container: '#task-list-panel',
                data: {},
                success: function (data) {
                    if(data.taskCount > 0){
                        swal({
                            title: "@lang('messages.sweetAlertTitle')",
                            text: "@lang('messages.markCompleteTask')",
                            dangerMode: true,
                            icon: 'warning',
                            buttons: {
                                cancel: "@lang('messages.confirmNoArchive')",
                                confirm: {
                                    text: "@lang('messages.completeIt')",
                                    value: true,
                                    visible: true,
                                    className: "danger",
                                }
                            }
                        }).then(function (isConfirm) {
                            if (isConfirm) {
                                updateTask(id,status)
                            }
                        });
                    }
                    else{
                        updateTask(id,status)
                    }

                }
            });
        }
        else{
            updateTask(id,status)
        }
    }

    // Update Task
    function updateTask(id,status){
        var url = "{{route('member.tasks.changeStatus')}}";
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: url,
            type: "POST",
            container: '.r-panel-body',
            data: {'_token': token, taskId: id, status: status, sortBy: 'id'},
            async: false,
            success: function (data) {
                $('#columnStatus').css('color', data.textColor);
                $('#columnStatus').html(data.column);
                if(status == 'completed'){

                    $('#inCompletedButton').removeClass('hidden');
                    $('#completedButton').addClass('hidden');
                    if($('#reminderButton').length){
                        $('#reminderButton').addClass('hidden');
                    }
                }
                else{
                    $('#completedButton').removeClass('hidden');
                    $('#inCompletedButton').addClass('hidden');
                    if($('#reminderButton').length){
                        $('#reminderButton').removeClass('hidden');
                    }
                }

                if( typeof table !== 'undefined'){
                    table._fnDraw();
                }
                else{
                    if ($.fn.showTable) {
                        showTable();
                    }
                }
                refreshTask(id);
            }
        })
    }

    $('#start-task-timer').click(function () {
        var task_id = "{{ $task->id }}";
        var project_id = "{{ $task->project_id }}";
        var user_id = "{{ user()->id }}";
        var memo = "{{ $task->heading }}";
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            url: '{{route('member.time-log.store')}}',
            container: '#startTimer',
            type: "POST",
            data: {task_id: task_id, project_id: project_id, memo: memo, '_token': token, user_id: user_id},
            success: function (data) {
                refreshTask(task_id);
            }
        })
    });

    $('#stop-task-timer').click(function () {
        var id = $(this).data('time-id');
        var url = '{{route('member.all-time-logs.stopTimer', ':id')}}';
        url = url.replace(':id', id);
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            url: url,
            type: "POST",
            data: {timeId: id, _token: token},
            success: function (data) {
                refreshTask("{{ $task->id }}");
            }
        })
    });

    function refreshTask(taskId) {
        var id = taskId;
        var url = "{{ route('member.all-tasks.show',':id') }}";
        url = url.replace(':id', id);

        $.easyAjax({
            type: 'GET',
            url: url,
            success: function (response) {
                if (response.status == "success") {
                    $('#right-sidebar-content').html(response.view);
                }
            }
        });
    }

    $('body').on('click', '#pinnedItem', function(){
        var type = $('#pinnedItem').attr('data-pinned');
        var id = {{ $task->id }};
        var pinType = 'task';

        var dataPin = type.trim(type);
        if(dataPin == 'pinned'){
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.unpinTask')",
                dangerMode: true,
                icon: 'warning',
                buttons: {
                    cancel: "@lang('messages.confirmNoArchive')",
                    confirm: {
                        text: "@lang('messages.confirmUnpin')",
                        value: true,
                        visible: true,
                        className: "danger",
                    }
                }
            }).then(function (isConfirm) {
                if (isConfirm) {
                    var url = "{{ route('member.pinned.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE','type':pinType},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                $('.pin-icon').removeClass('pinned');
                                $('.pin-icon').addClass('unpinned');
                                $('#pinnedItem').attr('data-pinned','unpinned');
                                $('#pinnedItem').attr('data-original-title','Pin');
                                $("#pinnedItem").tooltip("hide");
                                table._fnDraw();
                            }
                        }
                    })
                }
            });
        }
        else {
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.pinTask')",
                dangerMode: true,
                icon: 'warning',
                buttons: {
                    cancel: "@lang('messages.confirmNoArchive')",
                    confirm: {
                        text: "@lang('app.yes')",
                        value: true,
                        visible: true,
                        className: "danger",
                    }
                }
            }).then(function (isConfirm) {
                if (isConfirm) {
                    var url = "{{ route('member.pinned.store') }}?type="+pinType;

                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token,'task_id':id},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                $('.pin-icon').removeClass('unpinned');
                                $('.pin-icon').addClass('pinned');
                                $('#pinnedItem').attr('data-pinned','pinned');
                                $('#pinnedItem').attr('data-original-title','Unpin');
                                $("#pinnedItem").tooltip("hide");
                                table._fnDraw();
                            }
                        }
                    });
                }
            });
        }
    });

</script>


<script>
    $('body').on('click', '.file-delete', function () {
        var id = $(this).data('file-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.removeFileText')",
            dangerMode: true,
            icon: 'warning',
            buttons: {
                cancel: "@lang('messages.confirmNoArchive')",
                confirm: {
                    text: "@lang('messages.confirmDelete')",
                    value: true,
                    visible: true,
                    className: "danger",
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('member.task-files.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $('#task-file-'+id).remove();
                            $('#totalUploadedFiles').html(response.totalFiles);
                            $('#list ul.list-group').html(response.html);
                        }
                    }
                });
            }
        });
    });

</script>

@if ($task->board_column->slug != 'completed' && !is_null($task->activeTimer))
    <script>

        $(document).ready(function(e) {
            var $worked = $("#active-task-timer");
            function updateTimer() {
                var myTime = $worked.html();
                var ss = myTime.split(":");

                var hours = ss[0];
                var mins = ss[1];
                var secs = ss[2];
                secs = parseInt(secs)+1;

                if(secs > 59){
                    secs = '00';
                    mins = parseInt(mins)+1;
                }

                if(mins > 59){
                    secs = '00';
                    mins = '00';
                    hours = parseInt(hours)+1;
                }

                if(hours.toString().length < 2) {
                    hours = '0'+hours;
                }
                if(mins.toString().length < 2) {
                    mins = '0'+mins;
                }
                if(secs.toString().length < 2) {
                    secs = '0'+secs;
                }
                var ts = hours+':'+mins+':'+secs;

                $worked.html(ts);
                setTimeout(updateTimer, 1000);
            }
            setTimeout(updateTimer, 1000);
        });

    </script>
@endif
