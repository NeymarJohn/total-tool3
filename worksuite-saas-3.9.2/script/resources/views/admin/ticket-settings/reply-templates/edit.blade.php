<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.update') @lang('modules.tickets.template')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'editTicketTemplate','class'=>'ajax-form','method'=>'PUT']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('modules.tickets.templateHeading')</label>
                        <input type="text" name="reply_heading" id="reply_heading" value="{{ $template->reply_heading }}" class="form-control">
                    </div>
                </div>
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('modules.tickets.templateText')</label>
                        <textarea name="reply_text" id="reply_text" class="form-control summernote2" rows="10">{{ $template->reply_text }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="update-template" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>

    $('#editTicketTemplate').on('submit', function(e) {
        return false;
    })
    $('.summernote2').summernote({
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
            ['insert', ['link']],
            ["view", ["fullscreen"]]
        ]
    });
    $('#update-template').click(function () {
        $.easyAjax({
            url: '{{route('admin.replyTemplates.update', $template->id)}}',
            container: '#editTicketTemplate',
            type: "PUT",
            data: $('#editTicketTemplate').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>