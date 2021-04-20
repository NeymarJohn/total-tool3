@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
            @if(!$groups->isEmpty())
                <a href="javascript:;" id="department-setting" class="btn btn-outline btn-success btn-sm">@lang('app.add') @lang('app.team') <i class="fa fa-plus" aria-hidden="true"></i></a>
            @endif
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="white-box">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="users-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('app.menu.teams')</th>
                            <th>@lang('app.menu.employees')</th>
                            <th>@lang('app.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($groups as $key=>$group)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $group->team_name }} <label class="label label-success">{{ sizeof($group->team_members) }} @lang('modules.projects.members')</label></td>
                                <td>
                                    @forelse($group->team_members as $item)
                                        <img data-toggle="tooltip" data-original-title="{{ ucwords($item->user->name) }}" src="{{ $item->user->image_url }}"
                                        alt="user" class="img-circle" width="25" height="25"> 
                                    @empty
                                        @lang('messages.noRecordFound')
                                    @endforelse
                                </td>
                                <td>

                                    <div class="btn-group dropdown m-r-10">
                                        <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                                        <ul role="menu" class="dropdown-menu pull-right">
                                            <li><a href="{{ route('admin.department.edit', [$group->id]) }}"><i class="icon-settings"></i> @lang('app.manage')</a></li>
                                            <li><a href="javascript:;"  data-group-id="{{ $group->id }}" class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> @lang('app.delete') </a></li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    <div class="empty-space" style="height: 200px;">
                                        <div class="empty-space-inner">
                                            <div class="icon" style="font-size:30px"><i
                                                        class="icon-layers"></i>
                                            </div>
                                            <div class="title m-b-15">@lang('messages.noDepartment')
                                            </div>
                                            <div class="subtitle">
                                                <a href="{{ route('admin.department.create') }}" class="btn btn-outline btn-success btn-sm">@lang('app.add') @lang('app.team') <i class="fa fa-plus" aria-hidden="true"></i></a>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script>
        $(function() {


            $('body').on('click', '.sa-params', function(){
                var id = $(this).data('group-id');
                swal({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.departmentText')",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "@lang('messages.confirmDelete')",
                    cancelButtonText: "@lang('messages.confirmNoArchive')",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm){
                    if (isConfirm) {

                        var url = "{{ route('admin.department.destroy',':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'DELETE',
                            url: url,
                            data: {'_token': token},
                            success: function (response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    window.location.reload();
                                }
                            }
                        });
                    }
                });
            });

            $('#department-setting').on('click', function (event) {
                var url = '{{ route('admin.department.quick-create')}}';
                $('#modelHeading').html("@lang('messages.manageDepartment')");
                $.ajaxModal('#projectTimerModal', url);
            });


        });

    </script>
@endpush
