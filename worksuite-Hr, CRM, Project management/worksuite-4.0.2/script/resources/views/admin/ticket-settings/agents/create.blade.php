<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.addNew') @lang('modules.tickets.agents')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'createAgents','class'=>'ajax-form','method'=>'POST']) !!}

            <div class="form-body">

                <div class="form-group" >
                    <label for="" class="required">@lang('modules.tickets.chooseAgents')</label>
                    <select class="select2 m-b-10 select2-multiple " multiple="multiple"
                            data-placeholder="@lang('modules.tickets.chooseAgents')" id="user_id_ajax" name="user_id[]">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ ucwords($emp->name). ' ['.$emp->email.']' }} @if($emp->id == $user->id)
                                    (YOU) @endif</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="" class="required">@lang('modules.tickets.assignGroup')</label>
                    <select class="selectpicker form-control" name="group_id" id="group_id_ajax"
                            data-style="form-control">
                        <option value="">--</option>

                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ ucwords($group->group_name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-actions">
                    <button type="button" id="save-members" class="btn btn-success"><i
                                class="fa fa-check"></i> @lang('app.save')
                    </button>
                </div>
            </div>

        {!! Form::close() !!}
    </div>
</div>

<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script>
    $("#user_id_ajax").select2();
    $("#group_id_ajax").selectpicker();

    $('#save-members').click(function () {
        $.easyAjax({
            url: '{{route('admin.ticket-agents.store')}}',
            container: '#createAgents',
            type: "POST",
            data: $('#createAgents').serialize(),
            success: function (response) {
                if ($('#agent_id').length !== 0) {
                    $('#agent_id').html(response.teamData);
                    $('#agent_id').select2();
                    $('#ticketModal').modal('hide');                        
                } else {
                    window.location.reload();
                }
            }
        })
    });


</script>