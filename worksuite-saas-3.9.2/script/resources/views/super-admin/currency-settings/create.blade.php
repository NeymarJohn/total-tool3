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
                <li><a href="{{ route('super-admin.currency.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="white-box">
                <h3 class="box-title m-b-0">@lang('modules.currencySettings.addNewCurrency')</h3>

                <p class="text-muted m-b-30 font-13"></p>

                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        {!! Form::open(['id'=>'createCurrency','class'=>'ajax-form','method'=>'POST']) !!}
                        <div class="form-group">
                            <label for="company_name">@lang('modules.currencySettings.currencyName')</label>
                            <input type="text" class="form-control" id="currency_name" name="currency_name"
                                   placeholder="Enter Currency Name">
                        </div>
                        <div class="form-group">
                            <label>@lang('modules.currencySettings.isCryptoCurrency')?</label>
                            <div class="radio-list">
                                <label class="radio-inline p-0">
                                    <div class="radio radio-info">
                                        <input type="radio" name="is_cryptocurrency" id="crypto_currency_yes" value="yes">
                                        <label for="crypto_currency_yes">@lang('app.yes')</label>
                                    </div>
                                </label>
                                <label class="radio-inline">
                                    <div class="radio radio-info">
                                        <input type="radio" name="is_cryptocurrency" checked id="crypto_currency_no" value="no">
                                        <label for="crypto_currency_no">@lang('app.no')</label>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_email">@lang('modules.currencySettings.currencySymbol')</label>
                            <input type="text" class="form-control" id="currency_symbol" name="currency_symbol">
                        </div>
                        <div class="form-group">
                            <label>@lang('modules.currencySettings.currencyPosition')?</label>
                            <div class="radio-list">
                                <label class="radio-inline p-0">
                                    <div class="radio radio-info">
                                        <input type="radio" name="currency_position"  id="currency_position" value="front" checked>
                                        <label for="currency_position">@lang('modules.currencySettings.front') ( @lang('modules.currencySettings.example'): <span class="currency_symbol_show">$</span>1000)</label>
                                    </div>
                                </label>
                                <label class="radio-inline">
                                    <div class="radio radio-info">
                                        <input type="radio" name="currency_position"  id="currency_position" value="behind">
                                        <label for="currency_position">@lang('modules.currencySettings.behind') ( @lang('modules.currencySettings.example') : 1000<span class="currency_symbol_show">$</span>) </label>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_phone">@lang('modules.currencySettings.currencyCode')</label>
                            <input type="text" class="form-control" id="currency_code" name="currency_code" >
                        </div>
                        <div class="form-group crypto-currency" style="display: none">
                            <label for="usd_price">@lang('modules.currencySettings.usdPrice') <a class="mytooltip" href="javascript:void(0)"> <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">@lang('modules.currencySettings.usdPriceInfo')</span></span></span></a></label>
                            <input type="text" class="form-control" id="usd_price" name="usd_price" >
                        </div>

                        <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                            @lang('app.save')
                        </button>
                        <button type="reset" class="btn btn-inverse waves-effect waves-light">@lang('app.reset')</button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
<script>

    $("input[name=is_cryptocurrency]").click(function () {
        if($(this).val() == 'yes'){
            $('.regular-currency').hide();
            $('.crypto-currency').show();
        }
        else{
            $('.crypto-currency').hide();
            $('.regular-currency').show();
        }
    })

    $("input[name=currency_symbol]").change(function () {
        $('.currency_symbol_show').html($('#currency_symbol').val())
    })

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('super-admin.currency.store')}}',
            container: '#createCurrency',
            type: "POST",
            redirect: true,
            data: $('#createCurrency').serialize()
        })
    });
</script>
@endpush

