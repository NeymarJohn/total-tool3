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
                <li><a href="{{ route('admin.employees.index') }}">@lang($pageTitle)</a></li>
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
    <link rel="stylesheet" href="{{ asset('plugins/tagify-master/dist/tagify.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            {!!   $smtpSetting->set_smtp_message !!}

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.employees.createTitle')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createEmployee','class'=>'ajax-form','method'=>'POST']) !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.employees.employeeId')</label>
                                        <a class="mytooltip" href="javascript:void(0)">
                                            <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span
                                                            class="tooltip-inner2">@lang('modules.employees.employeeIdInfo')</span></span></span></a>
                                        <input type="text" name="employee_id" id="employee_id" class="form-control" value="{{ $lastEmployeeID+1 }}"
                                               autocomplete="nope">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.employees.employeeName')</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                               autocomplete="nope">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.employees.employeeEmail')</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                               autocomplete="nope">
                                        <span class="help-block">@lang('modules.employees.emailNote')</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.employees.employeePassword')</label>
                                        <input type="password" style="display: none">
                                        <input type="password" name="password" id="password" readonly="readonly" onfocus="this.removeAttribute('readonly');" class="form-control auto-complete-off"
                                               autocomplete="nope">
                                        <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span class="help-block"> @lang('modules.employees.passwordNote') </span>
                                        <div class="checkbox checkbox-info">
                                            <input id="random_password" name="random_password" value="true" type="checkbox">
                                            <label for="random_password">@lang('modules.client.generateRandomPassword')</label>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>

                            <!--/row-->
                            <div class="row">
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label class="required">@lang('app.designation') <button  id="designation-setting" type="button" class="btn btn-xs btn-outline btn-info"><i class="ti-settings"></i> @lang('messages.manageDesignation')</button></label>
                                        <select name="designation" id="designation" class="form-control">
                                            <option value="">--</option>
                                            @forelse($designations as $designation)
                                                <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                            @empty
                                                <option value="">@lang('messages.noRecordFound')</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label class="required">@lang('app.department') <button  id="department-setting" type="button" class="btn btn-xs btn-outline btn-info"><i class="ti-settings"></i> @lang('messages.manageDepartment')</button></label>
                                        <select name="department" id="department" class="form-control">
                                            <option value="">--</option>
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"><i
                                                    class="fa fa-slack"></i> @lang('modules.employees.slackUsername')
                                        </label>
                                        <div class="input-group"><span class="input-group-addon">@</span>
                                            <input autocomplete="nope" type="text" id="slack_username"
                                                   name="slack_username" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.employees.joiningDate')</label>
                                        <input type="text" autocomplete="off" name="joining_date" id="joining_date"
                                               class="form-control" value="">
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('modules.employees.lastDate')</label>
                                        <input type="text" autocomplete="off" name="last_date" id="end_date"
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('modules.employees.gender')</label>
                                        <select name="gender" id="gender" class="form-control">
                                            <option value="male">@lang('app.male')</option>
                                            <option value="female">@lang('app.female')</option>
                                            <option value="others">@lang('app.others')</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.address')</label>
                                        <textarea name="address" id="address" rows="3" class="form-control"></textarea>
                                    </div>
                                </div>

                            </div>
                            <!--/span-->
                            <div class="row">
                                <div class="col-md-12 ">
                                    <div class="form-group">
                                        <label>@lang('app.skills')</label>
                                        <input name='tags' placeholder='@lang('app.skills')' value=''>
                                    </div>
                                </div>
                            </div>

                            <!--/row-->
                            <div class="row">
                                <div class="col-md-3">
                                    <label>@lang('app.mobile')</label>
                                    <div class="form-group">
                                        <select class="select2 phone_country_code form-control" name="phone_code">
                                            @foreach ($countries as $item)
                                                <option value="{{ $item->id }}">+{{ $item->phonecode.' ('.$item->iso.')' }}</option>
                                            @endforeach
                                        </select>
                                        <input type="tel" name="mobile" id="mobile" class="mobile" autocomplete="nope">
                                    </div>
                                   
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('modules.employees.hourlyRate') ({{ $global->currency->currency_code }})</label>
                                        <input type="number" step=".01" min="0" name="hourly_rate" id="hourly_rate" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('app.login')</label>
                                        <select name="login" id="login" class="form-control">
                                            <option value="enable">@lang('app.enable')</option>
                                            <option value="disable">@lang('app.disable')</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
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
                            </div>
                            <!--/row-->


                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('modules.profile.profilePicture')</label>
                                    <div class="form-group">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="http://via.placeholder.com/200x150.png?text=@lang('modules.profile.uploadPicture')"
                                                     alt=""/>
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                 style="max-width: 200px; max-height: 150px;"></div>
                                            <div>
                                <span class="btn btn-info btn-file">
                                    <span class="fileinput-new"> @lang('app.selectImage') </span>
                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                    <input type="file" id="image" name="image"> </span>
                                                <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                   data-dismiss="fileinput"> @lang('app.remove') </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!--/span-->

                            <div class="row">
                                @if(isset($fields))
                                    @foreach($fields as $field)
                                        <div class="col-md-6">
                                            <label>{{ ucfirst($field->label) }}</label>
                                            <div class="form-group">
                                                @if( $field->type == 'text')
                                                    <input type="text"
                                                           name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                           class="form-control" placeholder="{{$field->label}}"
                                                           value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                @elseif($field->type == 'password')
                                                    <input type="password"
                                                           name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                           class="form-control" placeholder="{{$field->label}}"
                                                           value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                @elseif($field->type == 'number')
                                                    <input type="number"
                                                           name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                           class="form-control" placeholder="{{$field->label}}"
                                                           value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">

                                                @elseif($field->type == 'textarea')
                                                    <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                              class="form-control" id="{{$field->name}}"
                                                              cols="3">{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>

                                                @elseif($field->type == 'radio')
                                                    <div class="radio-list">
                                                        @foreach($field->values as $key=>$value)
                                                            <label class="radio-inline @if($key == 0) p-0 @endif">
                                                                <div class="radio radio-info">
                                                                    <input type="radio"
                                                                           name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                                           id="optionsRadios{{$key.$field->id}}"
                                                                           value="{{$value}}"
                                                                           @if(isset($editUser) && $editUser->custom_fields_data['field_'.$field->id] == $value) checked
                                                                           @elseif($key==0) checked @endif>>
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
                                                                <input name="custom_fields_data[{{$field->name.'_'.$field->id}}][]"
                                                                       type="checkbox" value="{{$key}}"> {{$value}}
                                                                <span></span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @elseif($field->type == 'date')
                                                    <input type="text" class="form-control date-picker" size="16"
                                                           name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                           value="{{ isset($editUser->dob)?Carbon\Carbon::parse($editUser->dob)->format('Y-m-d'):Carbon\Carbon::now()->format('m/d/Y')}}">
                                                @endif
                                                <div class="form-control-focus"></div>
                                                <span class="help-block"></span>

                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>


                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-success"><i
                                        class="fa fa-check"></i> @lang('app.save')</button>
                            <button type="reset" class="btn btn-default">@lang('app.reset')</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="departmentModel" role="dialog" aria-labelledby="myModalLabel"
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
    <script src="{{ asset('plugins/tagify-master/dist/tagify.js') }}"></script>
    <script data-name="basic">
        (function () {
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
                    whitelist: {!! json_encode($skills) !!},
                    //  blacklist : [".NET", "PHP"] // <-- passed as an attribute in this demo
                });

// Chainable event listeners
            tagify.on('add', onAddTag)
                .on('remove', onRemoveTag)
                .on('input', onInput)
                .on('invalid', onInvalidTag)
                .on('click', onTagClick);

// tag added callback
            function onAddTag(e) {
                tagify.off('add', onAddTag) // exmaple of removing a custom Tagify event
            }

// tag remvoed callback
            function onRemoveTag(e) {
            }

// on character(s) added/removed (user is typing/deleting)
            function onInput(e) {
            }

// invalid tag added callback
            function onInvalidTag(e) {
            }

// invalid tag added callback
            function onTagClick(e) {
            }

        })()
    </script>

    <script>

        $("#joining_date, #end_date").datepicker({
            format: '{{ $global->date_picker_format }}',
            todayHighlight: true,
            autoclose: true
        });

        $("#joining_date").datepicker("setDate", new Date());
        $(".date-picker").datepicker({
            format: 'mm/dd/yyyy',
            todayHighlight: true,
            autoclose: true
        });

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.employees.store')}}',
                container: '#createEmployee',
                type: "POST",
                redirect: true,
                file: (document.getElementById("image").files.length == 0) ? false : true,
                data: $('#createEmployee').serialize()
            })
        });

        $('#random_password').change(function () {
            var randPassword = $(this).is(":checked");

            if (randPassword) {
                $('#password').val('{{ str_random(8) }}');
                $('#password').attr('readonly', 'readonly');
            } else {
                $('#password').val('');
                $('#password').removeAttr('readonly');
            }
        });

        $('#department-setting').on('click', function (event) {
            var url = '{{ route('admin.department.quick-create')}}';
            $('#modelHeading').html("@lang('messages.manageDepartment')");
            $.ajaxModal('#departmentModel', url);
        });

        $('#designation-setting').on('click', function (event) {
            var url = '{{ route('admin.designations.quick-create')}}';
            $('#modelHeading').html("@lang('messages.manageDepartment')");
            $.ajaxModal('#departmentModel', url);
        });

    </script>
@endpush

