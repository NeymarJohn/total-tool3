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
                <li><a href="{{ route('admin.currency.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.update')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.currencySettings.updateTitle')</div>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {!! Form::open(['id'=>'updateCurrency','class'=>'ajax-form','method'=>'PUT']) !!}
                                <div class="form-group">
                                    <label for="currency_name" class="required">@lang("modules.currencySettings.currencyName")</label>
                                    <input type="text" class="form-control" id="currency_name" name="currency_name" value="{{ $currency->currency_name }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('modules.currencySettings.isCryptoCurrency')?</label>
                                    <div class="radio-list">
                                        <label class="radio-inline p-0">
                                            <div class="radio radio-info">
                                                <input type="radio" name="is_cryptocurrency" @if($currency->is_cryptocurrency == 'yes') checked @endif id="crypto_currency_yes" value="yes">
                                                <label for="crypto_currency_yes">@lang('app.yes')</label>
                                            </div>
                                        </label>
                                        <label class="radio-inline">
                                            <div class="radio radio-info">
                                                <input type="radio" name="is_cryptocurrency" @if($currency->is_cryptocurrency == 'no') checked @endif id="crypto_currency_no" value="no">
                                                <label for="crypto_currency_no">@lang('app.no')</label>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <label for="currency_symbol" class="required">@lang("modules.currencySettings.currencySymbol")</label>
                                    <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" value="{{ $currency->currency_symbol }}">
                                </div>
                                <div class="form-group">
                                    <label for="currency_code" class="required">@lang("modules.currencySettings.currencyCode")</label>
                                    <input type="text" class="form-control" id="currency_code" name="currency_code" value="{{ $currency->currency_code }}">
                                </div>

                                <div class="form-group crypto-currency" @if($currency->is_cryptocurrency == 'no') style="display: none" @endif>
                                    <label for="usd_price">@lang('modules.currencySettings.usdPrice') <a class="mytooltip" href="javascript:void(0)"> <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">@lang('modules.currencySettings.usdPriceInfo')</span></span></span></a></label>
                                    <input type="text" class="form-control" id="usd_price" name="usd_price" value="{{ $currency->usd_price }}">
                                </div>

                                <div class="form-group regular-currency"  @if($currency->is_cryptocurrency == 'yes') style="display: none;" @endif>
                                    <label for="exchange_rate">@lang("modules.currencySettings.exchangeRate")</label>
                                    <input type="text" class="form-control" id="exchange_rate" name="exchange_rate" value="{{ $currency->exchange_rate }}">
                                    <a href="javascript:;" id="fetch-exchange-rate"><i class="fa fa-refresh fa-spin"></i> Fetch latest exchange rate</a>
                                </div>

                                <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                                    @lang('app.save')
                                </button>
                                <a href="{{route('admin.currency.index')}}" class="btn btn-default waves-effect waves-light">@lang('app.back')</a>
                                {!! Form::close() !!}
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
<script>
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.currency.update', $currency->id )}}',
            container: '#updateCurrency',
            type: "POST",
            data: $('#updateCurrency').serialize()
        })
    });

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

    $('#fetch-exchange-rate').click(function () {
        var currencyCode = $('#currency_code').val();
        var url = '{{route('admin.currency.exchange-rate', '#cc' )}}';
        url = url.replace('#cc', currencyCode);

        $.easyAjax({
            url: url,
            type: "GET",
            data: {currencyCode: currencyCode},
            success: function (response) {
                $('#exchange_rate').val(response);
            }
        })
    });
</script>
@endpush

