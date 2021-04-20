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
@push('head-script')
<link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('plugins/iconpicker/css/fontawesome-iconpicker.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">

<style>
    .hideBox {
        display: none;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="panel">

                <div class="vtabs customvtab p-t-10">
                    @if($global->front_design == 1)
                        @include('sections.saas.footer_page_setting_menu')
                    @else
                        @include('sections.front_setting_menu')
                    @endif

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="white-box">
                                        {!!  Form::open(['url' => '' ,'method' => 'put', 'id' => 'add-edit-form','class'=>'form-horizontal']) 	 !!}

                                        <h3>@lang('modules.footer.addFooterMenu')</h3>
                                        <hr>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">@lang('app.language')</label>
                                            <div class="col-sm-10">
                                                <select name="language" class="form-control selectpicker" id="language_switcher">
                                                    <option @if(is_null($footer->language_setting_id)) selected @endif value="0" data-content=" <span class='flag-icon flag-icon-us'></span> En"></option>
                                                    @forelse ($activeLanguages as $language)
                                                    <option
                                                        @if($language->id === $footer->language_setting_id) selected @endif
                                                        value="{{ $language->id }}" data-content=' <span class="flag-icon flag-icon-{{ $language->language_code }}"></span> {{ ucfirst($language->language_code) }}'>
                                                    </option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="name">@lang('app.title')</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $footer->name }}" id="title" name="title" >
                                                <div class="form-control-focus"> </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="name">@lang('modules.footerSettings.pageContent')</label>
                                                <div class="col-sm-10 radio-list">
                                                    <label class="radio-inline p-0">
                                                        <div class="radio radio-info">
                                                            <input type="radio" @if(!is_null($footer->description)) checked @endif class="upload-video" name="content" id="desc" value="desc">
                                                            <label for="content_type_desc">@lang('modules.footerSettings.useDescription')</label>
                                                        </div>
                                                    </label>
                                                    <label class="radio-inline">
                                                        <div class="radio radio-info">
                                                            <input type="radio" class="upload-video"  @if(!is_null($footer->external_link)) checked @endif  name="content" id="link" value="link">
                                                            <label for="content_type_link">@lang('modules.footerSettings.useExternalLink')</label>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group @if(!is_null($footer->external_link)) hideBox @endif"  id="descBox">
                                            <label class="col-sm-2 control-label" for="name">@lang('app.description')</label>
                                            <div class="col-sm-10">
                                                <textarea type="text" class="form-control summernote" id="description"  rows="3" name="description" > {!! $footer->description !!} </textarea>
                                                <div class="form-control-focus"> </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="form-group  @if(!is_null($footer->description)) hideBox @endif" id="linkBox">
                                            <label class="col-sm-2 control-label" for="videoLink">@lang('modules.footerSettings.externalLink')</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="{{ $footer->external_link }}" id="external_link" name="external_link" >
                                                <div class="form-control-focus"> </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="sttaus">@lang('app.status')</label>
                                            <div class="col-sm-10">
                                                <select name="status" id="status" class="form-control">
                                                    <option @if($footer->status == 'active') selected @endif  value="active">@lang('app.active')</option>
                                                    <option @if($footer->status == 'inactive') selected @endif value="inactive">@lang('app.inactive')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="sttaus">@lang('app.changeToPosition')</label>
                                            <div class="col-sm-10">
                                                <select name="type" id="type" class="form-control">
                                                    <option @if($footer->type == 'footer') selected @endif  value="footer">@lang('app.footer')</option>
                                                    <option @if($footer->type == 'header') selected @endif value="header">@lang('app.header')</option>
                                                    <option @if($footer->type == 'both') selected @endif value="both">@lang('app.both')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="seoBox" class="@if(!is_null($footer->external_link)) hideBox @endif">
                                            {{-- SEO Section --}}
                                            <h3>@lang('modules.frontCms.seoDetails')</h3>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="seo_title">@lang('modules.frontCms.seo_title')</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="seo_title" name="seo_title"
                                                           value="{{ $seoDetail ? $seoDetail->seo_title : '' }}">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="seo_author">@lang('modules.frontCms.seo_author')</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="seo_author" name="seo_author"
                                                           value="{{ $seoDetail ? $seoDetail->seo_author : '' }}">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="seo_description">@lang('modules.frontCms.seo_description')</label>
                                                <div class="col-sm-10">
                <textarea class="form-control" id="seo_description" rows="4"
                          name="seo_description">{{ $seoDetail ? $seoDetail->seo_description : '' }}</textarea>
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="seo_keywords">@lang('modules.frontCms.seo_keywords')</label>
                                                <div class="col-sm-10">
                <textarea class="form-control" id="seo_keywords" rows="4"
                          name="seo_keywords">{{ $seoDetail ? $seoDetail->seo_keywords : '' }}</textarea>
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- SEO Section --}}
                                        <br>
                                        <div class="row clearfix">
                                            <button type="submit" id="save"
                                                    class="btn btn-success waves-effect waves-light m-r-10">
                                                @lang('app.submit')
                                            </button>
                                        </div>

                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>    <!-- .row -->

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('footer-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/speakingurl/14.0.1/speakingurl.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slugify@1.3.5/slugify.min.js"></script>

<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script>
    $('.selectpicker').selectpicker();

    $('.summernote').summernote({
        dialogsInBody: true,
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link',  'hr','video']],
            ['view', ['fullscreen', 'codeview']],
            ['help', ['help']]
        ]
    });
    $(".upload-video").click(function() {

        var videoType = $("input[name=content]:checked").val();

        if(videoType == 'link'){
            $('#linkBox').show();
            $('#descBox').hide();
            $('#seoBox').hide();
        }else{
            $('#linkBox').hide();
            $('#descBox').show();
            $('#seoBox').show();
        }
    });

    $('#save').click(function () {
        var url = '{{ route('super-admin.footer-settings.update', $footer->id)}}';
        $.easyAjax({
            url: url,
            container: '#add-edit-form',
            type: "POST",
            file:true,
            success: function (response) {
                if(response.status == 'success'){
                    //window.location.reload();
                }
            }
        });
        return false;
    })

</script>
@endpush
