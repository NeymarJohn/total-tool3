

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><i class=""></i> @lang('app.notesDetails')</h4>
</div>
<div class="modal-body model-data">
        <div class="row">
            <div class="col-xs-12">
                {!! Form::open(['id'=>'updateNotes','class'=>'ajax-form','method'=>'POST']) !!}
                {!! Form::hidden('client_id', $client->id) !!}
                {!! Form::hidden('note_id', $notes->id) !!}

                <div class="form-body">
                    <div class="row m-t-20">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('modules.notes.enterPassword')</label>
                                <input type="password" name="password" id="password" value="" class="form-control">

                            </div>
                        </div>
                       
                        
                    </div>

                </div>
                <div class="form-actions m-b-20">
                    <button type="button" id="update-form" class="btn btn-success"><i class="fa fa-check"></i> verify
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
</div>
<script>

$('#update-form').click(function () {
        $.easyAjax({
            url: '{{route('member.notes.check-password', [$client->id])}}',
            container: '#updateNotes',
            type: "POST",
            data: $('#updateNotes').serialize(),
            success: function (response) {
                if(response.status == 'success')
                {
                    $("#modal-data-application").addClass("modal-dialog modal-lg");
                    $('.model-data').html(response.view);
                }
            }
        })
    });
</script>