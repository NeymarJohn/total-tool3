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
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">
@endpush

@section('content')
        <div class="col-xs-12">
            <div class="white-box">
                {!! Form::open(['id'=>'addEditFaq','class'=>'ajax-form']) !!}
                @if(isset($faq->id)) <input type="hidden" name="_method" value="PUT"> @endif

                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">@lang('app.add') @lang('app.faqCategory') <a href="javascript:;" id="addCategory" class="text-info"><i class="ti-settings text-info"></i></a>
                                </label>
                                <select class="selectpicker form-control" name="category_id" id="category_id"
                                        data-style="form-control">
                                    @forelse($categories as $category)
                                        <option value="{{ $category->id }}">{{ ucwords($category->name) }}</option>
                                    @empty
                                        <option value="">@lang('messages.noProjectCategoryAdded')</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 ">
                            <div class="form-group">
                                <label>@lang('app.title')</label>
                                <input type="text" name="title" class="form-control" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 ">
                            <div class="form-group">
                                <label>@lang('app.description')</label>
                                <textarea name="description" class="form-control summernote"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-20">
                        <div class="col-xs-12">
                            <button type="button"
                                    class="btn btn-block btn-outline-info btn-sm col-md-2 select-image-button"
                                    style="margin-bottom: 10px;display: none "><i class="fa fa-upload"></i>
                                File Select Or Upload
                            </button>
                            <div id="file-upload-box">
                                <div class="row" id="file-dropzone">
                                    <div class="col-xs-12">
                                        <div class="dropzone"
                                             id="file-upload-dropzone">
                                            {{ csrf_field() }}
                                            <div class="fallback">
                                                <input name="file" type="file" multiple/>
                                            </div>
                                            <input name="image_url" id="image_url" type="hidden"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="faqID" id="faqID">
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" id="save-faq-category" onclick="saveFAQ();return false;" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- .row -->
        {{--Ajax Modal--}}
        <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel"
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
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.js') }}"></script>

<script>

    Dropzone.autoDiscover = false;
    //Dropzone class
    myDropzone = new Dropzone("div#file-upload-dropzone", {
        url: "{{ route('super-admin.faq.file-store') }}",
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        paramName: "file",
        maxFilesize: 10,
        maxFiles: 10,
        acceptedFiles: "image/*,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/docx,application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        autoProcessQueue: false,
        uploadMultiple: true,
        addRemoveLinks: true,
        parallelUploads: 10,
        dictDefaultMessage: "@lang('modules.projects.dropFile')",
        init: function () {
            myDropzone = this;
            this.on("success", function (file, response) {
                if(response.status == 'fail') {
                    $.showToastr(response.message, 'error');
                    return;
                }
            })
        }
    });

    myDropzone.on('sending', function (file, xhr, formData) {
        console.log(myDropzone.getAddedFiles().length, 'sending');
        var ids = $('#faqID').val();
        formData.append('faq_id', ids);
    });

    myDropzone.on('completemultiple', function () {
        var msgs = "@lang('messages.createFaq')";
        $.showToastr(msgs, 'success');
        window.location.href = '{{ route('super-admin.faq.index') }}'

    });
    $('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        dialogsInBody: true,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link',  'hr','video','picture']],
            ['view', ['fullscreen', 'codeview']],
            ['help', ['help']]
        ]
    });

    function saveFAQ() {
        var url = "{{ route('super-admin.faq.store') }}";
        $.easyAjax({
            url: url,
            container: '#addEditFaq',
            type: "POST",
            file: true,
            success: function (response) {
                var dropzone = 0;
                dropzone = myDropzone.getQueuedFiles().length;
                console.log(['dropzone', dropzone]);
                if(dropzone > 0){
                    faqID = response.faqID;
                    $('#faqID').val(response.faqID);
                    myDropzone.processQueue();
                } else {
                    var msgs = "@lang('messages.createFaq')";
                    $.showToastr(msgs, 'success');
                    window.location.href = '{{ route('super-admin.faq.index') }}'
                }
                $.unblockUI();

            }
        })
    }

    $('#addEditFaq').on('click', '#addCategory', function () {
        var url = '{{ route('super-admin.faq-category.create')}}';
        $('#modelHeading').html("@lang('app.faqCategory')");
        $.ajaxModal('#projectCategoryModal', url);
    })

</script>
@endpush