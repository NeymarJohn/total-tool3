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
    <link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang($pageTitle)</div>

                <div class="vtabs customvtab m-t-10">
                    @include('sections.gdpr_settings_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h3 class="box-title m-b-0">Right to erasure</h3>
                                    <div class="row b-t m-t-20 p-10">
                                        <div class="col-md-12">
                                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}
                                            <label for="">Enable customers to request to remove data</label>
                                            <div class="form-group">
                                                <label class="radio-inline">
                                                    <input type="radio"
                                                           class="checkbox"
                                                           @if($gdprSetting->data_removal) checked @endif
                                                           value="1" name="data_removal">Yes
                                                </label>
                                                <label class="radio-inline m-l-10">
                                                    <input type="radio"
                                                           @if($gdprSetting->data_removal==0) checked @endif
                                                           value="0" name="data_removal">No
                                                </label>


                                            </div>
                                            <hr>
                                            <h3 class="box-title m-t-20">Leads to erasure</h3>
                                            <label for="" class="b-t  p-10">Enable lead to request data removal (via public form)</label>
                                            <div class="form-group">
                                                <label class="radio-inline">
                                                    <input type="radio"
                                                           class="checkbox"
                                                           @if($gdprSetting->lead_removal_public_form==1) checked @endif
                                                           value="1" name="lead_removal_public_form">Yes
                                                </label>
                                                <label class="radio-inline m-l-10">
                                                    <input type="radio"
                                                           @if($gdprSetting->lead_removal_public_form==0) checked @endif
                                                           value="0" name="lead_removal_public_form">No
                                                </label>


                                            </div>
                                            <button type="button" onclick="submitForm();" class="btn btn-primary">Submit</button>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- /.row -->

                            <div class="clearfix"></div>
                            <hr>
                            <h3 class="box-title m-t-10">Removal Requests</h3>
                            <div class="row">
                                <div class="col-md-12">
                                <ul class="nav customtab nav-tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#home1" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-user"></i></span><span class="hidden-xs"> @lang('app.customers')</span></a>
                                    </li>
                                    <li role="presentation" class=""><a href="#profile1" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-receipt"></i></span> <span class="hidden-xs"> @lang('app.lead')</span></a></li>
                                </ul>

                                <div class="tab-content" style="display: inline;width: 100%">
                                        <div role="tabpanel" class="tab-pane fade active in" id="home1">
                                            <div class="col-md-12">
                                                <div class="table-responsive m-t-20" >
                                                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="consent-table">
                                                        <thead>
                                                        <tr>
                                                            <th>@lang('app.id')</th>
                                                            <th>@lang('app.name')</th>
                                                            <th>@lang('app.description')</th>
                                                            <th>@lang('app.date')</th>
                                                            <th>@lang('app.status')</th>
                                                            <th>@lang('app.action')</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div role="tabpanel" class="tab-pane fade " id="profile1">
                                            <div class="table-responsive m-t-20" >
                                                <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="consent-lead-table">
                                                    <thead>
                                                    <tr>
                                                        <th>@lang('app.id')</th>
                                                        <th>@lang('modules.lead.companyName')</th>
                                                        <th>@lang('app.description')</th>
                                                        <th>@lang('app.date')</th>
                                                        <th>@lang('app.status')</th>
                                                        <th>@lang('app.action')</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>


            </div>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>

    <script>
        function submitForm(){

            $.easyAjax({
                url: '{{route('admin.gdpr.store')}}',
                container: '#editSettings',
                type: "POST",
                data: $('#editSettings').serialize(),
            })
        }
        table = $('#consent-table').dataTable({
            responsive: true,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.gdpr.removal-data') !!}',
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
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },

                { data: 'created_at', name: 'created_at' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' }
            ]
        });

        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('user-id');
            var type = $(this).data('type');
            var text = '';
            var btnType = '';
            if(type =='approved'){
                text = 'Approve'
                btnType = 'success'
            }else{
                text = 'Reject'
                btnType = 'warning'
            }
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.textDelete')",
                type: btnType,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, " + text + " it!",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.gdpr.approve-reject',[':id',':type']) }}";
                    url = url.replace(':id', id);
                    url = url.replace(':type', type);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'GET',
                        url: url,
                        data: {'_token': token},
                        success: function (response) {
                            if (response.status === "success") {
                                $.unblockUI();
                                table._fnDraw();
                            }
                        }
                    });
                }
            });
        });

        tableLead = $('#consent-lead-table').dataTable({
            responsive: true,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.gdpr.lead.removal-data') !!}',
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
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },

                { data: 'created_at', name: 'created_at' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' }
            ]
        });

        $('body').on('click', '.sa-params1', function(){
            var id = $(this).data('lead-id');
            var type = $(this).data('type');
            var text = '';
            var btnType = '';
            if(type =='approved'){
                text = 'Approve'
                btnType = 'success'
            }else{
                text = 'Reject'
                btnType = 'warning'
            }
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.deleteleadText')",
                type: btnType,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, " + text + " it!",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.gdpr.lead.approve-reject',[':id',':type']) }}";
                    url = url.replace(':id', id);
                    url = url.replace(':type', type);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'GET',
                        url: url,
                        data: {'_token': token},
                        success: function (response) {
                            if (response.status === "success") {
                                $.unblockUI();
                                tableLead._fnDraw();
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush

