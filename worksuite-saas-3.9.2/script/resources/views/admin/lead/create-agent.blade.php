<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.leadAgent')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('app.leadAgent')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($agentData as $key=>$empAgent)
                    <tr id="cat-{{ $empAgent->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($empAgent->user->name). ' ['.$empAgent->user->email.']' }}</td>
                        <td><a href="javascript:;" data-cat-id="{{ $empAgent->id }}" class="btn btn-sm btn-danger btn-rounded delete-category">@lang("app.remove")</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noLeadAgent')</td>
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
                        <label for="">@lang('modules.tickets.chooseAgents')</label>
                        <select class="select2 form-control" data-placeholder="@lang('modules.tickets.chooseAgents')" id="agent_name" name="agent_name">
                            <option value="">@lang('modules.tickets.chooseAgents')</option>
                            @foreach($employeeData as $empData)
                                <option id="employeeList{{ $empData->id }}" value="{{ $empData->id }}">{{ ucwords($empData->name) }} @if($empData->id == $user->id)
                                        (YOU) @endif</option>
                            @endforeach
                        </select>
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
    $("#agent_name").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    $('.delete-category').click(function () {
        var id = $(this).data('cat-id');
        var url = "{{ route('admin.lead-agent-settings.destroy',':id') }}";
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
                    var empOptions = [];
                    var rData = [];
                    var empData = [];
                    rData = response.data;
                    empData = response.empData;

                    $.each(rData, function( index, value ) {
                        var selectData = '';
                        selectData = '<option value="'+value.id+'">'+value.name+'</option>';
                        options.push(selectData);
                    });

                    $('#agent_id').html(options);
                    $("#agent_name").select2();

                    var you = '';
                    $.each(empData, function( empIndex, empValue ) {
                        var selectEmpData = '';
                        var userID = {{$user->id}};
                        if(empValue.id == userID){
                            you = '(YOU)';
                        }
                        else{
                            you = '';
                        }
                        selectEmpData = '<option  id="employeeList'+empValue.id+'" value="'+empValue.id+'">'+empValue.name+' '+you+'</option>';
                        empOptions.push(selectEmpData);
                    });

                    $('#agent_name').html(empOptions);
                    $("#agent_name").select2();
                }
            }
        });
    });

    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('admin.lead-agent-settings.create-agent')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: $('#createProjectCategory').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    if(response.status == 'success'){
                        console.log(response.data);
                        var options = [];
                        var rData = [];
                        rData = response.data;
                        console.log(rData);
                        $.each(rData, function( index, value ) {
                            var selectData = '';
                            selectData = '<option value="'+value.id+'">'+value.name+'</option>';
                            options.push(selectData);
                        });

                        $('#agent_id').html(options);
                        $("#agent_name").select2();
                        $('#projectCategoryModal').modal('hide');
                    }
                }
            }
        })
    });
</script>