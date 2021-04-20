<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.productCategory.productSubCategory')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('modules.productCategory.subCategory')</th>
                    <th>@lang('modules.productCategory.category')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($subCategories as $key=>$subCategory)
                    <tr id="cat-{{ $subCategory->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($subCategory->category_name) }}</td>
                        <td>{{ ucwords($subCategory->category->category_name) }}</td>
                        <td><a href="javascript:;" data-cat-id="{{ $subCategory->id }}" class="btn btn-sm btn-danger btn-rounded delete-category">@lang("app.remove")</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noProjectCategory')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <hr>
        {!! Form::open(['id'=>'createProjectCategory','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('modules.productCategory.category')</label>
                        <select class="select2 form-control" name="category" id="category"
                                data-style="form-control">
                            @forelse($categories as $category)
                                <option @if($categoryID == $category->id) selected @endif value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                            @empty
                                <option value="">@lang('messages.noProductCategory')</option>
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('modules.productCategory.subCategoryName')</label>
                        <input type="text" name="sub_category_name" id="sub_category_name" class="form-control">
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
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script>

    $("#category").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('.delete-category').click(function () {
        var id = $(this).data('cat-id');
        var url = "{{ route('admin.productSubCategory.destroy',':id') }}";
        url = url.replace(':id', id);

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token, '_method': 'DELETE'},
            success: function (response) {

                if (response.status == "success") {
                    $.unblockUI();
                    $('#cat-'+id).fadeOut();
                    var options = [];
                    var rData = [];
                    rData = response.data;
                    subCategories = response.data; // using it on product create/edit page
                    var opts = '';
                    var catIds = '{{ $categoryID }}';
                    var subCategory = rData.filter(function (item) {
                        return item.category_id == catIds;
                    });
                    subCategory.forEach(project => {
                        opts += `<option value='${project.id}'>${project.category_name}</option>`
                    })

                    $('#sub_category_id').html('<option value="">Select Sub Category...</option>'+opts);

                    $("#sub_category_id").select2({
                        formatNoMatches: function () {
                            return "{{ __('messages.noRecordFound') }}";
                        }
                    });
                }
            }
        });
    });

    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('admin.productSubCategory.store')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: $('#createProjectCategory').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    var options = [];
                    var rData = [];
                    rData = response.data;
                    subCategories = response.data; // using it on product create/edit page

                    var opts = '';
                    var catIds =  '{{ $categoryID }}';
                    var subCategory = rData.filter(function (item) {
                        return item.category_id == catIds;
                    });

                    subCategory.forEach(project => {
                        opts += `<option value='${project.id}'>${project.category_name}</option>`
                    })

                    $('#sub_category_id').html('<option value="">Select Sub Category...</option>'+opts);

                    $("#sub_category_id").select2({
                        formatNoMatches: function () {
                            return "{{ __('messages.noRecordFound') }}";
                        }
                    });
                    $('#taxModal').modal('hide');

                }
            }
        })
    });
</script>