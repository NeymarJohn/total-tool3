<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $superadmin->favicon_url }}">
    {{--<link rel="manifest" href="{{ asset('favicon/manifest.json') }}">--}}
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ $superadmin->favicon_url }}">
    <meta name="theme-color" content="#ffffff">

    <title>{{ $task->heading }}</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css'>
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css'>

    <!-- This is Sidebar menu CSS -->
    <link href="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">

    <link href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}"   rel="stylesheet">
    <link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}"   rel="stylesheet">

    <!-- This is a Animation CSS -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">

@stack('head-script')

<!-- This is a Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- color CSS you can use different color css from css/colors folder -->
    <!-- We have chosen the skin-blue (default.css) for this starter
       page. However, you can choose any other skin from folder css / colors .
       -->
    <link href="{{ asset('css/colors/default.css') }}" id="theme"  rel="stylesheet">
    <link href="{{ asset('plugins/froiden-helper/helper.css') }}"   rel="stylesheet">
    <link href="{{ asset('css/custom-new.css') }}"   rel="stylesheet">
    <link href="{{ asset('css/rounded.css') }}" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .admin-logo {
            max-height: 40px;
        }

        .right-sidebar {
            position: unset;
            background: #fff;
            top: 0;
            height: 100%;
            box-shadow: 5px 1px 40px rgba(0,0,0,.1);
            transition: all .3s ease;
            display: block;
            width: 100%;
        }

        .nav>li>a {
           padding: 10px 7px;
        }
    </style>
</head>
<body class="fix-sidebar">
<!-- Preloader -->
<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>
<div id="wrapper">

<!-- Left navbar-header end -->
    <!-- Page Content -->
    <div id="page-wrapper" style="margin-left: 0px !important;">
        <div class="container-fluid">

        <!-- .row -->
            <div class="row">
                <div class="col-md-offset-2 col-md-8 m-t-40 m-b-40">
                </div>

                <div class="col-md-offset-2 col-md-8 col-md-offset-2">
                    <div class="card">
                        <div class="card-body right-sidebar">
           
                            <div class="rpanel-title"> @lang('app.task') # {{ $task->id }}</div>
                            <div class="r-panel-body">

                                <div class="row">
                                    <div class="col-xs-12 m-b-10">
                                        <label class="label" style="background-color: {{ $task->board_column->label_color }}">{{ $task->board_column->column_name }}</label>
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
                                        <li role="presentation" class=""><a href="#messages1" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false">@lang('app.file') ({{ sizeof($task->files) }})</a></li>
                                        <li role="presentation" class=""><a href="#settings1" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false">@lang('modules.tasks.comment') ({{ count($task->comments) }})</a></li>
                                        
                                    </ul>

                                    <div class="tab-content" id="task-detail-section">
                                        <div role="tabpanel" class="tab-pane fade active in" id="home1">
                                            <div class="col-xs-12" id="task-detail-section">
                                                <div class="col-xs-6 col-md-3 font-12 m-t-10">
                                                    <label class="font-12" for="">@lang('modules.tasks.assignTo')</label><br>
                                                    @foreach ($task->users as $item)
                                                        <img src="{{ $item->image_url }}" data-toggle="tooltip" data-original-title="{{ ucwords($item->name) }}" data-placement="right" class="img-circle" width="25" height="25" alt="">
                                                    @endforeach
                                                </div>
                                                @if($task->create_by)
                                                    <div class="col-xs-6 col-md-3 font-12 m-t-10">
                                                        <label class="font-12" for="">@lang('modules.tasks.assignBy')</label><br>
                                                        <img src="{{ $task->create_by->image_url }}" class="img-circle" width="25" height="25" alt="">

                                                        {{ ucwords($task->create_by->name) }}
                                                    </div>
                                                @endif

                                                @if($task->start_date)
                                                    <div class="col-xs-6 col-md-3 font-12 m-t-10">
                                                        <label class="font-12" for="">@lang('app.startDate')</label><br>
                                                        <span class="text-success" >{{ $task->start_date->format($global->date_format) }}</span><br>
                                                    </div>
                                                @endif
                                                <div class="col-xs-6 col-md-3 font-12 m-t-10">
                                                    <label class="font-12" for="">@lang('app.dueDate')</label><br>
                                                    <span @if($task->due_date->isPast()) class="text-danger" @endif>
                                                        {{ $task->due_date->format($global->date_format) }}
                                                    </span>
                                                    <span style="color: {{ $task->board_column->label_color }}" id="columnStatus"> {{ $task->board_column->column_name }}</span>

                                                </div>
                                                <div class="col-xs-12 task-description b-all p-10 m-t-20">
                                                    {!! ucfirst($task->description) !!}
                                                </div>


                                            </div>
                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="profile1">
                                            <div class="col-xs-12 m-t-5">
                                                <h5><i class="ti-check-box"></i> @lang('modules.tasks.subTask')
                                                    @if (count($task->subtasks) > 0)
                                                        <span class="pull-right"><span class="donut" data-peity='{ "fill": ["#00c292", "#eeeeee"],    "innerRadius": 5, "radius": 8 }'>{{ count($task->completedSubtasks) }}/{{ count($task->subtasks) }}</span> <span class="text-muted font-12">{{ floor((count($task->completedSubtasks)/count($task->subtasks))*100) }}%</span></span>
                                                    @endif
                                                </h5>
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
                                                <a href="javascript:;" id="uploadedFiles" class="btn btn-primary btn-sm btn-rounded btn-outline"><i class="fa fa-image"></i> @lang('modules.tasks.uplodedFiles') (<span id="totalUploadedFiles">{{ sizeof($task->files) }}</span>) </a>
                                            </div>
                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="settings1">
                                            <div class="col-xs-12 m-t-15 b-b">
                                                <h5>@lang('modules.tasks.comment')</h5>
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
                                    </div>



                                    <div class="col-xs-12" id="task-history-section">
                                    </div>


                                </div>

                            </div>

                            

                   
                        </div>
                    </div>
                </div>


            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->

{{--Ajax Modal--}}
<div class="modal fade bs-modal-md in" id="subTaskModal" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" id="modal-data-application">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <span class="caption-subject font-red-sunglo bold uppercase" id="subTaskModelHeading">Sub Task e</span>
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

<!-- jQuery -->
<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js'></script>

<!-- Sidebar menu plugin JavaScript -->
<script src="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>
<!--Slimscroll JavaScript For custom scroll-->
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('js/waves.js') }}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{ asset('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
<script src="{{ asset('plugins/froiden-helper/helper.js') }}"></script>
<script src="{{ asset('plugins/bower_components/toast-master/js/jquery.toast.js') }}"></script>

{{--sticky note script--}}
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bower_components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/icheck/icheck.init.js') }}"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('plugins/bower_components/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/peity/jquery.peity.init.js') }}"></script>
<script>


    $('.close-task-history').click(function () {
        $('#task-detail-section').show();
        $('#task-history-section').html('');
        $(this).hide();
        $('#view-task-history').show();
    })

    $('#uploadedFiles').click(function () {

        var url = '{{ route("front.task-files", ':id') }}';

        var id = {{ $task->id }};
        url = url.replace(':id', id);

        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    });
</script>

</body>
</html>
