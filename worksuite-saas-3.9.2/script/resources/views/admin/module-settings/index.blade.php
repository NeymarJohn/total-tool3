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
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.accountSettings.updateTitle')</div>

                <div class="vtabs customvtab m-t-10">
                    @include('sections.module_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="white-box">
                                        <h3 class="box-title m-b-0">{{ ucfirst($type) }} @lang("modules.moduleSettings.moduleSetting")</h3>

                                        <p class="text-muted m-b-10 font-13">
                                            @lang("modules.moduleSettings.employeeSubTitle") {{ ucfirst($type) }} @lang("modules.moduleSettings.section")
                                        </p>

                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12 b-t p-t-20">
                                                {!! Form::open(['id'=>'editSettings','class'=>'ajax-form form-horizontal','method'=>'PUT']) !!}

                                                @foreach($modulesData as $setting)
                                                    <div class="form-group col-md-4">
                                                        <label class="control-label col-xs-6" >@lang('modules.module.'.$setting->module_name)</label>
                                                        <div class="col-xs-6">
                                                            <div class="switchery-demo">
                                                                <input type="checkbox" @if($setting->status == 'active') checked @endif class="js-switch change-module-setting" data-setting-id="{{ $setting->id }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

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

    $('.change-module-setting').change(function () {
        var id = $(this).data('setting-id');

        if($(this).is(':checked'))
            var moduleStatus = 'active';
        else
            var moduleStatus = 'deactive';

        var url = '{{route('admin.module-settings.update', ':id')}}';
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            type: "POST",
            container:".data-section",
            data: { 'id': id, 'status': moduleStatus, '_method': 'PUT', '_token': '{{ csrf_token() }}' },
            success: function(res) {
                if(res.status == 'fail' && res.error_name == 'module_dependent') {
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000)
                }
            }
        })
    });
</script>
@endpush