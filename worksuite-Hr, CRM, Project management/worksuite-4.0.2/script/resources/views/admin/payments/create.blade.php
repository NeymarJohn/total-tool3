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

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.payments.addPayment')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createPayment','class'=>'ajax-form','method'=>'POST']) !!}
                        <div class="form-body">
                            <div class="row">

                                <div class="col-md-6 ">

                                    @if (isset($projectId))
                                        <input type="hidden" value="{{ $projectId }}" name="project_id_direct">
                                    @endif

                                    <div class="form-group">
                                        <label>@lang('app.selectProject')</label>
                                        <select class="select2 form-control" data-placeholder="@lang('app.selectProject') (@lang('app.optional'))" name="project_id">
                                            <option value="">@lang('app.selectProject') (@lang('app.optional'))</option>
                                            @foreach($projects as $project)
                                                <option
                                                    @if (isset($projectId) && $project->id == $projectId)
                                                        selected
                                                    @endif
                                                        value="{{ $project->id }}">{{ $project->project_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('modules.payments.paidOn')</label>
                                        <input type="text" class="form-control" name="paid_on" id="paid_on" value="{{ Carbon\Carbon::now()->timezone($global->timezone)->format('d/m/Y H:i') }}">
                                    </div>
                                </div>


                                <!--/span-->

                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label>@lang('modules.invoices.currency')</label>
                                        <select class="form-control" name="currency_id" id="currency_id">
                                            <option value="">@lang('app.selectCurrency')</option>
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.invoices.amount')</label>
                                        <input type="number" value="0" min="0" step=".01" name="amount" id="amount" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('modules.payments.paymentGateway')</label>
                                        <input type="text" name="gateway" id="gateway" class="form-control">
                                        <span class="help-block"> Paypal, Authorize.net, Stripe, Bank Transfer, Cash or others.</span>
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('modules.payments.transactionId')</label>
                                        <input type="text" name="transaction_id" id="transaction_id" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.receipt')</label>
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput"> 
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
                                            </div>
                                            <span class="input-group-addon btn btn-default btn-file"> 
                                                <span class="fileinput-new">@lang('app.selectFile')</span> 
                                                <span class="fileinput-exists">@lang('app.change')</span>
                                                <input type="file" name="bill" id="bill">
                                            </span> 
                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">@lang('app.remove')</a> 
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.remark')</label>
                                        <textarea id="remarks" name="remarks" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form-2" class="btn btn-success"><i class="fa fa-check"></i>
                                @lang('app.save')
                            </button>
                            <button type="reset" id="resetForm" class="btn btn-default">@lang('app.reset')</button>
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

    $(".select2").select2({
        allowClear: true,
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
            file: true,
            data: $('#createPayment').serialize()
        })
    });

    $('#resetForm').click(function(){
        $(".select2").select2("val", "");
    });
</script>
@endpush