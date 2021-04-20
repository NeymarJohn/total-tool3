<form id="editSettings" class="ajax-form" data-language-id="{{ $languageId }}">
    @csrf
    <div class="row">
        <div class="col-sm-12 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="price_title">@lang('modules.frontCms.priceTitle')</label>
                <input type="text" class="form-control" id="price_title" name="price_title"
                    value="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="price_description">@lang('modules.frontCms.priceDescription')</label>
                <textarea class="form-control" id="price_description" rows="5"
                        name="price_description"></textarea>
            </div>
        </div>
    </div>

    <button type="button" id="save-form"
            class="btn btn-success waves-effect waves-light m-r-10">
        @lang('app.update')
    </button>
</form>