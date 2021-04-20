@extends('layouts.member-app')

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
                <li><a href="{{ route('member.employees.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.edit')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/tagify-master/dist/tagify.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.employees.updateTitle')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'updateEmployee','class'=>'ajax-form','method'=>'PUT']) !!}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4 ">
                                        <div class="form-group">
                                            <label class="required">@lang('modules.employees.employeeId')</label>
                                            <input type="text" name="employee_id" id="employee_id" class="form-control"
                                                   value="{{ $employeeDetail->employee_id }}" autocomplete="nope">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required">@lang('modules.employees.employeeName')</label>
                                            <input type="text" name="name" id="name" class="form-control" value="{{ $userDetail->name }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required">@lang('modules.employees.employeeEmail')</label>
                                            <input type="email" name="email" id="email" class="form-control" value="{{ $userDetail->email }}">
                                            <span class="help-block">Employee will login using this email.</span>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="required">@lang('modules.employees.employeePassword')</label>
                                            <input type="password" style="display: none">
                                            <input type="password" name="password" id="password" class="form-control">
                                            <span class="help-block"> @lang('modules.employees.updatePasswordNote')</span>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
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
                                </div>
                                <!--/row-->

                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label"><i class="fa fa-slack"></i> @lang('modules.employees.slackUsername')</label>
                                            <div class="input-group"> <span class="input-group-addon">@</span>
                                                <input type="text" id="slack_username" name="slack_username" class="form-control" value="{{ $employeeDetail->slack_username ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <!--/span-->

                                </div>
                                <!--/row-->

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">@lang('app.address')</label>
                                            <textarea name="address"  id="address"  rows="5" class="form-control">{{ $employeeDetail->address ?? '' }}</textarea>
                                        </div>
                                    </div>

                                </div>
                                <!--/span-->
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <div class="form-group">
                                            <label>@lang('app.skills')</label>
                                            <input name='tags' placeholder='@lang('app.skills')' value='{{implode(',', $userDetail->skills()) }}' >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label>@lang('app.designation')</label>
                                            <select name="designation" id="designation" class="form-control">
                                                @forelse($designations as $designation)
                                                    <option @if($employeeDetail->designation_id == $designation->id) selected @endif value="{{ $designation->id }}">{{ $designation->name }}</option>
                                                @empty
                                                    <option value="">@lang('messages.noRecordFound')</option>
                                                @endforelse()
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label>@lang('app.department') <a href="javascript:;" id="department-setting" ><i class="ti-settings text-info"></i></a></label>
                                            <select name="department" id="department" class="form-control">
                                                @forelse($teams as $team)
                                                    <option @if($employeeDetail->team_id == $team->id) selected @endif value="{{ $team->id }}">{{ $team->team_name }}</option>
                                                @empty
                                                    <option value="">@lang('messages.noRecordFound')</option>
                                                @endforelse()
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--/row-->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('modules.employees.hourlyRate')</label>
                                            <input type="text" name="hourly_rate" id="hourly_rate" class="form-control" value="{{ $employeeDetail->hourly_rate ?? '' }}">
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!--/row-->
                                <div class="row">
                                    @if(isset($fields))

                                        @foreach($fields as $field)
                                            <div class="col-md-6">
                                                <label>{{ ucfirst($field->label) }}</label>
                                                <div class="form-group">
                                                    @if( $field->type == 'text')
                                                        <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                    @elseif($field->type == 'password')
                                                        <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                    @elseif($field->type == 'number')
                                                        <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}">

                                                    @elseif($field->type == 'textarea')
                                                        <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" id="{{$field->name}}" cols="3">{{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>

                                                    @elseif($field->type == 'radio')
                                                        <div class="radio-list">
                                                            @foreach($field->values as $key=>$value)
                                                                <label class="radio-inline @if($key == 0) p-0 @endif">
                                                                    <div class="radio radio-info">
                                                                        <input type="radio" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" id="optionsRadios{{$key.$field->id}}" value="{{$value}}" @if(isset($employeeDetail) && $employeeDetail->custom_fields_data['field_'.$field->id] == $value) checked @elseif($key==0) checked @endif>>
                                                                        <label for="optionsRadios{{$key.$field->id}}">{{$value}}</label>
                                                                    </div>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @elseif($field->type == 'select')
                                                        {!! Form::select('custom_fields_data['.$field->name.'_'.$field->id.']',
                                                                $field->values,
                                                                 isset($employeeDetail)?$employeeDetail->custom_fields_data['field_'.$field->id]:'',['class' => 'form-control gender'])
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
                                                               value="{{ ($employeeDetail->custom_fields_data['field_'.$field->id] != '') ? \Carbon\Carbon::createFromFormat('m/d/Y', $employeeDetail->custom_fields_data['field_'.$field->id])->format('m/d/Y') : \Carbon\Carbon::now()->format('m/d/Y')}}">
                                                    @endif
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block"></span>

                                                </div>
                                            </div>
                                        @endforeach

                                    @endif

                                </div>

                            </div>
                            <div class="form-actions">
                                <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.update')</button>
                                <a href="{{ route('member.employees.index') }}" class="btn btn-default">@lang('app.back')</a>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/tagify-master/dist/tagify.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script data-name="basic">
    (function(){
        $("#department").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        $("#designation").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        var input = document.querySelector('input[name=tags]'),
            // init Tagify script on the above inputs
            tagify = new Tagify(input, {
                whitelist : {!! json_encode($skills) !!},
                //  blacklist : [".NET", "PHP"] // <-- passed as an attribute in this demo
            });

// Chainable event listeners
        tagify.on('add', onAddTag)
            .on('remove', onRemoveTag)
            .on('input', onInput)
            .on('invalid', onInvalidTag)
            .on('click', onTagClick);

// tag added callback
        function onAddTag(e){
            tagify.off('add', onAddTag) // exmaple of removing a custom Tagify event
        }

// tag remvoed callback
        function onRemoveTag(e){
        }

// on character(s) added/removed (user is typing/deleting)
        function onInput(e){
        }

// invalid tag added callback
        function onInvalidTag(e){
        }

// invalid tag added callback
        function onTagClick(e){
        }

    })()
</script>
<script>
    $(".date-picker").datepicker({
        todayHighlight: true,
        autoclose: true
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('member.employees.update', [$userDetail->id])}}',
            container: '#updateEmployee',
            type: "POST",
            redirect: true,
            data: $('#updateEmployee').serialize()
        })
    });
</script>
@endpush

