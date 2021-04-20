<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"> @lang('modules.tasks.uplodedFiles')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="row" id="list">
            <ul class="list-group" id="files-list">
                @forelse($taskFiles as $file)
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-9">
                            {{ $file->filename }}
                        </div>
                        <div class="col-md-3">
                                <a target="_blank" href="{{ $file->file_url }}"
                                   data-toggle="tooltip" data-original-title="View"
                                   class="btn btn-info btn-circle"><i
                                            class="fa fa-search"></i></a>
                           
                            <span class="clearfix m-l-10">{{ $file->created_at->diffForHumans() }}</span>
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
    </div>
</div>

