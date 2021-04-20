<div class="panel-body bg-owner-reply"  id="replyMessageBox_{{$reply->id}}">

    <div class="row">

        <div class="col-xs-2 col-md-1">
            {!!  ($reply->user->image) ? '<img src="'.asset_url('avatar/'.$reply->user->image).'"
                                alt="user" class="img-circle" width="40">' : '<img src="'.asset('img/default-profile-3.png').'"
                                alt="user" class="img-circle" width="40">' !!}
        </div>
        <div class="col-xs-10 col-md-11">
            <h5 class="m-t-0 font-bold">
                <a
                        class="text-inverse">{{ ucwords($reply->user->name) }}
                    <span class="text-muted font-12 font-normal">{{ $reply->created_at->timezone($global->timezone)->format($global->date_format.' '.$global->time_format) }}</span>
                </a>
            </h5>

            <div class="font-light">
                {!! ucfirst(nl2br($reply->message)) !!}
            </div>
        </div>


    </div>
    <!--/row-->

    @if(sizeof($reply->files) > 0)
        <div class="row bg-white" id="list">
            <ul class="list-group" id="files-list">
                @forelse($reply->files as $file)
                    <li class="list-group-item b-none col-md-6">
                        <div class="row">
                            <div class="col-md-9">
                                {{ $file->filename }}
                            </div>
                            <div class="col-md-3">

                                    <a target="_blank" href="{{ $file->file_url }}"
                                       title="View"
                                       class="btn btn-info btn-sm btn-outline"><i
                                                class="fa fa-search"></i></a>

                                <span class="clearfix font-12 text-muted">{{ $file->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-10">
                                @lang('messages.noFileUploaded')
                            </div>
                        </div>
                    </li>
                @endforelse

            </ul>
        </div>
        <!--/row-->
    @endif

</div>
