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
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">

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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.expenses.itemName')</label>
                                        <input type="text" name="item_name" id="item_name" value="{{ $expense->item_name }}" class="form-control">
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
                                                <option @if($category->id == $expense->category_id) selected @endif value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noProjectCategoryAdded')</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('app.description')</label>
                                        <textarea type="text" name="description" class="form-control summernote"> {{ $expense->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label>@lang('modules.messages.chooseMember')</label>
                                        <select id="user_id" class="select2 form-control" data-placeholder="@lang('modules.messages.chooseMember')" name="user_id">
                                            @foreach($employees as $employee)
                                                <option  @if($employee['user']['id'] == $expense->user_id) selected @endif  value="{{ $employee['user']['id'] }}">{{ ucwords($employee['user']['name']) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!--/span-->

                                <div class="col-sm-12 col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="project_id">@lang('modules.invoices.project')</label>
                                        <select class="select2 form-control" id="project_id" name="project_id">
                                            <option value="0">@lang('app.selectProject')</option>
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
                                        <label class="required">@lang('app.price')</label>
                                        <input type="text" name="price" id="price" value="{{ $expense->price }}" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-sm-12 col-md-6 col-xs-12">
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

                                <!--/span-->
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.invoices.billingFrequency')</label>
                                        <select class="form-control" onchange="changeRotation(this.value);" name="rotation" id="rotation">
                                            <option value="daily">@lang('app.daily')</option>
                                            <option value="weekly">@lang('app.weekly')</option>
                                            <option value="bi-weekly">@lang('app.bi-weekly')</option>
                                            <option value="monthly">@lang('app.monthly')</option>
                                            <option value="quarterly">@lang('app.quarterly')</option>
                                            <option value="half-yearly">@lang('app.half-yearly')</option>
                                            <option value="annually">@lang('app.annually')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 dayOfWeek">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('modules.expensesRecurring.dayOfWeek') </label>
                                        <select class="form-control"  name="day_of_week" id="dayOfWeek">
                                            <option value="1">@lang('app.sunday')</option>
                                            <option value="2">@lang('app.monday')</option>
                                            <option value="3">@lang('app.tuesday')</option>
                                            <option value="4">@lang('app.wednesday')</option>
                                            <option value="5">@lang('app.thursday')</option>
                                            <option value="6">@lang('app.friday')</option>
                                            <option value="7">@lang('app.saturday')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 dayOfMonth">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('modules.expensesRecurring.dayOfMonth')</label>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <select class="form-control"  name="day_of_month" id="dayOfMonth">
                                                    @for($m=1; $m<=31; ++$m)
                                                        <option value="{{ $m }}">{{ $m }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 billingInterval">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.invoices.billingCycle') </label>
                                        <div class="input-icon">
                                            <input type="number" class="form-control" name="billing_cycle" id="billing_cycle"
                                                   @if($expense->unlimited_recurring == 1)
                                                        value="-1"
                                                   @else
                                                        value="{{ $expense->billing_cycle }}"
                                                   @endif
                                            >
                                        </div>
                                        <p class="text-muted">@lang('messages.setForInfinite')</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label for="usd_price">@lang('app.status') </label>
                                        <select class="form-control" name="status">
                                            <option @if($expense->status == 'active') selected @endif value="active">@lang('app.active')</option>
                                            <option  @if($expense->status == 'inactive') selected @endif  value="inactive">@lang('app.inactive')</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.bill')</label>
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                                            <span class="input-group-addon btn btn-default btn-file"> <span class="fileinput-new">@lang('app.selectFile')</span> <span class="fileinput-exists">@lang('app.change')</span>
                                            <input type="file" name="bill" id="bill">
                                            </span> <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">@lang('app.remove')</a>
                                        </div>
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
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script>
    var employees = @json($employees);
    var expense = @json($expense);

    $('#rotation').val(expense['rotation']);
    $('#dayOfWeek').val(expense['day_of_week']);
    $('#dayOfMonth').val(expense['day_of_month']);
//    changeRotation(expense.rotation);

    $(function () {
        changeRotation(expense.rotation);
    });
    var defaultOpt = '<option @if(is_null($expense->project_id)) selected @endif value="0">Select Project...</option>'

    var employee = employees.filter(function (item) {
        return item.user_id == expense.user_id
    })

    var options =  '';

    employee[0].user.projects.forEach(project => {
        options += `<option ${project.id === expense.project_id ? 'selected' : ''} value='${project.id}'>${project.project_name}</option>`
    });



    $('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen"]]
        ]
    });


    function changeRotation (rotationValue){
        if(rotationValue == 'weekly' || rotationValue == 'bi-weekly'){
            $('.dayOfWeek').show().fadeIn(300);
            $('.dayOfMonth').hide().fadeOut(300);
        }
        else if(rotationValue == 'monthly' || rotationValue == 'quarterly' || rotationValue == 'half-yearly' || rotationValue == 'annually'){
            $('.dayOfWeek').hide().fadeOut(300);
            $('.dayOfMonth').show().fadeIn(300);
        }
        else{
            $('.dayOfWeek').hide().fadeOut(300);
            $('.dayOfMonth').hide().fadeOut(300);
        }
    }

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
            url: '{{route('admin.expenses-recurring.update', $expense->id)}}',
            container: '#updateExpense',
            type: "POST",
            redirect: true,
            file: (document.getElementById("bill").files.length == 0) ? false : true,
            data: $('#updateExpense').serialize()
        })
    });

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
