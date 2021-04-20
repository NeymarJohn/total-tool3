@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.all-tasks.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">
<style>
    .panel-black .panel-heading a, .panel-inverse .panel-heading a {
        color: unset!important;
    }

    .panel-black a, .panel-inverse a {
        color: #4c5667;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.tasks.newTask')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'storeTask','class'=>'ajax-form','method'=>'POST']) !!}

                        <div class="form-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.project')</label>
                                        <select class="select2 form-control" data-placeholder="@lang("app.selectProject")" id="project_id" name="project_id">
                                            <option value="all">--</option>
                                            @foreach($projects as $project)
                                                <option
                                                        value="{{ $project->id }}">{{ ucwords($project->project_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.tasks.taskCategory')
                                            <a href="javascript:;"
                                               id="createTaskCategory"
                                               class="btn btn-xs btn-outline btn-success">
                                                <i class="fa fa-plus"></i> @lang('modules.taskCategory.addTaskCategory')
                                            </a>
                                        </label>
                                        <select class="select2 form-control" name="category_id" id="category_id"
                                                data-style="form-control">
                                            @forelse($categories as $category)
                                                <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noTaskCategoryAdded')</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('app.title')</label>
                                        <input type="text" id="heading" name="heading" class="form-control" >
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.description')</label>
                                        <textarea id="description" name="description" class="form-control summernote"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">

                                        <div class="checkbox checkbox-info">
                                            <input id="dependent-task" name="dependent" value="yes"
                                                   type="checkbox">
                                            <label for="dependent-task">@lang('modules.tasks.dependent')</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="dependent-fields" style="display: none">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">@lang('modules.tasks.dependentTask')</label>
                                            <select class="select2 form-control" data-placeholder="@lang('modules.tasks.chooseTask')" name="dependent_task_id" id="dependent_task_id" >
                                                <option value=""></option>
                                                @foreach($allTasks as $allTask)
                                                    <option value="{{ $allTask->id }}">{{ $allTask->heading }} (@lang('app.dueDate'): {{ $allTask->due_date->format($global->date_format) }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label required">@lang('app.startDate')</label>
                                                <input type="text" name="start_date" id="start_date2" class="form-control" value="{{ \Carbon\Carbon::now($global->timezone)->format($global->date_format)  }}" autocomplete="off">
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <!--/span-->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label required">@lang('app.dueDate')</label>
                                                <input type="text" name="due_date" id="due_date2" class="form-control"
                                                       autocomplete="off">
                                            </div>
                                        </div>
    
                                        <!--/span-->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label required">@lang('modules.tasks.assignTo')</label>
                                                <a href="javascript:;" id="add-employee" class="btn btn-xs btn-success btn-outline"><i class="fa fa-plus"></i></a>
                                                <select class="select2 select2-multiple " multiple="multiple"
                                                        data-placeholder="@lang('modules.tasks.chooseAssignee')"
                                                        name="user_id[]" id="user_id">
                                                    <option value=""></option>
                                                    @foreach($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                                    @endforeach
                                                </select>
    
                                            </div>
                                        </div>
                                        <div class="col-md-3 m-t-25" id="assign-self-block">
                                            <div class="form-group">
                                                <a href="javascript:;" style="color:#1b69b6" class="text-info" id="assign-self">@lang('modules.tasks.assignMe')</a>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">@lang('app.label')
                                                    <a href="javascript:;"
                                                       id="createTaskLabel"
                                                       class="btn btn-xs btn-outline btn-success">
                                                        <i class="fa fa-plus"></i> @lang('app.add') @lang('app.menu.taskLabel')
                                                    </a>
                                                </label>
                                                <select id="multiselect" name="task_labels[]"  multiple="multiple" class="selectpicker form-control">
                                                    @foreach($taskLabels as $label)
                                                        <option data-content="<label class='badge b-all' style='background:{{ $label->label_color }};'>{{ $label->label_name }}</label> " value="{{ $label->id }}">{{ $label->label_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
    
    
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
    
                                                <div class="checkbox checkbox-info">
                                                    <input id="private-task" name="is_private" value="true" type="checkbox">
                                                    <label for="private-task">@lang('modules.tasks.makePrivate') 
                                                        <a class="mytooltip font-12" href="javascript:void(0)"> 
                                                        <i class="fa fa-info-circle"></i>
                                                        <span class="tooltip-content5">
                                                            <span class="tooltip-text3">
                                                                <span class="tooltip-inner2">@lang('modules.tasks.privateInfo')</span>
                                                            </span>
                                                        </span>
                                                        </a>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="checkbox checkbox-info">
                                                    <input id="billable-task" checked name="billable" value="true" type="checkbox">
                                                    <label for="billable-task">@lang('modules.tasks.billable') 
                                                        <a class="mytooltip font-12" href="javascript:void(0)"> 
                                                            <i class="fa fa-info-circle"></i>
                                                            <span class="tooltip-content5">
                                                                <span class="tooltip-text3">
                                                                    <span class="tooltip-inner2">@lang('modules.tasks.billableInfo')</span>
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="checkbox checkbox-info">
                                                    <input id="set-time-estimate" name="set_time_estimate" value="true" type="checkbox">
                                                    <label for="set-time-estimate">@lang('modules.tasks.setTimeEstimate')</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="set-time-estimate-fields" style="display: none">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    
                                                    <input type="number" min="0" value="0" class="w-50 p-5 p-10" name="estimate_hours" > @lang('app.hrs')
                                                    &nbsp;&nbsp;
                                                    <input type="number" min="0" value="0" name="estimate_minutes" class="w-50 p-5 p-10"> @lang('app.mins')
                                                </div>
                                            </div>
                                           
                                        </div>
    
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
        
                                                <div class="checkbox checkbox-info">
                                                    <input id="repeat-task" name="repeat" value="yes"
                                                           type="checkbox">
                                                    <label for="repeat-task">@lang('modules.events.repeat')</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="repeat-fields" style="display: none">
                                            <div class="col-md-3 ">
                                                <div class="form-group">
                                                    <label>@lang('modules.events.repeatEvery')</label>
                                                    <input type="number" min="1" value="1" name="repeat_count" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <select name="repeat_type" id="" class="form-control">
                                                        <option value="day">@lang('app.day')</option>
                                                        <option value="week">@lang('app.week')</option>
                                                        <option value="month">@lang('app.month')</option>
                                                        <option value="year">@lang('app.year')</option>
                                                    </select>
                                                </div>
                                            </div>
        
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('modules.events.cycles') <a class="mytooltip" href="javascript:void(0)"> <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">@lang('modules.tasks.cyclesToolTip')</span></span></span></a></label>
                                                    <input type="number" name="repeat_cycles" id="repeat_cycles" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
    
                                  
    
                                    @if(count($fields) > 0)
                                    <h3 class="box-title">@lang('modules.projects.otherInfo')</h3>
                                        <div class="row">
                                            @foreach($fields as $field)
                                                <div class="col-md-3">
                                                    <label>{{ ucfirst($field->label) }}</label>
                                                    <div class="form-group">
                                                        @if( $field->type == 'text')
                                                            <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                        @elseif($field->type == 'password')
                                                            <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                        @elseif($field->type == 'number')
                                                            <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
    
                                                        @elseif($field->type == 'textarea')
                                                            <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" id="{{$field->name}}" cols="3">{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>
    
                                                        @elseif($field->type == 'radio')
                                                            <div class="radio-list">
                                                                @foreach($field->values as $key=>$value)
                                                                    <label class="radio-inline @if($key == 0) p-0 @endif">
                                                                        <div class="radio radio-info">
                                                                            <input type="radio" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" id="optionsRadios{{$key.$field->id}}" value="{{$value}}" @if(isset($editUser) && $editUser->custom_fields_data['field_'.$field->id] == $value) checked @elseif($key==0) checked @endif>>
                                                                            <label for="optionsRadios{{$key.$field->id}}">{{$value}}</label>
                                                                        </div>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        @elseif($field->type == 'select')
                                                            {!! Form::select('custom_fields_data['.$field->name.'_'.$field->id.']',
                                                                    $field->values,
                                                                    isset($editUser)?$editUser->custom_fields_data['field_'.$field->id]:'',['class' => 'form-control gender'])
                                                            !!}
    
                                                        @elseif($field->type == 'checkbox')
                                                            <div class="mt-checkbox-inline">
                                                                @foreach($field->values as $key => $value)
                                                                    <label class="mt-checkbox mt-checkbox-outline">
                                                                        <input name="custom_fields_data[{{$field->name.'_'.$field->id}}][]" type="checkbox" value="{{$key}}"> {{$value}}
                                                                        <span></span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        @elseif($field->type == 'date')
                                                            <input type="text" class="form-control date-picker" size="16" name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                                value="{{ isset($editUser->dob)?Carbon\Carbon::parse($editUser->dob)->format('Y-m-d'):Carbon\Carbon::now()->format($global->date_format)}}">
                                                        @endif
                                                        <div class="form-control-focus"> </div>
                                                        <span class="help-block"></span>
    
                                                    </div>
                                                </div>
                                            @endforeach
    
                                        </div>
                                    @endif
    
                                    <!--/span-->
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="" class="control-label">@lang('app.status')</label>
                                                <select name="board_column_id" id="board_column_id" class="form-control">
                                                    @foreach ($taskboardColumns as $item)
                                                        <option value="{{ $item->id }}">{{ $item->column_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="control-label">@lang('modules.tasks.priority')</label>

                                                <div class="radio-list">
                                                    <label class="radio-inline p-0">
                                                        <div class="radio radio-danger">
                                                            <input type="radio" name="priority" id="radio13"
                                                                value="high">
                                                            <label for="radio13" class="text-danger">
                                                                @lang('modules.tasks.high') </label>
                                                        </div>
                                                    </label>
                                                    <label class="radio-inline">
                                                        <div class="radio radio-warning">
                                                            <input type="radio" name="priority"
                                                                id="radio14" checked value="medium">
                                                            <label for="radio14" class="text-warning">
                                                                @lang('modules.tasks.medium') </label>
                                                        </div>
                                                    </label>
                                                    <label class="radio-inline">
                                                        <div class="radio radio-success">
                                                            <input type="radio" name="priority" id="radio15"
                                                                value="low">
                                                            <label for="radio15" class="text-success">
                                                                @lang('modules.tasks.low') </label>
                                                        </div>
                                                    </label>
                                                </div>
    
                                                
                                               
                                               
                                            </div>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="row m-b-20">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-block btn-outline-info btn-sm col-md-2 select-image-button" style="margin-bottom: 10px;display: none "><i class="fa fa-upload"></i> File Select Or Upload</button>
                                            <div id="file-upload-box" >
                                                <div class="row" id="file-dropzone">
                                                    <div class="col-md-12">
                                                        <div class="dropzone"
                                                             id="file-upload-dropzone">
                                                            {{ csrf_field() }}
                                                            <div class="fallback">
                                                                <input name="file" type="file" multiple/>
                                                            </div>
                                                            <input name="image_url" id="image_url"type="hidden" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="taskID" id="taskID">
                                            <input type="hidden" name="addedFiles" id="addedFiles">
                                        </div>
                                    </div>
    
                                </div>
                                
                            </div>
                            <!--/row-->

                        </div>
                        <div class="form-actions">
                            <button type="button" id="store-task" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="taskCategoryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->.
    </div>
    {{--Ajax Modal Ends--}}
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-lg in" id="taskLabelModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->.
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.js') }}"></script>

<script>
//    $('#multiselect').selectpicker();
    projectID = '';
    Dropzone.autoDiscover = false;
    //Dropzone class
    myDropzone = new Dropzone("div#file-upload-dropzone", {
        url: "{{ route('admin.task-files.store') }}",
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        paramName: "file",
        maxFilesize: 10,
        maxFiles: 10,
        //  acceptedFiles: "image/*,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/docx,application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        autoProcessQueue: false,
        uploadMultiple: true,
        addRemoveLinks:true,
        parallelUploads:10,
        init: function () {
            myDropzone = this;
        }
    });

    myDropzone.on('sending', function(file, xhr, formData) {
        console.log(myDropzone.getAddedFiles().length,'sending');
        var ids = $('#taskID').val();
        formData.append('task_id', ids);
    });

    myDropzone.on('completemultiple', function () {
        var msgs = "@lang('messages.taskCreatedSuccessfully')";
        $.showToastr(msgs, 'success');
        window.location.href = '{{ route('admin.all-tasks.index') }}'

    });

    $('#assign-self').click(function () {
        $('#user_id').val('{{$user->id}}');
        $('#user_id').select2().trigger('change');
        $('#assign-self-block').remove();
    });
    //    update task
    $('#store-task').click(function () {
        $('#addedFiles').val(myDropzone.getQueuedFiles().length);
        $.easyAjax({
            url: '{{route('admin.all-tasks.store')}}',
            container: '#storeTask',
            type: "POST",
            data: $('#storeTask').serialize(),
            success: function(response){
                if(myDropzone.getQueuedFiles().length > 0){
                    taskID = response.taskID;
                    $('#taskID').val(response.taskID);
                    myDropzone.processQueue();
                }
                else{
                    var msgs = "@lang('messages.taskCreatedSuccessfully')";
                    $.showToastr(msgs, 'success');
                    window.location.href = '{{ route('admin.all-tasks.index') }}'
                }

            }
        })
    });

    $('#due_date2').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true
    });
    var minDate = new Date();
    $('#due_date2').datepicker("update", minDate);
    $('#due_date2').datepicker('setStartDate', minDate);

    jQuery('#start_date2').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true
    }).on('changeDate', function (selected) {

        var minDate = new Date(selected.date.valueOf());
        $('#due_date2').datepicker("update", minDate);
        $('#due_date2').datepicker('setStartDate', minDate);
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $(".date-picker").datepicker({
        todayHighlight: true,
        autoclose: true,
        format: '{{ $global->date_picker_format }}'
    });

    $('#project_id').change(function () {
        var id = $(this).val();
        var url = '{{route('admin.all-tasks.members', ':id')}}';
        url = url.replace(':id', id);

        $.easyAjax({
            url: url,
            type: "GET",
            redirect: true,
            success: function (data) {
                $('#user_id').html(data.html);
                $('#user_id').val(null).trigger('change.select2');
            }
        });

        // For getting dependent task
        var dependentTaskUrl = '{{route('admin.all-tasks.dependent-tasks', ':id')}}';
        dependentTaskUrl = dependentTaskUrl.replace(':id', id);
        $.easyAjax({
            url: dependentTaskUrl,
            type: "GET",
            success: function (data) {
                $('#dependent_task_id').html(data.html);
            }
        })
    });

    $('.summernote').summernote({
        height: 200,                 // set editor height
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

    $('#repeat-task').change(function () {
        if($(this).is(':checked')){
            $('#repeat-fields').show();
        }
        else{
            $('#repeat-fields').hide();
        }
    })

    $('#set-time-estimate').change(function () {
        if($(this).is(':checked')){
            $('#set-time-estimate-fields').show();
        }
        else{
            $('#set-time-estimate-fields').hide();
        }
    })

    $('#dependent-task').change(function () {
        if($(this).is(':checked')){
            $('#dependent-fields').show();
        }
        else{
            $('#dependent-fields').hide();
        }
    })

</script>
<script>
    $('#createTaskCategory').click(function(){
        var url = '{{ route('admin.taskCategory.create-cat')}}';
        $('#modelHeading').html("@lang('modules.taskCategory.manageTaskCategory')");
        $.ajaxModal('#taskCategoryModal', url);
    })

    $('#createTaskLabel').click(function(){
        var url = '{{ route('admin.task-label.create-label')}}';
        $('#modelHeading').html("");
        $.ajaxModal('#taskLabelModal', url);
    })

    $('#add-employee').click(function () {
        var url = '{{ route('admin.employees.create')}}';
        $('#modelHeading').html("@lang('app.add') @lang('app.employee')");
        $.ajaxModal('#projectTimerModal', url);
    });

</script>
@endpush

