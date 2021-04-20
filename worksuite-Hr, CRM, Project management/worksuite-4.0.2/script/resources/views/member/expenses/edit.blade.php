@extends('layouts.member-app')

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
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.expenses.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.update')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.expenses.updateExpense')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'updateExpense','class'=>'ajax-form','method'=>'PUT']) !!}
                        <div class="form-body">
                            <div class="row">
                                @if($user->can('edit_expenses'))
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label required">@lang('modules.employees.title')</label>
                                            <select class="form-control select2" name="employee" id="employee" data-style="form-control">
                                                @forelse($employees as $employee)
                                                    <option @if($employee->id == $user->id) selected @endif value="{{$employee->id}}">{{ ucfirst($employee->name) }}</option>
                                                @empty
                                                    <option value="">--</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="project_id">@lang('modules.invoices.project')</label>
                                            <select class="select2 form-control" id="project_id" name="project_id">
                                                <option @if(is_null($expense->project_id)) selected @endif value="0">@lang('app.selectProject')</option>
                                                @forelse($projects as $project)
                                                    <option @if($project->id == $expense->project_id) selected @endif value="{{ $project->id }}">
                                                        {{ $project->project_name }}
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="company_phone">@lang('modules.invoices.currency')</label>
                                            <select class="form-control" id="currency_id" name="currency_id">
                                                @forelse($currencies as $currency)
                                                    <option @if($currency->id == $expense->currency_id) selected @endif value="{{ $currency->id }}">
                                                        {{ $currency->currency_name }} - ({{ $currency->currency_symbol }})
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.expenses.itemName')</label>
                                        <input type="text" name="item_name" id="item_name"
                                               value="{{ $expense->item_name }}" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="required">@lang('app.price')</label>
                                        <input type="text" name="price" id="price" value="{{ number_format((float)$expense->price, 2, '.', '') }}"
                                               class="form-control">
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('modules.expenses.purchaseFrom')</label>
                                        <input type="text" name="purchase_from" id="purchase_from"
                                               value="{{ $expense->purchase_from }}" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('modules.expenses.purchaseDate')</label>
                                        <input type="text" class="form-control" name="purchase_date" id="purchase_date"
                                               value="{{ $expense->purchase_date->format($global->date_format) }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.invoice')</label>

                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput"><i
                                                        class="glyphicon glyphicon-file fileinput-exists"></i> <span
                                                        class="fileinput-filename"></span></div>
                                                <span class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new">@lang('app.selectFile')</span>
                                                    <span class="fileinput-exists">@lang('app.change')</span>
                                                    <input type="file" name="bill" id="bill">
                                                </span>
                                                <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                               data-dismiss="fileinput">@lang('app.remove')</a>
                                        </div>
                                        @if(!is_null($expense->bill))
                                            <a target="_blank" href="{{ $expense->bill_url }}">View Invoice</a>
                                        @endif
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
<script>


    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    jQuery('#purchase_date').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true
    });

    $('#save-form-2').click(function () {
        $.easyAjax({
            url: '{{route('member.expenses.update', $expense->id)}}',
            container: '#updateExpense',
            type: "POST",
            redirect: true,
            file: (document.getElementById("bill").files.length == 0) ? false : true,
            data: $('#updateExpense').serialize()
        })
    });
</script>
@endpush
