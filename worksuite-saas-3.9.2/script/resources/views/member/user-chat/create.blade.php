<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><i class="icon-note"></i> @lang("modules.messages.startConversation")</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'createChat','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">

            @if($messageSetting->allow_client_employee == 'yes')
            <div class="form-group">
                <div class="radio-list">
                    <label class="radio-inline p-0">
                        <div class="radio radio-info">
                            <input type="radio" name="user_type" id="user_employee" value="employee" checked>
                            <label for="user_employee">@lang('app.menu.employees')</label>
                        </div>
                    </label>
                    <label class="radio-inline">
                        <div class="radio radio-info">
                            <input type="radio" name="user_type" id="user_client" value="client">
                            <label for="user_client">@lang('app.menu.clients')</label>
                        </div>
                    </label>
                </div>
            </div>
            @endif


            <div class="row">
                <div class="col-xs-12 " id="member-list">
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

                @if($messageSetting->allow_client_employee == 'yes')
                <div class="col-xs-12 " id="client-list" style="display: none">
                    <div class="form-group">
                        <label>@lang("modules.client.clientName")</label>
                        <select class="select2 form-control" data-placeholder="@lang("modules.client.clientName")" name="client_id" id="client_id">
                            @foreach($clients as $client)
                                <option
                                        value="{{ $client->id }}">{{ ucwords($client->name) }}</option>
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
        if($(this).val() == 'client'){
            $('#member-list').hide();
            $('#client-list').show();
        }
        else{
            $('#client-list').hide();
            $('#member-list').show();
        }
    })

    $('#post-message').click(function () {
        $.easyAjax({
            url: '{{route('member.user-chat.message-submit')}}',
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