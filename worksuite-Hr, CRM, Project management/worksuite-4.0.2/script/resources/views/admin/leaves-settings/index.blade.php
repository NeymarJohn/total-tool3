@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorselector/bootstrap-colorselector.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.update') @lang('app.menu.leaveSettings')</div>

                <div class="vtabs customvtab m-t-10">

                    @include('sections.admin_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <div class="radio-list">
                                            <label class="radio-inline p-0">
                                                <div class="radio radio-info">
                                                    <input type="radio" name="leaves_start_from" @if($global->leaves_start_from == 'joining_date') checked @endif id="crypto_currency_joining" value="joining_date">
                                                    <label for="crypto_currency_joining">@lang('modules.leaves.countLeavesFromDateOfJoining')</label>
                                                </div>
                                            </label>
                                            <label class="radio-inline">
                                                <div class="radio radio-info">
                                                    <input type="radio" name="leaves_start_from" @if($global->leaves_start_from == 'year_start') checked @endif id="crypto_currency_year" value="year_start">
                                                    <label for="crypto_currency_year">@lang('modules.leaves.countLeavesFromStartOfYear')</label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table" id="leave-type-table">
                                            <thead>
                                            <tr>
                                                <th>@lang('modules.leaves.leaveType')</th>
                                                <th>@lang('modules.leaves.noOfLeaves')</th>
                                                <th>@lang('modules.leaves.leavePaidStatus')</th>
                                                <th>@lang('app.action')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($leaveTypes as $key=>$leaveType)
                                                <tr id="type-{{ $leaveType->id }}">
                                                    <td>
                                                        <label class="label label-{{ $leaveType->color }}">{{ ucwords($leaveType->type_name) }}</label>
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" value="{{ $leaveType->no_of_leaves }}"
                                                                class="form-control leave-count-{{ $leaveType->id }}">
                                                    </td>
                                                    <td>
                                                        
                                                    <select  class="form-control"  name="paid" id="paid" >
                                                    <option @if($leaveType->paid == 1) selected @endif
                                                            value="1">@lang('modules.credit-notes.paid')</option>
                                                    <option @if($leaveType->paid == 0) selected @endif
                                                                value="0">@lang('modules.credit-notes.unpaid')</option>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <button type="button" data-type-id="{{ $leaveType->id }}"
                                                            class="btn btn-sm btn-success btn-outline update-category">
                                                        <i class="fa fa-check"></i></button>
                                                        
                                                        <button type="button" data-cat-id="{{ $leaveType->id }}"
                                                                class="btn btn-sm btn-danger btn-outline delete-category">
                                                            <i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">@lang('messages.noLeaveTypeAdded')</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <hr>
                                    {!! Form::open(['id'=>'createLeaveType','class'=>'ajax-form','method'=>'POST']) !!}
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-xs-12 ">
                                                <div class="form-group">
                                                    <label class="required">@lang('app.add') @lang('modules.leaves.leaveType')</label>
                                                    <input type="text" name="type_name" id="type_name"
                                                           class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 ">
                                                <label>@lang('modules.leaves.leavePaidStatus')</label>
                                                <div class="form-group">
                                                    <select  class="form-control"  name="paid" id="paid" >
                                                        <option value="1">@lang('app.paid')</option>
                                                        <option value="0">@lang('app.unpaid')</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 ">
                                                <div class="form-group">
                                                    <label class="required">@lang('modules.customFields.label') @lang('modules.sticky.colors')</label>
                                                    <select id="colorselector" name="color">
                                                        <option value="info" data-color="#5475ed" selected>Blue
                                                        </option>
                                                        <option value="warning" data-color="#f1c411">Yellow</option>
                                                        <option value="purple" data-color="#ab8ce4">Purple</option>
                                                        <option value="danger" data-color="#ed4040">Red</option>
                                                        <option value="success" data-color="#00c292">Green</option>
                                                        <option value="inverse" data-color="#4c5667">Grey</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 ">
                                                <div class="form-group">
                                                    <label>@lang('modules.leaves.noOfLeaves')</label>
                                                    <input type="number" min="0" name="leave_number" value="0" id="leave_number" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="col-xs-12">
                                                <div class="form-group">

                                                    <div class="checkbox checkbox-info">
                                                        <input id="all_employees" name="all_employees"
                                                               type="checkbox">
                                                        <label for="all_employees">@lang('app.toAllEmployee')</label>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="button" id="save-type" class="btn btn-success"><i  class="fa fa-check"></i> @lang('app.save')</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bootstrap-colorselector/bootstrap-colorselector.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>

    <script>

        @if($leaveTypes->count() > 0)

            $('#leave-type-table').dataTable({
                responsive: true,
                "columnDefs": [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: 1 }
                ],
                searching: false,
                paging: false,
                info: false
            });
        @endif


        $('#colorselector').colorselector();

        $('#createLeaveType').submit(function () {
            $.easyAjax({
                url: '{{route('admin.leaveType.store')}}',
                container: '#createLeaveType',
                type: "POST",
                data: $('#createLeaveType').serialize(),
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload();
                    }
                }
            })
            return false;
        })

        $('.update-category').click(function () {
            var id = $(this).data('type-id');
            var leaves = $('.leave-count-'+id).val();
            var url = "{{ route('admin.leaveType.update',':id') }}";
            url = url.replace(':id', id);

            var token = "{{ csrf_token() }}";

            $.easyAjax({
                type: 'POST',
                url: url,
                data: {'_token': token, '_method': 'PUT', 'leaves': leaves}
            });
        });

        $('body').on('click', '.delete-category', function () {
            var id = $(this).data('cat-id');
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.deleteLeaveType')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.confirmDelete')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    var url = "{{ route('admin.leaveType.destroy',':id') }}";
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
                                $('#type-' + id).fadeOut();
                            }
                        }
                    });
                }
            });
        });

        $('#save-type').click(function () {
            $.easyAjax({
                url: '{{route('admin.leaveType.store')}}',
                container: '#createLeaveType',
                type: "POST",
                data: $('#createLeaveType').serialize(),
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.reload();
                    }
                }
            })
        });

        $('input[name=leaves_start_from]').click(function () {
            var leaveCountFrom = $('input[name=leaves_start_from]:checked').val();
            $.easyAjax({
                url: '{{route('admin.leaves-settings.store')}}',
                type: "POST",
                data: {'_token': '{{ csrf_token() }}', 'leaveCountFrom': leaveCountFrom}
            })
        });
    </script>


@endpush