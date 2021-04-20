@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('super-admin.support-tickets.create') }}"
                               class="btn btn-success btn-outline btn-sm">@lang('modules.tickets.addTicket') <i class="fa fa-plus"
                                                                                                 aria-hidden="true"></i></a>
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}">

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
@endpush



@section('filter-section')
<div class="row" id="ticket-filters">

    <form action="" id="filter-form">
        <div class="col-xs-12">
            <div class="form-group">
            <h5 >@lang('app.selectDateRange')</h5>
            <div class="input-daterange input-group" id="date-range">
                <input type="text" class="form-control" id="start-date" autocomplete="off" placeholder="@lang('app.startDate')"
                       value=""/>
                <span class="input-group-addon bg-info b-0 text-white">@lang('app.to')</span>
                <input type="text" class="form-control" autocomplete="off"  id="end-date" placeholder="@lang('app.endDate')"
                       value=""/>
            </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <label class="control-label">@lang('modules.tickets.agent') </label>
                <select class="form-control selectpicker" name="agent_id" id="agent_id" data-style="form-control">
                    <option value="all">@lang('app.all')</option>
                    @forelse($superadmins as $superadmin)
                        <option value="{{ $superadmin->id }}">{{ ucwords($superadmin->name).' ['.$superadmin->email.']' }}</option>
                    @empty
                        <option value="">@lang('messages.noGroupAdded')</option>
                    @endforelse
                </select>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <label class="control-label">@lang('app.status')</label>
                <select class="form-control selectpicker" name="status" id="status" data-style="form-control">
                    <option value="all">@lang('app.all')</option>
                    <option selected value="open">@lang('modules.tickets.totalOpenTickets')</option>
                    <option value="pending">@lang('modules.tickets.totalPendingTickets')</option>
                    <option value="resolved">@lang('modules.tickets.totalResolvedTickets')</option>
                    <option value="closed">@lang('modules.tickets.totalClosedTickets')</option>
                </select>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <label class="control-label">@lang('modules.tasks.priority')</label>
                <select class="form-control selectpicker" name="priority" id="priority" data-style="form-control">
                    <option value="all">@lang('app.all')</option>
                    <option value="low">@lang('modules.tasks.low')</option>
                    <option value="medium">@lang('modules.tasks.medium')</option>
                    <option value="high">@lang('modules.tasks.high')</option>
                    <option value="urgent">@lang('modules.tickets.urgent')</option>
                </select>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <label class="control-label">@lang('modules.invoices.type')</label>
                <select class="form-control selectpicker" name="type_id" id="type_id" data-style="form-control">
                    <option value="all">@lang('app.all')</option>
                    @forelse($types as $type)
                        <option value="{{ $type->id }}">{{ ucwords($type->type) }}</option>
                    @empty
                        <option value="">@lang('messages.noTicketTypeAdded')</option>
                    @endforelse
                </select>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <div class="checkbox checkbox-info  col-md-12">
                    <input id="self" name="self" value="yes" checked type="checkbox">
                    <label for="self">@lang('messages.selfTickets')</label>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <label class="control-label col-xs-12">&nbsp;</label>
                <button type="button" id="apply-filters" class="btn btn-success col-md-6"><i class="fa fa-check"></i> @lang('app.apply')</button>
                <button type="button" id="reset-filters" class="btn btn-inverse col-md-5 col-md-offset-1"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
            </div>
        </div>
    </form>
</div>

@endsection


