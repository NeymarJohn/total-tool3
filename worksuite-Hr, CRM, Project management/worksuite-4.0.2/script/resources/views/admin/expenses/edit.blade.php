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
                <li><a href="{{ route('admin.expenses.index') }}">@lang($pageTitle)</a></li>
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
<style>
    .recurringPayment {
        display: none;
    }
    .label-font{
        font-weight: 500 !important;
    }
</style>
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
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label>@lang('modules.messages.chooseMember')</label>
                                        <select class="select2 form-control"
                                                data-placeholder="@lang('modules.messages.chooseMember')"
                                                id="user_id"
                                                name="user_id">
                                            @foreach($employees as $employee)
                                                <option
                                                        @if($employee['user']['id'] == $expense->user_id) selected @endif
                                                value="{{ $employee['user']['id'] }}">{{ ucwords($employee['user']['name']) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!--/span-->
                            
                                <div class="col-sm-12 col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="project_id">@lang('modules.invoices.project')</label>
                                        <select class="select2 form-control" id="project_id" name="project_id">
                                            @forelse($employees[0]['user']['projects'] as $project)
                                                <option @if($project['id'] == $expense->project_id) selected @endif value="{{ $project['id'] }}">
                                                    {{ $project['project_name'] }}
                                                </option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.expenses.itemName')</label>
                                        <input type="text" name="item_name" id="item_name"
                                               value="{{ $expense->item_name }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('modules.expenses.purchaseFrom')</label>
                                        <input type="text" name="purchase_from" id="purchase_from"
                                               value="{{ $expense->purchase_from }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">@lang('app.price')</label>
                                        <input type="text" name="price" id="price" value="{{ $expense->price }}"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class=" col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="company_phone">@lang('modules.invoices.currency')</label>
                                        <select class="form-control select2" id="currency_id" name="currency_id">
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
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('modules.expenses.purchaseDate')</label>
                                        <input type="text" class="form-control" name="purchase_date" id="purchase_date"
                                               value="{{ $expense->purchase_date->format($global->date_format) }}">
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.expenses.expenseCategory')
                                            <a href="javascript:;" id="addExpenseCategory" class="btn btn-xs btn-success btn-outline"><i class="fa fa-plus"></i></a>
                                        </label>
                                        <select class="select2 form-control" name="category_id" id="category_id"
                                                data-style="form-control">
                                            @forelse($categories as $category)
                                                <option @if($expense->category_id == $category->id) selected @endif value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noProjectCategoryAdded')</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required" class="control-label">@lang('app.status')</label>
                                        <select class="form-control" name="status" id="status">
                                            <option @if($expense->status == 'approved') selected @endif >@lang('app.approved')
                                            </option>
                                            <option @if($expense->status == 'pending') selected @endif>@lang('app.pending')</option>
                                            <option @if($expense->status == 'rejected') selected @endif>@lang('app.rejected')
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.invoice')</label>

                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                <span class="fileinput-filename">{{ $expense->bill }}</span>
                                            </div>
                                            <span class="input-group-addon btn btn-default btn-file">
                                                <span class="fileinput-new">@lang('app.selectFile')</span>
                                                <span class="fileinput-exists">@lang('app.change')</span>
                                                <input type="file" name="bill" id="bill">
                                            </span>
                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                               data-dismiss="fileinput">@lang('app.remove')</a>
                                        </div>
                                        @if(!is_null($expense->bill))
                                            <a target="_blank"
                                               href="{{ $expense->bill_url }}">@lang('app.view') @lang('app.invoice')</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!--/span-->

                            @if(count($fields) > 0)
                            <h3 class="box-title">@lang('modules.projects.otherInfo')</h3>
                                <div class="row">
                                    @foreach($fields as $field)
                                        <div class="col-md-3">
                                            <label>{{ ucfirst($field->label) }}</label>
                                            <div class="form-group">
                                                @if( $field->type == 'text')
                                                <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}"
                                                    value="{{$expense->custom_fields_data['field_'.$field->id] ?? ''}}">                                    @elseif($field->type == 'password')
                                                <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}"
                                                    value="{{$expense->custom_fields_data['field_'.$field->id] ?? ''}}">                                    @elseif($field->type == 'number')
                                                <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}"
                                                    value="{{$expense->custom_fields_data['field_'.$field->id] ?? ''}}">                                    @elseif($field->type == 'textarea')
                                                <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" id="{{$field->name}}" cols="3">{{$expense->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>                                    @elseif($field->type == 'radio')
                                                <div class="radio-list">
                                                    @foreach($field->values as $key=>$value)
                                                    <label class="radio-inline @if($key == 0) p-0 @endif">
                                                                        <div class="radio radio-info">
                                                                            <input type="radio" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" id="optionsRadios{{$key.$field->id}}" value="{{$value}}" @if(isset($expense) && $expense->custom_fields_data['field_'.$field->id] == $value) checked @elseif($key==0) checked @endif>>
                                                                            <label for="optionsRadios{{$key.$field->id}}">{{$value}}</label>
                                                </div>
                                                </label>
                                                @endforeach
                                            </div>
                                            @elseif($field->type == 'select') {!! Form::select('custom_fields_data['.$field->name.'_'.$field->id.']', $field->values,
                                            isset($expense)?$expense->custom_fields_data['field_'.$field->id]:'',['class' => 'form-control
                                            gender']) !!} @elseif($field->type == 'checkbox')
                                            <div class="mt-checkbox-inline">
                                                @foreach($field->values as $key => $value)
                                                <label class="mt-checkbox mt-checkbox-outline">
                                                                        <input name="custom_fields_data[{{$field->name.'_'.$field->id}}][]" type="checkbox" value="{{$key}}"> {{$value}}
                                                                        <span></span>
                                                                    </label> @endforeach
                                            </div>
                                            @elseif($field->type == 'date')
                                            <input type="text" class="form-control date-picker" size="16" name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                   value="{{ ($expense->custom_fields_data['field_'.$field->id] != '') ? \Carbon\Carbon::parse($expense->custom_fields_data['field_'.$field->id])->format($global->date_format) : \Carbon\Carbon::now()->format($global->date_format)}}">
                                            @endif
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block"></span>
    
                                        </div>
                                    </div>
                                    @endforeach
                            </div>
                            @endif


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
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="expenseCategoryModal" role="dialog" aria-labelledby="myModalLabel"
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
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>
    var employees = @json($employees);
    var expense = @json($expense);

    var defaultOpt = '<option @if(is_null($expense->project_id)) selected @endif value="0">Select Project...</option>'

    var employee = employees.filter(function (item) {
        return item.user_id == expense.user_id
    })

    var options =  '';

    employee[0].user.projects.forEach(project => {
        options += `<option ${project.id === expense.project_id ? 'selected' : ''} value='${project.id}'>${project.project_name}</option>`
    });

    $(function () {
        recurringPayment();
    });
    // Add category
    $('#addExpenseCategory').click(function () {
        var url = '{{ route('admin.expenseCategory.create-cat')}}';
        $('#modelHeading').html('...');
        $.ajaxModal('#expenseCategoryModal', url);
    });

    $('#project_id').html(defaultOpt+options)

    $('#user_id').change(function (e) {
        // get projects of selected users
        var opts = '';

        var employee = employees.filter(function (item) {
            return item.user_id == e.target.value
        });

        employee[0].user.projects.forEach(project => {
            opts += `<option value='${project.id}'>${project.project_name}</option>`
        })

        $('#project_id').html('<option value="0">Select Project...</option>'+opts)
        $('.select2#project_id').val('0').trigger('change')
    });

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
            url: '{{route('admin.expenses.update', $expense->id)}}',
            container: '#updateExpense',
            type: "POST",
            redirect: true,
            file: (document.getElementById("bill").files.length == 0) ? false : true,
            data: $('#updateExpense').serialize()
        })
    });

    function recurringPayment() {
        var recurring = $('#recurring_payment').val();

        if(recurring == 'yes')
        {
            $('.recurringPayment').show().fadeIn(300);
            if($('#infinite-expenses').is(':checked')) {
                $('.billingInterval').hide().fadeOut(300);
            }
        } else {
            if(!$('#infinite-expenses').is(':checked')) {
                $('.billingInterval').show().fadeIn(300);
            }
            $('.recurringPayment').hide().fadeOut(300);
        }
    }

    $('#infinite-expenses').change(function () {
        if($(this).is(':checked')){
            $('.billingInterval').hide();
        }
        else{
            $('.billingInterval').show();
        }
    });
</script>
@endpush
