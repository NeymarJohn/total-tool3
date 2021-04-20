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


                            @if(is_null($file->external_link))
                            <a href="{{ route('admin.task-files.download', $file->id) }}"
                               data-toggle="tooltip" data-original-title="Download"
                               class="btn btn-default btn-circle"><i
                                        class="fa fa-download"></i></a>
                            @endif

                            <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{ $file->id }}"
                               data-pk="list" class="btn btn-danger btn-circle sa-delete"><i class="fa fa-times"></i></a>
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

<script>
    $('body').on('click', '.sa-delete', function () {
        var id = $(this).data('file-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.confirmation.deleteFile')",
            dangerMode: true,
            icon: 'warning',
            buttons: {
                cancel: "@lang('messages.confirmNoArchive')",
                confirm: {
                    text: "@lang('messages.deleteConfirmation')",
                    value: true,
                    visible: true,
                    className: "danger",
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.task-files.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $('#totalUploadedFiles').html(response.totalFiles);
                            $('#list ul.list-group').html(response.html);
                        }
                    }
                });
            }
        });
    });



    </script>
