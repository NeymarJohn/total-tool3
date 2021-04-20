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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
<link rel="stylesheet"
      href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.10/styles/default.min.css">
@endpush

@section('content')

<div class="row">
    <div class="white-box">

        <div class="col-md-6 m-b-10">

                <div class="row p-10 font-semi-bold b-b">
                    <div class="col-xs-2">#</div>
                    <div class="col-xs-5">@lang('app.fields')</div>
                    <div class="col-xs-5">@lang('app.status')</div>
                </div>

                {!! Form::open(['id'=>'editSettings','class'=>'ajax-form form-horizontal','method'=>'PUT']) !!}
                <div id="sortable">
                    @foreach ($ticketFormFields as $item)
                        <div class="row p-10 b-b">
                            <div class="col-xs-2">
                                <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                <input type="hidden" name="sort_order[]" value="{{ $item->id }}">
                            </div>
                            <div class="col-xs-5">@lang('modules.tickets.'.$item->field_name)</div>
                            <div class="col-xs-5">
                                @if($item->required == 0)
                                <div class="switchery-demo">
                                    <input type="checkbox"
                                        @if($item->status == 'active') checked
                                        @endif class="js-switch change-setting"
                                        data-color="#99d683" data-size="small"
                                        data-setting-id="{{ $item->id }}"/>
                                </div>
                               @endif
                            </div>
                        </div>
                    @endforeach
                        @if($global->google_recaptcha == 1)
                            <div class="row p-10 b-b">
                                <div class="col-xs-2">
                                </div>
                                <div class="col-xs-5">@lang('modules.tickets.googleCaptcha')</div>
                                <div class="col-xs-5">
                                    <div class="switchery-demo">
                                        <input type="checkbox"
                                               @if($global->ticket_form_google_captcha == 1) checked
                                               @endif class="js-switch change-setting"
                                               data-color="#99d683" data-size="small"
                                               data-setting-id="0"/>
                                    </div>
                                </div>
                            </div>
                        @endif
                </div>
                {!! Form::close() !!}

                <div class="row m-t-20">
                    <div class="col-xs-12">
                        <h4>@lang('modules.lead.iframeSnippet')</h4>
                        <code>
                            &lt;iframe src="{{ route('front.ticketForm') }}" width="100%"  frameborder="0">&lt;/iframe&gt;

                        </code>
                    </div>
                </div>

        </div>

        <div class="col-md-6">
            <div class="white-box b-all">
                <h4>@lang('app.preview')</h4>
                <hr>
                <iframe src="{{ route('front.ticketForm') }}" width="100%" id="previewIframe" onload="resizeIframe(this)" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

@endsection
@push('footer-script')
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.10/highlight.min.js"></script>
    <script>

        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());

        });

        $(function () {
            $( "#sortable" ).sortable({
                update: function( event, ui ) {
                    var sortedValues = new Array();
                    $('input[name="sort_order[]"]').each(function(index, value) { sortedValues[index] = $(this).val(); });
                    $.easyAjax({
                        url: '{{route('admin.ticket-form.sortFields')}}',
                        type: "POST",
                        data: {'sortedValues': sortedValues, '_token': '{{ csrf_token() }}'},
                        success: function (response) {
                            var iframe = document.getElementById('previewIframe');
                            iframe.src = iframe.src;
                        }
                    })
                }
            });
        });

        $('.change-setting').change(function () {
            var id = $(this).data('setting-id');

            if ($(this).is(':checked'))
                var status = 'active';
            else
                var status = 'inactive';

            var url = '{{route('admin.ticket-form.update', ':id')}}';
            url = url.replace(':id', id);
            $.easyAjax({
                url: url,
                type: "POST",
                data: {'id': id, 'status': status, '_method': 'PUT', '_token': '{{ csrf_token() }}'},
                success: function (response) {
                    var iframe = document.getElementById('previewIframe');
                    iframe.src = iframe.src;
                }
            })
        });

    </script>
    <script>
        function resizeIframe(obj) {
          obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 50 + 'px';
        }
      </script>

@endpush
