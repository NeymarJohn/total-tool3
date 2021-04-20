<form id="editSettings" class="ajax-form" data-language-id="{{ $languageId }}">
    @csrf
    <div class="row">
        <div class="col-sm-12 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="company_name">@lang('modules.frontCms.headerTitle')</label>
                <input type="text" class="form-control" id="header_title" name="header_title"
                    value="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="address">@lang('modules.frontCms.headerDescription')</label>
                <textarea class="form-control summernote" id="header_description" rows="5"
                        name="header_description"></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="exampleInputPassword1">@lang('modules.frontCms.mainImage')</label>
                <div class="col-xs-12">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail"
                            style="width: 200px; height: 150px;">
                            <img src="{{ asset('saas/img/home/home-crm.png') }}" alt=""/>
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail"
                            style="max-width: 200px; max-height: 150px;"></div>
                        <div>
                        <span class="btn btn-info btn-file">
                            <span class="fileinput-new"> @lang('app.selectImage') </span>
                            <span class="fileinput-exists"> @lang('app.change') </span>
                            <input type="file" name="image" id="image">
                        </span>
                            <a href="javascript:;" class="btn btn-danger fileinput-exists"
                            data-dismiss="fileinput"> @lang('app.remove') </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> @lang('messages.headerImageSizeMessage')</div>
        </div>
    </div>

    <button type="button" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
        @lang('app.save')
    </button>
</form>