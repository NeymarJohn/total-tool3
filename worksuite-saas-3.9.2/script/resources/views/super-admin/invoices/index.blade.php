@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}
                <span class="text-info b-l p-l-10 m-l-5">{{ $totalInvoices }}</span> <span
                class="font-12 text-muted m-l-5"> @lang('app.total') @lang('app.menu.invoices')</span>
            </h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
@endpush

@section('filter-section')
<div class="row"  id="ticket-filters">

    <form action="" id="filter-form">
        <div class="col-xs-12 m-t-30">
            <div class="form-group">
                <label class="control-label">@lang('app.company')</label>
                <select class="form-control select2" name="company" id="company" data-style="form-control">
                    <option value="all">@lang('app.all')</option>
                    @foreach( $companies as $item)
                        <option value="{{ $item->id }}">{{ ucwords($item->company_name) }}</option>
                    @endforeach
                </select>
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


        <div class="col-xs-12">
            <div class="white-box">
                <div class="table-responsive">
                <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="users-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('app.company')</th>
                        <th>@lang('app.package')</th>
                        <th>@lang('modules.payments.transactionId')</th>
                        <th>@lang('app.amount')</th>
                        <th>@lang('app.date')</th>
                        <th>@lang('modules.billing.nextPaymentDate')</th>
                        <th>@lang('modules.payments.paymentGateway')</th>
                        <th>@lang('app.action')</th>
                    </tr>
                    </thead>
                </table>
                    </div>
            </div>
        </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>

    <script>
        $(function() {
            $(".select2").select2({
                formatNoMatches: function () {
                    return "{{ __('messages.noRecordFound') }}";
                }
            });

            var table;
            $('#apply-filters').click(function () {
                loadTable();
            });

            $('#reset-filters').click(function () {
                $('#filter-form')[0].reset();
                $('#company').val('all');
                $('#company').select2();
                loadTable();
            });

            function loadTable(){

                var company = $('#company').val();

                var table = $('#users-table').dataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    destroy: true,
                    ajax: '{!! route('super-admin.invoices.data') !!}?company_id='+company,
                    language: {
                        "url": "<?php echo __("app.datatable") ?>"
                    },
                    "fnDrawCallback": function( oSettings ) {
                        $("body").tooltip({
                            selector: '[data-toggle="tooltip"]'
                        });
                    },
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'company', name: 'companies.company_name'},
                        { data: 'package', name: 'packages.name' },
                        { data: 'transaction_id', name: 'transaction_id'},
                        { data: 'amount', name: 'amount' },
                        { data: 'paid_on', name: 'offline_invoices.pay_date' },
                        { data: 'next_pay_date', name: 'offline_invoices.next_pay_date' },
                        { data: 'method', name: 'offline_method_id' },
                        { data: 'action', name: 'action' }
                    ]
                });

            } 

            loadTable();
        });
    </script>
@endpush