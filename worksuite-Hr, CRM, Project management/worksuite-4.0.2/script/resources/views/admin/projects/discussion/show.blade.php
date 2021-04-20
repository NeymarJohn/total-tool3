@extends('layouts.app')

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
            <a href="javascript:;" class="btn btn-sm btn-success btn-outline" id="discussion-category"><i class="fa fa-gear"></i> @lang('modules.discussions.discussionCategory')</a>
            <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-sm btn-info btn-outline" ><i class="icon-note"></i> @lang('app.edit')</a>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.projects.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('modules.projects.discussion')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/jquery-asColorPicker-master/css/asColorPicker.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<style>
    #projects-table_wrapper .dt-buttons, #projects-table thead{
        display: none !important;
    }

    #projects-table tbody td{
        /* border: none; */
        padding: 15px 8px;
    }

    #projects-table.table-hover>tbody>tr:hover {
        background-color: #f7fafc!important;
    }

    .action-div {
        visibility: hidden;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">

                    @include('admin.projects.show_project_menu')

                    <div class="content-wrap">
                        <section class="show" id="discussion">
                            <div class="white-box">
                                <div class="row">
                                    <div class="col-md-3 ">
                                        <div class="row" id="button-area">
                                            <div class="col-xs-12">
                                                <button class="btn btn-info" type="button" id="add-discussion"><i class="fa fa-plus"></i> @lang('app.new') @lang('modules.projects.discussion')</button>
                                            </div>
                                        </div>

                                        <div class="row" id="categories-area">
                                            <div class="col-xs-12">
                                                <ul class="discussion-categories">
                                                    <li class="active">
                                                        <a href="javascript:;" class="text-dark" data-category-id="">
                                                            <i class="fa fa-circle-o"></i> 
                                                            @lang('app.all') @lang('modules.projects.discussion')
                                                        </a>
                                                    </li>
                                                    @foreach ($discussionCategories as $item)
                                                        <li>
                                                            <a href="javascript:;" data-category-id="{{ $item->id }}" style="color: {{ $item->color }}"><i class="fa fa-circle-o"></i> {{ ucwords($item->name) }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-9">
                                      

                                        <div class="row">
                                            <div class="col-md-12">
                                                    <input type="hidden" id="project_id" value="{{ $project->id }}">
                                                    <input type="hidden" id="category_id">
                                    
                                                    <div class="table-responsive">
                                                        {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
                                                    </div>
                                            </div>
                                        </div>
                                        <!-- .row -->

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
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
<script src="{{ asset('plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

{!! $dataTable->scripts() !!}

<script>


    function showData() {
        $('#projects-table').on('preXhr.dt', function (e, settings, data) {
            var projectId = $('#project_id').val();
            var categoryId = $('#category_id').val();

            data['project_id'] = projectId;
            data['category_id'] = categoryId;
        });

        window.LaravelDataTables["projects-table"].draw();
    }

    $('body').on('click', '.delete-discussion', function () {
        var id = $(this).data('discussion-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.recoverRecord')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.discussion.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            showData();
                        }
                    }
                });
            }
        });
    });

    $('body').on('click', '#discussion-category', function () {
        var url = '{{ route('admin.discussion-category.create') }}';

        $('#modelHeading').html('{{ __('app.edit') }} {{ __('modules.projects.milestones') }}');
        $.ajaxModal('#editTimeLogModal', url);

    });

    $('body').on('click', '#add-discussion', function () {
        var url = '{{ route('admin.discussion.create', ['id' => $project->id]) }}';

        $('#modelHeading').html('{{ __('app.edit') }} {{ __('modules.projects.milestones') }}');
        $.ajaxModal('#editTimeLogModal', url);

    });

    $('body').on('click', '.edit-category', function () {
        var categoryId = $(this).data('category-id');
        var url = '{{ route('admin.discussion-category.edit', ':id') }}';
        url = url.replace(':id', categoryId);

        $('#modelHeading').html('{{ __('app.edit') }} {{ __('modules.projects.discussion') }}');
        $.ajaxModal('#editTimeLogModal', url);
    });

    $('.discussion-categories li a').click(function () {
        $('.discussion-categories li').removeClass('active');
        $(this).closest('li').addClass('active');

        var categoryId = $(this).data('category-id');
        $('#category_id').val(categoryId);
        showData();
    });

    $('body').on('mouseover', '#projects-table tr', function () {
        $(this).find('.action-div').css('visibility', 'visible');
    });

    $('body').on('mouseout', '#projects-table tr', function () {
        $('.action-div').css('visibility', 'hidden');
    });
   
    $('ul.showProjectTabs .discussion').addClass('tab-current');
</script>
@endpush
