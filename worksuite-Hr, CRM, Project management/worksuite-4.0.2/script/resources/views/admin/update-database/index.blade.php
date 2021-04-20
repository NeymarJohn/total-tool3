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

        @include('vendor.froiden-envato.update.update_blade')

        <div class="col-md-12">
            <div class="white-box">

                <div class="panel panel-inverse">
                    <div class="panel-heading">@lang($pageTitle)</div>

                    <div class="vtabs customvtab m-t-10">

                        @include('sections.admin_setting_menu')

                        <div id="vhome3" class="tab-pane active">
                            @include('vendor.froiden-envato.update.version_info')

                                <!--row-->
                            <div class="row">

                                @include('vendor.froiden-envato.update.changelog')
                                @include('vendor.froiden-envato.update.plugins')
                            </div>
                            <!--/row-->

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
    @include('vendor.froiden-envato.update.update_script')


@endpush
