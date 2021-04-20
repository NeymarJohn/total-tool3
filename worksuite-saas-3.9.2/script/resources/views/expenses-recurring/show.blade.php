@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang("app.menu.home")</a></li>
                <li><a href="{{ route('admin.expenses-recurring.index') }}">@lang('app.menu.expensesRecurring')</a></li>
                <li class="active">@lang('app.menu.expensesRecurring')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">

<style>
    .ribbon-wrapper {
        background: #ffffff !important;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12 m-t-20">
            <div class="white-box">
                <div class="col-md-4 text-center">
                    <h4><span class="text-dark">{{ $expense->total_amount }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.payments.totalAmount')</span></h4>
                </div>

                <div class="col-md-4 text-center b-l">
                    <h4><span class="text-success">{{ $expense->currency->currency_symbol.' '.$expense->recurrings->sum('price') }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.payments.totalPaid')</span></h4>
                </div>

                {{--<div class="col-md-4 text-center b-l">--}}
                    {{--<h4><span class="text-danger">{{ $invoice->currency->currency_symbol.' '.$invoice->amountDue() }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.payments.totalDue')</span></h4>--}}
                {{--</div>--}}

            </div>
            <hr>
        </div>

        <div class="white-box printableArea" >

            {{--<p class="list-group-item-text">--}}
            <div class="row margin-top-5">
                <div class="col-md-4">
                    <b>@lang('app.member')</b>  <br>
                    {{ (!is_null($expense->user_id)) ? ucfirst($expense->user->name) : "--"}}
                </div>
                <div class="col-md-4">
                    <b>@lang('app.category')</b>  <br>
                    {{ (!is_null($expense->category_id)) ? ucwords($expense->category->category_name) : "--"}}
                </div>
                <div class="col-md-4">
                    <b>@lang('app.project')</b>  <br>
                    {{ (!is_null($expense->project_id)) ? ucwords($expense->project->project_name) : "--"}}
                </div>
                <div class="col-md-4">
                    <b>@lang('app.status')</b> <br>
                    @if ($expense->status == 'inactive')
                        <label class="label label-danger">{{ strtoupper($expense->status) }}</label>
                    @else
                        <label class="label label-success">{{ strtoupper($expense->status) }}</label>
                    @endif
                </div>
            </div>
            <hr>
            <div class="row margin-top-5">
                <div class="col-md-3">
                    <b>@lang('app.amount')</b>  <br>
                    {{ $expense->total_amount }}
                </div>

                @if($expense->bill_url)
                    <div class="col-md-3">
                        <b>@lang('app.bill')</b> <br>
                        <a target="_blank"
                           href="{{ $expense->bill_url }}">@lang('app.view') @lang('app.bill') <i class="fa fa-external-link"></i></a>

                    </div>
                @endif
            </div>
            <hr>
            <div class="row margin-top-5">
                <div class="col-md-6">
                    <b>@lang('app.description')</b>  <br>
                    {!! $expense->description !!}
                </div>
            </div>

            <div class="clearfix"></div>

        </div>
    </div>


    <div class="row">

        <div class="col-md-12">

            <div class="white-box">
                <div class="clearfix"></div>
                {{--custom fields data end--}}

            </div>
        </div>
    </div>


@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/clipboard/clipboard.min.js') }}"></script>

<script>
    var clipboard = new ClipboardJS('.btn-copy');

    clipboard.on('success', function(e) {
        var copied = "<?php echo __("app.copied") ?>";
        // $('#copy_payment_text').html(copied);
        $.toast({
            heading: '',
            text: 'Successfully copied',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'success',
            hideAfter: 3500
        });
    });

    function deleteAppliedCredit(invoice_id, id) {
        let url = '{{ route('admin.all-invoices.delete-applied-credit', [':id']) }}';
        url = url.replace(':id', id);

        $.easyAjax({
            url: url,
            type: 'POST',
            data: { invoice_id: invoice_id, _token: '{{csrf_token()}}'},
            success: function (response) {
                $('#appliedCredits .modal-content').html(response.view);
                $('#appliedCredits').on('hide.bs.modal', function (e) {
                    location.reload();
                })
            }
        })
    }

    $(function () {
        var table = $('#invoices-table').dataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('client.invoices.create') }}',
            deferRender: true,
            "order": [[0, "desc"]],
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function (oSettings) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'project_name', name: 'projects.project_name'},
                {data: 'invoice_number', name: 'invoice_number'},
                {data: 'currency_symbol', name: 'currencies.currency_symbol'},
                {data: 'total', name: 'total'},
                {data: 'issue_date', name: 'issue_date'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

    });


</script>
@endpush