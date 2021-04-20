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
            @if(!$roles->isEmpty())
                <a href="javascript:;" id="addRole"
                    class="btn btn-success btn-sm btn-outline  waves-effect waves-light"><i
                         class="fa fa-gear"></i> @lang("modules.roles.addRole")</a>
            @endif
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    
    <style>
        .mytooltip {
            z-index: 999 !important;
        }
    </style>
@endpush

@section('content')

@include('sections.admin_setting_menu')
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">@lang($pageTitle)</h3>
                <div class="row">

                    @forelse($roles as $role)
                        <div class="col-md-12 b-all m-t-10">
                            <div class="row custom-role-row">
                                <div class="col-md-4 text-center p-10 bg-inverse custom-role-column">
                                    <h5 class="text-white"><strong id="role_display_name">{{ ucwords($role->display_name) }}</strong></h5>
                                </div>
                                <div class="col-md-4 text-center bg-inverse role-members custom-role-column">
                                    <button class="btn btn-xs btn-danger btn-rounded show-members"
                                            data-role-id="{{ $role->id }}"><i
                                                class="fa fa-users"></i> {{ count($role->roleuser)  }} Member(s)
                                    </button>
                                </div>
                                <div class="col-md-4 p-10 bg-inverse text-center custom-role-column" style="padding-bottom: 14px !important;">
                                    <button class="btn btn-default btn-sm btn-rounded toggle-permission"
                                            data-role-id="{{ $role->id }}"><i class="fa fa-key"></i> Permissions
                                    </button>
                                </div>


                                <div class="col-md-12 b-t permission-section" style="display: none;"
                                     id="role-permission-{{ $role->id }}">
                                    <table class="table ">
                                        <thead>
                                        <tr class="bg-white">
                                            <th>
                                                <div class="form-group">
                                                    <div class="checkbox checkbox-info  col-md-10">
                                                        <input id="select_all_permission_{{ $role->id }}"
                                                               @if(count($role->permissions) == $totalPermissions) checked
                                                               @endif
                                                               class="select_all_permission" value="{{ $role->id }}"
                                                               type="checkbox">
                                                        <label for="select_all_permission_{{ $role->id }}">@lang('modules.permission.selectAll')</label>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>@lang('app.add')</th>
                                            <th>@lang('app.view')</th>
                                            <th>@lang('app.update')</th>
                                            <th>@lang('app.delete')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($modulesData as $moduleData)
                                            <tr>
                                                <td>@lang('modules.module.'.$moduleData->module_name)

                                                    @if($moduleData->description != '')
                                                        <a class="mytooltip" href="javascript:void(0)"> <i
                                                                    class="fa fa-info-circle"></i><span
                                                                    class="tooltip-content5"><span
                                                                        class="tooltip-text3"><span
                                                                            class="tooltip-inner2">{{ $moduleData->description  }}</span></span></span></a>
                                                    @endif
                                                </td>

                                                @foreach($moduleData->permissions as $permission)
                                                    <td>
                                                        <div class="switchery-demo">

                                                            <input type="checkbox"

                                                                   @if($role->hasPermission([$permission->name]))
                                                                   checked
                                                                   @endif
                                                                   class="js-switch assign-role-permission permission_{{ $role->id }}"
                                                                   data-size="small" data-color="#00c292"
                                                                   data-permission-id="{{ $permission->id }}"
                                                                   data-role-id="{{ $role->id }}"/>
                                                        </div>
                                                    </td>
                                                @endforeach

                                                @if(count($moduleData->permissions) < 4)
                                                    @for($i=1; $i<=(4-count($moduleData->permissions)); $i++)
                                                        <td>&nbsp;</td>
                                                    @endfor
                                                @endif

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    @empty

                        <div class="text-center">
                            <div class="empty-space" style="height: 200px;">
                                <div class="empty-space-inner">
                                    <div class="icon" style="font-size:30px"><i
                                                class="ti-lock"></i>
                                    </div>
                                    <div class="title m-b-15">@lang('messages.defaultRolesCantDelete')
                                    </div>
                                    <div class="subtitle">
                                        <a href="javascript:;" id="addRole"
                                           class="btn btn-success btn-sm btn-outline  waves-effect waves-light"><i
                                                    class="fa fa-gear"></i> @lang("modules.roles.addRole")</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforelse

                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
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
        <!-- /.modal-dialog -->.
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
    <script>
        $(function () {
            $('.assign-role-permission').on('change', assignRollPermission);
        });

        $('.toggle-permission').click(function () {
            var roleId = $(this).data('role-id');
            $('#role-permission-' + roleId).toggle();
        })


        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());

        });

        // Initialize multiple switches
        var animating = false;
        var masteranimate = false;

        //    if (Array.prototype.forEach) {
        //        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        //        elems.forEach(function() {
        //            var switcherys = new Switchery($(this)[0], $(this).data());
        //        });
        //    }
        //    else {
        //        var elems = document.querySelectorAll('.js-switch');
        //        for (var i = 0; i < elems.length; i++) {
        //            var switcherys = new Switchery(elems[i]);
        //        }
        //    }

        var assignRollPermission = function () {

            var roleId = $(this).data('role-id');
            var permissionId = $(this).data('permission-id');

            if ($(this).is(':checked'))
                var assignPermission = 'yes';
            else
                var assignPermission = 'no';

            var url = '{{route('admin.role-permission.store')}}';

            $.easyAjax({
                url: url,
                type: "POST",
                data: {
                    'roleId': roleId,
                    'permissionId': permissionId,
                    'assignPermission': assignPermission,
                    '_token': '{{ csrf_token() }}'
                }
            })
        };

        $('.assign-role-permission').change(assignRollPermission());

        $('.select_all_permission').change(function () {
            if ($(this).is(':checked')) {
                var roleId = $(this).val();
                var url = '{{ route('admin.role-permission.assignAllPermission') }}';

                $.easyAjax({
                    url: url,
                    type: "POST",
                    data: {'roleId': roleId, '_token': '{{ csrf_token() }}'},
                    success: function () {
                        masteranimate = true;
                        if (!animating) {
                            var masterStatus = true;
                            $('.assign-role-permission').off('change');
                            $('input.permission_' + roleId).each(function (index) {
                                var switchStatus = $('input.permission_' + roleId)[index].checked;
                                if (switchStatus != masterStatus) {

                                    $(this).trigger('click');


                                }
                                // $('.assign-role-permission').on('change');
                            });
                            $('.assign-role-permission').on('change', assignRollPermission);
                        }
                        masteranimate = false;
                    }
                })
            }
            else {
                var roleId = $(this).val();
                var url = '{{ route('admin.role-permission.removeAllPermission') }}';

                $.easyAjax({
                    url: url,
                    type: "POST",
                    data: {'roleId': roleId, '_token': '{{ csrf_token() }}'},
                    success: function () {
                        masteranimate = true;
                        if (!animating) {
                            var masterStatus = false;
                            $('.assign-role-permission').off('change');
                            $('input.permission_' + roleId).each(function (index) {
                                var switchStatus = $('input.permission_' + roleId)[index].checked;
                                if (switchStatus != masterStatus) {
                                    $(this).trigger('click');
                                }
                            });
                            $('.assign-role-permission').on('change', assignRollPermission);
                        }
                        masteranimate = false;
                    }
                })
            }
        })

        $('.show-members').click(function () {
            var id = $(this).data('role-id');
            var url = '{{ route('admin.role-permission.showMembers', ':id')}}';
            url = url.replace(':id', id);

            $('#modelHeading').html('Role Members');
            $.ajaxModal('#projectCategoryModal', url);
        })

        $('#addRole').click(function () {
            var url = '{{ route('admin.role-permission.create')}}';

            $('#modelHeading').html('Role Members');
            $.ajaxModal('#projectCategoryModal', url);
        })

    </script>
@endpush

