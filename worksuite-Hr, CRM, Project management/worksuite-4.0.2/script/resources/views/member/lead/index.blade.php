@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
            @if($user->can('add_lead'))
                <a href="{{ route('member.leads.create') }}" class="btn btn-outline btn-success btn-sm">@lang('modules.lead.addNewLead') <i class="fa fa-plus" aria-hidden="true"></i></a>
            @endif
           
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
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
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
@endpush


@if($user->can('view_lead'))
    @section('filter-section')                        
    <div class="row" id="ticket-filters">
    
        <form action="" id="filter-form">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">@lang('modules.lead.client')</label>
                    <select class="form-control selectpicker" name="client" id="client" data-style="form-control">
                        <option value="all">@lang('modules.lead.all')</option>
                        <option value="lead">@lang('modules.lead.lead')</option>
                        <option value="client">@lang('modules.lead.client')</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">@lang('modules.tickets.chooseAgents')</label>
                    <select class="selectpicker form-control" data-placeholder="@lang('modules.tickets.chooseAgents')" id="agent_id" name="agent_id">
                        <option value="all">@lang('modules.lead.all')</option>
                        @foreach($leadAgents as $emp)
                            <option value="{{ $emp->id }}">{{ ucwords($emp->user->name) }} @if($emp->user->id == $user->id)
                                    (YOU) @endif</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">@lang('modules.lead.followUp')</label>
                    <select class="form-control selectpicker" name="followUp" id="followUp" data-style="form-control">
                        <option value="all">@lang('modules.lead.all')</option>
                        <option value="pending">@lang('modules.lead.pending')</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <button type="button" id="apply-filters" class="btn btn-success col-md-6"><i class="fa fa-check"></i> @lang('app.apply')</button>
                    <button type="button" id="reset-filters" class="btn btn-inverse col-md-5 col-md-offset-1"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
                </div>
            </div>
        </form>
    </div>
    @endsection
@endif

@section('content')

    <div class="row dashboard-stats">
        <div class="col-md-12 m-b-30">
            <div class="white-box">
                <div class="col-md-4 text-center">
                    <h4><span class="text-dark">{{ $totalLeads }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.dashboard.totalLeads')</span></h4>
                </div>
                <div class="col-md-4 text-center b-l">
                    <h4><span class="text-info">{{ $totalClientConverted }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.dashboard.totalConvertedClient')</span></h4>
                </div>
                <div class="col-md-4 text-center b-l">
                    <h4><span class="text-warning">{{ $pendingLeadFollowUps }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.dashboard.totalPendingFollowUps')</span></h4>
                </div>
            </div>
        </div>

    </div>
    
    <div class="row">

        <div class="col-md-12">
            <div class="white-box">

                <div class="table-responsive">
                <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="users-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('app.clientName')</th>
                        <th>@lang('modules.lead.companyName')</th>
                        <th>@lang('app.createdOn')</th>
                        <th>@lang('modules.lead.leadAgent')</th>
                        <th>@lang('modules.lead.nextFollowUp')</th>
                        <th>@lang('app.status')</th>
                        <th>@lang('app.action')</th>
                    </tr>
                    </thead>
                </table>
                    </div>
            </div>
        </div>
    </div>
    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="followUpModal" role="dialog" aria-labelledby="myModalLabel"
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
        var table;
        $(function() {
            $(".selectpicker").selectpicker();
            tableLoad();
            $('#reset-filters').click(function () {
                $('#filter-form')[0].reset();
                $('#filter-form').find('select').selectpicker('render');
                tableLoad();
            })
            var table;
        $('#apply-filters').click(function () {
            tableLoad();
        });
      function tableLoad() {
          var client = "";
          var followUp = "";
          var agent = "";

          if($('#client').length) {
            client = $('#client').val();
          }
          if($('#followUp').length) {
            followUp = $('#followUp').val();
          }
          if($('#agent_id').length) {
            agent = $('#agent_id').val();
          }

          table = $('#users-table').dataTable({
              responsive: true,
              processing: true,
              serverSide: true,
              destroy: true,
              stateSave: true,
              ajax: '{!! route('member.leads.data') !!}?client='+client+'&followUp='+followUp+'&agent='+agent,
              language: {
                  "url": "<?php echo __("app.datatable") ?>"
              },
              "fnDrawCallback": function( oSettings ) {
                  $("body").tooltip({
                      selector: '[data-toggle="tooltip"]'
                  });
                  $(".statusChange").selectpicker();
              },
              columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'client_name', name: 'client_name' },
                { data: 'company_name', name: 'company_name' },
                { data: 'created_at', name: 'created_at' },
                { data: 'agent_name', name: 'agent_name' },
                { data: 'next_follow_up_date', name: 'next_follow_up_date' },
                { data: 'status', name: 'status'},
                { data: 'action', name: 'action'}
              ]
          });
      }


            $('body').on('click', '.sa-params', function(){
                var id = $(this).data('user-id');
                swal({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.deleteleadText')",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "@lang('messages.confirmDelete')",
                    cancelButtonText: "@lang('messages.confirmNoArchive')",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm){
                    if (isConfirm) {

                        var url = "{{ route('member.leads.destroy',':id') }}";
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



        });

       function changeStatus(leadID, statusID){
           var url = "{{ route('member.leads.change-status') }}";
           var token = "{{ csrf_token() }}";

           $.easyAjax({
               type: 'POST',
               url: url,
               data: {'_token': token,'leadID': leadID,'statusID': statusID},
               success: function (response) {
                   if (response.status == "success") {
                       $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
//                        table._fnDraw();
                   }
               }
           });
        }

        $('.edit-column').click(function () {
            var id = $(this).data('column-id');
            var url = '{{ route("admin.taskboard.edit", ':id') }}';
            url = url.replace(':id', id);

            $.easyAjax({
                url: url,
                type: "GET",
                success: function (response) {
                    $('#edit-column-form').html(response.view);
                    $(".colorpicker").asColorPicker();
                    $('#edit-column-form').show();
                }
            })
        })

        function followUp (leadID) {

            var url = '{{ route('member.leads.follow-up', ':id')}}';
            url = url.replace(':id', leadID);

            $('#modelHeading').html('Add Follow Up');
            $.ajaxModal('#followUpModal', url);
        }

        $('.toggle-filter').click(function () {
            $('#ticket-filters').toggle('slide');
        })
    </script>
@endpush