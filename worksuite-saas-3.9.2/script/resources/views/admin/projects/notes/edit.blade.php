
{{-- @push('head-script')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
    <style>
        .d-none {
            display: none;
        }
        

    </style>
{{-- @endpush --}}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><i class="fa fa-clock-o"></i> @lang('app.updateNotesDetails')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="row">
            <div class="col-xs-12">
                {!! Form::open(['id'=>'updateNotes','class'=>'ajax-form','method'=>'PUT']) !!}
                <div class="form-body">
                    <div class="row m-t-30">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('modules.notes.notesTitle')</label>
                                <input type="text" name="notes_title" id="notes_title" value="{{ $notes->notes_title }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('modules.notes.notesType')</label>
                                <div class="radio-list">
                                    <label class="radio-inline p-0">
                                        <div class="radio radio-info">
                                            <input type="radio" name="notes_type" @if($notes->notes_type == '0') checked @endif  class="radio-button" id="public"
                                            checked="" value="0">
                                            <label>@lang('modules.notes.public')</label>
                                        </div>
                                    </label>
                                    <label class="radio-inline">
                                        <div class="radio radio-info">
                                            <input type="radio" name="notes_type"  @if($notes->notes_type == '1') checked @endif class="radio-button" id="private"
                                                value="1">
                                            <label>@lang('modules.notes.private')</label>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                </div>
                <div class="col-xs-12 private_div @if($notes->notes_type == '0') d-none @endif" id="type_private" >
                    <div class="form-group">
                        <div class="row m-t-30">
                            <div class="col-md-4 ">
                                <div class="form-group">
                                    <label>@lang('modules.notes.selectMember')</label>
                                    <select class="select2 select2-multiple " multiple="multiple"
                                    data-placeholder="@lang('modules.tasks.chooseAssignee')"
                                    name="user_id[]" id="user_id">
                                    @forelse($employees as $employee)
                                        
                                        <option @if(in_array($employee->id, $noteMembers)) selected @endif value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                    @empty
                                        <option value="">@lang('messages.noCategoryAdded')</option>
                                    @endforelse
                                       
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 ">
                                <div class="form-group" style="padding-top: 19px;">
                                    <div class="checkbox checkbox-info">
                                        <input name="is_client_show" @if($notes->is_client_show == 1) checked @endif
                                            type="checkbox" >
                                        <label for="check_amount">@lang('modules.notes.showClient')</label>
                                    </div>
                                    
                                </select>
                                </div>
                            </div>
                            <div class="col-md-4 ">
                                <div class="form-group" style="padding-top: 19px;">
                                    <div class="checkbox checkbox-info">
                                        <input name="ask_password" @if($notes->ask_password == 1) checked @endif
                                            type="checkbox" >
                                        <label for="check_amount">@lang('modules.notes.askReenterPassword')</label>
                                    </div>
                                    
                                </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label">@lang('modules.notes.notesDetails')</label>
                            <textarea name="note_details"  id="note_details"  rows="5"  class="form-control">{{ $notes->note_details ?? '' }}</textarea>
                        </div>
                    </div>
                    <!--/span-->

                </div>
                <div class="form-actions m-t-30">
                    <button type="button" id="update-form" class="btn btn-success"><i class="fa fa-check"></i> Save
                    </button>
                </div>
                {!! Form::close() !!}

            </div>
        </div>

    </div>
</div>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>

$("#updateNotes #user_id").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
$('.radio-list').click(function () {
    if($('.radio-button:checked').val() == 1) {
        $(".private_div").show();
    } else {
        $(".private_div").hide();
    }
})
$('#update-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.project-notes.update', [$notes->id])}}',
            container: '#updateNotes',
            type: "POST",
            data: $('#updateNotes').serialize(),
            success: function (response) {
                $('#editContactModal').modal('hide');
                table._fnDraw();
            }
        })
    });
</script>