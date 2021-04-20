@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
@endpush

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">{{ __($pageTitle) }}</div>

                <div class="vtabs customvtab m-t-10">
                    @include('sections.gdpr_settings_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3 class="box-title m-b-0">@lang('app.consent')</h3>
                                    <div class="row b-t m-t-20 p-10">
                                        <div class="col-xs-12">
                                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}
                                            <label for="">@lang('modules.gdpr.enableConsentForCustomers')</label>
                                            <div class="form-group">
                                                <label class="radio-inline">
                                                    <input type="radio"
                                                           class="checkbox"
                                                           @if($gdprSetting->consent_customer) checked @endif
                                                           value="1" name="consent_customer">@lang('app.yes')
                                                </label>
                                                <label class="radio-inline m-l-10">
                                                    <input type="radio"
                                                           @if($gdprSetting->consent_customer==0) checked @endif
                                                           value="0" name="consent_customer">@lang('app.no')
                                                </label>


                                            </div>
                                            <hr>
                                            <label for="">@lang('modules.gdpr.enableConsentForLeads')</label>
                                            <div class="form-group">
                                                <label class="radio-inline">
                                                    <input type="radio"
                                                           class="checkbox"
                                                           @if($gdprSetting->consent_leads) checked @endif
                                                           value="1" name="consent_leads">@lang('app.yes')
                                                </label>
                                                <label class="radio-inline m-l-10">
                                                    <input type="radio"
                                                           @if($gdprSetting->consent_leads==0) checked @endif
                                                           value="0" name="consent_leads">@lang('app.no')
                                                </label>


                                            </div>

                                            <hr>
                                            <label for=""> @lang('modules.gdpr.publicPageConsentInformationBlock')</label>
                                            <div class="form-group">
                                                <textarea name="consent_block" id="" cols="30" rows="10"
                                                          class="summernote">
                                                    {{$gdprSetting->consent_block}}
                                                </textarea>

                                            </div>

                                            <button type="button" onclick="submitForm();" class="btn btn-primary">
                                                Submit
                                            </button>
                                            {!! Form::close() !!}

                                            <hr>
                                            <h3 class="box-title m-b-0">@lang('modules.gdpr.purposeOfConsent')</h3>
                                            <p>
                                                <button type="button" id="addConsent" class="btn btn-success"><i
                                                            class="icon-plus"></i>  @lang('modules.gdpr.purposeOfConsent')
                                                </button>
                                            </p>

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="consent-table">
                                                    <thead>
                                                    <tr>
                                                        <th>@lang('app.id')</th>
                                                        <th>@lang('modules.notices.notice')</th>
                                                        <th>@lang('app.description')</th>
                                                        <th>@lang('app.date')</th>
                                                        <th>@lang('app.action')</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- /.row -->

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{--Ajax Modal--}}
        <div class="modal fade bs-modal-md in" id="consentModal" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-md" id="modal-data-application">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading">@lang('modules.gdpr.newConsentPurpose')</span>
                    </div>
                    <div class="modal-body">
                        Loading...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">@lang('app.close')</button>
                        <button type="button" class="btn blue">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        {{--Ajax Modal Ends--}}

    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    <script>
        $('.summernote').summernote({
            height: 200,                 // set editor height
            minHeight: null,             // set minimum height of editor
            maxHeight: null,             // set maximum height of editor
            focus: false,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['fontsize', ['fontsize']],
                ['para', ['ul', 'ol', 'paragraph']],
                ["view", ["fullscreen"]]
            ]
        });

        function submitForm() {

            $.easyAjax({
                url: '{{route('admin.gdpr.store')}}',
                container: '#editSettings',
                type: "POST",
                data: $('#editSettings').serialize(),
            })
        }

        $('#addConsent').on('click', function () {
            var url = '{{ route('admin.gdpr.add-consent')}}';
            $.ajaxModal('#consentModal', url);
        });

        $('body').on('click','.sa-params-edit', function () {
            var id = $(this).data('user-id');
            var url = "{{ route('admin.gdpr.edit-consent',':id') }}";
            url = url.replace(':id', id);
            $.ajaxModal('#consentModal', url);
        });

        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('user-id');
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.confirmation.deleteNotice')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.deleteConfirmation')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.gdpr.purpose-delete',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
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

        table = $('#consent-table').dataTable({
            responsive: true,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.gdpr.purpose-data') !!}',
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
                { data: 'action', name: 'action' }
            ]
        });
    </script>
@endpush

