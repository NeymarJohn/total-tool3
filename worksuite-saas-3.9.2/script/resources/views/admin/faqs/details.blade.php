<style>
.portlet-body .description {
    overflow:hidden;

}

.portlet-body iframe{
        width:100%;
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
                <div class="col-md-12">
                    @foreach($faqDetails->files as $file)
                        <img width="50%" height="50%" src="{{ $file->file_url }}" alt=""/>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>