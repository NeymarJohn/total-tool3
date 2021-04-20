@extends('layouts.member-app')
@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">

@endpush
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
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.contracts.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('app.add') @lang('app.menu.contract')</div>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        @if($clients->isEmpty())
                        <div class="text-center">

                            <div class="empty-space" style="height: 200px;">
                                <div class="empty-space-inner">
                                    <div class="icon" style="font-size:30px"><i
                                                class="fa fa-user-secret"></i>
                                    </div>
                                    <div class="title m-b-15">@lang('messages.noClientFound')</div>
                                    <div class="subtitle">
                                        <a href="{{ route('member.clients.create') }}"
                                           class="btn btn-outline btn-success btn-sm">@lang('modules.client.addNewClient') <i class="fa fa-plus"
                                                                                                                              aria-hidden="true"></i></a>

                                    </div>
                                </div>
                            </div>

                        </div>
                        @else
                        {!! Form::open(['id'=>'createContract','class'=>'ajax-form','method'=>'POST']) !!}
                        <div class="row">
                            <div class="col-md-1 ">
                                <div class="form-group" style="margin-top: 23px">
                                    <select name="login" id="login" class="form-control">
                                            <option value="enable">@lang('app.mr')</option>
                                            <option value="disable">@lang('app.mrs')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="company_name" class="required">@lang('app.client')</label>
                                    <div>
                                        <select class="select2 form-control" data-placeholder="@lang('app.client')" name="client" id="clientID">
                                            @foreach($clients as $client)
                                                <option
                                                        value="{{ $client->id }}">{{ ucwords($client->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required" for="subject">@lang('app.subject')</label>
                                    <input type="text" class="form-control" id="subject" name="subject">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="required" for="subject">@lang('app.amount') ({{ $global->currency->currency_symbol }})</label>
                                    <input type="number" min="0" class="form-control" id="amount" name="amount">
                                </div>

                            </div>
                            <div class="col-md-3">
                                <div class="form-group" style="padding-top: 19px; padding-left: 53px;">
                                    <div class="checkbox checkbox-info">
                                        <input name="no_amount" id="check_amount"
                                               type="checkbox" onclick="setAmount()">
                                        <label for="check_amount">@lang('modules.contracts.noValue')</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required" class="control-label">@lang('modules.contracts.contractType')
                                        <a href="javascript:;"
                                        id="createContractType"
                                        class="btn btn-xs btn-outline btn-success">
                                            <i class="fa fa-plus"></i> @lang('modules.contracts.addContractType')
                                        </a>
                                    </label>
                                    <div>
                                        <select class="select2 form-control" data-placeholder="@lang('app.client')" id="contractType" name="contract_type">
                                            @foreach($contractType as $type)
                                                <option
                                                        value="{{ $type->id }}">{{ ucwords($type->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">@lang('modules.timeLogs.startDate')</label>
                                    <input id="start_date" name="start_date" type="text"
                                        class="form-control"
                                        value="{{ \Carbon\Carbon::today()->format($global->date_format) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="required">@lang('modules.timeLogs.endDate')</label>
                                    <input id="end_date" name="end_date" type="text"
                                        class="form-control"
                                        value="{{ \Carbon\Carbon::today()->format($global->date_format) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" style="padding-top: 19px; padding-left: 53px;">
                                    <div class="checkbox checkbox-info" onclick="setEndDate()">
                                        <input name="no_enddate" id="no_enddate" type="checkbox">
                                        <label for="no_enddate">@lang('modules.contracts.noEndDate')</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('modules.contracts.contractName')</label>
                                    <input name="contract_name" type="text"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('modules.contracts.alternateAddress')</label>
                                    <textarea class="form-control" name="alternate_address" 
                                        class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                               
                            <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label>@lang('modules.stripeCustomerAddress.city')</label>
                                        <input type="text" name="city" id="city"  value=""   class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label>@lang('modules.stripeCustomerAddress.state')</label>
                                        <input type="text" name="state" id="state"  value="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label>@lang('modules.stripeCustomerAddress.country')</label>
                                        <input type="text" name="country" id="country"  value=""   class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label>@lang('modules.stripeCustomerAddress.postalCode')</label>
                                        <input type="text" name="postal_code" id="postalcode"  value="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label>@lang('modules.client.cell')</label>
                                        <input type="text" name="cell" id="cell"  value="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label>@lang('modules.client.officePhoneNumber')</label>
                                        <input type="text" name="office" id="office"  value="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('modules.contracts.summery')</label>
                                        <textarea class="form-control summernote" id="contract_detail" name="contract_detail"> </textarea>
                                    </div>
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('modules.contracts.notes')</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>@lang('modules.contracts.companyLogo')</label>
                                <div class="form-group">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                            <img src="{{ $global->logo_url }}"
                                                 alt=""/>
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="max-width: 200px; max-height: 150px;"></div>
                                        <div>
                                         <span class="btn btn-info btn-file">
                                            <span class="fileinput-new"> @lang('app.selectImage') </span>
                                            <span class="fileinput-exists"> @lang('app.change') </span>
                                            <input type="file" id="company_logo" name="company_logo"> </span>
                                            <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                               data-dismiss="fileinput"> @lang('app.remove') </a>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                            @lang('app.save')
                        </button>
                        <button type="reset" class="btn btn-inverse waves-effect waves-light">@lang('app.reset')</button>
                         </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="taskCategoryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

    <script>
        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
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
        $("#start_date").datepicker({
            format: '{{ $global->date_picker_format }}',
            todayHighlight: true,
            autoclose: true,
        }).on('changeDate', function (selected) {
            $('#end_date').datepicker({
                format: '{{ $global->date_picker_format }}',
                autoclose: true,
                todayHighlight: true
            });
            var minDate = new Date(selected.date.valueOf());
            $('#end_date').datepicker("update", minDate);
            $('#end_date').datepicker('setStartDate', minDate);
        });
        jQuery('#start_date, #end_date').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: '{{ $global->date_picker_format }}',
        });
        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('member.contracts.store')}}',
                container: '#createContract',
                type: "POST",
                file: (document.getElementById("company_logo").files.length == 0) ? false : true,
                redirect: true,
                data: $('#createContract').serialize()
            })
        });
        $('#createContractType').click(function(){
            var url = '{{ route('member.contract-type.create-contract-type')}}';
            $('#modelHeading').html("@lang('modules.contracts.manageContractType')");
            $.ajaxModal('#taskCategoryModal', url);
        })
        function setAmount() {
            let no_amount = document.getElementById("check_amount").checked;
            if(no_amount == true){
                document.getElementById("amount").value = "0";
            }else{
                document.getElementById("amount").value = "";
            }
        
        }
        function setEndDate() {
            let no_amount = document.getElementById("no_enddate").checked;
            if(no_amount == true){
                document.getElementById("end_date").value = "";
            }else{
                document.getElementById("end_date").value = "{{ \Carbon\Carbon::today()->format($global->date_format) }}";
            }
        
        }

    </script>
@endpush

