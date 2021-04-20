@extends('layouts.member-app')

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
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.notices.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection
@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">

<style>
    .d-none {
        display: none;
    }
</style>
@endpush
@section('content')

    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.notices.addNotice')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createNotice','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-xs-12 ">
                                        <div class="form-group">
                                            <label class="required">@lang("modules.notices.noticeHeading")</label>
                                            <input type="text" name="heading" id="heading" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            {{--                                            <label>Select Duration</label>--}}
                                            <div class="radio-list">
                                                <label class="radio-inline p-0">
                                                    <div class="radio radio-info">
                                                        <input type="radio" name="to" id="toEmployee" checked="" value="employee">
                                                        <label for="duration_single">@lang('modules.notices.toEmployee')</label>
                                                    </div>
                                                </label>
                                                @if($user->cans('view_clients'))
                                                    <label class="radio-inline">
                                                        <div class="radio radio-info">
                                                            <input type="radio" name="to" id="toClient" value="client">
                                                            <label for="duration_multiple">@lang('modules.notices.toClients')</label>
                                                        </div>
                                                    </label>
                                                @endif

                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-xs-12 " id="department">
                                        <div class="form-group">
                                            <label>@lang("app.department")</label>
                                            <select name="team_id" id="team_id" class="form-control">
                                                <option value=""> -- </option>
                                                @foreach($teams as $team)
                                                    <option value="{{ $team->id }}">{{ ucwords($team->team_name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--/row-->

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">@lang("modules.notices.noticeDetails")</label>
                                            <textarea name="description" id="description" rows="5" class="form-control summernote"></textarea>
                                        </div>
                                    </div>

                                </div>
                                <!--/span-->
                                <div class="row">
                                    <div class="form-group col-xs-6">
                                        <label class="control-label">@lang('app.attachment')</label>
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                                            <span class="input-group-addon btn btn-default btn-file"> <span class="fileinput-new">@lang('app.selectFile')</span> <span class="fileinput-exists">@lang('app.change')</span>
                                            <input type="file" name="file" id="image">
                                            </span> <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">@lang('app.remove')</a> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>

                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script>
    $(function () {

        $('.radio-list').click(function () {
            if($('input[name=to]:checked').val() === 'employee') {
                $('#department').removeClass('d-none').addClass('d-block');
            } else {
                $('#department').removeClass('d-block').addClass('d-none');
            }
        })

    });
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('member.notices.store')}}',
            container: '#createNotice',
            type: "POST",
            redirect: true,
            file: true
        })
    });
    $('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen"]]
        ]
    });
</script>

@endpush

