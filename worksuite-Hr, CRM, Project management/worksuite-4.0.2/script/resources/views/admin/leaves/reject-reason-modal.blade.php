<div id="event-detail">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">@lang('app.menu.leaves') @lang('app.reject') @lang('app.reason')</h4>
    </div>
    <div class="modal-body">
        {!! Form::open(['id'=>'updateMessage','class'=>'ajax-form','method'=>'GET']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('app.reject') @lang('app.reason')? (@lang('app.optional'))</label>
                        <textarea name="reason"  id="reason"  rows="5" class="form-control"></textarea>
                    </div>
                </div>

            </div>
        </div>
        {!! Form::close() !!}

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">@lang('app.close')</button>
        <button type="button" class="btn btn-danger save-event waves-effect waves-light"> @lang('app.reject')
        </button>
    </div>

</div>

<script>

    $('.save-event').click(function () {
        var url = '{{ route("admin.leaves.leaveAction") }}';
        var action = '{{ $leaveAction }}';
        var leaveId = '{{ $leaveID }}';
        var reason = $('#reason').val();

        $.easyAjax({
            type: 'POST',
            url: url,
            data: { 'action': action, 'leaveId': leaveId, '_token': '{{ csrf_token() }}', 'reason': reason },
            success: function (response) {
                if(response.status == 'success') {
                    window.location.reload();
                }
            }
        });
    });

</script>