<form id="editSettings" class="ajax-form" data-language-id="{{ $languageId }}">
    @csrf
    <div class="row">
        <div class="col-sm-12 col-md-3 col-xs-12">
            <div class="form-group">
                <label for="title">@lang('app.home')</label>
                <input type="text" class="form-control" id="home" name="home" value="">
            </div>
        </div>

        <div class="col-sm-12 col-md-3 col-xs-12">
            <div class="form-group">
                <label for="title">@lang('app.price')</label>
                <input type="text" class="form-control" id="price" name="price" value="">
            </div>
        </div>

        <div class="col-sm-12 col-md-3 col-xs-12">
            <div class="form-group">
                <label for="title">@lang('app.contact')</label>
                <input type="text" class="form-control" id="contact" name="contact" value="">
            </div>
        </div>

        <div class="col-sm-12 col-md-3 col-xs-12">
            <div class="form-group">
                <label for="title">@lang('app.feature')</label>
                <input type="text" class="form-control" id="feature" name="feature" value="">
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-12 col-md-3 col-xs-12">
            <div class="form-group">
                <label for="title">@lang('app.get_start')</label>
                <input type="text" class="form-control" id="get_start" name="get_start" value="">
            </div>
        </div>

        <div class="col-sm-12 col-md-3 col-xs-12">
            <div class="form-group">
                <label for="title">@lang('app.login')</label>
                <input type="text" class="form-control" id="login" name="login" value="">
            </div>
        </div>

        <div class="col-sm-12 col-md-3 col-xs-12">
            <div class="form-group">
                <label for="title">@lang('app.contact_submit')</label>
                <input type="text" class="form-control" id="contact_submit" name="contact_submit" value="">
            </div>
        </div>
    </div>

    <button type="button" id="save-form"
            class="btn btn-success waves-effect waves-light m-r-10">
        @lang('app.update')
    </button>
</form>