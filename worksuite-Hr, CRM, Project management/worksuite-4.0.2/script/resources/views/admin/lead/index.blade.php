@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-8 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('admin.leads.create') }}" class="btn btn-outline btn-success btn-sm">@lang('modules.lead.addNewLead') <i class="fa fa-plus"  aria-hidden="true"></i></a>
            <a href="{{ route('admin.leads.kanbanboard') }}" class="btn btn-outline btn-primary btn-sm">@lang('modules.lead.kanbanboard') </a>

            <a href="{{ route('admin.lead-form.index') }}" class="btn btn-outline btn-inverse btn-sm">@lang('modules.lead.leadForm') <i class="fa fa-pencil"  aria-hidden="true"></i></a>
            
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <style>

        .dashboard-stats .white-box .list-inline {
            margin-bottom: 0;
        }

        .dashboard-stats .white-box {
            padding: 10px;
        }

        .dashboard-stats .white-box .box-title {
            font-size: 13px;
            text-transform: capitalize;
            font-weight: 300;
        }
        #leads-table_wrapper .dt-buttons{
            display: none !important;
        }
    </style>
@endpush

@section('filter-section')
    <div class="row" id="ticket-filters">

        <form action="" id="filter-form">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">@lang('app.selectFollowUpDateRange')</label>
                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control" autocomplete="off" id="start-date" placeholder="@lang('app.startDate')"
                               value=""/>
                        <span class="input-group-addon bg-info b-0 text-white">@lang('app.to')</span>
                        <input type="text" class="form-control" autocomplete="off" id="end-date" placeholder="@lang('app.endDate')"
                               value=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">@lang('modules.lead.client')</label>
                    <select class="form-control selectpicker" name="client" id="client"
                            data-style="form-control">
                        <option value="all">@lang('modules.lead.all')</option>
                        <option value="lead">@lang('modules.lead.lead')</option>
                        <option value="client">@lang('modules.lead.client')</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">@lang('modules.lead.followUp')</label>
                    <select class="form-control selectpicker" name="followUp" id="followUp"
                            data-style="form-control">
                        <option value="all">@lang('modules.lead.all')</option>
                        <option value="yes">@lang('app.yes')</option>
                        <option value="no">@lang('app.no')</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">@lang('modules.tickets.chooseAgents')</label>
                    <select class="selectpicker form-control" data-placeholder="@lang('modules.tickets.chooseAgents')" id="agent_id" name="agent_id">
                        <option value="all">@lang('modules.lead.all')</option>
                        @foreach($leadAgents as $emp)
                            <option value="{{ $emp->id }}">{{ ucwords($emp->user->name) }} @if($emp->user->id == $user->id)
                                    (YOU) @endif</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">@lang('modules.lead.leadCategory')</label>
                    <select class="select2 form-control" data-placeholder="@lang('modules.lead.leadCategory')" id="category_id">
                        <option selected value="all">@lang('app.all')</option>
                      
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                       
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">@lang('modules.lead.leadSource')</label>
                    <select class="select2 form-control" data-placeholder="@lang('modules.lead.leadSource')" id="source_id">
                        <option selected value="all">@lang('app.all')</option>
                        @foreach($sources as $source)
                            <option value="{{ $source->id }}">{{ $source->type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-xs-12">&nbsp;</label>
                    <button type="button" id="apply-filters" class="btn btn-success col-md-6"><i
                                class="fa fa-check"></i> @lang('app.apply')</button>
                    <button type="button" id="reset-filters"
                            class="btn btn-inverse col-md-5 col-md-offset-1"><i
                                class="fa fa-refresh"></i> @lang('app.reset')</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('content')

    <div class="row dashboard-stats">
        <div class="col-md-12 m-b-30">
            <div class="white-box">
                <div class="col-md-4 text-center">
                    <h4><span class="text-dark">{{ $totalLeads }}</span> <span
                                class="font-12 text-muted m-l-5"> @lang('modules.dashboard.totalLeads')</span></h4>
                </div>
                <div class="col-md-4 text-center b-l">
                    <h4><span class="text-info">{{ $totalClientConverted }}</span> <span
                                class="font-12 text-muted m-l-5"> @lang('modules.dashboard.totalConvertedClient')</span>
                    </h4>
                </div>
                <div class="col-md-4 text-center b-l">
                    <h4><span class="text-warning">{{ $pendingLeadFollowUps }}</span> <span
                                class="font-12 text-muted m-l-5"> @lang('modules.dashboard.totalPendingFollowUps')</span>
                    </h4>
                </div>
            </div>
        </div>

    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="white-box">

                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="followUpModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
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
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

    {!! $dataTable->scripts() !!}
    <script>
        $(function () {
            $('#reset-filters').click(function () {
                $('#filter-form')[0].reset();
                $('#filter-form').find('.selectpicker').selectpicker('render');
                $.easyBlockUI('#leads-table');
                window.LaravelDataTables["leads-table"].draw();
                $.easyUnblockUI('#leads-table');
            });
            jQuery('#date-range').datepicker({
                toggleActive: true,
                format: '{{ $global->date_picker_format }}',
                language: '{{ $global->locale }}',
                autoclose: true
            });
            var table;
            $('#apply-filters').click(function () {

                $('#leads-table').on('preXhr.dt', function (e, settings, data) {
                    var client = $('#client').val();
                    var agent = $('#agent_id').val();
                    var followUp = $('#followUp').val();
                    var category_id = $('#category_id').val();
                    var source_id = $('#source_id').val();



                    var startDate = $('#start-date').val();

                    if (startDate == '') {
                        startDate = null;
                    }

                    var endDate = $('#end-date').val();

                    if (endDate == '') {
                        endDate = null;
                    }

                    data['startDate'] = startDate;
                    data['endDate'] = endDate;
                    data['client'] = client;
                    data['agent'] = agent;
                    data['followUp'] = followUp;
                    data['category_id'] = category_id;
                    data['source_id'] = source_id;
                    
                });

                $.easyBlockUI('#leads-table');
                window.LaravelDataTables["leads-table"].draw();
                $.easyUnblockUI('#leads-table');
            });
            $('body').on('click', '.sa-params', function () {
                var id = $(this).data('user-id');
                swal({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.deleteleadText')",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "@lang('messages.confirmDelete')",
                    cancelButtonText: "@lang('messages.confirmNoArchive')",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {

                        var url = "{{ route('admin.leads.destroy',':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            success: function (response) {
                                if (response.status == "success") {
                                    $.easyBlockUI('#leads-table');
                                    // window.LaravelDataTables["leads-table"].draw();
                                    window.location.reload();
                                    $.easyUnblockUI('#leads-table');
                                }
                            }
                        });
                    }
                });
            });


        });

        function changeStatus(leadID, statusID) {
            var url = "{{ route('admin.leads.change-status') }}";
            var token = "{{ csrf_token() }}";

            $.easyAjax({
                type: 'POST',
                url: url,
                data: {'_token': token, 'leadID': leadID, 'statusID': statusID},
                success: function (response) {
                    if (response.status == "success") {
                        $.easyBlockUI('#leads-table');
                        window.LaravelDataTables["leads-table"].draw();
                        $.easyUnblockUI('#leads-table');
                    }
                }
            });
        }

        $('.edit-column').click(function () {
            var id = $(this).data('column-id');
            var url = '{{ route("admin.taskboard.edit", ':id') }}';
            url = url.replace(':id', id);

            $.easyAjax({
                url: url,
                type: "GET",
                success: function (response) {
                    $('#edit-column-form').html(response.view);
                    $(".colorpicker").asColorPicker();
                    $('#edit-column-form').show();
                }
            })
        })

        function followUp(leadID) {

            var url = '{{ route('admin.leads.follow-up', ':id')}}';
            url = url.replace(':id', leadID);

            $('#modelHeading').html('Add Follow Up');
            $.ajaxModal('#followUpModal', url);
        }

        $('.toggle-filter').click(function () {
            $('#ticket-filters').toggle('slide');
        })

        function exportData() {

            var client = $('#client').val();
            var followUp = $('#followUp').val();

            var url = '{{ route('admin.leads.export', [':followUp', ':client']) }}';
            url = url.replace(':client', client);
            url = url.replace(':followUp', followUp);

            window.location.href = url;
        }
    </script>
@endpush
