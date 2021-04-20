<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.frontClient') @lang('app.edit')</h4>
</div>

{!!  Form::open(['url' => '' ,'method' => 'put', 'id' => 'add-edit-form','class'=>'form-horizontal']) 	 !!}
<div class="modal-body">
    <div class="box-body">
        <div class="form-group">
            <label class="col-sm-2 control-label">@lang('app.language')</label>
            <div class="col-sm-10">
                <select name="language" class="form-control selectpicker" id="language_switcher">
                    <option @if(is_null($frontClient->language_setting_id)) selected @endif value="0" data-content=" <span class='flag-icon flag-icon-us'></span> En"></option>
                    @forelse ($activeLanguages as $language)
                    <option
                        @if($language->id === $frontClient->language_setting_id) selected @endif
                        value="{{ $language->id }}" data-content=' <span class="flag-icon flag-icon-{{ $language->language_code }}"></span> {{ ucfirst($language->language_code) }}'>
                    </option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="title">@lang('app.title')</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="title" value="{{ $frontClient->title }}" name="title" >
                <div class="form-control-focus"> </div>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="image">@lang('app.image') </label>
            <div class="col-sm-10">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail"
                         style="width: 200px; height: 150px;">
                        <img src="{{ $frontClient->image_url }}"
                             alt=""/>
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail"
                         style="max-width: 200px; max-height: 150px;"></div>
                    <div>
                                    <span class="btn btn-info btn-file">
                                        <span class="fileinput-new"> @lang('app.selectImage') </span>
                                        <span class="fileinput-exists"> @lang('app.change') </span>
                                        <input type="file" name="image" id="image"> </span>
                        <a href="javascript:;" class="btn btn-danger fileinput-exists"
                           data-dismiss="fileinput"> @lang('app.remove') </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal-footer">
    <button id="save" type="button" class="btn btn-custom">@lang('app.update')</button>
</div>
{{ Form::close() }}

<script>
    $('.selectpicker').selectpicker();

    $('#save').click(function () {
        var url = "{{ route('super-admin.client-settings.update', $frontClient->id) }}";
        $.easyAjax({
            url: url,
            container: '#add-edit-form',
            type: "POST",
            file:true,
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
        return false;
    })
</script>

