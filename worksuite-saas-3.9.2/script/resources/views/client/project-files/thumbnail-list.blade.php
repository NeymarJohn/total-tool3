<div class="row">
    @foreach($project->files as $file)
        <div class="col-md-2 m-b-10">
            <div class="card">
                    <div class="file-bg">
                        <div class="overlay-file-box">
                            <div class="user-content">
                                @if($file->icon == 'images')
                                    <img class="card-img-top img-responsive" src="{{ $file->file_url }}" alt="Card image cap">
                                @else
                                    <i class="fa {{$file->icon}}" style="font-size: -webkit-xxx-large; padding-top: 65px;"></i>
                                @endif
                            </div>
                        </div>
                    </div>

                <div class="card-block">
                    <h6 class="card-title">{{ $file->filename }}</h6>

                        <a target="_blank" href="{{ $file->file_url }}"
                           data-toggle="tooltip" data-original-title="View"
                           class="btn btn-info btn-circle"><i
                                    class="fa fa-search"></i></a>

                    @if(is_null($file->external_link))
                    <a href="{{ route('client.files.download', $file->id) }}"
                       data-toggle="tooltip" data-original-title="Download"
                       class="btn btn-default btn-circle"><i
                                class="fa fa-download"></i></a>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
