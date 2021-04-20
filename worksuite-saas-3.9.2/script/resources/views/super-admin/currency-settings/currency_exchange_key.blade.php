<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.accountSettings.currencyConverterKey')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="alert alert-info ">
            <i class="fa fa-info-circle"></i> @lang('messages.currencyConvertApiKeyUrl') <a href="https://www.currencyconverterapi.com" target="_blank"> https://www.currencyconverterapi.com</a>
        </div>
        {!! Form::open(['id'=>'createCurrencyKey','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('modules.accountSettings.currencyConverterKey')</label>
                        <input type="text" name="currency_converter_key" id="currency_converter_key" value="{{ $global->currency_converter_key }}" class="form-control">
                    </div>
                </div>
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <select name="currency_key_version" id="" class="form-control">
                            <option 
                            @if ($global->currency_key_version == "free")
                                selected
                            @endif
                             value="free">Free Key</option>
                            <option 
                            @if ($global->currency_key_version == "api")
                                selected
                            @endif

                            value="api">Paid Key</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-category" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>

    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('super-admin.currency.exchange-key-store')}}',
            container: '#createCurrencyKey',
            type: "POST",
            data: $('#createCurrencyKey').serialize(),
            success: function (response) {
              $('#projectCategoryModal').modal('hide');
              window.location.reload();
            }
        })
    });
</script>