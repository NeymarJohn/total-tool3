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

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="panel">

                <div class="vtabs customvtab p-t-10">
                    @if($global->front_design == 1)
                        @include('sections.front_setting_new_theme_menu')
                    @else
                        @include('sections.front_setting_menu')
                    @endif
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="white-box">
                                {{-- <h3 class="box-title m-b-10">@lang('modules.frontCms.seoDetails') </h3> --}}
                                <div class="row">
                                    <div class="table-responsive" style="clear: both;">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>@lang("app.name")</th>
                                                <th>@lang("modules.frontCms.seo_title")</th>
                                                <th>@lang("modules.frontCms.seo_author")</th>
                                                <th>@lang("modules.frontCms.seo_description")</th>
                                                <th>@lang("modules.frontCms.seo_keywords")</th>
                                                <th>@lang("app.edit")</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach($seoDetails as $key => $seoDetail)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $seoDetail->page_name }}</td>
                                                    <td>{{ $seoDetail->seo_title }}</td>
                                                    <td>{{ $seoDetail->seo_author }}</td>
                                                    <td>{{ $seoDetail->seo_description }}</td>
                                                    <td>{{ $seoDetail->seo_keywords }}</td>

                                                    <td><a href="javascript:;"
                                                           onclick="editSeoDetail('{{ $seoDetail->id }}')"
                                                           class="btn btn-info btn-circle" data-toggle="tooltip"
                                                           data-original-title="Edit"><i class="fa fa-pencil"
                                                                                         aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    <!-- .row -->
                </div>

            </div>
        </div>


    </div>
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="seoDetailModel" role="dialog" aria-labelledby="myModalLabel"
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

    <script>
        $("body").tooltip({
            selector: '[data-toggle="tooltip"]'
        });

        function editSeoDetail(id) {
            var url = "{{ route('super-admin.seo-detail.edit', ':id')}}";
            url = url.replace(':id', id);
            $('#modelHeading').html("@lang('app.seo') @lang('app.edit')");
            $.ajaxModal('#seoDetailModel', url);
        }

    </script>
@endpush
