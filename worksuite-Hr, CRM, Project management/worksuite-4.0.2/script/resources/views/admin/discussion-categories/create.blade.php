<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.manage') @lang('modules.discussions.discussionCategory')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('app.category')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($categories as $key=>$item)
                    <tr id="role-{{ $item->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>
                            <i class="fa fa-circle-o" style="color: {{ $item->color }}"></i> {{ ucfirst($item->name) }}
                        </td>
                        <td>
                            <a href="javascript:;"  data-category-id="{{ $item->id }}" class="btn btn-xs btn-info btn-outline edit-category">@lang("app.edit")</a>
                            <a href="javascript:;"  data-category-id="{{ $item->id }}" class="btn btn-xs btn-danger btn-outline delete-category">@lang("app.remove")</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noRecordFound')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {!! Form::open(['id'=>'createProjectCategory','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <label class="required">@lang('app.category')</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                </div>
            
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <label class="required">@lang('modules.sticky.colors')</label>
                        <div class="example m-b-10">
                            <input type="text" class="complex-colorpicker form-control" name="color" />
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-category" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asColor.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asGradient.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>

<script>
    $(".colorpicker").asColorPicker();
    $(".complex-colorpicker").asColorPicker({
        mode: 'complex'
    });
    $(".gradient-colorpicker").asColorPicker({
        mode: 'gradient'
    });
    
    $('.delete-category').click(function () {
        var roleId = $(this).data('category-id');
        var url = "{{ route('admin.discussion-category.destroy', ':id') }}";
        url = url.replace(':id', roleId);

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token, '_method': 'DELETE', 'roleId': roleId},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    window.location.reload();
                }
            }
        });
    });

    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('admin.discussion-category.store')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: $('#createProjectCategory').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>