
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.edit') @lang('modules.discussions.discussionCategory')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        

        {!! Form::open(['id'=>'createProjectCategory','class'=>'ajax-form','method'=>'PUT']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label class="required">@lang('app.category')</label>
                        <input type="text" name="name" id="name" value="{{ $category->name }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required">@lang('modules.sticky.colors')</label>
                        <div class="example m-b-10">
                            <input type="text" class="complex-colorpicker form-control" name="color" value="{{ $category->color }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="update-category" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
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

    $('#update-category').click(function () {
        var roleId = "{{ $category->id }}";
        var url = "{{ route('admin.discussion-category.update', ':id') }}";
        url = url.replace(':id', roleId);

        $.easyAjax({
            url: url,
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