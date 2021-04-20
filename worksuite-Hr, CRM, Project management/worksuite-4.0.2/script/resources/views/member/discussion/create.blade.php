<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.new') @lang('modules.projects.discussion')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'createProjectCategory','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            {!! Form::hidden('project_id', $projectId) !!}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">@lang('app.category')</label>
                        <select class="select2 form-control" data-placeholder="@lang("app.category")" id="discussion_category_id" name="discussion_category_id">
                            <option value="">--</option>
                            @foreach($categories as $item)
                                <option value="{{ $item->id }}" >{{ ucwords($item->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="required">@lang('app.title')</label>
                        <input type="text" name="title" id="title" class="form-control">
                    </div>
                </div>
            
                

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">@lang('app.description')</label>
                        <textarea id="description" name="description" class="form-control summernote"></textarea>
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

<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script>

    $("#discussion_category_id").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    
    $('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen"]],
        ]
    });

    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('member.discussion.store')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: $('#createProjectCategory').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>