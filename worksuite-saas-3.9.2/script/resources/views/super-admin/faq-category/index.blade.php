@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="javascript:void(0);" onclick="showFaqCategoryCreate()" class="btn btn-outline btn-success btn-sm">@lang('app.add') @lang('app.faqCategory') <i class="fa fa-plus" aria-hidden="true"></i></a>

            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
@endpush

@section('content')

        <div class="col-xs-12">
            <div class="white-box">

                <div class="table-responsive">
                <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="faq-category-table">
                    <thead>
                    <tr>
                        <th>@lang('app.id')</th>
                        <th>@lang('app.name')</th>
                        <th>@lang('app.menu.faq')</th>
                        <th>@lang('app.action')</th>
                    </tr>
                    </thead>
                </table>
                    </div>
            </div>
        </div>
    </div>
    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="faqCategoryModal" role="dialog" aria-labelledby="myModalLabel"
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

        {{--Ajax Modal--}}
        <div class="modal fade bs-modal-md in" id="faqModal" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" id="faq-modal-data-application">
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


        {{--<div class="modal-content">--}}
            {{--<div class="modal-header">--}}
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>--}}
                {{--<h4 class="modal-title">Insert Video</h4>--}}
            {{--</div>--}}
            {{--<div class="modal-body">--}}
                {{--<div class="form-group row-fluid"><label>Video URL? <small class="text-muted">(YouTube, Vimeo, Vine, Instagram, DailyMotion or Youku)</small></label><input class="note-video-url form-control span12" type="text"></div>--}}
            {{--</div>--}}
            {{--<div class="modal-footer">--}}
                {{--<button href="#" class="btn btn-primary note-video-btn disabled" disabled="">Insert Video</button>--}}
            {{--</div>--}}
        {{--</div>--}}
@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    <script>
        var table = $('#faq-category-table');

        $(function() {
            table.dataTable({
                destroy: true,
                responsive: true,
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: '{!! route('super-admin.faq-category.data') !!}',
                language: {
                    "url": "<?php echo __("app.datatable") ?>"
                },
                "fnDrawCallback": function( oSettings ) {
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'faq', name: 'faq' },
                    { data: 'action', name: 'action' }
                ]
            });


            $('body').on('click', '.sa-params', function(){
                var id = $(this).data('user-id');
                swal({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.confirmation.deleteItem')",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "@lang('messages.deleteConfirmation')",
                    cancelButtonText: "@lang('messages.confirmNoArchive')",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm){
                    if (isConfirm) {

                        var url = "{{ route('super-admin.faq-category.destroy',':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            success: function (response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    table._fnDraw();
                                }
                            }
                        });
                    }
                });
            });
        });

//        $('.summernote').summernote({
//            height: 300,                 // set editor height
//            width: "90%",                 // set editor height
//            minHeight: null,             // set minimum height of editor
//            maxHeight: null,             // set maximum height of editor
//            dialogsInBody: true
//        });

        function showFaqCategoryCreate() {
            var url = '{{ route('super-admin.faq-category.create')}}';

            $.ajaxModal('#faqCategoryModal', url);
        }

        function showFaqCategoryEdit(id) {
            var url = '{{ route('super-admin.faq-category.edit', ':id')}}';
            url = url.replace(':id', id);

            $.ajaxModal('#faqCategoryModal', url);
        }

        function saveCategory(id) {

            if(typeof id != 'undefined'){
                var url  ="{{route('super-admin.faq-category.update',':id')}}";
                url      = url.replace(':id',id);
            }

            if (typeof id == 'undefined'){
                url = "{{ route('super-admin.faq-category.store') }}";
            }

            $.easyAjax({
                url: url,
                container: '#addEditFaqCategory',
                type: "POST",
                data: $('#addEditFaqCategory').serialize(),
                success: function (response) {
                    if(response.status == 'success'){
                        table._fnDraw();
                        $.unblockUI();
                        $('#faqCategoryModal').modal('hide');
                    }
                }
            })
        }

        //region FAQ
        function showFaqAdd(categoryId) {
            var url = '{{ route('super-admin.faq.create', ':categoryId')}}';
            url      = url.replace(':categoryId',categoryId);

            $.ajaxModal('#faqModal', url);
        }

        function showFaqEdit(categoryId, id) {
            var url = '{{ route('super-admin.faq.edit', [':categoryId', ':id'])}}';
            url      = url.replace(':categoryId',categoryId);
            url = url.replace(':id', id);

            $.ajaxModal('#faqModal', url);
        }

        function saveFAQ(categoryId, id) {

            if(typeof id != 'undefined'){
                var url  ="{{route('super-admin.faq.update', [':categoryId', ':id'])}}";
                url      = url.replace(':categoryId',categoryId);
                url      = url.replace(':id',id);
            }

            if (typeof id == 'undefined'){
                var url = "{{ route('super-admin.faq.store', ':categoryId') }}";
                url      = url.replace(':categoryId',categoryId);
            }

            $.easyAjax({
                url: url,
                container: '#addEditFaq',
                type: "POST",
                file: true,
                success: function (response) {
                    if(response.status == 'success'){
                        table._fnDraw();
                        $.unblockUI();
                        $('#faqModal').modal('hide');
                    }
                }
            })
        }

        function deleteFAQ(categoryId, id) {

            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.confirmation.deleteItem')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.deleteConfirmation')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('super-admin.faq.destroy', [':categoryId', ':id']) }}";
                    url      = url.replace(':categoryId',categoryId);
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                $('#faqModal').modal('hide');
                                table._fnDraw();
                            }
                        }
                    });
                }
            });
        }

        //endregion
    </script>
@endpush