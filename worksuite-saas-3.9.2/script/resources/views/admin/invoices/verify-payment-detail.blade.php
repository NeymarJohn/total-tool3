<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"> <i class="fa fa-search"></i>  @lang('modules.payments.paymentDetails')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Payment Method</th>
                    <th>@lang('app.status')</th>
                    <th>@lang('app.description')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>

                @php
                    $status = ['pending' => 'warning', 'approve' => 'success', 'reject' => 'danger'];
                    $statusString = ['pending' => 'Pending', 'approve' => 'approved', 'reject' => 'rejected'];
                @endphp

                @forelse($invoice->offline_invoice_payment as $key=>$type)
                    <tr id="offline-payment-{{ $type->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($type->payment_method->name) }}</td>
                        <td><label class="label label-{{ $status[$type->status] }}">{{ ucwords($statusString[$type->status]) }}</label></td>
                        <td>{{ ucwords($type->description) }}</td>
                        <td>
                            <a href="{{ $type->slip }}" data-toggle="tooltip" data-title="View File" target="_blank" data-cat-id="{{ $type->id }}" class="btn btn-sm btn-success btn-rounded">
                                <i class="fa fa-eye"></i>
                            </a>
                            @if($type->status == 'pending')
                                <a href="javascript:;" data-toggle="tooltip" onclick="accept('{{ $type->id }}'); return false;" data-title="Verify" data-cat-id="{{ $type->id }}" class="btn btn-sm btn-primary btn-rounded" data>
                                    <i class="fa fa-check"></i>
                                </a>
                                <a href="javascript:;"  data-toggle="tooltip" onclick="reject('{{ $type->id }}'); return false;"  data-title="Reject" data-cat-id="{{ $type->id }}" class="btn btn-sm btn-danger btn-rounded">
                                    <i class="fa fa-remove"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No offline payment found !</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">@lang('app.close')</button>
</div>

<script>
    $(function () {
        $("body").tooltip({
            selector: '[data-toggle="tooltip"]'
        });
    })

    function accept(id) {
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.confirmation.recoverVerifiedRequest')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('app.yes')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm) {
            if (isConfirm) {
                var url = '{{ route('admin.offline-invoice-payment.verify', ':id') }}';
                url = url.replace(':id', id)
                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {
                        '_token': '{{ csrf_token() }}'
                        , id: id
                    },
                    success: function (response) {
                        if (response.status == "success") {
                            $('#offlinePaymentDetails').modal('hide');
                            if(typeof window.LaravelDataTables != "undefined") {
                                window.LaravelDataTables["invoices-table"].draw();
                            } else {
                                window.location.reload();
                            }

                        }
                    }
                });
            }
        });
    }

    function reject(id) {
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.confirmation.recoveRejectedRequest')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.reject')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm) {
            if (isConfirm) {
                var url = '{{ route('admin.offline-invoice-payment.reject', ':id') }}';
                url = url.replace(':id', id)
                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {
                        '_token': '{{ csrf_token() }}'
                        , id: id
                    },
                    success: function (response) {
                        if (response.status == "success") {
                            $('#offlinePaymentDetails').modal('hide');
                            if(typeof window.LaravelDataTables != "undefined") {
                                window.LaravelDataTables["invoices-table"].draw();
                            } else {
                                window.location.reload();
                            }
                        }
                    }
                });
            }
        });
    }
</script>


