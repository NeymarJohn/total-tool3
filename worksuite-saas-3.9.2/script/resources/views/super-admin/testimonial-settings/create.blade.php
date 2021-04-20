<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.add') @lang('app.testimonial')</h4>
</div>

{!!  Form::open(['url' => '' ,'method' => 'post', 'id' => 'add-edit-form','class'=>'form-horizontal']) 	 !!}
<div class="modal-body">
    <div class="box-body">
        <div class="form-group">
            <label class="col-sm-2 control-label">@lang('app.language')</label>
            <div class="col-sm-10">
                <select name="language" class="form-control selectpicker" id="language_switcher">
                    <option value="0" data-content=" <span class='flag-icon flag-icon-us'></span> En"></option>
                    @forelse ($activeLanguages as $language)
                    <option
                            value="{{ $language->id }}" data-content=' <span class="flag-icon flag-icon-{{ $language->language_code }}"></span> {{ ucfirst($language->language_code) }}'></option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="name">@lang('app.name')</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" >
                <div class="form-control-focus"> </div>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="name">@lang('app.comment')</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control summernote" id="comment" rows="3" name="comment" > </textarea>
                <div class="form-control-focus"> </div>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="name">@lang('app.rating')</label>
            <div class="col-sm-10">
                <input type="number"  class="form-control" min="1" max="5" id="rating" name="rating" >
                <div class="form-control-focus"> </div>
                <span class="help-block">@lang('messages.ratingShouldBe')</span>
            </div>
        </div>

    </div>
</div>

<div class="modal-footer">
    <button id="save" type="button" class="btn btn-custom">@lang('app.submit')</button>
</div>
{{ Form::close() }}

<script>
    $('.selectpicker').selectpicker();

    $('#save').click(function () {
        $.easyAjax({
            url: '{{route('super-admin.testimonial-settings.store')}}',
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

