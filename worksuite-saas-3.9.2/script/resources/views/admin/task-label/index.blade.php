@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('admin.task-label.create') }}" class="btn btn-outline btn-success btn-sm">@lang('modules.taskLabel.createLabel') <i class="fa fa-plus" aria-hidden="true"></i></a>
            <a href="{{ route('admin.all-tasks.index') }}" class="btn btn-outline btn-primary btn-sm"> @lang('app.task') </a>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<style>
    table tbody tr td .badge{
        text-transform: unset !important;
    }
</style>
@endpush

@section('content')
    <div class="row dashboard-stats">
        <div class="col-md-12 m-b-30">
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="white-box">
                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

{!! $dataTable->scripts() !!}
<script>
    var table;
    $(function() {

        loadTable();

        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('contract-id');
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.confirmation.deleteTasklabel')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.deleteConfirmation')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.task-label.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                loadTable();
                            }
                        }
                    });
                }
            });
        });

    });

    function loadTable(){
        window.LaravelDataTables["taskLabelList-table"].draw();
    }

//    $('#contracts-table').on('preXhr.dt', function (e, settings, data) {
//       //
//    });

    {{--function exportData(){--}}

        {{--var startDate = $('#start-date').val();--}}

        {{--if (startDate == '') {--}}
            {{--startDate = null;--}}
        {{--}--}}

        {{--var endDate = $('#end-date').val();--}}

        {{--if (endDate == '') {--}}
            {{--endDate = null;--}}
        {{--}--}}

        {{--var status = $('#status').val();--}}

        {{--var url = '{{ route('admin.estimates.export', [':startDate', ':endDate', ':status']) }}';--}}
        {{--url = url.replace(':startDate', startDate);--}}
        {{--url = url.replace(':endDate', endDate);--}}
        {{--url = url.replace(':status', status);--}}

        {{--window.location.href = url;--}}
    {{--}--}}

</script>
@endpush