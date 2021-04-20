<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.frontCms.defaultLanguage')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="alert alert-info ">
            <i class="fa fa-info-circle"></i> @lang('messages.defaultLanguageCompany')
        </div>
        {!! Form::open(['id'=>'setDefaultLanguage','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="address">@lang('modules.frontCms.defaultLanguage')</label>
                        <select name="default_language" id="default_language" class="form-control select2">
                            <option @if($global->new_company_locale == "en") selected @endif value="en">English
                            </option>
                            @foreach($languageSettings as $language)
                                <option value="{{ $language->language_code }}" @if($global->new_company_locale == $language->language_code) selected @endif >{{ $language->language_name }}</option>
                            @endforeach
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
            url: '{{route('super-admin.companies.default-language-save')}}',
            container: '#setDefaultLanguage',
            type: "POST",
            data: $('#setDefaultLanguage').serialize(),
            success: function (response) {
                $('#projectCategoryModal').modal('hide');
                window.location.reload();
            }
        })
    });
</script>