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

    <title>@lang('modules.projects.viewGanttChart')</title>
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
    <link href="{{ asset('css/custom.css') }}"   rel="stylesheet">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="{{ asset('plugins/frappe/frappe-gantt.css') }}">

    <style>
        .sidebar .notify  {
            margin: 0 !important;
        }
        .sidebar .notify .heartbit {
            top: -23px !important;
            right: -15px !important;
        }
        .sidebar .notify .point {
            top: -13px !important;
        }
        .gantt .handle {
  display: none;
}
        .logo img {
            max-height: 60px;
            display: inline-block;
            line-height: 90px;
            text-align: center;
            margin: 15px 0;
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
            <div class="row logo text-center" style="margin-top: 40px; !important;">
                <img src="{{ $global->logo_url }}" class="logo-default" alt="home" />
            </div>
            <hr>
            <div class="row" >
                <div class="col-md-offset-1 col-md-10 col-md-offset-1">
                    <h2>{{ $project->project_name }} @lang('modules.projects.viewGanttChart')</h2>
                    <div class="row m-t-20">
                        <div class="col-md-4">
                        <!-- ASSIGN START -->
                        <div class="select-box py-2 px-lg-3 px-md-3 px-0">
                            <h5>@lang('app.view')</h5>
                            <div class="form-group">
                                <select class="form-control select2" id="gantt-view" data-size="8">
                                    <option value="Day">@lang('app.day')</option>
                                    <option value="Week">@lang('app.week')</option>
                                    <option value="Month">@lang('app.month')</option>
                                </select>
                            </div>
                        </div>
                        <!-- ASSIGN END -->
                        </div>
                    </div>
                    <div id="gantt"></div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->

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

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/frappe/frappe-gantt.js') }}"></script>

<script type="text/javascript">

    

    function loadData() {
            var projectID = "{{ $project->id }}";
            var assignedTo = $('#assignedTo').val();
            var viewMode = $('#gantt-view').val();
            var token = "{{ csrf_token() }}";

            var url = '{{ route("front.gantt-data", $ganttProjectId) }}';

            $.easyAjax({
                url: url,
                blockUI: true,
                container: '.content-wrapper',
                success: function(response) {
                    if (!response.length) {
                        $("#gantt").html(
                            "<div class='d-flex justify-content-center p-20'>{{ __('messages.noRecordFound') }}</div>"
                        );
                        return;
                    }

                    $("#gantt").html("");

                    var gantt = new Gantt("#gantt", response, {
                        popup_trigger: "mouseover",
                        view_mode: viewMode,
                        on_click: function(task) {
                            refresh(task);
                            // console.log(task);
                        },
                        on_date_change: function(task, start, end) {
                            refresh(task);
                            // console.log(task, start, end);
                        },
                        on_progress_change: function(task, progress) {
                            refresh(task);
                            // console.log(task, progress);
                        },
                        on_view_change: function(mode) {
                            refresh(task);
                            // console.log(mode);
                        }
                    });

                }
            });
        }

        $('#gantt-view').on('change keyup', function() {
            loadData();
        });

        loadData();

</script>

</body>
</html>