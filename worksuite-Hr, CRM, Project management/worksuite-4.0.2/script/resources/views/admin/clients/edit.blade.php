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
                <li><a href="{{ route('admin.clients.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.edit')</li>
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

@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.client.updateTitle')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'updateClient','class'=>'ajax-form','method'=>'PUT']) !!}
                        <div class="form-body">
                            <h3 class="box-title">@lang('modules.client.companyDetails')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.client.companyName')</label>
                                        <input type="text" id="company_name" name="company_name" class="form-control"  value="{{ $clientDetail->company_name ?? '' }}">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.client.website')</label>
                                        <input type="text" id="website" name="website" class="form-control" value="{{ $clientDetail->website ?? '' }}" >
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.address')</label>
                                        <textarea name="address"  id="address"  rows="3" class="form-control">{{ $clientDetail->address ?? '' }}</textarea>
                                    </div>
                                </div>
                                <!--/span-->

                            </div>
                            <!--/row-->
                            <div class="row">
                                <!--/span-->
                                <div class="col-md-3 ">
                                <label>@lang('app.mobile')</label>
                                        <div class="form-group">
                                            <select class="select2 phone_country_code form-control" name="phone_code">
                                            <option value="">--</option>
                                            @foreach ($countries as $item)
                                                <option
                                                @if ($item->id == $userDetail->country_id)
                                                    selected
                                                @endif
                                                value="{{ $item->id }}">+{{ $item->phonecode.' ('.$item->iso.')' }}</option>
                                            @endforeach
                                            </select>
                                           <input type="tel" name="mobile" id="mobile" class="mobile" autocomplete="nope" value="{{ $userDetail->mobile }}"> 
                                        </div>
                                    </div>
                                           <!--/span-->
                                    <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label>@lang('modules.client.officePhoneNumber')</label>
                                            <input type="text" name="office" id="office"  value="{{ $clientDetail->office ?? '' }}" class="form-control">
                                        </div>
                                    </div>
                                <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label>@lang('modules.stripeCustomerAddress.city')</label>
                                            <input type="text" name="city" id="city"  value="{{ $clientDetail->city ?? '' }}"class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label>@lang('modules.stripeCustomerAddress.state')</label>
                                            <input type="text" name="state" id="state"  value="{{ $clientDetail->state ?? '' }}" class="form-control">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label>@lang('modules.stripeCustomerAddress.postalCode')</label>
                                            <input type="text" name="postal_code" id="postalcode"  value="{{ $clientDetail->postal_code ?? '' }}"   class="form-control">
                                        </div>
                                    </div>
                                </div>

                            <h3 class="box-title ">@lang('modules.client.clientDetails')</h3>
                            <hr>
                            <div class="row">
                            
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.client.clientName')</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ $userDetail->name }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.client.clientEmail')</label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ $userDetail->email }}">
                                        <span class="help-block">@lang('modules.client.emailNote')</span>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('modules.client.password')</label>
                                        <input type="password" style="display: none">
                                        <input type="password" name="password" id="password" class="form-control">
                                        <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span class="help-block"> @lang('modules.client.passwordUpdateNote') </span>
                                    </div>
                                </div>
                               
                            </div>
                            <div class ="row">
                            <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">@lang('modules.client.clientCategory')
                                             <a href="javascript:;" id="addClientCategory" class="text-info"><i
                                                                class="ti-settings text-info"></i> </a>
                                        </label>
                                        <select class="select2 form-control" data-placeholder="@lang('modules.client.clientCategory')"  id="category_id" name="category_id">
                                        <option value="">@lang('messages.pleaseSelectCategory')</option>
                                        @forelse($categories as $category)
                                         <option @if( $category->id == $clientDetail->category_id) selected @endif value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                          @empty
                                         <option value="">@lang('messages.noCategoryAdded')</option>
                                          @endforelse                
                                         </select>
                                     </div>
                                </div> 
                            <div class="col-md-3">
                                 <div class="form-group">
                                     <label for="">@lang('modules.client.clientSubCategory')
                                     <a href="javascript:;" id="addClientSubCategory" class="text-info"><i
                                                                class="ti-settings text-info"></i> </a>
                                    </label>
                                   
                                      <select class="select2 form-control" data-placeholder="@lang('modules.client.clientSubCategory')"  id="sub_category_id" name="sub_category_id">
                                      <option value="">@lang('messages.selectSubCategory')</option>
                                      @forelse($subcategories as $subcategory)
                                     
                                       <option @if( $subcategory->id == $clientDetail->sub_category_id) selected @endif value="{{ $subcategory->id }}">{{ ucwords($subcategory->category_name) }}</option>
                                         @empty
                                         <option value="">@lang('messages.noCategoryAdded')</option>
                                        @endforelse                            
                                     </select>
                                   </div>
                              </div>
                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Skype</label>
                                        <input type="text" name="skype" id="skype" class="form-control" value="{{ $clientDetail->skype ?? '' }}">
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Linkedin</label>
                                        <input type="text" name="linkedin" id="linkedin" class="form-control" value="{{ $clientDetail->linkedin ?? '' }}">
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Twitter</label>
                                        <input type="text" name="twitter" id="twitter" class="form-control" value="{{ $clientDetail->twitter ?? '' }}">
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Facebook</label>
                                        <input type="text" name="facebook" id="facebook" class="form-control" value="{{ $clientDetail->facebook ?? '' }}">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->

                            <!--row gst number-->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gst_number">@lang('app.gstNumber')</label>
                                        <input type="text" id="gst_number" name="gst_number" class="form-control" value="{{ $clientDetail->gst_number ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('app.login')</label>
                                        <select name="login" id="login" class="form-control">
                                            <option @if($userDetail->login == 'enable') selected @endif value="enable">@lang('app.enable')</option>
                                            <option @if($userDetail->login == 'disable') selected @endif value="disable">@lang('app.disable')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="m-b-10">
                                            <label class="control-label">@lang('modules.emailSettings.emailNotifications')</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" 
                                            @if ($userDetail->email_notifications)
                                                checked
                                            @endif
                                            name="email_notifications" id="email_notifications1" value="1">
                                            <label for="email_notifications1" class="">
                                                @lang('app.enable') </label>

                                        </div>
                                        <div class="radio radio-inline ">
                                            <input type="radio" name="email_notifications"
                                            @if (!$userDetail->email_notifications)
                                                checked
                                            @endif

                                                   id="email_notifications2" value="0">
                                            <label for="email_notifications2" class="">
                                                @lang('app.disable') </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('app.status')</label>
                                        <select name="status" id="status" class="form-control">
                                            <option @if($userDetail->status == 'active') selected
                                                    @endif value="active">@lang('app.active')</option>
                                            <option @if($userDetail->status == 'deactive') selected
                                                    @endif value="deactive">@lang('app.inactive')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--/row-->

                            <div class="row">
                                @if(isset($fields))
                                    @foreach($fields as $field)
                                        <div class="col-md-4">
                                            <label>{{ ucfirst($field->label) }}</label>
                                            <div class="form-group">
                                                @if( $field->type == 'text')
                                                    <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$clientDetail->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                @elseif($field->type == 'password')
                                                    <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$clientDetail->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                @elseif($field->type == 'number')
                                                    <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$clientDetail->custom_fields_data['field_'.$field->id] ?? ''}}">

                                                @elseif($field->type == 'textarea')
                                                    <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" id="{{$field->name}}" cols="3">{{$clientDetail->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>

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
                                                             isset($clientDetail)?$clientDetail->custom_fields_data['field_'.$field->id]:'',['class' => 'form-control gender'])
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
                                                    <input type="text" class="form-control date-picker" size="16" name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                           value="{{ ($clientDetail->custom_fields_data['field_'.$field->id] != '') ? \Carbon\Carbon::createFromFormat('m/d/Y', $clientDetail->custom_fields_data['field_'.$field->id])->format('m/d/Y') : \Carbon\Carbon::now()->format('m/d/Y')}}">
                                                @endif
                                                <div class="form-control-focus"> </div>
                                                <span class="help-block"></span>

                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>@lang('app.shippingAddress')</label>
                                    <div class="form-group">
                                        <textarea name="shipping_address" id="shipping_address" class="form-control" rows="4">{{$clientDetail->shipping_address ?? ''}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>@lang('app.note')</label>
                                    <div class="form-group">
                                        <textarea name="note" id="note" class="form-control summernote" rows="3">{{ $clientDetail->note ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.update')</button>
                            <a href="{{ route('admin.clients.index') }}" class="btn btn-default">@lang('app.back')</a>
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
     var subCategories = @json($subcategories);
        $('#category_id').change(function (e) {
            // get projects of selected users
            var opts = '';

            var subCategory = subCategories.filter(function (item) {
                return item.category_id == e.target.value
            });
            subCategory.forEach(project => {
                console.log(project);
            opts += `<option value='${project.id}'>${project.category_name}</option>`
        })

            $('#sub_category_id').html('<option value="">Select Sub Category...</option>'+opts)
            $("#sub_category_id").select2({
                formatNoMatches: function () {
                    return "{{ __('messages.noRecordFound') }}";
                }
            });
        });
    $(".date-picker").datepicker({
        todayHighlight: true,
        autoclose: true
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.clients.update', [$userDetail->id])}}',
            container: '#updateClient',
            type: "POST",
            redirect: true,
            data: $('#updateClient').serialize()
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
</script>
@endpush
