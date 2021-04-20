<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><i class="icon-note"></i> @lang("modules.messages.startConversation")</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'createChat','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">

            <div class="form-group">
                <div class="radio-list">
                    @if($messageSetting->allow_client_admin == 'yes')
                    <label class="radio-inline p-0">
                        <div class="radio radio-info">
                            <input type="radio" name="user_type" id="user_admin" value="admin" checked>
                            <label for="user_admin">Admin</label>
                        </div>
                    </label>
                    @endif

                    @if($messageSetting->allow_client_employee == 'yes')
                    <label class="radio-inline">
                        <div class="radio radio-info">
                            <input type="radio" name="user_type" id="user_employee" value="employee" >
                            <label for="user_employee">@lang('app.menu.employees')</label>
                        </div>
                    </label>
                    @endif
                </div>
            </div>

            <div class="row">

                @if($messageSetting->allow_client_admin == 'yes')
                <div class="col-xs-12 " id="admin-list">
                    <div class="form-group">
                        <label>@lang("modules.messages.chooseAdmin")</label>
                        <select class="select2 form-control" data-placeholder="@lang("modules.messages.chooseAdmin")" name="admin_id" id="admin_id">
                            @foreach($admins as $admin)
                                <option
                                        value="{{ $admin->id }}">{{ ucwords($admin->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                @if($messageSetting->allow_client_employee == 'yes')
                <div class="col-xs-12 " id="member-list" style="display: none">
                    <div class="form-group">
                        <label>@lang("modules.messages.chooseMember")</label>
                        <select class="select2 form-control" data-placeholder="@lang("modules.messages.chooseMember")" name="user_id" id="user_id">
                            @foreach($members as $member)
                                <option
                                        value="{{ $member->id }}">{{ ucwords($member->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif


            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="">@lang("modules.messages.message")</label>
                        <textarea name="message" class="form-control" id="message" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions m-t-20">
            <button type="button" id="post-message" class="btn btn-success"><i class="fa fa-send-o"></i> @lang("modules.messages.send")</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>


<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>

<script>

    $('.select2').select2();

    $("input[name=user_type]").click(function () {
        if($(this).val() == 'admin'){
            $('#member-list').hide();
            $('#admin-list').show();
        }
        else{
            $('#admin-list').hide();
            $('#member-list').show();
        }
    })

    $('#post-message').click(function () {
        $.easyAjax({
            url: '{{route('client.user-chat.message-submit')}}',
            container: '#createChat',
            type: "POST",
            data: $('#createChat').serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    var blank = "";
                    $('#submitTexts').val('');

                    //getting values by input fields
                    var dpID = $('#dpID').val();
                    var dpName = $('#dpName').val();


                    //set chat data
                    getChatData(dpID, dpName);

                    //set user list
                    $('.userList').html(response.userList);

                    //set active user
                    if (dpID) {
                        $('#dp_' + dpID + 'a').addClass('active');
                    }

                    $('#newChatModal').modal('hide');
                }
            }
        })
    });
</script>