@extends('layouts.app')
<style>
    #sticky-note-toggle{display: none !important;}
</style>
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
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.clients.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.edit')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/image-picker/image-picker.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/steps-form/steps.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.menu.accountSetup')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div id="updateClient">
                        <!-- progressbar -->
                            <ul id="progressbar">
                                <li class="active">@lang('app.menu.accountSetup')</li>
                                <li>@lang('modules.accountSettings.updateTitle')</li>
                                <li>@lang('modules.invoiceSettings.updateTitle')</li>
                            </ul>
                            <!-- fieldsets -->
                            <fieldset>
                                <h2 class="fs-title">@lang('app.congratulations')!</h2>
                                <h3 class="fs-subtitle">@lang('app.paperlessOffice')</h3>
                                <input type="button" name="next" class="next action-button" value="Next" />
                            </fieldset>
                            <fieldset>
                                {!! Form::open(['id'=>'companySettings','class'=>'ajax-form','method'=>'PUT']) !!}
                                    <h3 class="fs-subtitle">@lang('modules.accountSettings.updateTitle')</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_name">@lang('modules.accountSettings.companyName')</label>
                                                <input type="text" class="form-control" id="company_name" name="company_name"
                                                       value="{{ $global->company_name }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_email">@lang('modules.accountSettings.companyEmail')</label>
                                                <input type="email" class="form-control" id="company_email" name="company_email"
                                                       value="{{ $global->company_email }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_phone">@lang('modules.accountSettings.companyPhone')</label>
                                                <input type="tel" class="form-control" id="company_phone" name="company_phone"
                                                       value="{{ $global->company_phone }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">@lang('modules.accountSettings.companyWebsite')</label>
                                                <input type="text" class="form-control" id="website" name="website"
                                                       value="{{ $global->website }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">@lang('modules.accountSettings.companyLogo')</label>
                                        <div class="col-xs-12">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail"
                                                     style="width: 200px; height: 150px;">
                                                    @if(is_null($global->logo))
                                                        <img src="https://via.placeholder.com/200x150.png?text={{ str_replace(' ', '+', __('modules.accountSettings.uploadLogo')) }}"
                                                             alt=""/>
                                                    @else
                                                        <img src="{{ asset_url('app-logo/'.$global->logo) }}"
                                                             alt=""/>
                                                    @endif
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                     style="max-width: 200px; max-height: 150px;"></div>
                                                <div>
                                                    <span class="btn btn-info btn-file">
                                                        <span class="fileinput-new"> @lang('app.selectImage') </span>
                                                        <span class="fileinput-exists"> @lang('app.change') </span>
                                                        <input type="file" name="logo" id="logo">
                                                    </span>
                                                    <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                       data-dismiss="fileinput"> @lang('app.remove') </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address">@lang('modules.accountSettings.companyAddress')</label>
                                                <textarea class="form-control" id="address" rows="2"
                                                          name="address">{{ $global->address }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="currency_id">@lang('modules.accountSettings.defaultCurrency')</label>
                                                <select name="currency_id" id="currency_id" class="form-control">
                                                    @foreach($currencies as $currency)
                                                        <option
                                                                @if($currency->id == $global->currency_id) selected @endif
                                                        value="{{ $currency->id }}">{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="timezone">@lang('modules.accountSettings.defaultTimezone')</label>
                                                <select name="timezone" id="timezone" class="form-control select2">
                                                    @foreach($timezones as $tz)
                                                        <option @if($global->timezone == $tz) selected @endif>{{ $tz }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="date_format">@lang('modules.accountSettings.dateFormat')</label>
                                                <select name="date_format" id="date_format" class="form-control select2">
                                                    <option value="d-m-Y" @if($global->date_format == 'd-m-Y') selected @endif >d-m-Y ({{ $dateObject->format('d-m-Y') }}) </option>
                                                    <option value="m-d-Y" @if($global->date_format == 'm-d-Y') selected @endif >m-d-Y ({{ $dateObject->format('m-d-Y') }}) </option>
                                                    <option value="Y-m-d" @if($global->date_format == 'Y-m-d') selected @endif >Y-m-d ({{ $dateObject->format('Y-m-d') }}) </option>
                                                    <option value="d.m.Y" @if($global->date_format == 'd.m.Y') selected @endif >d.m.Y ({{ $dateObject->format('d.m.Y') }}) </option>
                                                    <option value="m.d.Y" @if($global->date_format == 'm.d.Y') selected @endif >m.d.Y ({{ $dateObject->format('m.d.Y') }}) </option>
                                                    <option value="Y.m.d" @if($global->date_format == 'Y.m.d') selected @endif >Y.m.d ({{ $dateObject->format('Y.m.d') }}) </option>
                                                    <option value="d/m/Y" @if($global->date_format == 'd/m/Y') selected @endif >d/m/Y ({{ $dateObject->format('d/m/Y') }}) </option>
                                                    <option value="m/d/Y" @if($global->date_format == 'm/d/Y') selected @endif >m/d/Y ({{ $dateObject->format('m/d/Y') }}) </option>
                                                    <option value="Y/m/d" @if($global->date_format == 'Y/m/d') selected @endif >Y/m/d ({{ $dateObject->format('Y/m/d') }}) </option>
                                                    <option value="d-M-Y" @if($global->date_format == 'd-M-Y') selected @endif >d-M-Y ({{ $dateObject->format('d-M-Y') }}) </option>
                                                    <option value="d/M/Y" @if($global->date_format == 'd/M/Y') selected @endif >d/M/Y ({{ $dateObject->format('d/M/Y') }}) </option>
                                                    <option value="d.M.Y" @if($global->date_format == 'd.M.Y') selected @endif >d.M.Y ({{ $dateObject->format('d.M.Y') }}) </option>
                                                    <option value="d-M-Y" @if($global->date_format == 'd-M-Y') selected @endif >d-M-Y ({{ $dateObject->format('d-M-Y') }}) </option>
                                                    <option value="d M Y" @if($global->date_format == 'd M Y') selected @endif >d M Y ({{ $dateObject->format('d M Y') }}) </option>
                                                    <option value="d F, Y" @if($global->date_format == 'd F, Y') selected @endif >d F, Y ({{ $dateObject->format('d F, Y') }}) </option>
                                                    <option value="D/M/Y" @if($global->date_format == 'D/M/Y') selected @endif >D/M/Y ({{ $dateObject->format('D/M/Y') }}) </option>
                                                    <option value="D.M.Y" @if($global->date_format == 'D.M.Y') selected @endif >D.M.Y ({{ $dateObject->format('D.M.Y') }}) </option>
                                                    <option value="D-M-Y" @if($global->date_format == 'D-M-Y') selected @endif >D-M-Y ({{ $dateObject->format('D-M-Y') }}) </option>
                                                    <option value="D M Y" @if($global->date_format == 'D M Y') selected @endif >D M Y ({{ $dateObject->format('D M Y') }}) </option>
                                                    <option value="d D M Y" @if($global->date_format == 'd D M Y') selected @endif >d D M Y ({{ $dateObject->format('d D M Y') }}) </option>
                                                    <option value="D d M Y" @if($global->date_format == 'D d M Y') selected @endif >D d M Y ({{ $dateObject->format('D d M Y') }}) </option>
                                                    <option value="dS M Y" @if($global->date_format == 'dS M Y') selected @endif >dS M Y ({{ $dateObject->format('dS M Y') }}) </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="time_format">@lang('modules.accountSettings.timeFormat')</label>
                                                <select name="time_format" id="time_format" class="form-control select2">
                                                    <option value="h:i A" @if($global->time_format == 'H:i A') selected @endif >12 Hour  (6:20 PM) </option>
                                                    <option value="h:i a" @if($global->time_format == 'H:i a') selected @endif >12 Hour  (6:20 pm) </option>
                                                    <option value="H:i" @if($global->time_format == 'H:i') selected @endif >24 Hour  (18:20) </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locale">@lang('modules.accountSettings.changeLanguage')</label>
                                                <select name="locale" id="locale" class="form-control select2">
                                                    <option @if($global->locale == "en") selected @endif value="en">English
                                                    </option>
                                                    @foreach($languageSettings as $language)
                                                        <option value="{{ $language->language_code }}" @if($global->locale == $language->language_code) selected @endif >{{ $language->language_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.update')</button>
                                {!! Form::close() !!}
                                <input type="button" name="next" class="next action-button" value="Next" style="display: none" />
                            </fieldset>
                            <fieldset>
                                {!! Form::open(['id'=>'invoiceSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                                    {{--   <h2 class="fs-title">@lang('modules.invoiceSettings.updateTitle')</h2>--}}
                                    <h3 class="fs-subtitle">@lang('modules.invoiceSettings.updateTitle')</h3>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="invoice_prefix">@lang('modules.invoiceSettings.invoicePrefix')</label>
                                                <input type="text" class="form-control" id="invoice_prefix" name="invoice_prefix"
                                                       value="{{ $invoiceSetting->invoice_prefix }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="invoice_digit">@lang('modules.invoiceSettings.invoiceDigit')</label>
                                                <input type="number" min="2" class="form-control" id="invoice_digit" name="invoice_digit"
                                                       value="{{ $invoiceSetting->invoice_digit }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="invoice_look_like">@lang('modules.invoiceSettings.invoiceLookLike')</label>
                                                <input type="text" class="form-control" id="invoice_look_like" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="estimate_prefix">@lang('modules.invoiceSettings.estimatePrefix')</label>
                                                <input type="text" class="form-control" id="estimate_prefix" name="estimate_prefix"
                                                       value="{{ $invoiceSetting->estimate_prefix }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="estimate_digit">@lang('modules.invoiceSettings.estimateDigit')</label>
                                                <input type="number" min="2" class="form-control" id="estimate_digit" name="estimate_digit"
                                                       value="{{ $invoiceSetting->estimate_digit }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="estimate_look_like">@lang('modules.invoiceSettings.estimateLookLike')</label>
                                                <input type="text" class="form-control" id="estimate_look_like" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="credit_note_prefix">@lang('modules.invoiceSettings.credit_notePrefix')</label>
                                                <input type="text" class="form-control" id="credit_note_prefix" name="credit_note_prefix"
                                                       value="{{ $invoiceSetting->credit_note_prefix }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="credit_note_digit">@lang('modules.invoiceSettings.credit_noteDigit')</label>
                                                <input type="number" min="2" class="form-control" id="credit_note_digit" name="credit_note_digit"
                                                       value="{{ $invoiceSetting->credit_note_digit }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="credit_note_look_like">@lang('modules.invoiceSettings.credit_noteLookLike')</label>
                                                <input type="text" class="form-control" id="credit_note_look_like" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="template">@lang('modules.invoiceSettings.template')</label>
                                                <select name="template" class="image-picker show-labels show-html">
                                                    <option data-img-src="{{ asset('invoice-template/1.png') }}"
                                                            @if($invoiceSetting->template == 'invoice-1') selected @endif
                                                            value="invoice-1">Template
                                                        1
                                                    </option>
                                                    <option data-img-src="{{ asset('invoice-template/2.png') }}"
                                                            @if($invoiceSetting->template == 'invoice-2') selected @endif
                                                            value="invoice-2">Template
                                                        2
                                                    </option>
                                                    <option data-img-src="{{ asset('invoice-template/3.png') }}"
                                                            @if($invoiceSetting->template == 'invoice-3') selected @endif
                                                            value="invoice-3">Template
                                                        3
                                                    </option>
                                                    <option data-img-src="{{ asset('invoice-template/4.png') }}"
                                                            @if($invoiceSetting->template == 'invoice-4') selected @endif
                                                            value="invoice-4">Template
                                                        4
                                                    </option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="due_after">@lang('modules.invoiceSettings.dueAfter')</label>

                                                <div class="input-group m-t-10">
                                                    <input type="number" id="due_after" name="due_after" class="form-control" value="{{ $invoiceSetting->due_after }}">
                                                    <span class="input-group-addon">@lang('app.days')</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="gst_number">@lang('app.gstNumber')</label>
                                                <input type="text" id="gst_number" name="gst_number" class="form-control" value="{{ $invoiceSetting->gst_number }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label" >@lang('app.showGst')</label>
                                                <div class="switchery-demo">
                                                    <input type="checkbox" name="show_gst" @if($invoiceSetting->show_gst == 'yes') checked @endif class="js-switch " data-color="#99d683"  />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="invoice_terms">@lang('modules.invoiceSettings.invoiceTerms')</label>
                                                <textarea name="invoice_terms" id="invoice_terms" class="form-control"
                                                          rows="4">{{ $invoiceSetting->invoice_terms }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.update')</button>
                                {!! Form::close() !!}
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/steps-form/steps.js') }}"></script>
    <script src="{{ asset('plugins/image-picker/image-picker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>


    <script>
        $(".image-picker").imagepicker();
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());

        });
        $('#companySettings #save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.account-setup.update', $global->id)}}',
                container: '#companySettings',
                type: "POST",
                data: $('#companySettings').serialize(),
                file:true,
                success: function (response) {
                    console.log(response);
                    if(response.status == 'success'){
                        $('#companySettings').siblings('.next').trigger('click');
                    }
                }
            })
        });
        $('#invoiceSettings #save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.account-setup.update-invoice', $invoiceSetting->id)}}',
                container: '#invoiceSettings',
                type: "POST",
                data: $('#invoiceSettings').serialize()
            })
        });

        $('#invoice_prefix, #invoice_digit, #estimate_prefix, #estimate_digit, #credit_note_prefix, #credit_note_digit').on('keyup', function () {
            genrateInvoiceNumber();
        });

        genrateInvoiceNumber();

        function genrateInvoiceNumber() {
            var invoicePrefix = $('#invoice_prefix').val();
            var invoiceDigit = $('#invoice_digit').val();
            var invoiceZero = '';
            for ($i=0; $i<invoiceDigit-1; $i++){
                invoiceZero = invoiceZero+'0';
            }
            invoiceZero = invoiceZero+'1';
            var invoice_no = invoicePrefix+'#'+invoiceZero;
            $('#invoice_look_like').val(invoice_no);

            var estimatePrefix = $('#estimate_prefix').val();
            var estimateDigit = $('#estimate_digit').val();
            var estimateZero = '';
            for ($i=0; $i<estimateDigit-1; $i++){
                estimateZero = estimateZero+'0';
            }
            estimateZero = estimateZero+'1';
            var estimate_no = estimatePrefix+'#'+estimateZero;
            $('#estimate_look_like').val(estimate_no);

            var creditNotePrefix = $('#credit_note_prefix').val();
            var creditNoteDigit = $('#credit_note_digit').val();
            var creditNoteZero = '';
            for ($i=0; $i<creditNoteDigit-1; $i++){
                creditNoteZero = creditNoteZero+'0';
            }
            creditNoteZero = creditNoteZero+'1';
            var creditNote_no = creditNotePrefix+'#'+creditNoteZero;
            $('#credit_note_look_like').val(creditNote_no);
        }
    </script>
    <script>
        $(".date-picker").datepicker({
            todayHighlight: true,
            autoclose: true,
            format: '{{ $global->date_picker_format }}',
        });

        {{--$('#save-form').click(function () {--}}
        {{--    $.easyAjax({--}}
        {{--        url: '{{route('admin.clients.update', [$clientDetail->id])}}',--}}
        {{--        container: '#updateClient',--}}
        {{--        type: "POST",--}}
        {{--        redirect: true,--}}
        {{--        data: $('#updateClient').serialize()--}}
        {{--    })--}}
        {{--});--}}
    </script>
@endpush
