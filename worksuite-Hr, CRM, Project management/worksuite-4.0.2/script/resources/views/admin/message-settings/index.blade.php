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

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.menu.messageSettings')</div>

                <div class="vtabs customvtab m-t-10">
                    @include('sections.admin_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    {!! Form::open(['id'=>'updateProfile','class'=>'ajax-form','method'=>'PUT']) !!}
                                    <div class="form-body">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="checkbox checkbox-info  col-md-10">
                                                        <input id="allow-client-admin" name="allow_client_admin" value="yes"
                                                               @if($messageSettings->allow_client_admin == 'yes') checked @endif
                                                               type="checkbox">
                                                        <label for="allow-client-admin">@lang('modules.messages.allowClientAdminChat')</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="checkbox checkbox-info  col-md-10">
                                                        <input id="allow-client-employee" name="allow_client_employee" value="yes"
                                                               @if($messageSettings->allow_client_employee == 'yes') checked @endif
                                                               type="checkbox">
                                                        <label for="allow-client-employee">@lang('modules.messages.allowClientEmployeeChat')</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->

                                        </div>
                                        <!--/row-->
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" id="save-form-2" class="btn btn-success"><i
                                                    class="fa fa-check"></i>
                                            @lang('app.update')
                                        </button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>

                </div>
            </div>    <!-- .row -->
        </div>
    </div>

@endsection

@push('footer-script')
<script>
    $('#save-form-2').click(function () {
        $.easyAjax({
            url: '{{route('admin.message-settings.update', [1])}}',
            container: '#updateProfile',
            type: "POST",
            data: $('#updateProfile').serialize()
        })
    });
</script>
@endpush
