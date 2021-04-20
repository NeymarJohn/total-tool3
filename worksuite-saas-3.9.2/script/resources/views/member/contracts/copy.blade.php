@extends('layouts.member-app')
@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">

    <style>
        .img-width {
            width: 185px;
        }
        .tabs-style-line nav a {
            box-shadow: unset !important;
        }
        .steamline .sl-left {
            margin-left: -7px !important;
        }
        .history-remove {
            display: none;
        }
        .sl-item:hover .history-remove {
            display: block;
        }
    </style>
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
        {!! Form::open(['id'=>'createContract','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="col-md-6">
            <div class="white-box">
                <h3 class="box-title m-b-0">{{ $contract->subject }}
                    <a href="{{ route('member.contracts.show', md5($contract->id)) }}" target="_blank" class="btn btn-sm btn-default pull-right">View Contract</a>
                </h3>

                <div class="sttabs tabs-style-line" id="invoice_container">
                    <nav>
                        <ul class="customtab" role="tablist" id="myTab">
                            <li class="nav-item active"><a class="nav-link" href="#summery" data-toggle="tab" role="tab"><span><i class="glyphicon glyphicon-file"></i> @lang('app.menu.contract')</span></a>
                            </li>
                        </ul>
                    </nav>
                    <div class="tab-content tabcontent-border">
                        <div class="tab-pane active" id="summery" role="tabpanel">
                            <div class="row">
                                <div class="col-xs-12">
                                    <p class="text-muted m-b-30 font-13"></p>
                                    <div class="form-group">
                                    <textarea name="contract_detail" id="contract_detail"
                                          class="summernote">{{ $contract->contract_detail }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="white-box">
                <h3 class="box-title m-b-0">@lang('app.edit') @lang('app.menu.contract')</h3>

                <p class="text-muted m-b-30 font-13"></p>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required" for="company_name">@lang('app.client')</label>
                            <div>
                                <select class="select2 form-control" data-placeholder="@lang('app.client')" name="client" id="clientID">
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" @if($client->id == $contract->client_id) selected @endif>{{ ucwords($client->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required" for="subject">@lang('app.subject')</label>
                            <input type="text" class="form-control" id="subject" name="subject"  value="{{ $contract->subject ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required" for="subject">@lang('app.amount') ({{ $global->currency->currency_symbol }})</label>
                            <input type="number" class="form-control" id="amount" name="amount" value="{{ $contract->amount ?? '' }}">
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
                                   value="{{ $contract->start_date->timezone($global->timezone)->format($global->date_format) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required">@lang('modules.timeLogs.endDate')</label>
                            <input id="end_date" name="end_date" type="text"
                                   class="form-control"
                                   value="{{  $contract->end_date==null ? '' : $contract->end_date->timezone($global->timezone)->format($global->date_format) ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                           <label>@lang('modules.contracts.contractName')</label>
                            <input name="contract_name" type="text"
                                     value="{{$contract->contract_name ??''}}"   class="form-control">
                         </div>
                     </div>
                    <div class="col-md-6">
                         <div class="form-group">
                          <label>@lang('modules.contracts.alternateAddress')</label>
                             <textarea class="form-control" name="alternate_address" 
                               class="form-control">{{ $contract->alternate_address ?? '' }}</textarea>
                        </div>
                     </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('modules.lead.mobile')</label>
                            <input type="tel" name="mobile" value="{{ $contract->mobile ?? '' }}" id="mobile" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label>@lang('modules.clients.officePhoneNumber')</label>
                            <input type="text" name="office_phone" id="office_phone"  value="{{ $contract->office_phone ?? '' }}"  class="form-control">
                        </div>
                    </div>
                     <div class="col-md-6 ">
                        <div class="form-group">
                            <label>@lang('modules.stripeCustomerAddress.city')</label>
                            <input type="text" name="city" id="city"  value="{{ $contract->city ?? '' }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label>@lang('modules.stripeCustomerAddress.state')</label>
                            <input type="text" name="state" id="state"   value="{{ $contract->state ?? '' }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label>@lang('modules.stripeCustomerAddress.country')</label>
                            <input type="text" name="country" id="country"  value="{{ $contract->country ?? '' }}"  class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 ">
                            <div class="form-group">
                                <label>@lang('modules.stripeCustomerAddress.postalCode')</label>
                                <input type="text" name="postal_code" id="postalCode"  value="{{ $contract->postal_code ?? '' }}"class="form-control">
                            </div>
                        </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="required">@lang('modules.contracts.notes')</label>
                            <textarea class="form-control summernote" id="description" name="description" rows="4">{{ $contract->description ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6" >
                        <label>@lang('modules.contracts.companyLogo')</label>
                        <div class="form-group">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                    <img src="{{$contract->image_url}}" alt=""/>

                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"
                                    style="max-width: 200px; max-height: 150px;"></div>
                                    <div>
                                        <span class="btn btn-info btn-file">
                                            <span class="fileinput-new"> @lang('app.selectImage') </span>
                                            <span class="fileinput-exists"> @lang('app.change') </span>
                                            <input type="file" name="company_logo" id="company_logo"> </span>
                                            <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                data-dismiss="fileinput"> @lang('app.remove') </a>
                                    </div>
                                </div>
                            </div>

                        </div>                            
                    </div> 
                </div>
            </div>
        </div>
        <div class="col-md-12 text-center m-t-15 m-b-15">
            <a href="{{ route('member.contracts.index') }}" class="btn btn-inverse waves-effect waves-light m-r-10">@lang('app.back')</a>
            <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light">
                @lang('app.save')
            </button>
        </div>
        {!! Form::close() !!}
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
        $(document).ready(() => {
            $('#start_date').trigger('changeDate');
            $('.slimscrolltab').slimScroll({
                height: '283px'
                , position: 'right'
                , size: "5px"
                , color: '#dcdcdc'
                , });
        });

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
            if(selected.date == undefined){
                var minDate = new Date("{{ $contract->start_date->timestamp }}");
                minDate += '000';
            }
            else{
                console.log('minDate'+selected.date.valueOf());
            }
            $('#end_date').datepicker("update", minDate);
            $('#end_date').datepicker('setStartDate', minDate);
        });

        jQuery('#start_date, #end_date').datepicker({
            format: '{{ $global->date_picker_format }}',
            autoclose: true,
            todayHighlight: true,

        });

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('member.contracts.copy-submit')}}',
                container: '#createContract',
                type: "POST",
                file: true,
                redirect: true,
                data: $('#createContract').serialize()
            })
        });

        $('#createContractType').click(function(){
            var url = '{{ route('member.contract-type.create-contract-type')}}';
            $('#modelHeading').html("@lang('modules.contracts.manageContractType')");
            $.ajaxModal('#taskCategoryModal', url);
        })
    </script>
@endpush

