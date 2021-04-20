@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.language-settings.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')

@include('sections.admin_setting_menu')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.add') @lang('app.language')</div>

                 <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {!! Form::open(['id'=>'createCurrency','class'=>'ajax-form','method'=>'POST']) !!}
                                <div class="form-group">
                                    <label for="language_name" class="required">@lang('app.language') @lang('app.name')</label>
                                    <input type="text" class="form-control" id="language_name" name="language_name"
                                        placeholder="Enter Language Name">
                                </div>
                                <div class="form-group">
                                    <label for="language_code" class="required">@lang('app.language_code')</label>
                                    <input type="text" class="form-control" id="language_code" name="language_code"
                                        placeholder="Enter Language Code">
                                </div>
                                <div class="form-group ">
                                    <label for="usd_price">@lang('app.status') </label>
                                    <select class="form-control" name="status">
                                        <option value="enabled">@lang('app.enabled')</option>
                                        <option value="disabled">@lang('app.disabled')</option>
                                    </select>
                                </div>

                                <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                                    @lang('app.save')
                                </button>
                                <button type="reset" class="btn btn-inverse waves-effect waves-light">@lang('app.reset')</button>
                                {!! Form::close() !!}
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
<script>

    // store language
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.language-settings.store')}}',
            container: '#createCurrency',
            type: "POST",
            redirect: true,
            data: $('#createCurrency').serialize()
        })
    });
</script>
@endpush

