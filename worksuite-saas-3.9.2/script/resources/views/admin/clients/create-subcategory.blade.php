<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.clients.clientSubCategory')</h4>
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
                
                @forelse($subcategories as $key=>$subcategory)
                    <tr id="cat-{{ $subcategory->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($subcategory->category_name) }}</td>
                        <td>{{ ucwords($subcategory->client_category->category_name) }}</td>
                        <td><a href="javascript:;" data-cat-id="{{ $subcategory->id }}" class="btn btn-sm btn-danger btn-rounded delete-category">@lang("app.remove")</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noSubCategoryAdded')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <hr>
        {!! Form::open(['id'=>'createProjectCategory','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
            <div class="col-xs-12">
            <div class="form-group">
              <label for="">@lang('modules.clients.clientCategory')</label>
               <select class="select2 form-control" data-placeholder="@lang('modules.client.clientCategory')"  id="category" name="category_id">
                <option value="">@lang('messages.pleaseSelectCategory')</option>
                @forelse($categories as $category)
                <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                   @empty
                    <option value="">@lang('messages.noCategoryAdded')</option>
                    @endforelse
                </select>
            </div>
            </div>
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label class="required"> @lang('modules.clients.subcategoryName')</label>
                        <input type="text" name="category_name" id="category_name" class="form-control">
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

<script>

    $('.delete-category').click(function () {
        var id = $(this).data('cat-id');
        var url = "{{ route('admin.clientSubCategory.destroy',':id') }}";
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
                }
            }
        });
    });

    $('#save-category').click(function () {
        category_id = $('#category_id').val();    //id of create client category field
         category = $('#category').val();      // id of modal category drop down

        $.easyAjax({
            url: '{{route('admin.clientSubCategory.store')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: $('#createProjectCategory').serialize(),
            success: function (response) {
                    if(response.status == 'success'){
                        var options = [];
                        var rData = [];
                        rData = response.data;
                        $.each(rData, function( index, value ) {
                            var selectData = '';
                            selectData = '<option value="'+value.id+'">'+value.category_name+'</option>';
                            options.push(selectData);
                        });
                         if(category_id == category ){
                            getCategory(category);
                         }
                        $('#sub_category_id').selectpicker('refresh');
                        $('#sub_category_id').html(options);
                        $('#clientCategoryModal').modal('hide');
                    }
            }
        })
    });
</script>