@section('content')

    <div class="row">

        {{--<div class="col-xs-12">--}}
            {{--<div class="white-box p-b-0 m-b-0">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-sm-4">--}}
                        {{--<label class="control-label">@lang('app.selectDateRange')</label>--}}

                        {{--<div class="form-group">--}}
                            {{--<input class="form-control input-daterange-datepicker" type="text" name="daterange"--}}
                                   {{--value="{{ $startDate.' - '.$endDate }}"/>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                {{--</div>--}}

            {{--</div>--}}
        {{--</div>--}}

        <div class="col-md-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                                {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>


    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>

<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>

<script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>

<script src="{{ asset('plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
<script src="{{ asset('plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

{!! $dataTable->scripts() !!}
<script>
    jQuery('#date-range').datepicker({
        toggleActive: true,
        format: 'yyyy-mm-dd',
        language: 'en',
        autoclose: true,
    });

    $('.toggle-filter').click(function () {
        $('#ticket-filters').toggle('slide');
    })

    var total = '@lang('app.total')';
    var resolved = '@lang('app.resolved')';
    var closed = '@lang('app.closed')';
    var open = '@lang('app.open')';
    var pending = '@lang('app.pending')';

    function ticketChart(chartData){
        Morris.Area({
            element: 'morris-area-chart',
            data: chartData,
            xkey: 'date',
            ykeys: [total.toLowerCase(), resolved.toLowerCase(), 'closed', open.toLowerCase(), pending.toLowerCase()],
            labels: [total, resolved, 'Closed', open, pending],
            pointSize: 3,
            fillOpacity: 0.3,
            pointStrokeColors: ['#4c5667', '#5475ed', '#12ed0b', '#f11219' , '#f1c411'],
            behaveLikeLine: true,
            gridLineColor: '#e0e0e0',
            lineWidth: 3,
            hideHover: 'auto',
            lineColors: ['#4c5667', '#5475ed', '#12ed0b', '#f11219' , '#f1c411'],
            resize: true

        });
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var target = $(e.target).attr("href") // activated tab

        switch (target) {
            case "#home1":
            $(window).trigger('resize');
            break;
            case "#profile1":
            $(window).trigger('resize');
            break;
        }
    });

    $('#superadmin-support-ticket-table').on('preXhr.dt', function (e, settings, data) {

        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = null;
        }

        var agentId = $('#agent_id').val();
        if (agentId == "") {
            agentId = 0;
        }

        var status = $('#status').val();
        if (status == "") {
            status = 0;
        }

        var priority = $('#priority').val();
        if (priority == "") {
            priority = 0;
        }

        var channelId = $('#channel_id').val();
        if (channelId == "") {
            channelId = 0;
        }

        var typeId = $('#type_id').val();
        if (typeId == "") {
            typeId = 0;
        }

        var tagId = $('#tag_id').val();
        if (tagId == "") {
            tagId = 0;
        }
        var self = '';
        if($('#self').is(':checked')){
            self = 'yes';
        }


        data['startDate'] = startDate;
        data['endDate'] = endDate;
        data['agentId'] = agentId;
        data['priority'] = priority;
        data['channelId'] = channelId;
        data['typeId'] = typeId;
        data['tagId'] = tagId;
        data['status'] = status;
        data['self'] = self;
    });

    var table;

    function showTable() {

        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = 0;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = 0;
        }

        var agentId = $('#agent_id').val();
        if (agentId == "") {
            agentId = 0;
        }

        var status = $('#status').val();
        if (status == "") {
            status = 0;
        }

        var priority = $('#priority').val();
        if (priority == "") {
            priority = 0;
        }

        var channelId = $('#channel_id').val();
        if (channelId == "") {
            channelId = 0;
        }

        var typeId = $('#type_id').val();
        if (typeId == "") {
            typeId = 0;
        }

        window.LaravelDataTables["superadmin-support-ticket-table"].draw();
    }

    $('#apply-filters').click(function () {
        showTable();
    });

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
        $('#filter-form').find('select').selectpicker('render');
        showTable();
    })


    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('ticket-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.confirmation.recoverDeleteTicket')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.deleteConfirmation')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('super-admin.support-tickets.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            window.LaravelDataTables["superadmin-support-ticket-table"].draw();
                        }
                    }
                });
            }
        });
    });

    function initConter() {
        $(".counter").counterUp({
            delay: 100,
            time: 1200
        });
    }

    $(document).ready(function(){
        showTable();
    });

    function exportData(){

        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = 0;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = 0;
        }

        var agentId = $('#agent_id').val();
        if (agentId == "") {
            agentId = 0;
        }

        var status = $('#status').val();
        if (status == "") {
            status = 0;
        }

        var priority = $('#priority').val();
        if (priority == "") {
            priority = 0;
        }

        var channelId = $('#channel_id').val();
        if (channelId == "") {
            channelId = 0;
        }

        var typeId = $('#type_id').val();
        if (typeId == "") {
            typeId = 0;
        }


        //refresh counts and chart
        var url = '{!!  route('super-admin.support-tickets.export', [':startDate', ':endDate', ':agentId', ':status', ':priority', ':channelId', ':typeId']) !!}';
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':agentId', agentId);
        url = url.replace(':status', status);
        url = url.replace(':priority', priority);
        url = url.replace(':channelId', channelId);
        url = url.replace(':typeId', typeId);

        window.location.href = url;
    }
</script>
@endpush