<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.invoices.tax')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('modules.invoices.taxName')</th>
                    <th>@lang('modules.invoices.rate') %</th>
                    <th>@lang('app.action')</th>

                </tr>
                </thead>
                <tbody>
                @forelse($taxes as $key=>$tax)
                    <tr id="tax-{{ $tax->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($tax->tax_name) }}</td>
                        <td>{{ $tax->rate_percent }}</td>
                        <td><a href="javascript:;" data-cat-id="{{ $tax->id }}" class="btn btn-sm btn-danger btn-rounded delete-tax">@lang("app.remove")</a></td>
                    </tr>
                @empty
                    <tr class="message">
                        <td colspan="4">@lang('messages.noRecordFound')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <hr>
        {!! Form::open(['id'=>'createTax','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <label>@lang('modules.invoices.taxName')</label>
                        <input type="text" name="tax_name" id="tax_name" class="form-control">
                    </div>
                </div>
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <label>@lang('modules.invoices.rate') %</label>
                        <input type="text" name="rate_percent" id="rate_percent" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-tax" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $('#createTax').submit(function () {
        $.easyAjax({
            url: '{{route('admin.taxes.store')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: $('#createTax').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
        return false;
    })

    $('.delete-tax').click(function () {
        var id = $(this).data('cat-id');
        alert(id);
        var url = "{{ route('admin.taxes.destroy',':id') }}";
        url = url.replace(':id', id);

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token, '_method': 'DELETE'},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                    $('#tax-'+id).fadeOut();
                }
            }
        });
    });

    $('#save-tax').click(function () {
        $.easyAjax({
            url: '{{route('admin.taxes.store')}}',
            container: '#createTax',
            type: "POST",
            data: $('#createTax').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>