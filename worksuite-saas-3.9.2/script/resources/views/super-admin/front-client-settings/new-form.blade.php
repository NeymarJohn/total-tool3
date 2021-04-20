<form id="editSettings" class="ajax-form" data-language-id="{{ $languageId }}">
    @csrf
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <label for="title">@lang('app.title')</label>
                <input type="text" class="form-control" id="title" name="title" value="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="address">@lang('app.description')</label>
                <textarea class="form-control" id="detail" rows="5" name="detail"></textarea>
            </div>
        </div>
    </div>

    <button type="submit" id="save-form"
            class="btn btn-success waves-effect waves-light m-r-10">
        @lang('app.update')
    </button>
</form>