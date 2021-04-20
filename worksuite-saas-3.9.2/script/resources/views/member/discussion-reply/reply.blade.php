@foreach ($discussionReplies as $key=>$reply)
    <div class="panel-body 
        @if ($discussion->best_answer_id == $reply->id)
            bg-best-reply 
        @else
            @if($reply->user->id == $user->id) 
            bg-owner-reply 
            @else 
            bg-other-reply 
            @endif 
        @endif

        " id="replyMessageBox_{{$reply->id}}">

        @if ($key == 0)
            <div class="row">

                <div class="col-md-10 m-b-10">
                    <h4 class="text-capitalize">{{ $discussion->title }}</h4>

                </div>
                
                <div class="col-md-2 m-b-10 text-right">
                    <span style="color:  {{ $discussion->category->color }}"><i class="fa fa-circle"></i> {{ ucwords($discussion->category->name) }}</span>

                </div>


                {!! Form::hidden('project_id', $discussion->project_id, ['id' => 'project_id']) !!}

            </div>
        @endif

        <div class="row">

            <div class="col-xs-2 col-md-1">
                <img src="{{ $reply->user->image_url }}" alt="user" class="img-circle" width="40" height="40">
            </div>
            <div class="col-xs-7 col-md-9">
                <h5 class="m-t-0 font-bold">
                    <a
                        @if($reply->user->hasRole('employee'))
                        href="{{ route('member.employees.show', $reply->user_id) }}"
                        @elseif($reply->user->hasRole('client'))
                        href="{{ route('member.clients.projects', $reply->user_id) }}"
                        @endif
                        class="text-inverse">{{ ucwords($reply->user->name) }}
                        <span class="text-muted font-12 font-normal">{{ $reply->created_at->timezone($global->timezone)->format($global->date_format.' '.$global->time_format) }}</span>
                    </a>
                </h5>

                <div class="font-light">
                    {!! $reply->body !!}
                </div>
            </div>

            @if ($key != 0 && is_null($discussion->best_answer_id) && $discussion->user_id == $user->id)
                <div class="col-md-2 col-xs-3 text-right">
                    <a href="javascript:;" data-reply-id="{{ $reply->id }}" class="btn btn-default set-best-answer btn-outline btn-sm">@lang('modules.discussions.bestReply')</a>
                </div>
            @elseif($discussion->best_answer_id == $reply->id)
                <div class="col-md-2 col-xs-3 text-right">
                    <label for="" class="label label-success">@lang('modules.discussions.bestReply')</label>
                </div>
            @endif

            <div class="col-xs-10 col-xs-offset-2 col-md-11 col-md-offset-1 action-div">
                <a href="javascript:;"
                data-reply-id="{{ $reply->id }}" class="font-12 add-reply text-muted">
                <i  class="fa fa-mail-reply"></i> @lang('app.reply')</a>    
                
                
                @if($discussion->best_answer_id == $reply->id && $discussion->user_id == $user->id)
                    <a href="javascript:;"
                    data-reply-id="{{ $reply->id }}" class="m-l-10 font-12 unset-best-answer text-muted">
                    <i  class="fa fa-times"></i> @lang('modules.discussions.removeBestReply')</a>
                @endif

                @if ($reply->user_id == $user->id)

                    <a href="javascript:;"
                    data-reply-id="{{ $reply->id }}" class="m-l-10 font-12 edit-reply text-muted">
                    <i  class="fa fa-edit"></i> @lang('app.edit')</a>

                    @if ($key != 0)
                        <a href="javascript:;"
                        data-reply-id="{{ $reply->id }}" class="m-l-10 font-12 delete-reply text-muted">
                        <i  class="fa fa-trash"></i> @lang('app.delete')</a>
                    @endif
                @endif
                            
            </div>


        </div>
        
    </div>
@endforeach