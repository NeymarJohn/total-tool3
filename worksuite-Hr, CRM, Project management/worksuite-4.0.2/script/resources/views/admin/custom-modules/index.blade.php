@extends('layouts.app')

@section('page-title')
<div class="row bg-title">
    <!-- .page title -->
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
    </div>
    <!-- /.page title -->
    <!-- .breadcrumb -->
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
        @if($allModules)
        <a href="{{ route('admin.custom-modules.create') }}" class="btn btn-success btn-sm btn-outline"><i
                class="fa fa-refresh"></i> @lang('app.install')/@lang('app.update') @lang('app.module')</a>
        @endif
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
            <li class="active">@lang($pageTitle)</li>
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

            <div class="vtabs customvtab">
                @include('sections.module_setting_menu')

                <div class="tab-content">
                    <div id="vhome3" class="tab-pane active">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="white-box">
                                    <h3 class="box-title m-b-0">@lang("app.menu.customModule")</h3>

                                    <div class="row">

                                        <div class="col-md-12">

                                            <ul class="list-group m-t-20" id="files-list">
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <strong>@lang('app.name')</strong>
                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            <strong>Envato Purchase code</strong>
                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            <strong>@lang('app.currentVersion')</strong>
                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            <strong>@lang('app.latestVersion')</strong>
                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            <strong>@lang('app.status')</strong>
                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            <strong>@lang('app.action')</strong>
                                                        </div>
                                                    </div>
                                                </li>
                                                @php
                                                $count = 0;
                                                @endphp
                                                @forelse ($allModules as $key=>$module)

                                                <li class="list-group-item" id="file-{{ $count++ }}">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            {{ $key }}
                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            @if(in_array($module, $worksuitePlugins))

                                                            @if (config(strtolower($module).'.setting'))
                                                            @php
                                                            $settingInstance = config(strtolower($module).'.setting');

                                                            $fetchSetting = $settingInstance::first();
                                                            @endphp

                                                            @if (config(strtolower($module).'.verification_required'))
                                                            {!! $fetchSetting->purchase_code ?? '<a href="javascript:;"
                                                                class="btn btn-success btn-sm btn-outline verify-module"
                                                                data-module="'. strtolower($module).'">'.__('app.verifyEnvato').'</a>'
                                                            !!}
                                                            @endif
                                                            @endif


                                                            @endif


                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            @if (config(strtolower($module).'.setting'))
                                                            <label
                                                                class="label label-info">{{ File::get($module->getPath() . '/version.txt') }}</label>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            @if (config(strtolower($module).'.setting'))
                                                            <label
                                                                class="label label-info">{{ File::get($module->getPath() . '/version.txt') }}</label>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            <div class="switchery-demo">
                                                                <input type="checkbox" @if(in_array($module,
                                                                    $worksuitePlugins)) checked @endif
                                                                    class="js-switch change-module-setting"
                                                                    data-size="small"
                                                                    data-module-name="{{ $module }}" />
                                                            </div>

                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            @if (config(strtolower($module).'.setting'))
                                                            <a href="" class="btn btn-success btn-sm btn-outline"
                                                                data-file-no="{{ $module }}">@lang('app.download')
                                                                @lang('app.update') <i class="fa fa-download"></i></a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </li>
                                                @empty
                                                <div class="text-center">
                                                    <div class="empty-space" style="height: 200px;">
                                                        <div class="empty-space-inner">
                                                            <div class="icon" style="font-size:30px"><i
                                                                    class="icon-layers"></i>
                                                            </div>
                                                            <div class="title m-b-15">@lang('messages.noModules')
                                                            </div>
                                                            <div class="subtitle">
                                                                <a href="{{ route('admin.custom-modules.create') }}"
                                                                    class="btn btn-success btn-sm btn-outline"><i
                                                                        class="fa fa-refresh"></i> @lang('app.install')
                                                                    / @lang('app.update') @lang('app.module')</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforelse

                                            </ul>
                                        </div>

                                        @include('vendor.froiden-envato.update.plugins',['allModules'=>
                                        $allModules])


                                    </div>
                                    <!--/row-->
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

{{--Ajax Modal--}}
<div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal-data-application">
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
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
<script>
    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());

    });

    $('.change-module-setting').change(function () {
        var module = $(this).data('module-name');

        if($(this).is(':checked'))
            var moduleStatus = 'active';
        else
            var moduleStatus = 'inactive';

        var url = '{{route('admin.custom-modules.update', ':module')}}';
        url = url.replace(':module', module);
        $.easyAjax({
            url: url,
            type: "POST",
            data: { 'id': module, 'status': moduleStatus, '_method': 'PUT', '_token': '{{ csrf_token() }}' }
        })
    });

    $('.verify-module').click(function () {
        var module = $(this).data('module');
        var url = '{{route('admin.custom-modules.show', ':module')}}';
        url = url.replace(':module', module);
        $('#modelHeading').html('Verify your purchase');
        $.ajaxModal('#projectCategoryModal', url);
    })
</script>

@endpush