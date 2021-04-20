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
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <title>@lang('modules.tasks.taskBoard')</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css'>
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css'>

    <!-- This is Sidebar menu CSS -->
    <link href="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">

    <link href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet">

    <!-- This is a Animation CSS -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">

@stack('head-script')

<!-- This is a Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- color CSS you can use different color css from css/colors folder -->
    <!-- We have chosen the skin-blue (default.css) for this starter
       page. However, you can choose any other skin from folder css / colors .
       -->
    <link href="{{ asset('css/colors/default.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('plugins/froiden-helper/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-new.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('plugins/bower_components/lobipanel/dist/css/lobipanel.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">

    @if($global->rounded_theme)
        <link href="{{ asset('css/rounded.css') }}" rel="stylesheet">
    @endif

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


    {{--Custom theme styles end--}}

    <style>
        .sidebar .notify {
            margin: 0 !important;
        }

        .sidebar .notify .heartbit {
            top: -23px !important;
            right: -15px !important;
        }

        .sidebar .notify .point {
            top: -13px !important;
        }

        .admin-logo {
            max-height: 40px;
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
            <div class="row m-l-50 m-t-20">
                <div class="col-md-1"></div>
                <div class="col-md-3 text-center">
                    <img src="{{ $global->logo_url }}" alt="home" class="admin-logo" />
                </div>
                <div class="col-md-3 text-center"><h2 class="">@lang('modules.tasks.taskBoard')</h2></div>
                <div class="col-md-4 text-right">


                        <h2>
                            {{-- <a href="javascript:" id="toggle_fullscreen"
                               class="btn btn-inverse btn-outline btn-sm pull-right m-l-10"><i
                                        class="icon-size-fullscreen"></i>
                            </a> --}}

                            <a href="javascript:" id="toggle-filter"
                               class="btn btn-sm btn-inverse btn-outline toggle-filter pull-right m-l-10"><i
                                        class="fa fa-sliders"></i>  @lang('app.filterResults')
                                </a>
                            </h2>
                        <h5 id="filter-result" class="p-t-10 m-r-10 p-r-10"></h5> &nbsp;
                </div>
                <div class="col-md-1"></div>

                
            </div>
            <div class="row">

                <div class="col-md-offset-1 col-md-10 col-md-offset-1">

                    <div class="row" style="display: none;" id="ticket-filters">


                        <form action="" id="filter-form">
                            <div class="col-md-3 m-t-30">
                                <div class="input-daterange input-group m-t-5" id="date-range">
                                    <input type="text" class="form-control" id="start-date"
                                           placeholder="@lang('app.startDate')"
                                           value="{{ \Carbon\Carbon::now()->subDays(15)->format($global->date_format) }}"/>
                                    <span class="input-group-addon bg-info b-0 text-white">@lang('app.to')</span>
                                    <input type="text" class="form-control" id="end-date"
                                           placeholder="@lang('app.endDate')"
                                           value="{{ \Carbon\Carbon::now()->addDays(15)->format($global->date_format) }}"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5>@lang('app.selectProject')</h5>
                                <div class="form-group">
                                    <select class="select2 form-control" data-placeholder="@lang('app.selectProject')"
                                            id="project_id">
                                        <option value="all">@lang('app.all')</option>
                                        @foreach($projects as $project)
                                            <option
                                                    value="{{ $project->id }}">{{ ucwords($project->project_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5>@lang('app.select') @lang('app.client')</h5>

                                <div class="form-group">
                                    <select class="select2 form-control" data-placeholder="@lang('app.client')"
                                            id="clientID">
                                        <option value="all">@lang('app.all')</option>
                                        @foreach($clients as $client)
                                            <option
                                                    value="{{ $client->id }}">{{ ucwords($client->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5>@lang('app.select') @lang('modules.tasks.assignTo')</h5>

                                <div class="form-group">
                                    <select class="select2 form-control"
                                            data-placeholder="@lang('modules.tasks.assignTo')" id="assignedTo">
                                        <option value="all">@lang('app.all')</option>
                                        @foreach($employees as $employee)
                                            <option
                                                    value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5>@lang('app.select') @lang('modules.tasks.assignBy')</h5>

                                <div class="form-group">
                                    <select class="select2 form-control"
                                            data-placeholder="@lang('modules.tasks.assignBy')" id="assignedBY">
                                        <option value="all">@lang('app.all')</option>
                                        @foreach($employees as $employee)
                                            <option
                                                    value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group m-t-10">
                                    <label class="control-label col-xs-12">&nbsp;</label>
                                    <button type="button" id="apply-filters" class="btn btn-success btn-sm"><i
                                                class="fa fa-check"></i> @lang('app.apply')</button>
                                    <button type="button" id="reset-filters " class="btn btn-inverse btn-sm"><i
                                                class="fa fa-refresh"></i> @lang('app.reset')</button>
                                    <button type="button" class="btn btn-default btn-sm toggle-filter"><i
                                                class="fa fa-close"></i> @lang('app.close')</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card">
                        <div class="card-body">

                            <div class="container-scroll white-box" id="task-data">


                                <!-- .row -->
                            </div>
                        </div>
                    </div>
                </div>

                @include('sections.right_sidebar')

            </div>

        </div>
        <!-- /.container-fluid -->
        <footer class="text-center"> {{ \Carbon\Carbon::now()->year }} &copy; {{ $global->company_name }} </footer>
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
<script src="{{ asset('plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src='https:{{ asset('js/bootstrap-select.min.js') }}'></script>

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
<script src="{{ asset('plugins/bower_components/lobipanel/dist/js/lobipanel.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>

<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>

<script src="https://js.pusher.com/5.0/pusher.min.js"></script>

<script type="text/javascript">

    jQuery('#date-range').datepicker({
        toggleActive: true,
        format: '{{ $global->date_picker_format }}',
        language: '{{ $global->locale }}',
        autoclose: true
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('#apply-filters').click(function () {
        loadData();
    });
    $('#reset-filters').click(function () {
        $('.select2').val('all');
        $('#start-date').val('{{ $startDate }}');
        $('#end-date').val('{{ $endDate }}');

        loadData();
    });

    $('.toggle-filter').click(function () {
        $('#ticket-filters').slideToggle();
    });


    function loadData() {
        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = null;
        }

        var projectID = $('#project_id').val();
        var clientID = $('#clientID').val();
        var assignedBY = $('#assignedBY').val();
        var assignedTo = $('#assignedTo').val();

        var url = '{{route('front.taskBoardData')}}?startDate=' + encodeURIComponent(startDate) + '&endDate=' + encodeURIComponent(endDate) + '&clientID=' + clientID + '&assignedBY=' + assignedBY + '&assignedTo=' + assignedTo + '&projectID=' + projectID;

        $.easyAjax({
            url: url,
            container: '#task-data',
            type: "GET",
            success: function (response) {
                $('#task-data').html(response.view);
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            }
        })
    }

    loadData();

    $('body').on('click', '.right-side-toggle', function () {
        $(".right-sidebar").slideDown(50).removeClass("shw-rside");
    })
</script>

<script>
    $('#toggle_fullscreen').on('click', function () {
        // if already full screen; exit
        // else go fullscreen
        if (
            document.fullscreenElement ||
            document.webkitFullscreenElement ||
            document.mozFullScreenElement ||
            document.msFullscreenElement
        ) {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        } else {
            element = $('.container-scroll').get(0);
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }
        }
    });
</script>

@if ($pusherSettings->status)
    <script>

        // Enable pusher logging - don't include this in production
        // Pusher.logToConsole = true;

        var pusher = new Pusher("{{ $pusherSettings->pusher_app_key }}", {
            cluster: "{{ $pusherSettings->pusher_cluster }}",
            forceTLS: "{{ $pusherSettings->force_tls }}"
        });


        // Subscribe to the channel we specified in our Laravel Event
        var channel = pusher.subscribe('task-updated-channel');
        channel.bind('task-updated', function (data) {
            loadData()
        });
    </script>
@endif

</body>
</html>
