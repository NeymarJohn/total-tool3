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
                <li><a href="{{ route('admin.products.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.add') @lang('app.menu.products')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('app.add') @lang('app.menu.products')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createProduct','class'=>'ajax-form','method'=>'POST']) !!}
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('app.name')</label>
                                        <input type="text" id="name" name="name" class="form-control" >
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('app.price')</label>
                                        <input type="text" id="price" name="price" class="form-control" >
                                        <span class="help-block"> @lang('messages.productPrice')</span>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-xs-12 col-md-6 ">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.productCategory.productCategory') <a href="javascript:;" id="addProjectCategory" class="text-info"><i class="ti-settings text-info"></i></a>
                                        </label>
                                        <select class="selectpicker form-control" name="category_id" id="category_id"
                                                data-style="form-control">
                                            <option value="">@lang('messages.pleaseSelectCategory')</option>
                                            @forelse($categories as $category)
                                                <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noProductCategory')</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 ">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.productCategory.productSubCategory') <a href="javascript:;" id="addProductSubCategory" class="text-info"><i class="ti-settings text-info"></i></a>
                                        </label>
                                        <select class="select2 form-control" name="sub_category_id" id="sub_category_id"
                                                data-style="form-control">
                                            <option value="">@lang('messages.noProductSubCategoryAdded')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--/row-->


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.invoices.tax') <a href="javascript:;" id="tax-settings" ><i class="ti-settings text-info"></i></a></label>
                                        <select id="multiselect" name="tax[]"  multiple="multiple" class="selectpicker form-control type">
                                            @foreach($taxes as $tax)
                                                <option value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if($invoiceSetting->hsn_sac_code_show)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">@lang('app.hsnSacCode')</label>
                                            <input type="text" id="hsn_sac_code" name="hsn_sac_code" class="form-control" >
                                        </div>
                                    </div>
                                @endif

                                <!--/span-->
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label required">@lang('app.description')</label>
                                        <textarea name="description" id="" cols="30" rows="4" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">

                                        <div class="checkbox checkbox-info">
                                            <input id="purchase_allow" name="purchase_allow" value="no"
                                                   type="checkbox">
                                            <label for="purchase_allow">@lang('app.purchaseAllow')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
                            <button type="reset" class="btn btn-default">@lang('app.reset')</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->


    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="taxModal" role="dialog" aria-labelledby="myModalLabel"
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
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script>

        var subCategories = @json($subCategories);

        $('#category_id').change(function (e) {
            // get projects of selected users
            var opts = '';

            var subCategory = subCategories.filter(function (item) {
                return item.category_id == e.target.value
            });
            subCategory.forEach(project => {
                console.log(project);
            opts += `<option value='${project.id}'>${project.category_name}</option>`
        })

            $('#sub_category_id').html('<option value="0">Select Sub Category...</option>'+opts)
            $("#sub_category_id").select2({
                formatNoMatches: function () {
                    return "{{ __('messages.noRecordFound') }}";
                }
            });
        });


        $('#createProduct').on('click', '#addProjectCategory', function () {

            var url = '{{ route('admin.productCategory.create')}}';
            $('#modelHeading').html('Manage Project Category');
            $.ajaxModal('#taxModal', url);
        });

        $('#createProduct').on('click', '#addProductSubCategory', function () {
            var catID = $('#category_id').val();
            var url = '{{ route('admin.productSubCategory.create')}}?catID='+catID;
            $('#modelHeading').html('Manage Project Sub Category');
            $.ajaxModal('#taxModal', url);
        });
        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        $('#tax-settings').on('click', function (event) {
            event.preventDefault();
            var url = '{{ route('admin.taxes.create')}}';
            $('#modelHeading').html('...');
            $.ajaxModal('#taxModal', url);
        });

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.products.store')}}',
                container: '#createProduct',
                type: "POST",
                redirect: true,
                data: $('#createProduct').serialize()
            })
        });
    </script>
@endpush

