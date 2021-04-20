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
                <li><a href="{{ route('admin.currency.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.update')</li>
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
        <div class="col-md-12">
            <div class="panel panel-inverse">

                <div class="vtabs customvtab m-t-10">
                    @include('sections.notification_settings_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            {!! Form::open(['id'=>'updateCurrency','class'=>'ajax-form','method'=>'PUT','autocomplete'=>"off"]) !!}
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="currency_name">PUSHER APP ID</label>
                                                <input type="text" readonly="readonly" onfocus="this.removeAttribute('readonly');" class="form-control auto-complete-off" id="pusher_app_id" name="pusher_app_id" value="{{ $pusherSettings->pusher_app_id }}">
                                            </div>

                                            <div class="form-group" >
                                                <label for="currency_symbol">PUSHER APP KEY</label>
                                                <input type="password" class="form-control" id="pusher_app_key" name="pusher_app_key" value="{{ $pusherSettings->pusher_app_key }}">
                                                <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="currency_code">PUSHER APP SECRET</label>
                                                <input type="password" class="form-control" id="pusher_app_secret" name="pusher_app_secret" value="{{ $pusherSettings->pusher_app_secret }}">
                                                <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="currency_code">PUSHER CLUSTER</label>
                                                <input type="text" class="form-control" id="pusher_cluster" name="pusher_cluster" value="{{ $pusherSettings->pusher_cluster }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="currency_code">Force TLS</label>
                                                <select name="force_tls" id="force_tls" class="form-control">
                                                    <option value="0"
                                                    @if ($pusherSettings->force_tls == "0")
                                                        selected
                                                    @endif
                                                    >False</option>
                                                    <option value="1"
                                                    @if ($pusherSettings->force_tls == "1")
                                                        selected
                                                    @endif

                                                >True</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" >@lang('app.status')</label>
                                                <div class="switchery-demo">
                                                    <input type="checkbox" name="status" @if ($pusherSettings->status) checked  @endif class="js-switch " data-color="#00c292" data-secondary-color="#f96262"  />
                                                </div>
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
            url: '{{route('admin.pusher-settings.update', $pusherSettings->id )}}',
            container: '#updateCurrency',
            type: "POST",
            data: $('#updateCurrency').serialize()
        })
    });
</script>
@endpush

