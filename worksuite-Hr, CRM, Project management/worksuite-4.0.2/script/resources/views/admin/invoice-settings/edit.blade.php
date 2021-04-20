@extends('layouts.app')

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
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/image-picker/image-picker.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">

@endpush

@section('content')


    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.invoiceSettings.updateTitle')</div>

                <div class="vtabs customvtab m-t-10">
                    @include('sections.admin_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="white-box">

                                        {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="invoice_prefix" class="required">@lang('modules.invoiceSettings.invoicePrefix')</label>
                                                    <input type="text" class="form-control" id="invoice_prefix" name="invoice_prefix"
                                                           value="{{ $invoiceSetting->invoice_prefix }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="invoice_prefix">@lang('modules.invoiceSettings.invoiceDigit')</label>
                                                    <input type="number" min="2" class="form-control" id="invoice_digit" name="invoice_digit"
                                                           value="{{ $invoiceSetting->invoice_digit }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="invoice_prefix">@lang('modules.invoiceSettings.invoiceLookLike')</label>
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
                                                    <label for="template" class="required">@lang('modules.invoiceSettings.template')</label>
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
                                                        <option data-img-src="{{ asset('invoice-template/5.png') }}"
                                                                @if($invoiceSetting->template == 'invoice-5') selected @endif
                                                                value="invoice-5">Template
                                                            5
                                                        </option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="due_after" class="required">@lang('modules.invoiceSettings.dueAfter')</label>

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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label" >@lang('app.showGst')</label>
                                                    <div class="switchery-demo">
                                                        <input type="checkbox" name="show_gst" @if($invoiceSetting->show_gst == 'yes') checked @endif class="js-switch " data-color="#99d683"  />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label class="control-label" for="hsn_sac_code_show" >@lang('app.hsnSacCodeShow')</label>
                                                    <div class="switchery-demo">
                                                        <input type="checkbox" id="hsn_sac_code_show" name="hsn_sac_code_show" @if($invoiceSetting->hsn_sac_code_show) checked @endif class="js-switch " data-color="#99d683"  />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="invoice_terms" class="required">@lang('modules.invoiceSettings.invoiceTerms')</label>
                            <textarea name="invoice_terms" id="invoice_terms" class="form-control"
                                      rows="4">{{ $invoiceSetting->invoice_terms }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="estimate_terms" class="required">@lang('modules.invoiceSettings.estimateTerms')</label>
                                                      <textarea name="estimate_terms" id="estimate_terms" class="form-control"
                                                      rows="4">{{ $invoiceSetting->estimate_terms }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address">@lang('modules.accountSettings.changeLanguage')</label>
                                                    <select name="locale" id="locale" class="form-control select2">
                                                        <option @if($invoiceSetting->locale == "en") selected @endif value="en">English
                                                        </option>
                                                        @foreach($languageSettings as $language)
                                                            <option value="{{ $language->language_code }}" @if($invoiceSetting->locale == $language->language_code) selected @endif >{{ $language->language_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1"> @lang('modules.invoiceSettings.logo')</label>

                                                    <div class="col-md-12">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-new thumbnail"
                                                                 style="width: 200px; height: 80px;">
                                                                <img src="{{$invoiceSetting->logo_url}}" alt=""/>

                                                            </div>
                                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                                 style="max-width: 200px; max-height: 80px;"></div>
                                                            <div>
                                <span class="btn btn-info btn-file">
                                    <span class="fileinput-new"> @lang('app.selectImage') </span>
                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                    <input type="file" name="logo" id="logo"> </span>
                                                                <a href="javascript:;"
                                                                   class="btn btn-danger fileinput-exists"
                                                                   data-dismiss="fileinput"> @lang('app.remove') </a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                                                    @lang('app.update')
                                                </button>
                                                <button type="reset"
                                                        class="btn btn-inverse waves-effect waves-light">@lang('app.reset')</button>
                                            </div>

                                        </div>
                                        {!! Form::close() !!}
                                    </div>
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



    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/image-picker/image-picker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>


<script>
    $(".image-picker").imagepicker();
    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function () {
        new Switchery($(this)[0], $(this).data());

    });
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.invoice-settings.update', $invoiceSetting->id)}}',
            container: '#editSettings',
            type: "POST",
            redirect: true,
            file: true,
            data: $('#editSettings').serialize()
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
@endpush

