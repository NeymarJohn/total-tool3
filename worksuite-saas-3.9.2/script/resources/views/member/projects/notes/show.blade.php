@extends('layouts.member-app')
@push('head-script')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <style>
        .d-none {
            display: none;
        }
        

    </style>
@endpush
@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.projects.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('modules.projects.milestones')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection
@push('head-script')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
@endpush
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <section>
                <div class="sttabs tabs-style-line">
                    @include('admin.projects.show_project_menu')
                    <div class="content-wrap">
                        <section id="section-line-3" class="show">
                            <div class="row">
                                <div class="col-xs-12" id="issues-list-panel">
                                    <div class="white-box">
                                        <h2>@lang('app.menu.notes')</h2>
                                        @if($user->cans('add_projects'))
                                            <div class="row m-b-10">
                                                <div class="col-xs-12">
                                                    <a href="javascript:;" id="show-add-form"
                                                    class="btn btn-success btn-outline"> @lang('modules.notes.addNotes')</a>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-xs-12">
                                                {!! Form::open(['id'=>'addNotes','class'=>'ajax-form hide','method'=>'POST']) !!}
                                                {!! Form::hidden('project_id', $project->id) !!}
                                                {!! Form::hidden('client_id', $project->client->id) !!}

                                                <div class="form-body" id ="addContact">
                                                    <div class="row m-t-30">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('modules.notes.notesTitle')</label>
                                                                <input type="text" name="notes_title" id="notes_title"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('modules.notes.notesType')</label>
                                                                <div class="radio-list">
                                                                    <label class="radio-inline p-0">
                                                                        <div class="radio radio-info">
                                                                            <input type="radio" name="notes_type" id="public"
                                                                                checked="" value="0">
                                                                            <label>@lang('modules.notes.public')</label>
                                                                        </div>
                                                                    </label>
                                                                    <label class="radio-inline">
                                                                        <div class="radio radio-info">
                                                                            <input type="radio" name="notes_type" id="private"
                                                                                value="1">
                                                                            <label>@lang('modules.notes.private')</label>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                   

                                                </div>
                                                <div class="col-xs-12 " id="type_private">
                                                    <div class="form-group">
                                                        <div class="row m-t-30">
                                                            <div class="col-md-4 ">
                                                                <div class="form-group">
                                                                    <label>@lang('modules.notes.selectMember')</label>
                                                                    <select class="select2 m-b-10 select2-multiple" multiple="multiple" name="user_id[]" id="user_id">
                                                                        @forelse($employees as $employee)
                                                                      
                                                                        <option  value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                                                    @empty
                                                                        <option value="">@lang('messages.noProjectCategoryAdded')</option>
                                                                    @endforelse
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 ">
                                                                <div class="form-group" style="padding-top: 19px;">
                                                                    <div class="checkbox checkbox-info">
                                                                        <input name="is_client_show" value ="1"
                                                                            type="checkbox" >
                                                                        <label for="check_amount">@lang('modules.notes.showClient')</label>
                                                                    </div>
                                                                    
                                                                </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 ">
                                                                <div class="form-group" style="padding-top: 19px; padding-left: 53px;">
                                                                    <div class="checkbox checkbox-info">
                                                                        <input name="ask_password" value = "1"
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
                                                            <textarea name="note_details"  id="note_details"  rows="5"  class="form-control">{{ $leadDetail->address ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                
                                                </div>
                                                <div class="form-actions m-t-30">
                                                    <button type="button" id="save-form" class="btn btn-success"> <i
                                                            class="fa fa-check"></i> @lang('app.save')</button>
                                                            <button type="button" id="close-form" class="btn btn-default"><i
                                                                class="fa fa-times"></i> @lang('app.close')</button>
                                                        </div>
                                                {!! Form::close() !!}

                                                <hr>
                                            </div>
                                        </div>

                                        <div class="table-responsive m-t-30">
                                            <table
                                                class="table table-bordered table-hover toggle-circle default footable-loaded footable"
                                                id="contacts-table">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('app.id')</th>
                                                        <th>@lang('app.notesTitle')</th>
                                                        <th>@lang('app.notesType')</th>
                                                        <th>@lang('app.action')</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </section>

                    </div><!-- /content -->
                </div><!-- /tabs -->
            </section>
        </div>
        {{-- Ajax Modal --}}
        <div class="modal fade bs-modal-md in" id="editContactModal" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" id="modal-data-application">
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
        {{-- Ajax Modal Ends --}}

    </div>

@endsection

@push('footer-script')

<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>

    <script>
       var table = $('#contacts-table').dataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: '{!! route('member.project-notes.data', $project->id) !!}',
        deferRender: true,
        language: {
            "url": "<?php echo __("app.datatable") ?>"
        },
        "fnDrawCallback": function( oSettings ) {
            $("body").tooltip({
                selector: '[data-toggle="tooltip"]'
            });
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'notes_title', name: 'notes_title' },
            { data: 'notes_type', name: 'notes_type' },
            { data: 'action', name: 'action' }
        ]
    });
             $('ul.showClientTabs .clientNotes').addClass('tab-current');
             if ($('input[name=notes_type]:checked').val() === "private") {
                    $('#type_private').removeClass('d-none').addClass('d-block');
                } else {
                    $('#type_private').removeClass('d-block').addClass('d-none');
                }
            $('.radio-list').click(function() {
                console.log($('input[name=notes_type]:checked').val());
                if ($('input[name=notes_type]:checked').val() === '1') {
                    $('#type_private').removeClass('d-none').addClass('d-block');
                } else {
                    $('#type_private').removeClass('d-block').addClass('d-none');
                }
            })

            $('#save-form').click(function () {
                $.easyAjax({
                    url: '{{route('member.project-notes.store')}}',
                    container: '#addNotes',
                    type: "POST",
                    redirect: true,
                    data: $('#addNotes').serialize(),
                    success: function (data) {
                    if(data.status == 'success'){
                        location.reload();

                     //   $('#addNotes').toggleClass('hide', 'show');
                        table._fnDraw();
                    }
            }
                })
             });
             $('body').on('click', '.edit-contact', function () {
                var id = $(this).data('contact-id');

                var url = '{{ route('member.project-notes.edit', ':id')}}';
                url = url.replace(':id', id);

                $('#modelHeading').html('Update Contact');
                $.ajaxModal('#editContactModal',url);

            });
            $('body').on('click', '.view-notes-modal', function () {
                var id = $(this).data('contact-id');
              
                $("#modal-data-application").removeClass("modal-dialog modal-lg");
                 $("#modal-data-application").addClass("modal-dialog modal-md");
                var url = '{{ route('member.project-notes.verify-password', ':id')}}';
                url = url.replace(':id', id);

                $('#modelHeading').html('Update Contact');
                $.ajaxModal('#editContactModal',url);

            });
            $('body').on('click', '.view-notes', function () {
                var id = $(this).data('contact-id');
                $("#modal-data-application").removeClass("modal-dialog modal-md");
                 $("#modal-data-application").addClass("modal-dialog modal-lg");
                var url = '{{ route('member.project-notes.view', ':id')}}';
                url = url.replace(':id', id);

                $('#modelHeading').html('Update Contact');
                $.ajaxModal('#editContactModal',url);

            });


    $('body').on('click', '.sa-params', function(){
        var id = $(this).data('contact-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.confirmation.recoverNotes')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.deleteConfirmation')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {
                var url = "{{ route('member.project-notes.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            success: function (response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    table._fnDraw();
                                }
                            }
                });
            }
        });
    });
             $('#show-add-form,#close-form').click(function () {
                $('#addNotes').toggleClass('hide', 'show');
            });
            $(".select2").select2({
                formatNoMatches: function () {
                    return "{{ __('messages.noRecordFound') }}";
                }
            });
    </script>
@endpush
