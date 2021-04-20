@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)
                <span class="text-info b-l p-l-10 m-l-5">{{ $totalProducts }}</span> <span class="font-12 text-muted m-l-5">@lang('app.total') @lang('app.menu.products')</span>
            </h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('admin.products.create') }}" class="btn btn-outline btn-success btn-sm">@lang('app.addNew') @lang('app.menu.products') <i class="fa fa-plus" aria-hidden="true"></i></a>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
    <style>

        .dashboard-stats .white-box .list-inline {
            margin-bottom: 0;
        }

        .dashboard-stats .white-box {
            padding: 10px;
        }

        .dashboard-stats .white-box .box-title {
            font-size: 13px;
            text-transform: capitalize;
            font-weight: 300;
        }
        #products-table_wrapper .dt-buttons{
            display: none !important;
        }
    </style>
@endpush
@section('filter-section')
    <form action="" id="filter-form">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">@lang('modules.productCategory.category')</label>
                    <select class="select2 form-control" name="category_id" id="category_id"
                            data-style="form-control">
                        <option selected value="all">@lang('app.all')</option>
                        @forelse($categories as $category)
                            <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                        @empty
                            <option value="">@lang('messages.noProductCategory')</option>
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">@lang('modules.productCategory.subCategory')</label>
                    <select class="select2 form-control" data-placeholder="@lang('modules.productCategory.subCategory')" id="sub_category_id">
                        <option selected value="all">@lang('app.all')</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group p-t-10">
                    <button type="button" class="btn btn-success" id="filter-results"><i class="fa fa-check"></i> @lang('app.apply')
                    </button>
                    <button type="button" id="reset-filters"
                            class="btn btn-inverse"><i
                                class="fa fa-refresh"></i> @lang('app.reset')</button>
                </div>
            </div>

        </div>
    </form>
@endsection
@section('content')

    <div class="row">

        <div class="col-md-12">
            <div class="white-box">

                <div class="table-responsive">
{{--                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="products-table">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th>@lang('app.id')</th>--}}
{{--                            <th>@lang('app.name')</th>--}}
{{--                            <th>@lang('app.price') (@lang('app.inclusiveAllTaxes'))</th>--}}
{{--                            <th>@lang('app.purchaseAllow')</th>--}}
{{--                            <th>@lang('app.action')</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                    </table>--}}
                    {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

    {!! $dataTable->scripts() !!}
    <script>
        $(function() {
            $(".select2").select2({
                formatNoMatches: function () {
                    return "{{ __('messages.noRecordFound') }}";
                }
            });
            var subCategories = @json($subCategories);

            $('#category_id').change(function (e) {
                // get projects of selected users
                var opts = '';

                var subCategory = subCategories.filter(function (item) {
                    return item.category_id == e.target.value
                });
                subCategory.forEach(project => {
                opts += `<option value='${project.id}'>${project.category_name}</option>`
            })

                $('#sub_category_id').html('<option value="all">All</option>'+opts)
                $("#sub_category_id").select2({
                    formatNoMatches: function () {
                        return "{{ __('messages.noRecordFound') }}";
                    }
                });
            });


            $('body').on('click', '.sa-params', function(){
                var id = $(this).data('user-id');
                swal({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.deleteProduct')",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "@lang('messages.confirmDelete')",
                    cancelButtonText: "@lang('messages.confirmNoArchive')",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm){
                    if (isConfirm) {

                        var url = "{{ route('admin.products.destroy',':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            success: function (response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    LaravelDataTables["products-table"].draw();
                                }
                            }
                        });
                    }
                });
            });



        });

        $('#products-table').on('preXhr.dt', function (e, settings, data) {
            var categoryID = $('#category_id').val();
            var subCategoryID = $('#sub_category_id').val();


            data['category_id'] = categoryID;
            data['sub_category_id'] = subCategoryID;
        });

        window.LaravelDataTables["products-table"].draw();

        function showData() {
            $('#products-table').on('preXhr.dt', function (e, settings, data) {
                var categoryID = $('#category_id').val();
                var subCategoryID = $('#sub_category_id').val();


                data['category_id'] = categoryID;
                data['sub_category_id'] = subCategoryID;
            });

            window.LaravelDataTables["products-table"].draw();
        }

        $('#filter-results').on('click', function (event) {
            event.preventDefault();
            showData();
        });

        function exportData(){
            var url = '{{ route('admin.products.export') }}';
            window.location.href = url;
        }

        $('#reset-filters').click(function () {
            $("#category_id").val('all').trigger('change');
            $("#sub_category_id").val('all').trigger('change');
            showData();
        })
    </script>
@endpush
