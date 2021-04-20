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
            <li class="active">{{ __($pageTitle) }}</li>
        </ol>
    </div>
    <!-- /.breadcrumb -->
</div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush

@section('content')

<div class="row">
    @if(!$global->hide_cron_message)
    <div class="col-xs-12">
        <div class="alert alert-info ">
            <h5 class="text-white">Set following cron command on your server (Ignore if already done)</h5>
            @php
            try {
            echo '<code>* * * * * '.PHP_BINDIR.'/php  '. base_path() .'/artisan schedule:run >> /dev/null 2>&1</code>';
            } catch (\Throwable $th) {
            echo '<code>* * * * * /php'. base_path() .'/artisan schedule:run >> /dev/null 2>&1</code>';
            }
            @endphp
        </div>
    </div>
    @endif

    @if($global->show_public_message)
    <div class="col-xs-12">
        <div class="alert alert-success">
            <h4>Remove public from URL</h4>
            <h5 class="text-white">Create a file with the name <code>.htaccess</code> at the root of folder
                (where app, bootstrap, config folder resides) and add the following content</h5>

            <pre>
                        <code class="apache hljs">
<span class="hljs-section">&lt;IfModule mod_rewrite.c&gt;</span>

  <span class="hljs-attribute">RewriteEngine </span><span class="hljs-literal"> On</span>
  <span class="hljs-attribute"><span class="hljs-nomarkup">RewriteRule</span></span><span class="hljs-variable"> ^(.*)$ public/$1</span><span
                                    class="hljs-meta"> [L]</span>

