@extends('layouts.client-app')

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
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
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
                <div class="row">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-6">
                        <div class="form-group pull-right">
                            <a href="{{ route('client.products.create') }}" class="btn btn-outline btn-success btn-sm cartButton"><i class="fa fa-shopping-cart"></i> <span class="badge badge-info right productCounter">{{ sizeof($productDetails) }}</span></a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="products-table">
                        <thead>
                        <tr>
                            <th>@lang('app.id')</th>
                            <th>@lang('app.name')</th>
                            <th>@lang('app.price') (@lang('app.inclusiveAllTaxes'))</th>
                            <th>@lang('app.action')</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-6">
                        <div class="form-group pull-right">
                            <a href="{{ route('client.products.create') }}" class="btn btn-outline btn-info btn-sm cartButton"> @lang('app.goToCart') <span class="badge badge-info right productCounter"> {{ sizeof($productDetails) }}</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="productShow" role="dialog" aria-labelledby="myModalLabel"
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

<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
<script>

    showData();
    var table ;

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

    function showData() {
        var categoryID = $('#category_id').val();
        var subCategoryID = $('#sub_category_id').val();
        table = $('#products-table').dataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            stateSave: true,
            destroy: true,
            ajax: '{!! route('client.products.data') !!}?category_id='+categoryID+'&sub_category_id='+subCategoryID,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function (oSettings) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'price', name: 'price'},
                {data: 'action', name: 'action'}
            ]
        });
    }

    $('body').on('click', '.add-product', function () {
        let cartItems = [];
        var $this = $(this);
        cartItems  = cartItems.push($this.data('product-id'));

        if(cartItems === 0){
            swal('@lang("modules.booking.addItemsToCart")');
            $('#cart-item-error').html('@lang("modules.booking.addItemsToCart")');
            return false;
        }
        else {
            let url = '{{route('client.products.add-cart-item')}}';

            $.easyAjax({
                url: url,
                container: '#products-table',
                type: "GET",
                data: {'productID': $this.data('product-id')},
                success: function (response) {
                    cartItems = response.productItems;
                    $('.productCounter').html(cartItems.length);
                }
            })
        }

    });
    $('body').on('click', '.view-product', function () {
        var id = $(this).data('product-id');
        var url = '{{ route('client.products.show', ':id')}}';
        url = url.replace(':id', id);

        $('#edit-column-form').html('@lang('app.product')');
        $.ajaxModal('#productShow', url);
    })
    $('#filter-results').on('click', function (event) {
        event.preventDefault();
        showData();
    });

    $('#reset-filters').click(function () {
        $("#category_id").val('all').trigger('change');
        $("#sub_category_id").val('all').trigger('change');
        showData();
    })


</script>
@endpush