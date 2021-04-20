<style>
    .table .btn-info, #taxList .btn-info {
         color: #fff !important;
    }
    .table .btn-danger, #taxList .btn-danger {
         color: #fff !important;
    }
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.invoices.tax')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table" id="taxList">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('modules.invoices.taxName')</th>
                    <th>@lang('modules.invoices.rate') %</th>
{{--                    <th>@lang('app.action')</th>--}}
                </tr>
                </thead>
                <tbody>
                @forelse($taxes as $key=>$tax)
                    <tr id="tax-{{ $tax->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($tax->tax_name) }}</td>
                        <td>{{ $tax->rate_percent }}</td>
{{--                        <td>--}}
{{--                            <button type="button" data-cat-id="1" class="btn btn-xs btn-info" onclick="editTax('{{ $tax->id }}')"><i class="glyphicon glyphicon-edit"></i></button>--}}
{{--                            <button type="button" data-cat-id="1" class="btn btn-xs btn-danger" onclick="deleteTax('{{ $tax->id }}')"><i class="glyphicon glyphicon-trash"></i></button>--}}
{{--                        </td>--}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noRecordFound')</td>
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
 $(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
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
        });
        return false;
    });

    function editTax(id) {
        var url = "{{ route('admin.taxes.edit',':id') }}";
        url = url.replace(':id', id);
        $.easyAjax({
            type: 'GET',
            url: url,
            success: function (response) {
                $('.modal-content').html(response.view);
            }
        });
    }

    function deleteTax(id) {

        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.deleteTaxText')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm) {
            if (isConfirm) {
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
                            $('#tax-'+id).fadeOut();
                        }
                    }
                });
            }
        });


    }

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