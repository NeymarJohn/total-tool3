@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="javascript:;" id="new-chat" class="btn btn-success btn-outline btn-sm"><i class="icon-note"></i> @lang("modules.messages.startConversation")</a>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang("app.menu.home")</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">

@endpush

@section('other-section')
<div class="row">
    <div class="col-md-12">
        <div class="chat-main-box">

                <!-- .chat-left-panel -->
                <div class="chat-left-aside">
                    <div class="open-panel"><i class="ti-angle-right"></i></div>
                    <div class="chat-left-inner">

                        <div class="form-material"><input class="form-control p-20" id="userSearch" type="text"
                                                        placeholder="@lang("modules.messages.searchContact")"></div>
                        <ul class="chatonline style-none userList">
                            @forelse($userList as $users)
                                <li id="dp_{{$users->id}}" class="bg-white">
                                    <a href="javascript:void(0)" id="dpa_{{$users->id}}"
                                    onclick='getChatData("{{$users->id}}", "{{$users->name}}")'>
                                    @if(is_null($users->image))
                                            <img src="{{ asset('img/default-profile-3.png') }}" alt="user-img"
                                                 class="img-circle">
                                        @else
                                            <img src="{{ asset_url('avatar/'.$users->image) }}" alt="user-img"
                                                 class="img-circle">
                                        @endif
                                        <span @if($users->message_seen == 'no' && $users->user_one != $user->id) class="font-bold" @endif> {{$users->name}}
                                            <small class="text-simple"> @if($users->last_message){{  \Carbon\Carbon::parse($users->last_message)->diffForHumans()}} @endif
                                                @if(\App\User::isAdmin($users->id))
                                                    <label class="btn btn-danger btn-xs btn-outline">@lang('app.admin')</label>
                                                @elseif(\App\User::isClient($users->id))
                                                    <label class="btn btn-success btn-xs btn-outline">@lang('app.client')</label>
                                                @else
                                                    <label class="btn btn-warning btn-xs btn-outline">@lang('app.employee')</label>
                                                @endif
                                            </small>
                                        </span>
                                    </a>
                                </li>


                            @empty
                                <li>
                                    @lang("messages.noUser")
                                </li>
                            @endforelse


                            <li class="p-20"></li>
                        </ul>
                    </div>
                </div>
        </div>
    </div>
</div>

@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="chat-main-box" id="chatMainBox">

                <!-- .chat-right-panel -->
                <div class="chat-right-aside">
                    <div class="chat-main-header">
                        <div class="p-20 b-b row">
                            {{-- <h3 class="box-title col-md-9">@lang("app.menu.messages")</h3> --}}
                        </div>
                    </div>
                    <div class="chat-box ">

                        <ul class="chat-list slimscroll p-t-30 chats"></ul>

                        <div class="row send-chat-box">
                            <div class="col-sm-12">

                                <input type="text" name="message" id="submitTexts" autocomplete="off" placeholder="@lang("modules.messages.typeMessage")"
                                       class="form-control">
                                <input id="dpID" value="{{$dpData}}" type="hidden"/>
                                <input id="dpName" value="{{$dpName}}" type="hidden"/>

                                <div class="custom-send">
                                    <button id="submitBtn" class="btn btn-info btn-rounded" type="button">@lang("modules.messages.send")
                                    </button>
                                </div>
                                <div id="errorMessage"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- .chat-right-panel -->
            </div>
        </div>


    </div>
    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="newChatModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>

<script src="{{ asset('js/jquery-ui.min.js') }}"></script>

