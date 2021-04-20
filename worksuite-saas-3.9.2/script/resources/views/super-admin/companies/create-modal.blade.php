<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.offlinePayment.title')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('app.menu.method')</th>
                    <th>@lang('app.description')</th>
                    <th>@lang('app.status')</th>
                    <th width="20%">@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($offlineMethods as $key=>$method)
                    <tr id="method-{{ $method->id }}">
                        <td>{{ ($key+1) }}</td>
                        <td>{{ ucwords($method->name) }}</td>
                        <td>{!! ucwords($method->description) !!} </td>
                        <td>
                            @if($method->status == 'yes')
                                <label class="label label-success">
                                    @lang('modules.offlinePayment.active')
                                </label>
                            @else
                                <label class="label label-danger">
                                    @lang('modules.offlinePayment.inActive')
                                </label>
                            @endif
                        </td>
                        <td>
                            <a href="javascript:;" data-type-id="{{ $method->id }}"
                               class="btn btn-sm btn-danger btn-rounded btn-outline delete-type m-t-5"><i
                                        class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>
                            @lang('messages.noMethodsAdded')
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        ​
        <hr>
        {!! Form::open(['id'=>'createMethods','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>@lang('modules.offlinePayment.method')</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                    ​
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>@lang('modules.offlinePayment.description')</label>
                        <textarea id="description" name="description" class="form-control" rows="4"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-type" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
​
<script>
    $('#save-type').click(function () {
        $.easyAjax({
            url: '{{route('super-admin.offline-payment-setting.store')}}',
            container: '#createMethods',
            type: "POST",
            data: $('#createMethods').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    $('#offlineMethod').modal('hide');
                    var options = [];
                    var rData = [];
                    rData = response.data;
                    $.each(rData, function( index, value ) {
                        var selectData = '';
                        selectData = '<option value="'+value.id+'">'+value.name+'</option>';
                        options.push(selectData);
                    });
                    $('#multiselect').html(options);
                    $('#multiselect').selectpicker('refresh');
                }
            }
        })
    });

    $('body').on('click', '.delete-type', function () {
        var id = $(this).data('type-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.confirmation.removeMethod')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('app.yes')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                var url = "{{ route('super-admin.offline-payment-setting.destroy',':id') }}";
                url = url.replace(':id', id);
                var token = "{{ csrf_token() }}";
                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            $('#method-'+id).fadeOut();
                            var options = [];
                            var rData = [];
                            rData = response.data;
                            $.each(rData, function( index, value ) {
                                var selectData = '';
                                selectData = '<option value="'+value.id+'">'+value.name+'</option>';
                                options.push(selectData);
                            });
                            $('#multiselect').html(options);
                            $('#multiselect').selectpicker('refresh');
                        }
                    }
                });
            }
        });
    });
</script>