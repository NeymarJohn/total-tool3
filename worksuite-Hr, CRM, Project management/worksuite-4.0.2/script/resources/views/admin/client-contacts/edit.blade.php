<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><i class="fa fa-clock-o"></i> @lang('app.updateContactDetails')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(['id'=>'updateContact','class'=>'ajax-form','method'=>'PUT']) !!}


                <div class="form-body">
                    <div class="row m-t-30">
                        <div class="col-md-4 ">
                            <div class="form-group">
                                <label>Contact Name</label>
                                <input type="text" name="contact_name" id="contact_name" class="form-control" value="{{ $contact->contact_name }}">
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="form-group">
                                <label>Phone</label>
                                <input id="phone" name="phone" type="tel" class="form-control" value="{{ $contact->phone }}">
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="form-group">
                                <label>Email</label>
                                <input id="email" name="email" type="email" class="form-control" value="{{ $contact->email }}" >
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-actions m-t-30">
                    <button type="button" id="update-form" class="btn btn-success"><i class="fa fa-check"></i> Save
                    </button>
                </div>
                {!! Form::close() !!}

            </div>
        </div>

    </div>
</div>

<script>

    $('#update-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.contacts.update', $contact->id)}}',
            container: '#updateContact',
            type: "POST",
            data: $('#updateContact').serialize(),
            success: function (response) {
                $('#editContactModal').modal('hide');
                table._fnDraw();
            }
        })
    });
</script>