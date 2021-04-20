<div class="panel-body bg-owner-reply"  id="replyMessageBox_{{$reply->id}}">

    <div class="row m-b-5">

        <div class="col-xs-2 col-md-1">
            <img src="{{ $reply->user->image_url }}"
                                alt="user" class="img-circle" width="40" height="40">
        </div>
        <div class="col-xs-8 col-md-10">
            <h5 class="m-t-0 font-bold">
                <a href="{{ route('admin.employees.show', $reply->user_id) }}"

                        class="text-inverse">{{ ucwords($reply->user->name) }}
                    <span class="text-muted font-12 font-normal">{{ $reply->created_at->timezone($global->timezone)->format($global->date_format.' '.$global->time_format) }}</span>
                </a>
            </h5>

            <div class="font-light">
                {!! ucfirst(nl2br($reply->message)) !!}
            </div>
        </div>
        <div class="col-xs-2 col-md-1">
            <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete"
                data-file-id="{{ $reply->id }}"
                class="btn btn-inverse btn-outline sa-params" data-pk="list"><i
            class="fa fa-trash"></i></a>
        </div>

    </div>
    @if(sizeof($reply->files) > 0)
        <div class="row bg-white" id="list">
            <ul class="list-group" id="files-list">
                @forelse($reply->files as $file)
                    <li class="list-group-item b-none col-md-6">
                        <div class="row">
                            <div class="col-md-8">
                                {{ $file->filename }}
                            </div>
                            <div class="col-md-4 text-right">


                                <a target="_blank" href="{{ $file->file_url }}"
                                   data-toggle="tooltip" data-original-title="View"
                                   class="btn btn-inverse btn-sm btn-outline"><i
                                            class="fa fa-search"></i></a>


                                @if(is_null($file->external_link))
                                    &nbsp;&nbsp;
                                    <a href="{{ route('admin.support-ticket-files.download', $file->id) }}"
                                    data-toggle="tooltip" data-original-title="Download"
                                    class="btn btn-inverse btn-sm btn-outline"><i
                                                class="fa fa-download"></i></a>
                                @endif
                                {{--&nbsp;&nbsp;--}}
                                {{--<a href="javascript:;" data-toggle="tooltip"--}}
                                {{--data-original-title="Delete"--}}
                                {{--data-file-id="{{ $file->id }}"--}}
                                {{--class="btn btn-danger btn-circle sa-params" data-pk="list"><i--}}
                                            {{--class="fa fa-times"></i></a>--}}

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
