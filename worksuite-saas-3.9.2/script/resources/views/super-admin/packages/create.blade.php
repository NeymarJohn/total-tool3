@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('super-admin.packages.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">

    <style>
        .m-b-10{
            margin-bottom: 10px;
        }
        .mt-10{
            margin-top: 10px;
        }
        .hide-box{
            display: none;
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('app.add') @lang('app.package') @lang('app.info')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createClient','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label required">@lang('app.name')</label>
                                            <input type="text" id="name" name="name" value="" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label required">@lang('app.max') @lang('app.menu.employees')</label>
                                            <input type="number" name="max_employees" id="max_employees" value=""  class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <h3 class="box-title">Storage </h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label required">@lang('app.maxStorageSize')</label>
                                            <input type="number" min="-1" id="max_storage_size" name="max_storage_size" value="" class="form-control" >
                                            <p class="text-bold">Set -1 for unlimited storage size</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label required">@lang('app.storageUnit')</label>
                                            <select name="storage_unit" id="storage_unit" class="form-control">
                                                <option value="mb">@lang('app.mb')</option>
                                                <option value="gb">@lang('app.gb')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">

                                            <div class="checkbox checkbox-info">
                                                <input id="is_free" name="is_free" value="true"

                                                       type="checkbox">
                                                <label for="is_free">@lang('app.freePlan')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 hide-box">
                                        <div class="form-group">

                                            <div class="checkbox checkbox-info">
                                                <input id="is_auto_renew" name="is_auto_renew" value="true"

                                                       type="checkbox">
                                                <label for="is_auto_renew">@lang('app.autoRenew')</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">@lang('app.position')</label>
                                            <input type="number" id="position"  name="sort" value="{{ $position }}" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <h3 class="box-title payment-title">Payment Gateway Plans </h3>
                                <div class="row payment-box">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label class="control-label required">@lang('app.monthly') @lang('app.price') ({{ $global->currency->currency_symbol }})</label>
                                            <input type="number" name="monthly_price" id="monthly_price"  value=""   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label required">@lang('app.annual') @lang('app.price') ({{ $global->currency->currency_symbol }})</label>
                                            <input type="number" name="annual_price" id="annual_price" value=""  class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row payment-box">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('modules.package.stripeMonthlyPlanId')</label>
                                            <input type="text" name="stripe_monthly_plan_id" id="stripe_monthly_plan_id" value=""  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">@lang('modules.package.stripeAnnualPlanId')</label>
                                            <input type="text" id="stripe_annual_plan_id" name="stripe_annual_plan_id" value="" class="form-control" >
                                        </div>
                                    </div>

                                </div>
                                <div class="row payment-box">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('modules.package.razorpayMonthlyPlanId')</label>
                                            <input type="text" name="razorpay_monthly_plan_id" id="razorpay_monthly_plan_id" value=""  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">@lang('modules.package.razorpayAnnualPlanId')</label>
                                            <input type="text" id="razorpay_annual_plan_id" name="razorpay_annual_plan_id" value="" class="form-control" >
                                        </div>
                                    </div>

                                </div>
                                <div class="row ">
                                    <div class="col-md-6 payment-box">
                                        <div class="form-group">
                                            <label>@lang('modules.package.paystackMonthlyPlanId')</label>
                                            <input type="text" name="paystack_monthly_plan_id" id="paystack_monthly_plan_id" value=""  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 payment-box">
                                        <div class="form-group">
                                            <label class="control-label">@lang('modules.package.paystackAnnualPlanId')</label>
                                            <input type="text" id="paystack_annual_plan_id" name="paystack_annual_plan_id" value="" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">

                                                <div class="checkbox checkbox-info">
                                                    <input id="private-task" name="is_private" value="true"

                                                           type="checkbox">
                                                    <label for="private-task">@lang('modules.tasks.makePrivate') <a
                                                                class="mytooltip font-12" href="javascript:void(0)"> <i
                                                                    class="fa fa-info-circle"></i><span
                                                                    class="tooltip-content5"><span
                                                                        class="tooltip-text3"><span
                                                                            class="tooltip-inner2">@lang('modules.package.privateInfo')</span></span></span></a></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mt-10">
                                                <label class="control-label"> @lang('modules.package.isRecommended') </label>
                                                <div class="col-sm-2">
                                                    <div class="switchery-demo">
                                                        <input type="checkbox" id="is_recommended"
                                                               name="is_recommended"
                                                               class="js-switch" data-size="small" data-color="#00c292"
                                                               data-secondary-color="#f96262"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </div>

                                <h3 class="box-title">@lang('app.select') @lang('app.module')</h3>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="checkbox checkbox-info  col-md-10">
                                                <input id="select_all_permission"

                                                        class="select_all_permission" type="checkbox">
                                                <label for="select_all_permission">@lang('modules.permission.selectAll')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row form-group module-in-package">
                                            @foreach($modules as $module)
                                                <div class="col-md-2">
                                                    <div class="checkbox checkbox-inline checkbox-info m-b-10">
                                                        <input id="{{ $module->module_name }}" name="module_in_package[{{ $module->id }}]" value="{{ $module->module_name }}" class="module_checkbox"
                                                               type="checkbox">
                                                        <label for="{{ $module->module_name }}">{{  __('modules.module.'.$module->module_name) }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label required">@lang('app.description')</label>
                                            <textarea name="description"  id="description"  rows="5" value="" class="form-control"></textarea>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="form-actions">
                                <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>

                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>


<script>
    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function (elem) {
        new Switchery($(this)[0], $(this).data());

    });
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('super-admin.packages.store')}}',
            container: '#createClient',
            type: "POST",
            redirect: true,
            data: $('#createClient').serialize()
        })
    });

    $('.select_all_permission').change(function () {
        if($(this).is(':checked')){
            $('.module_checkbox').prop('checked', true);
        } else {
            $('.module_checkbox').prop('checked', false);
        }
    });
    $('#is_free').change(function () {
        if($(this).is(':checked')){
            $('.hide-box').show();
            $('.payment-title').hide();
            $('.payment-box').hide();
        } else {
            $('.hide-box').hide();
            $('.payment-title').show();
            $('.payment-box').show();
        }
    });
</script>
@endpush

