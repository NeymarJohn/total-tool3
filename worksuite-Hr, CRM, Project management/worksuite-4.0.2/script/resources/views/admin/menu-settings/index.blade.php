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
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css">
    <style>
        .dd-item {
            cursor: pointer !important;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.update') @lang('app.menu.menuSetting')</div>

                <div class="vtabs customvtab m-t-10">

                    @include('sections.admin_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            {!! Form::open(['id'=>'updateSettings','class'=>'ajax-form','method'=>'PUT']) !!}

                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="box-title m-b-0">Main Menu</h3>
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12 b-t p-t-20">
                                            <div class="dd" id="mainMenu">
                                                <ol class='dd-list sortable' id="main">

                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="box-title m-b-0">Settings Sub Menu </h3>
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12 b-t p-t-20">
                                            <div class="dd" id="settingMenu">
                                                <ol class='dd-list' id="setting">

                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 m-t-20">
                                    <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">@lang('app.update')</button>
                                    <button type="reset" id="reset-default" class="btn btn-inverse waves-effect waves-light">Reset Default</button>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script src="{{ asset('js/nestable.js') }}"></script>
    <script>
        var main = JSON.parse('{!! json_encode($menuSettings->main_menu) !!}');
        var setting = JSON.parse('{!! json_encode($menuSettings->setting_menu) !!}');

        function mainMenu(obj) {
            var output = '';
            function buildItem(item) {

                var html = "<li class='dd-item' data-id='" + item.id + "'>";
                html += "<div class='dd-handle'>" + item.translated_name + "</div>";

                if (item.children) {

                    html += "<ol class='dd-list'>";
                    $.each(item.children, function (index, sub) {
                        html += buildItem(sub);
                    });
                    html += "</ol>";

                }

                html += "</li>";

                return html;
            }

            $.each(obj, function (index, item) {
                output += buildItem(item);
            });

            return output;
        }


        $('#main').html(mainMenu(main));
        $('#setting').html(mainMenu(setting));

        var option = {
            maxDepth:2,
            beforeDragStop: function(l,e, p){
                // l is the main container
                // e is the element that was moved
                // p is the place where element was moved.
                // console.log(l[0].id,  'main container')
                if( (l[0].id == 'settingMenu' && p[0].id == 'main') ||
                    (l[0].id == 'mainMenu' && p[0].id == 'setting') ||
                    ( p[0].offsetParent.offsetParent.offsetParent.id != "" && l[0].id != p[0].offsetParent.offsetParent.offsetParent.id)) {
                    return false;
                }
            }
        };

        $('#mainMenu').nestable(option);
        $('#settingMenu').nestable(option);

        $('#save-form').on('click', function() {
            $.easyAjax({
                url: '{{route('admin.menu-settings.update', $menuSettings->id)}}',
                container: '#updateSettings',
                type: "POST",
                data: {
                    _method:'PUT',
                    _token: '{{ csrf_token() }}',
                    main_menu:$('#mainMenu').nestable('serialize'),
                    setting_menu:$('#settingMenu').nestable('serialize'),
                },
                success: function (response) {
                    if (response.status == "success") {
                        $.unblockUI();
                        window.location.reload();
                    }
                }
            })
        });

        $('#reset-default').on('click', function() {

            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.resetSettingText')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('app.yes')",
                cancelButtonText: "@lang('app.no')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {
                    $.easyAjax({
                        url: '{{route('admin.menu-settings.update', $menuSettings->id)}}',
                        container: '#updateSettings',
                        type: "POST",
                        data: {
                            _method:'PUT',
                            _token: '{{ csrf_token() }}',
                            type:'reset'
                        },
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                window.location.reload();
                            }
                        }
                    })
                }
            });

        });
    </script>

@endpush
