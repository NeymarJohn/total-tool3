@extends('layouts.member-app')

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
                <li><a href="{{ route('member.dashboard') }}">@lang("app.menu.home")</a></li>
                <li><a href="{{ route('member.notices.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.edit')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.notices.updateNotice')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'updateNotice','class'=>'ajax-form','method'=>'PUT']) !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-xs-12 ">
                                    <div class="form-group">
                                        <label class="required">@lang("modules.notices.noticeHeading")</label>
                                        <input type="text" name="heading" id="heading"  class="form-control" value="{{ $notice->heading }}">
                                    </div>
                                </div>
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label required">@lang("modules.notices.noticeDetails")</label>
                                        <textarea name="description" id="description" rows="5" class="form-control">{{ $notice->description }}</textarea>
                                    </div>
                                </div>

                            </div>
                            <!--/span-->

                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.update')</button>
                            <button type="reset" class="btn btn-default">@lang('app.reset')</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

@endsection

@push('footer-script')
<script>

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('member.notices.update', [$notice->id])}}',
            container: '#updateNotice',
            type: "POST",
            redirect: true,
            data: $('#updateNotice').serialize()
        })
    });
</script>

@endpush