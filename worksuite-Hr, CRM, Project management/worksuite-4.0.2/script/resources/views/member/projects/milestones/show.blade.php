@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle) #{{ $project->id }} - <span
                        class="font-bold">{{ ucwords($project->project_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-sm btn-success btn-outline" ><i class="icon-note"></i> @lang('app.edit')</a>
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.projects.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('modules.projects.milestones')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')

<link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">

                    @include('member.projects.show_project_menu')

                    <div class="content-wrap">
                        <section id="section-line-3" class="show">
                            <div class="row">
                                <div class="col-md-12" id="issues-list-panel">
                                    <div class="white-box">

                                        @if($user->can('add_projects'))
                                            <div class="row m-b-10">
                                                <div class="col-md-12">
                                                    <a href="javascript:;" id="show-add-form"
                                                    class="btn btn-success btn-outline"><i
                                                                class="fa fa-flag"></i> @lang('modules.projects.createMilestone')
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        

                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! Form::open(['id'=>'logTime','class'=>'ajax-form hide','method'=>'POST']) !!}

                                                {!! Form::hidden('project_id', $project->id) !!}
                                                <input type="hidden" name="currency_id" id="currency_id" value="{{ $project->currency_id ?? global_setting()->currency_id }}">

                                                <div class="form-body">
                                                    <div class="row m-t-30">

                                                        <div class="col-md-6 ">
                                                            <div class="form-group">
                                                                <label class="required">@lang('modules.projects.milestoneTitle')</label>
                                                                <input id="milestone_title" name="milestone_title" type="text"
                                                                       class="form-control" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 ">
                                                                <div class="form-group">
                                                                    <label>@lang('app.status')</label>
                                                                    <select name="status" id="status" class="form-control">
                                                                        <option value="incomplete">@lang('app.incomplete')</option>
                                                                        <option value="complete">@lang('app.complete')</option>
                                                                    </select>
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 ">
                                                            <div class="form-group">
                                                                <label>@lang('modules.projects.milestoneCost')</label>
                                                                <input id="cost" name="cost" type="number"
                                                                       class="form-control" value="0" min="0" step=".01">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 ">
                                                            <div class="form-group">
                                                                <label>@lang('modules.projects.addCostProjectBudget')</label>
                                                                <select name="add_to_budget" id="add_to_budget" class="form-control">
                                                                    <option value="no">@lang('app.no')</option>
                                                                    <option value="yes">@lang('app.yes')</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row m-t-20">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="required" for="memo">@lang('modules.projects.milestoneSummary')</label>
                                                                <textarea name="summary" id="" rows="4" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-actions m-t-10">
                                                    <button type="button" id="save-form" class="btn btn-success"><i
                                                                class="fa fa-check"></i> @lang('app.save')</button>
                                                    <button type="button" id="close-form" class="btn btn-default"><i
                                                                class="fa fa-times"></i> @lang('app.close')</button>
                                                </div>
                                                {!! Form::close() !!}

                                            </div>
                                        </div>

                                        <div class="table-responsive m-t-30">
                                            <table class="table table-bordered table-hover toggle-circle default footable-loaded footable"
                                                   id="timelog-table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('modules.projects.milestoneTitle')</th>
                                                    <th>@lang('modules.projects.milestoneCost')</th>
                                                    <th>@lang('app.status')</th>
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


    </div>
    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="editTimeLogModal" role="dialog" aria-labelledby="myModalLabel"
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
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')

<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>

<script>
    var table = $('#timelog-table').dataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: '{!! route('member.milestones.data', $project->id) !!}',
        deferRender: true,
        language: {
            "url": "<?php echo __("app.datatable") ?>"
        },
        "fnDrawCallback": function (oSettings) {
            $("body").tooltip({
                selector: '[data-toggle="tooltip"]'
            });
        },
        "order": [[0, "desc"]],
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            {data: 'milestone_title', name: 'milestone_title'},
            {data: 'cost', name: 'cost'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'}
        ]
    });


    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('member.milestones.store')}}',
            container: '#logTime',
            type: "POST",
            data: $('#logTime').serialize(),
            success: function (data) {
                if (data.status == 'success') {
                    $('#logTime').trigger("reset");
                    $('#logTime').toggleClass('hide', 'show');
                    table._fnDraw();
                }
            }
        })
    });

    $('#show-add-form, #close-form').click(function () {
        $('#logTime').toggleClass('hide', 'show');
    });


    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('milestone-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.deleteMilestone')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.milestones.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                            table._fnDraw();
                        }
                    }
                });
            }
        });
    });

    $('body').on('click', '.edit-milestone', function () {
        var id = $(this).data('milestone-id');

        var url = '{{ route('member.milestones.edit', ':id')}}';
        url = url.replace(':id', id);

        $('#modelHeading').html('{{ __('app.edit') }} {{ __('modules.projects.milestones') }}');
        $.ajaxModal('#editTimeLogModal', url);

    });

    $('body').on('click', '.milestone-detail', function () {
        var id = $(this).data('milestone-id');
        var url = '{{ route('member.milestones.detail', ":id")}}';
        url = url.replace(':id', id);
        $('#modelHeading').html('@lang('app.update') @lang('modules.projects.milestones')');
        $.ajaxModal('#editTimeLogModal',url);
    })
    $('ul.showProjectTabs .projectMilestones').addClass('tab-current');
</script>
@endpush