<span class="hljs-section">&lt;/IfModule&gt;</span>
</code></pre>
        </div>

    </div>
    @endif
    <div class="col-xs-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                @lang('modules.accountSettings.updateTitle')
                @if(!module_enabled('RestAPI'))
                @if($cachedFile)

                <a href="javascript:;" id="clear-cache" class="btn btn-sm btn-danger pull-right m-l-5 text-white"><i
                        class="fa fa-times"></i> @lang('app.disableCache')</a>
                <h6 class="text-white pull-right m-r-5">@lang('messages.cacheEnabled')</h6>
                @else


                <a href="javascript:;" id="refresh-cache" class="btn btn-sm btn-success pull-right text-white">
                    <i class="fa fa-check"></i> @lang('app.enableCache')</a>
                <h6 class="text-black pull-right m-r-5">@lang('messages.cacheDisabled')</h6>
                @endif
                @endif

            </div>

            <div class="vtabs customvtab m-t-10">
                @include('sections.super_admin_setting_menu')
                <div class="tab-content">
                    <div id="vhome3" class="tab-pane active">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label
                                                for="company_name">@lang('modules.accountSettings.companyName')</label>
                                            <input type="text" class="form-control" id="company_name"
                                                name="company_name" value="{{ $global->company_name }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label
                                                for="company_email">@lang('modules.accountSettings.companyEmail')</label>
                                            <input type="email" class="form-control" id="company_email"
                                                name="company_email" value="{{ $global->company_email }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label
                                                for="company_phone">@lang('modules.accountSettings.companyPhone')</label>
                                            <input type="tel" class="form-control" id="company_phone"
                                                name="company_phone" value="{{ $global->company_phone }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputPassword1">@lang('modules.accountSettings.companyWebsite')</label>
                                            <input type="text" class="form-control" id="website" name="website"
                                                value="{{ $global->website }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="company_phone">@lang('modules.invoices.currency')</label>
                                            <select class="form-control" id="currency_id" name="currency_id">
                                                @forelse($currencies as $currency)
                                                <option @if($currency->id == $global->currency_id) selected
                                                    @endif value="{{ $currency->id }}">
                                                    {{ $currency->currency_name }} -
                                                    ({{ $currency->currency_symbol }})
                                                </option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label
                                                for="address">@lang('modules.accountSettings.defaultTimezone')</label>
                                            <select name="timezone" id="timezone" class="form-control select2">
                                                @foreach($timezones as $tz)
                                                <option @if($global->timezone == $tz) selected @endif>{{ $tz
                                                            }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="address">@lang('modules.accountSettings.changeLanguage')</label>
                                            <select name="locale" id="locale" class="form-control select2">
                                                <option @if($global->locale == "en") selected @endif value="en">
                                                    English
                                                </option>
                                                @foreach($languageSettings as $language)
                                                <option value="{{ $language->language_code }}" @if($global->locale ==
                                                    $language->language_code) selected @endif >
                                                    {{ $language->language_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputPassword1">@lang('modules.accountSettings.companyLogo')</label>

                                            <div class="col-xs-12">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail"
                                                        style="width: 200px; height: 150px;">
                                                        <img src="{{ $global->logo_url }}" alt="" />
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                                        style="max-width: 200px; max-height: 150px;"></div>
                                                    <div>
                                                        <span class="btn btn-info btn-file">
                                                            <span class="fileinput-new"> @lang('app.selectImage')
                                                            </span>
                                                            <span class="fileinput-exists"> @lang('app.change') </span>
                                                            <input type="file" name="logo" id="logo"> </span>
                                                        <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                            data-dismiss="fileinput"> @lang('app.remove') </a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label
                                                for="favicon">@lang('modules.accountSettings.faviconImage')</label>

                                            <div class="col-xs-12">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail"
                                                        style="width: 200px; height: 150px;">
                                                        <img src="{{ $global->favicon_url }}" alt="" />
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                                        style="max-width: 200px; max-height: 150px;"></div>
                                                    <div>
                                                        <span class="btn btn-info btn-file">
                                                            <span class="fileinput-new"> @lang('app.selectImage')
                                                            </span>
                                                            <span class="fileinput-exists"> @lang('app.change') </span>
                                                            <input type="file" name="favicon" id="favicon"> </span>
                                                        <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                            data-dismiss="fileinput"> @lang('app.remove') </a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputPassword1">@lang('modules.accountSettings.frontLogo')</label>

                                            <div class="col-xs-12">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail"
                                                        style="width: 200px; height: 150px;">
                                                        <img src="{{ $global->logo_front_url }}" alt="" />
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                                        style="max-width: 200px; max-height: 150px;"></div>
                                                    <div>
                                                        <span class="btn btn-info btn-file">
                                                            <span class="fileinput-new"> @lang('app.selectImage')
                                                            </span>
                                                            <span class="fileinput-exists"> @lang('app.change') </span>
                                                            <input type="file" name="logo_front" id="logo_front">
                                                        </span>
                                                        <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                            data-dismiss="fileinput"> @lang('app.remove') </a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-sm-12 col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label>@lang('modules.themeSettings.loginScreenBackground')</label>

                                            <div class="col-md-12 m-b-20">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail"
                                                        style="width: 200px; height: 150px;">
                                                        <img src="{{ $global->login_background_url }}" alt="" />
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                                        style="max-width: 200px; max-height: 150px;"></div>
                                                    <div>
                                                        <span class="btn btn-info btn-file">
                                                            <span class="fileinput-new"> @lang('app.selectImage')
                                                            </span>
                                                            <span class="fileinput-exists"> @lang('app.change') </span>
                                                            <input type="file" name="login_background"
                                                                id="login_background"> </span>
                                                        <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                            data-dismiss="fileinput"> @lang('app.remove') </a>
                                                    </div>
                                                </div>
                                                <div class="note">Recommended size: 1500 X 1056 (Pixels)</div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="address">@lang('modules.accountSettings.companyAddress')</label>
                                            <textarea class="form-control" id="address" rows="5"
                                                name="address">{{ $global->address }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="address">@lang('modules.accountSettings.expiredMessage')</label>
                                            <textarea class="form-control" id="expired_message" rows="5"
                                                name="expired_message">{{ $global->expired_message }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="address">@lang('modules.accountSettings.weekStartFrom')</label>
                                            <select name="week_start" id="week_start" class="form-control select2">
                                                <option value="0" @if($global->week_start == '0') selected @endif >
                                                    @lang('app.sunday')
                                                </option>
                                                <option value="1" @if($global->week_start == '1') selected @endif>
                                                    @lang('app.monday')
                                                </option>
                                                <option value="2" @if($global->week_start == '2') selected @endif>
                                                    @lang('app.tuesday')
                                                </option>
                                                <option value="3" @if($global->week_start == '3') selected @endif>
                                                    @lang('app.wednesday')
                                                </option>
                                                <option value="4" @if($global->week_start == '4') selected @endif>
                                                    @lang('app.thursday')
                                                </option>
                                                <option value="5" @if($global->week_start == '5') selected @endif>
                                                    @lang('app.friday')
                                                </option>
                                                <option value="6" @if($global->week_start == '6') selected @endif>
                                                    @lang('app.saturday')
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="save-form"
                                    class="btn btn-success waves-effect waves-light m-r-10">
                                    @lang('app.update')
                                </button>

                                {!! Form::close() !!}
                            </div>
                        </div>

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
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>

<script>
    // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());

        });

        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        $('#refresh-cache').click(function () {
            $.easyAjax({
                url: '{{url("refresh-cache")}}',
                type: "GET",
                success: function () {
                    window.location.reload();
                }
            })
        });

        $('#clear-cache').click(function () {
            $.easyAjax({
                url: '{{url("clear-cache")}}',
                type: "GET",
                success: function () {
                    window.location.reload();
                }
            })
        });

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('super-admin.settings.update', $global->id)}}',
                container: '#editSettings',
                type: "POST",
                redirect: true,
                file: true,
            })
        });

</script>
@endpush