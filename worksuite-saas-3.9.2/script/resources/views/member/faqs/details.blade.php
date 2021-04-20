<style>
.portlet-body .description {
    overflow:hidden;

}

.portlet-body iframe{
        width:100%;
    }

.user-content {
    margin-top: 30px;
    width: 42px;
    height: 57px;
}
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">{{ $faqDetails->title }}</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="row">
            <div class="row">
                <div class="  col-xs-12 description" style="overflow:hidden">
                    {!! $faqDetails->description !!}
                </div>
            </div>
            <div class="row">
                @foreach($faqDetails->files as $file)
                    <div class="col-md-3 m-b-10">
                        <div class="card" style="min-height: 100px">
                            <div class="file-bg">
                                <div class="overlay-file-box">
                                    <div class="user-content">
                                        @if($file->icon == 'images')
                                            <img class="card-img-top img-responsive " src="{{ $file->file_url }}" alt="Card image cap">
                                        @else
                                            <i  class="fa {{$file->icon}} card-img-top img-responsive " style="font-size: -webkit-xxx-large;"></i>
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

{{--                                <a href="javascript:;" data-toggle="tooltip"--}}
{{--                                   data-original-title="Delete"--}}
{{--                                   data-file-id="{{ $file->id }}"--}}
{{--                                   class="btn btn-danger btn-circle sa-params" data-pk="thumbnail"><i--}}
{{--                                            class="fa fa-times"></i></a>--}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>