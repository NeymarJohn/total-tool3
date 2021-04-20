<link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorselector/bootstrap-colorselector.min.css') }}">

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.leaves.leaveType')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('modules.leaves.leaveType')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($leaveTypes as $key=>$leaveType)
                    <tr id="type-{{ $leaveType->id }}">
                        <td>{{ $key+1 }}</td>
                        <td><label class="label label-{{ $leaveType->color }}">{{ ucwords($leaveType->type_name) }}</label></td>
                        <td><a href="javascript:;" data-cat-id="{{ $leaveType->id }}" class="btn btn-sm btn-danger btn-rounded delete-category"><i class="fa fa-times"></i> @lang("app.remove")</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noLeaveTypeAdded')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <hr>
        {!! Form::open(['id'=>'createLeaveType','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('modules.leaves.leaveType')</label>
                        <input type="text" name="type_name" id="type_name" class="form-control">
                    </div>
                </div>
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('modules.leaves.noOfLeaves')</label>
                        <input type="number" min="0" name="leave_number" value="0" id="leave_number" class="form-control">
                    </div>
                </div>
                <div class="col-xs-12 ">
                    <label>@lang('modules.leaves.leavePaidStatus')</label>
                    <div class="form-group">
                        <select  class="form-control"  name="paid" id="paid" >
                            <option value="1">@lang('app.paid')</option>
                            <option value="0">@lang('app.unpaid')</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('modules.sticky.colors')</label>
                        <select id="colorselector" name="color">
                            <option value="info" data-color="#5475ed" selected>Blue</option>
                            <option value="warning" data-color="#f1c411">Yellow</option>
                            <option value="purple" data-color="#ab8ce4">Purple</option>
                            <option value="danger" data-color="#ed4040">Red</option>
                            <option value="success" data-color="#00c292">Green</option>
                            <option value="inverse" data-color="#4c5667">Grey</option>
                        </select>
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

<script src="{{ asset('plugins/bootstrap-colorselector/bootstrap-colorselector.min.js') }}"></script>
<script>
    $('#colorselector').colorselector();

    $('#createLeaveType').submit(function () {
        $.easyAjax({
            url: '{{route('admin.leaveType.store')}}',
            container: '#createLeaveType',
            type: "POST",
            data: $('#createLeaveType').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
        return false;
    })

    $('.delete-category').click(function () {
        var id = $(this).data('cat-id');
        var url = "{{ route('admin.leaveType.destroy',':id') }}";
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
                    $('#type-'+id).fadeOut();
                }
            }
        });
    });

    $('#save-type').click(function () {
        $.easyAjax({
            url: '{{route('admin.leaveType.store')}}',
            container: '#createLeaveType',
            type: "POST",
            data: $('#createLeaveType').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>