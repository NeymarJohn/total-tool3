@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-6 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">{{ __($pageTitle) }}</div>

                <div class="vtabs customvtab m-t-10">

                    @include('sections.super_admin_payment_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-xs-12">

                                    <h3 class="box-title m-b-0">@lang('app.menu.onlinePayment')</h3>

                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12 ">
                                            {!! Form::open(['id'=>'updateSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                                            <div class="form-body">
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li class="nav-item active">
                                                        <a class="nav-link active" data-toggle="tab" href="#Paypal" role="tab" aria-selected="true">
                                                        <span class="hidden-sm-up">
                                                            <i class="fa fa-paypal"></i>
                                                        </span>
                                                            <span class="hidden-xs-down">Paypal @if($credentials->paypal_status == 'active') <i class="fa fa-check-circle activated-gateway"></i> @endif</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-toggle="tab" href="#Stripe" role="tab" aria-selected="true">
                                                        <span class="hidden-sm-up">
                                                            <i class="fa fa-cc-stripe"></i>
                                                        </span>
                                                            <span class="hidden-xs-down">Stripe @if($credentials->stripe_status == 'active') <i class="fa fa-check-circle activated-gateway"></i> @endif</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-toggle="tab" href="#Razorpay" role="tab" aria-selected="true">
                                                        <span class="hidden-sm-up">
                                                            {{--<i class=" display-small"></i>--}}
                                                            <img src="{{ asset('img/razorpay.svg') }}" width="45px" class="display-small">
                                                        </span>
                                                            <span class="hidden-xs-down">Razorpay @if($credentials->razorpay_status == 'active') <i class="fa fa-check-circle activated-gateway"></i> @endif</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-toggle="tab" href="#Paystack" role="tab" aria-selected="true">
                                                        <span class="hidden-sm-up">
                                                             <img src="{{ asset('img/paystack.png') }}" width="45px" class="display-small">
                                                        </span>
                                                            <span class="hidden-xs-down">Paystack @if($credentials->paystack_status == 'active') <i class="fa fa-check-circle activated-gateway"></i> @endif</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-toggle="tab" href="#Mollie" role="tab" aria-selected="true">
                                                        <span class="hidden-sm-up">
                                                             <img src="{{ asset('img/mollie.svg') }}" width="35px" class="display-small">
                                                        </span>
                                                            <span class="hidden-xs-down">Mollie @if($credentials->mollie_status == 'active') <i class="fa fa-check-circle activated-gateway"></i> @endif</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-toggle="tab" href="#authorize" role="tab" aria-selected="true">
                                                        <span class="hidden-sm-up">
                                                             <img src="{{ asset('img/authorize.jpg') }}" width="25px" class="display-small">
                                                        </span>
                                                            <span class="hidden-xs-down">Authorize.net @if($credentials->authorize_status == 'active') <i class="fa fa-check-circle activated-gateway"></i> @endif</span>
                                                        </a>
                                                    </li>
                                                </ul>

                                                <div class="tab-content tabcontent-border">
                                                    <div class="tab-pane active" id="Paypal" role="tabpanel">
                                                        {{--<div class="p-20">--}}
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('modules.paymentSetting.paypalClientId')</label>
                                                                <input type="text" name="paypal_client_id" id="paypal_client_id"
                                                                       class="form-control" value="{{ $credentials->paypal_client_id }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('modules.paymentSetting.paypalSecret')</label>
                                                                <input type="password" name="paypal_secret"
                                                                       id="paypal_secret"
                                                                       class="form-control"
                                                                       value="{{ $credentials->paypal_secret }}">
                                                                <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                            </div>
                                                        </div>


                                                        <!--/span-->
                                                        <div class="col-md-6">

                                                            <div class="form-group">
                                                                <label>@lang('modules.paymentSetting.selectEnvironment')</label>
                                                                <select class="form-control" name="paypal_mode" id="paypal_mode" data-style="form-control">
                                                                    <option value="sandbox" @if($credentials->paypal_mode == 'sandbox') selected @endif>Sandbox</option>
                                                                    <option value="live" @if($credentials->paypal_mode == 'live') selected @endif>Live</option>
                                                                </select>
                                                            </div>
                                                        </div>


                                                        <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="mail_from_name">@lang('app.webhook')</label>
                                                                    <p class="text-bold">{{ route('verify-billing-ipn') }}</p>
                                                                    <p class="text-info">(@lang('messages.addPaypalWebhookUrl')
                                                                        )</p>
                                                                </div>
                                                            </div>
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label" >@lang('modules.payments.paypalStatus')</label>
                                                                <div class="switchery-demo">
                                                                    <input
                                                                            type="checkbox"
                                                                            data-type-name="paypal"
                                                                            name="paypal_status"
                                                                            @if($credentials->paypal_status == 'active') checked @endif
                                                                            class="js-switch special" id="paypalButton"
                                                                            data-color="#00c292"
                                                                            data-secondary-color="#f96262"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{--</div>--}}
                                                    </div>
                                                    <div class="tab-pane" id="Stripe" role="tabpanel">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('modules.paymentSetting.stripeClientId')</label>
                                                                <input type="text" name="api_key" id="stripe_client_id"
                                                                       class="form-control" value="{{ $credentials->api_key }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('modules.paymentSetting.stripeSecret')</label>
                                                                <input type="password" name="api_secret" id="stripe_secret"
                                                                       class="form-control" value="{{ $credentials->api_secret }}">
                                                                <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <label>@lang('modules.paymentSetting.stripeWebhookSecret')</label>
                                                                <input type="password" name="webhook_key" id="stripe_webhook_secret"
                                                                       class="form-control" value="{{ $credentials->webhook_key }}">


                                                                <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                            </div>
                                                            <input type="hidden" name="bothUncheck" id="bothUncheck" >
                                                            <input type="hidden" name="type" id="type" >
                                                            <input type="hidden" name="_method" id="method" >

                                                        </div>
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <label for="mail_from_name">@lang('app.webhook')</label>
                                                                <p class="text-bold">{{ route('save_webhook') }}</p>
                                                                <p class="text-info">(@lang('messages.addStripeWebhookUrl'))</p>
                                                                <p class="text-info">(@lang('messages.addStripeWebhookUrlMethod'))</p>
                                                            </div>
                                                        </div>
                                                        <!--/span-->

                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label" >@lang('modules.payments.stripeStatus')</label>
                                                                <div class="switchery-demo">
                                                                    <input
                                                                            type="checkbox"
                                                                            data-type-name="stripe"
                                                                            name="stripe_status"
                                                                            @if($credentials->stripe_status == 'active') checked @endif
                                                                            class="js-switch"
                                                                            data-color="#00c292" id="stripeButton"
                                                                            data-secondary-color="#f96262"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="Razorpay" role="tabpanel">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="">@lang('modules.paymentSetting.RazorpayKey')</label>
                                                                <input type="text" name="razorpay_key" id="razorpay_key"
                                                                       class="form-control" value="{{ $credentials->razorpay_key }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('modules.paymentSetting.razorpaySecretKey')</label>
                                                                <input type="password" name="razorpay_secret" id="razorpay_secret"
                                                                       class="form-control" value="{{ $credentials->razorpay_secret }}">
                                                                <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('modules.paymentSetting.razorpayWebhookSecretKey')</label>
                                                                <input type="password" name="razorpay_webhook_secret" id="razorpay_webhook_secret"
                                                                       class="form-control" value="{{ $credentials->razorpay_webhook_secret }}">
                                                                <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mail_from_name">@lang('app.webhook')</label>
                                                                <p class="text-bold">{{ route('save_razorpay-webhook') }}</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label" >@lang('modules.payments.razorpayStatus')</label>
                                                                <div class="switchery-demo">
                                                                    <input type="checkbox" name="razorpay_status" @if($credentials->razorpay_status == 'active') checked @endif class="js-switch " data-color="#00c292" data-secondary-color="#f96262"  />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="Paystack" role="tabpanel">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="">@lang('modules.paymentSetting.paystackKey')</label>
                                                                <input type="text" name="paystack_client_id" id="paystack_client_id"
                                                                       class="form-control" value="{{ $credentials->paystack_client_id }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('modules.paymentSetting.PaystackSecretKey')</label>
                                                                <input type="password" name="paystack_secret" id="paystack_secret"
                                                                       class="form-control" value="{{ $credentials->paystack_secret }}">
                                                                <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('modules.paymentSetting.paystackMerchantEmail')</label>
                                                                <input type="text" name="paystack_merchant_email" id="paystack_merchant_email"
                                                                       class="form-control" value="{{ $credentials->paystack_merchant_email }}">
                                                            </div>
                                                        </div>


                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label" >@lang('modules.paymentSetting.paystackStatus')</label>
                                                                <div class="switchery-demo">
                                                                    <input type="checkbox" name="paystack_status" @if($credentials->paystack_status == 'active') checked @endif class="js-switch " data-color="#00c292" data-secondary-color="#f96262"  />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mail_from_name">@lang('app.webhook')</label>
                                                                <p class="text-bold">{{ route('save_paystack-webhook') }}</p>
                                                                <p class="text-info">(@lang('messages.addPaystackWebhookUrl')
                                                                    )</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mail_from_name">@lang('app.callback')</label>
                                                                <p class="text-bold">{{ route('admin.payments.paystack.callback') }}</p>
                                                                <p class="text-info">(@lang('messages.addPaystackCallbackUrl')
                                                                    )</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="Mollie" role="tabpanel">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <label class="">@lang('modules.paymentSetting.mollieApiKey')</label>
                                                                <input type="text" name="mollie_api_key" id="paystack_client_id"
                                                                       class="form-control" value="{{ $credentials->mollie_api_key }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label" >@lang('modules.paymentSetting.mollieStatus')</label>
                                                                <div class="switchery-demo">
                                                                    <input type="checkbox" name="mollie_status" @if($credentials->mollie_status == 'active') checked @endif class="js-switch " data-color="#00c292" data-secondary-color="#f96262"  />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="authorize" role="tabpanel">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <label>@lang('modules.paymentSetting.authorizeApiLoginId')</label>
                                                                <input type="password" name="authorize_api_login_id" id="authorize_api_login_id"
                                                                       class="form-control" value="{{ $credentials->authorize_api_login_id }}">
                                                                <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <label> @lang('modules.paymentSetting.authorizeTransactionKey')</label>
                                                                <input type="password" name="authorize_transaction_key" id="authorize_transaction_key"
                                                                       class="form-control" value="{{ $credentials->authorize_transaction_key }}">
                                                                <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>@lang('modules.paymentSetting.authorizeSignatureKey')</label>
                                                                <input type="password" name="authorize_signature_key" id="authorize_signature_key"
                                                                       class="form-control" value="{{ $credentials->authorize_signature_key }}">
                                                                <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                            </div>



                                                            {{--<div class="form-group">--}}
                                                            {{--<label for="mail_from_name">@lang('app.webhook')</label>--}}
                                                            {{--<p class="text-bold">{{ route('verify-ipn') }}</p>--}}
                                                            {{--<p class="text-info">(@lang('messages.addPaypalWebhookUrl'))</p>--}}
                                                            {{--</div>--}}
                                                        </div>
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <h5>Select environment</h5>
                                                                <select class="form-control" name="authorize_environment" id="authorize_environment" data-style="form-control">
                                                                    <option value="sandbox" @if($credentials->authorize_environment == 'sandbox') selected @endif>Sandbox</option>
                                                                    <option value="live" @if($credentials->authorize_environment == 'live') selected @endif>Live</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label" >@lang('modules.paymentSetting.authorizeStatus')</label>
                                                                <div class="switchery-demo">
                                                                    <input type="checkbox" name="authorize_status" @if($credentials->authorize_status == 'active') checked @endif class="js-switch " data-color="#00c292" data-secondary-color="#f96262"  />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="mail_from_name">@lang('app.webhook')</label>
                                                                    <p class="text-bold">{{ route('save_authorize_webhook') }}</p>
                                                                    <p class="text-info">(@lang('messages.addAuthorizeWebhookUrl')
                                                                        )</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                </div>

                                                <!--/row-->

                                            </div>
                                            <div class="form-actions m-t-20">
                                                <button type="submit" id="save-form" class="btn btn-success"><i class="fa fa-check"></i>
                                                    @lang('app.save')
                                                </button>

                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>

                                </div>
                                <!-- .row -->

                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
        <!-- .row -->


        @endsection

        @push('footer-script')
        <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
        <script>

            $('.js-switch').each(function() {
                new Switchery($(this)[0], $(this).data());
            });

            $('#save-form').click(function () {
                var url = '{{route('super-admin.payment-settings.update', $credentials->id)}}';
                $('#method').val('PUT');
                $.easyAjax({
                    url: url,
                    type: "POST",
                    container: '#updateSettings',
                    data: $('#updateSettings').serialize(),
                    success: function(res) {
                        if(res.status == 'success') {
                            window.location.reload();
                        }
                    }
                })
            });

        </script>
    @endpush
