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
                <li><a href="{{ route('admin.payments.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datetime-picker/datetimepicker.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel ">
                <div class="panel-heading"> @lang('modules.payments.addPayment')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createPayment','class'=>'ajax-form','method'=>'POST']) !!}
                        <div class="form-body">
                            {!! Form::hidden('invoice_id', $invoice->id) !!}
                            <div class="row">

                                <div class="col-md-12">
                                   <h2>{{ $invoice->invoice_number }}</h2>
                                </div>
                                @if($invoice->project_id)
                                    <div class="col-md-12">
                                        <div class="col-md-4 form-group">
                                            <label>@lang('app.project')</label>
                                            <h3 class="form-control-static">{{ $invoice->project->project_name }}</h3>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-12">
                                    <div class="col-md-4 form-group">
                                        <label>@lang('modules.invoices.amount')</label>
                                        <h3 class="form-control-static">{{ $invoice->currency->currency_symbol.' '.$invoice->total }}</h3>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>@lang('modules.invoices.paid')</label>
                                        <h3 class="form-control-static">{{ $invoice->currency->currency_symbol}} {{ $paidAmount }}</h3>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>@lang('modules.invoices.due')</label>
                                        <h3 class="form-control-static">{{ $invoice->currency->currency_symbol}} {{ max(($invoice->total-$paidAmount),0) }}</h3>
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('modules.payments.amount')</label>
                                        <input type="number" name="amount" id="amount" value="{{ max(($invoice->total-$paidAmount),0) }}" class="form-control">
                                        <span class="help-block"> </span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('modules.payments.paymentGateway')</label>
                                        <input type="text" name="gateway" id="gateway" class="form-control">
                                        <span class="help-block"> Paypal, Authorize.net, Stripe, Bank Transfer, Cash or others.</span>
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('modules.payments.transactionId')</label>
                                        <input type="text" name="transaction_id" id="transaction_id" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.payments.paidOn')</label>
                                        <input type="text" class="form-control" name="paid_on" id="paid_on" value="{{ Carbon\Carbon::today()->format('d/m/Y H:i') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.payments.remark')</label>
                                        <textarea class="form-control" name="remarks" id="remark"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form-2" class="btn btn-success"><i class="fa fa-check"></i>
                                @lang('app.save')
                            </button>
                            <button type="reset" class="btn btn-default">@lang('app.reset')</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->
@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/datetime-picker/datetimepicker.js') }}"></script>

<script>

    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());

    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    jQuery('#paid_on').datetimepicker({
        format: 'D/M/Y HH:mm',
    });

    $('#save-form-2').click(function () {
        $.easyAjax({
            url: '{{route('admin.payments.store')}}',
            container: '#createPayment',
            type: "POST",
            redirect: true,
            data: $('#createPayment').serialize()
        })
    });
</script>
@endpush