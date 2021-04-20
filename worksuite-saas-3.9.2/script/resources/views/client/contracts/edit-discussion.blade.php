<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.contracts.editDiscussion')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'editDiscussion','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row ">

                <div class="col-xs-12 m-b-10">
                    <div class="form-group">
                            <textarea class="form-control" id="messages" name="messages">{{ $discussion->message }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<div class="modal-footer">
    <div class="form-actions">
        <button type="button" id="save-discussion" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.submit')</button>
    </div>
</div>

<script>
    $('#save-discussion').click(function () {
        $.easyAjax({
            url: '{{route('client.contracts.update-discussion',$discussion->id)}}',
            container: '#editDiscussion',
            type: "POST",
            data: $('#editDiscussion').serialize(),
            success: function (res) {
                $('#estimateAccept').modal('hide');
                $('#discussion-{{$discussion->id}}').text($('#messages').val());
            }
        })
    });
</script>