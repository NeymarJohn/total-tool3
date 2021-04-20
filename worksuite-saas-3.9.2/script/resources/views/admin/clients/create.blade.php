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
                <li><a href="{{ route('admin.clients.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
<style>
 .salutation .form-control {
     padding: 2px 2px;
   }
  .select-category button{
    background-color: white !important;
    font-size: 13px;
    color: #565656;
    border: 1px solid #e4e7ea !important;
   }
   .select-category button:hover{
    color: #565656;
    opacity: 1;
   }
  
   .bootstrap-select .dropdown-toggle:focus{
    outline: none !important;
   }
</style>

@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.client.createTitle')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createClient','class'=>'ajax-form','method'=>'POST']) !!}
                        @if(isset($leadDetail->id))
                            <input type="hidden" name="lead" value="{{ $leadDetail->id }}">
                        @endif
                            <div class="form-body">
                                <h3 class="box-title ">@lang('modules.client.clientDetails')</h3>
                                <hr>
                                <div class="row">
                                <div class="col-md-1 ">
                                        <div class="form-group salutation" style="margin-top: 23px">
                                        <select name="salutation" id="salutation" class="form-control">
                                            <option value="">--</option>
                                            <option value="mr">@lang('app.mr')</option>
                                            <option value="mrs">@lang('app.mrs')</option>
                                            <option value="mrs">@lang('app.miss')</option>
                                            <option value="dr">@lang('app.dr')</option>
                                            <option value="sir">@lang('app.sir')</option>
                                            <option value="madam">@lang('app.madam')</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5 ">
                                        <div class="form-group">
                                            <label class="required">@lang('modules.client.clientName')</label>
                                            <input type="text" name="name" id="name"  value="{{ $leadDetail->client_name ?? '' }}"   class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="required">@lang('modules.client.clientEmail')</label>
                                            <input type="email" name="email" id="email" value="{{ $leadDetail->client_email ?? '' }}"  class="form-control">
                                            <span class="help-block">@lang('modules.client.emailNote')</span>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="required">@lang('modules.employees.employeePassword')</label>
                                            <input type="password" style="display: none">
                                            <input type="password" name="password" id="password" class="form-control" autocomplete="nope">
                                            <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            <span class="help-block"> @lang('modules.employees.passwordNote') </span>
                                            <div class="checkbox checkbox-info">
                                                <input id="random_password" name="random_password" value="true" type="checkbox">
                                                <label for="random_password">@lang('modules.client.generateRandomPassword')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="box-title m-t-20">@lang('modules.client.companyDetails')</h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">@lang('modules.client.companyName')</label>
                                            <input type="text" id="company_name" name="company_name" value="{{ $leadDetail->company_name ?? '' }}" class="form-control" >
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">@lang('modules.client.website')</label>
                                            <input type="text" id="website" name="website" value="{{ $leadDetail->website ?? '' }}" class="form-control" >
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!--/row-->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">@lang('app.address')</label>
                                            <textarea name="address"  id="address"  rows="5"  class="form-control">{{ $leadDetail->address ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <!--/span-->

                                </div>
                                <!--/row-->
                                <div class="row">
                                <div class="col-md-3 ">      
                                        <label>@lang('app.mobile')</label>
                                        <div class="form-group">
                                        <select class="select2 phone_country_code form-control" name="phone_code">
                                            <option value ="">--</option>
                                                @foreach ($countries as $item)
                                                    <option value="{{ $item->phonecode }}">+{{ $item->phonecode.' ('.$item->iso.')' }}</option>
                                                @endforeach
                                            </select>
                                            <input type="tel" name="mobile" id="mobile" class="mobile" autocomplete="nope" value="{{ $leadDetail->mobile ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label>@lang('modules.clients.officePhoneNumber')</label>
                                            <input type="text" name="office_phone" id="office_phone"   class="form-control">
                                        </div>
                                    </div>
                                <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label>@lang('modules.stripeCustomerAddress.city')</label>
                                            <input type="text" name="city" id="city"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label>@lang('modules.stripeCustomerAddress.state')</label>
                                            <input type="text" name="state" id="state"   class="form-control">
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">@lang('modules.clients.country')</label>
                                            <select class="select2 form-control"  id="country_id" name="country_id">
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->nicename }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label>@lang('modules.stripeCustomerAddress.postalCode')</label>
                                            <input type="text" name="postal_code" id="postalCode"    class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">@lang('modules.clients.clientCategory')
                                                        <a href="javascript:;" id="addClientCategory" class="text-info"><i
                                                                class="ti-settings text-info"></i> </a>
                                                </label>
                                                <select class="select2 form-control client-category" data-placeholder="@lang('modules.clients.clientCategory')"  id="category_id" name="category_id">
                                                <option value="">@lang('messages.pleaseSelectCategory')</option>
                                                @forelse($categories as $category)
                                                <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                                  @empty
                                                <option value="">@lang('messages.noCategoryAdded')</option>
                                                 @endforelse
                                                    
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">@lang('modules.clients.clientSubCategory')
                                                        <a href="javascript:;" id="addClientSubCategory" class="text-info">
                                                        <i class="ti-settings text-info"></i> </a>
                                                </label>
                                                <select class="selectpicker form-control select-category" data-placeholder="@lang('modules.clients.clientSubCategory')"  id="sub_category_id" name="sub_category_id">                                                 
                                                <option value="">@lang('messages.noSubCategoryAdded')</option> 
                                                @forelse($subcategories as $subCategory)
                                                <option value="{{ $subCategory->id }}">{{ ucwords($subCategory->category_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noProductCategory')</option>
                                            @endforelse  
                                                </select>
                                            </div>
                                        </div>
                                    
                                </div>
                                </div>

                                <h3 class="box-title m-t-20">@lang('modules.client.clientOtherDetails')</h3>
                                <hr>
                                <!--/row-->
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Skype</label>
                                            <input type="text" name="skype" id="skype" class="form-control">
                                        </div>
                                    </div>
                                    <!--/span-->

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Linkedin</label>
                                            <input type="text" name="linkedin" id="linkedin" class="form-control">
                                        </div>
                                    </div>
                                    <!--/span-->

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Twitter</label>
                                            <input type="text" name="twitter" id="twitter" class="form-control">
                                        </div>
                                    </div>
                                    <!--/span-->

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Facebook</label>
                                            <input type="text" name="facebook" id="facebook" class="form-control">
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!--/row-->
                                <div class="row">
                                   
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="gst_number">@lang('app.gstNumber')</label>
                                            <input type="text" id="gst_number" name="gst_number" class="form-control" value="">
                                        </div>
                                    </div>
                                </div>
                                <!--/row-->

                                <div class="row">
                                    @if(isset($fields))
                                        @foreach($fields as $field)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label @if($field->required == 'yes') class="required" @endif>{{ ucfirst($field->label) }}</label>
                                                    @if( $field->type == 'text')
                                                        <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                    @elseif($field->type == 'password')
                                                        <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                    @elseif($field->type == 'number')
                                                        <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">

                                                    @elseif($field->type == 'textarea')
                                                        <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" id="{{$field->name}}" cols="3">{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>

                                                    @elseif($field->type == 'radio')
                                                        <div class="radio-list">
                                                            @foreach($field->values as $key=>$value)
                                                                <label class="radio-inline @if($key == 0) p-0 @endif">
                                                                    <div class="radio radio-info">
                                                                        <input type="radio" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" id="optionsRadios{{$key.$field->id}}" value="{{$value}}" @if(isset($clientDetail) && $clientDetail->custom_fields_data['field_'.$field->id] == $value) checked @elseif($key==0) checked @endif>>
                                                                        <label for="optionsRadios{{$key.$field->id}}">{{$value}}</label>
                                                                    </div>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @elseif($field->type == 'select')
                                                        {!! Form::select('custom_fields_data['.$field->name.'_'.$field->id.']',
                                                                $field->values,
                                                                 isset($editUser)?$editUser->custom_fields_data['field_'.$field->id]:'',['class' => 'form-control gender'])
                                                         !!}

                                                    @elseif($field->type == 'checkbox')
                                                        <div class="mt-checkbox-inline">
                                                            @foreach($field->values as $key => $value)
                                                                <label class="mt-checkbox mt-checkbox-outline">
                                                                    <input name="custom_fields_data[{{$field->name.'_'.$field->id}}][]" type="checkbox" value="{{$key}}"> {{$value}}
                                                                    <span></span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @elseif($field->type == 'date')
                                                        <input type="text" class="form-control form-control-inline date-picker" size="16" name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                                value="{{ isset($editUser->dob)?Carbon\Carbon::parse($editUser->dob)->format('Y-m-d'):Carbon\Carbon::now()->format($global->date_format)}}">
                                                    @endif
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block"></span>

                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>@lang('app.shippingAddress')</label>
                                        <div class="form-group">
                                            <textarea name="shipping_address" id="shipping_address" class="form-control" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>@lang('app.note')</label>
                                        <div class="form-group">
                                            <textarea name="note" id="note" class="form-control summernote" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 20px;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div style="margin-bottom: 10px;">
                                                <label class="control-label">@lang('modules.client.sendCredentials')</label>
                                                <a class="mytooltip" href="javascript:void(0)"> <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">@lang('modules.client.sendCredentialsMessage')</span></span></span></a>
                                            </div>
                                            <div class="radio radio-inline col-md-4">
                                                <input type="radio" name="sendMail" id="sendMail1"
                                                       value="yes">
                                                <label for="sendMail1" class="">
                                                    @lang('app.yes') </label>
                                            </div>
                                            <div class="radio radio-inline col-md-4">
                                                <input type="radio" name="sendMail"
                                                       id="sendMail2" checked value="no">
                                                <label for="sendMail2" class="">
                                                    @lang('app.no') </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="m-b-10">
                                                <label class="control-label">@lang('modules.emailSettings.emailNotifications')</label>
                                            </div>
                                            <div class="radio radio-inline">
                                                <input type="radio" checked name="email_notifications" id="email_notifications1" value="1">
                                                <label for="email_notifications1" class="">
                                                    @lang('app.enable') </label>
    
                                            </div>
                                            <div class="radio radio-inline ">
                                                <input type="radio" name="email_notifications"
                                                       id="email_notifications2" value="0">
                                                <label for="email_notifications2" class="">
                                                    @lang('app.disable') </label>
                                            </div>
                                           
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="address">@lang('modules.accountSettings.changeLanguage')</label>
                                                <select name="locale" id="locale" class="form-control select2">
                                                <option @if($global->locale == "en") selected @endif value="en">English
                                                    </option>
                                                    @foreach($languageSettings as $language)
                                                        <option value="{{ $language->language_code }}" >{{ $language->language_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>

                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="clientCategoryModal" role="dialog" aria-labelledby="myModalLabel"
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
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script>
    $('#sub_category_id').html("");
      var categories = @json($categories);
        $('#category_id').change(function (e) {
        var cat_id = $(this).val();
        getCategory(cat_id);
           
        });
        function getCategory(cat_id){
            var url = "{{route('admin.clients.getSubcategory')}}";
            var token = "{{ csrf_token() }}";
            $.easyAjax({
            url: url,
            type: "POST",
            data: {'_token': token, cat_id: cat_id},
            success: function (data) {
                var options = [];
                        var rData = [];
                        rData = data.subcategory;
                        $.each(rData, function( index, value ) {
                            var selectData = '';
                            selectData = '<option value="'+value.id+'">'+value.category_name+'</option>';
                            options.push(selectData);
                        });
                        $('#sub_category_id').html(options);
                        $('#sub_category_id').selectpicker('refresh');

            }
        })
        }
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    $(".date-picker").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.clients.store')}}',
            container: '#createClient',
            type: "POST",
            redirect: true,
            data: $('#createClient').serialize()
        })
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
    $('#addClientCategory').click(function () {
        var url = '{{ route('admin.clientCategory.create')}}';
        $('#modelHeading').html('...');
        $.ajaxModal('#clientCategoryModal', url);
    })
    $('#addClientSubCategory').click(function () {
        var url = '{{ route('admin.clientSubCategory.create')}}';
        $('#modelHeading').html('...');
        $.ajaxModal('#clientCategoryModal', url);
    })
    $('#random_password').change(function () {
        var randPassword = $(this).is(":checked");

        if(randPassword){
            $('#password').val('{{ str_random(8) }}');
            $('#password').attr('readonly', 'readonly');
        }
        else{
            $('#password').val('');
            $('#password').removeAttr('readonly');
        }
    });
</script>
@endpush

