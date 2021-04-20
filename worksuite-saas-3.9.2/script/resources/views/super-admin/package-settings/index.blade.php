@extends('layouts.super-admin')

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
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang("app.update") @lang("app.package") @lang('app.menu.settings')</div>

                <div class="vtabs customvtab m-t-10">
                    @include('sections.super_admin_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="white-box">
                                        <h3 class="box-title m-b-0"> @lang("app.freeTrial") @lang('app.menu.settings')</h3>

                                        <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-3 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">@lang('app.name')</label>
                                                        <input type="text" id="name" name="name"
                                                               value="{{ $package->name ?? '' }}" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-3 col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang('app.max') @lang('app.menu.employees')</label>
                                                        <input type="number" name="max_employees" id="max_employees"
                                                               value="{{ $package->max_employees ?? '' }}"
                                                               class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 col-md-3 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="no_of_days">@lang('modules.packageSetting.noOfDays')</label>
                                                        <input type="number" class="form-control" id="no_of_days"
                                                               name="no_of_days"
                                                               value="{{ $packageSetting->no_of_days }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-3 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="no_of_days">@lang('modules.packageSetting.notificationBeforeDays')</label>
                                                        <input type="number" class="form-control"
                                                               id="notification_before" name="notification_before"
                                                               value="{{ $packageSetting->notification_before }}">
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="no_of_days">@lang('modules.packageSetting.trialMessage')</label>
                                                        <input type="text" class="form-control" id="trial_message" name="trial_message"
                                                               value="{{ $packageSetting->trial_message }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <h3 class="box-title">Storage </h3>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang('app.menu.fileStorage')</label>
                                                        <input type="number" name="max_storage_size" min="-1"
                                                               id="max_storage_size"
                                                               value="{{ $package->max_storage_size ?? '' }}"
                                                               class="form-control">
                                                        <p class="text-bold">Set -1 for unlimited storage size</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label required">@lang('app.storageUnit')</label>
                                                        <select name="storage_unit" id="storage_unit"
                                                                class="form-control">
                                                            <option value="mb"
                                                                    @if($package->storage_unit == 'mb') selected @endif>
                                                                @lang('app.mb')
                                                            </option>
                                                            <option value="gb"
                                                                    @if($package->storage_unit == 'gb') selected @endif>
                                                                @lang('app.gb')
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <h3 class="box-title">@lang('app.select') @lang('app.module')</h3>
                                            <hr>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="row form-group module-in-package">
                                                        @foreach($modules as $module)
                                                            @php
                                                                $packageModules = (array)json_decode($packageSetting->modules);
                                                            @endphp
                                                            <div class="col-md-2">
                                                                <div class="checkbox checkbox-inline checkbox-info m-b-10">
                                                                    <input id="{{ $module->module_name }}" name="module_in_package[{{ $module->id }}]" value="{{ $module->module_name }}" type="checkbox" @if(isset($packageModules) && in_array($module->module_name, $packageModules) ) checked @endif>
                                                                    <label for="{{ $module->module_name }}">{{ ucfirst($module->module_name) }}</label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <label class="control-label" >@lang('app.status')</label>
                                                    <div class="switchery-demo">
                                                        <input type="checkbox" name="status" @if($packageSetting->status == 'active') checked @endif class="js-switch " data-color="#00c292" data-secondary-color="#f96262"  />
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" id="save-form"
                                                    class="btn btn-success waves-effect waves-light m-r-10">
                                                @lang('app.update')
                                            </button>

                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                    </div>
                                </div>

                            </div>
                            <!-- .row -->
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- .row -->



@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>

    <script>
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());

        });
        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('super-admin.package-settings.update', $packageSetting->id)}}',
                container: '#editSettings',
                type: "POST",
                redirect: true,
                file: true,
            })
        });

    </script>
@endpush
