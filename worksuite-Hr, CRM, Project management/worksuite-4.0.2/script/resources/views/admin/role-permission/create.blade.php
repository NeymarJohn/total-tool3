<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.roles.addRole')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('app.role')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($roles as $key=>$role)
                    <tr id="role-{{ $role->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>
                            @if(!in_array($role->name, ['admin','employee' ,'client']))
                                <a href="#"  data-name="name"  data-url="{{ route('admin.role-permission.update', $role->id) }}" class="roleEditable" data-type="text" data-pk="{{ $role->id }}" data-value="{{ ucfirst($role->name) }}" ></a>
                            @else
                                {{ ucfirst($role->name) }}
                            @endif
                        </td>
                          <td>
                              @if($role->id > 3)
                                  <a href="javascript:;"  data-role-id="{{ $role->id }}" class="btn btn-xs btn-danger btn-outline delete-category">@lang("app.remove")</a>
                              @else
                                  <span class="text-muted">@lang('messages.defaultRoleCantDelete')</span>
                              @endif
                          </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noRoleFound')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {!! Form::open(['id'=>'createProjectCategory','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label class="required">@lang('modules.permission.roleName')</label>
                        <input type="text" name="name" id="name" class="form-control">
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
<script src="{{ asset('plugins/bower_components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js') }}"></script>

<script>
    $(function() {
        var edit = $('.roleEditable').editable({
            send: 'always',
            type: 'text',
            emptytext: 'Enter Role',
            params: {
                '_method': 'PUT',
                '_token':  '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.status == 'success'){
                    $.showToastr(response.message, 'success','');
                    $('#role_display_name').text(response.display_name);
                }
            },
            validate: function(value) {
                if ($.trim(value) == '') return 'This field is required';
            },
            error: function(response, newValue) {
                var object = response.responseJSON;
                var string = '';
                console.log(object.errors.value[0]);

                string = string + '<div>' + object.errors.value[0] + '</div>';
                $('.editable-error-block').html(string);
            }
        });
    });
    $('.delete-category').click(function () {
        var roleId = $(this).data('role-id');
        var url = "{{ route('admin.role-permission.deleteRole') }}";

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token, 'roleId': roleId},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                    window.location.reload();
                }
            }
        });
    });

    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('admin.role-permission.storeRole')}}',
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