<script type="text/javascript">

    function deleteMessage(messageId){
        var id = messageId;
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.deleteMessageText')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.user-chat.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $('.commonMessageItem').each(function(){
                                var dpID = $('#dpID').val();
                                var dpName = $('#dpName').val();
                                //set chat data
                                getChatData(dpID, dpName);
                            })
                        }
                    }
                });
            }
        });
    }

    $('.chat-left-inner > .chatonline').slimScroll({
        height: '100%',
        position: 'right',
        size: "0px",
        color: '#dcdcdc',

    });
    $(function () {
        $(window).load(function () { // On load
            $('.chat-list').css({'height': (($(window).height()) - 370) + 'px'});
        });
        $(window).resize(function () { // On resize
            $('.chat-list').css({'height': (($(window).height()) - 370) + 'px'});
        });
    });

    // this is for the left-aside-fix in content area with scroll

    // $(function () {
    //     $(window).load(function () { // On load
    //         $('.chat-left-inner').css({
    //             'height': (($(window).height()) - 240) + 'px'
    //         });
    //     });
    //     $(window).resize(function () { // On resize
    //         $('.chat-left-inner').css({
    //             'height': (($(window).height()) - 240) + 'px'
    //         });
    //     });
    // });


    $(".open-panel").click(function () {
        $(".chat-left-aside").toggleClass("open-pnl");
        $(".open-panel i").toggleClass("ti-angle-left");
    });
</script>
<script>

    $(function () {
        $('#userList').slimScroll({
            height: '350px'
        });
    });

    var dpButtonID = "";
    var dpName = "";
    var scroll = true;

    var dpClassID = '{{$dpData}}';

    if (dpClassID) {
        $('#dp_' + dpClassID).addClass('active');
    }

    //getting data
    window.setInterval(function(){
        getChatData(dpButtonID, dpName);
        /// call your function here
    }, 30000);

    $('#submitTexts').keypress(function (e) {

        var key = e.which;
        if (key == 13)  // the enter key code
        {
            e.preventDefault();
            $('#submitBtn').click();
            return false;
        }
    });


    //submitting message
    $('#submitBtn').on('click', function (e) {
        e.preventDefault();
        //getting values by input fields
        var submitText = $('#submitTexts').val();
        var dpID = $('#dpID').val();
        //checking fields blank
        if (submitText == "" || submitText == undefined || submitText == null) {
            $('#errorMessage').html('<span class="text-danger"><p>@lang('messages.fieldBlank')</p></span>');
            return;
        } else if (dpID == '' || submitText == undefined) {
            $('#errorMessage').html('<span class="text-danger"><p>@lang('messages.noUser')</p></span>');
            return;
        } else {

            var url = "{{ route('admin.user-chat.message-submit') }}";
            var token = "{{ csrf_token() }}";
            $.easyAjax({
                type: 'POST',
                url: url,
                messagePosition: '',
                data: {'message': submitText, 'user_id': dpID, '_token': token},
                container: ".chat-form",
                blockUI: true,
                redirect: false,
                success: function (response) {
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
                }
            });
        }

        return false;
    });

    //getting all chat data according to user
    //submitting message
    $("#userSearch").keyup(function (e) {
        var url = "{{ route('admin.user-chat.user-search') }}";

        $.easyAjax({
            type: 'GET',
            url: url,
            messagePosition: '',
            data: {'term': this.value},
            container: ".userList",
            success: function (response) {
                //set messages in box
                $('.userList').html(response.userList);
            }
        });
    });

    //getting all chat data according to user
    function getChatData(id, dpName, scroll) {
        var getID = '';
        $('#errorMessage').html('');
        if (id != "" && id != undefined && id != null) {
            $('.userList li a.active ').removeClass('active');
            $('#dpa_' + id).addClass('active');
            $('#dpID').val(id);
            getID = id;
            $('#badge_' + id).val('');
        } else {
            $('.userList li:first-child a').addClass('active');
            getID = $('#dpID').val();
        }

        var url = "{{ route('admin.user-chat.index') }}";

        $.easyAjax({
            type: 'GET',
            url: url,
            messagePosition: '',
            data: {'userID': getID},
            container: "#chatMainBox",
            success: function (response) {
                //set messages in box
                $('.chats').html(response.chatData);
                scrollChat();
            }
        });
    }

    function scrollChat() {
        if(scroll == true) {
            $('.chat-list').stop().animate({
                scrollTop: $(".chat-list")[0].scrollHeight
            }, 800);
        }
        scroll = false;
    }

    $('#new-chat').click(function () {
        var url = '{{ route('admin.user-chat.create')}}';
        $('#modelHeading').html('Start Conversation');
        $.ajaxModal('#newChatModal',url);
    })

</script>

@if (request()->get('user') != "")
    <script>
        getChatData("{{ request()->get('user') }}", "{{ request()->get('user') }}");
    </script>
@endif
@endpush
