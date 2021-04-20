<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.add') @lang('app.faq')</h4>
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
            <label class="col-sm-2 control-label" for="question">@lang('app.question')</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="question" name="question" >
                <div class="form-control-focus"> </div>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="answer">@lang('app.answer')</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control summernote" id="answer" rows="3" name="answer" > </textarea>
                <div class="form-control-focus"> </div>
                <span class="help-block"></span>
            </div>
        </div>

    </div>
</div>

<div class="modal-footer">
    <button id="save" type="button" class="btn btn-custom">@lang('app.submit')</button>
</div>
{{ Form::close() }}
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script>
    $('.selectpicker').selectpicker();

    $('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link',  'hr','video']],
            ['view', ['fullscreen', 'codeview']],
            ['help', ['help']]
        ]
    });
    $('#save').click(function () {
        $.easyAjax({
            url: '{{route('super-admin.faq-settings.store')}}',
            container: '#add-edit-form',
            type: "POST",
            data: $('#add-edit-form').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
        return false;
    })
</script>

